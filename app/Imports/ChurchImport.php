<?php

namespace App\Imports;

use App\Models\Church;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\ChurchEntityType;
use App\Models\Personel;
use App\Models\StructureChurch;
use App\Models\MinistryRole;

use App\Models\LogErrorExcel;
use App\Models\StatusHistoryChurch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ChurchImport implements ToCollection, WithHeadingRow,  WithValidation
{
    use Importable;

    public function  __construct($attrs)
    {
      $this->filename = $attrs['filename'];
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $key => $row) {
           $this->singleRow($row);
        }
    }

    private function singleRow($row)
    {
        $country  = CountryList::where('country_name', $row['country'])->first();
        $church_type  =  ChurchEntityType::where('entities_type', $row['church_type'])->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row['rc_dpw'])->first();
        $row['founded_on'] = trim($row['founded_on'] ?? '');
        $date =  $row['founded_on'] == '-' || $row['founded_on'] == '' ? NULL : $this->formatDateExcel($row['founded_on']);
        
        $contact_person = $row['contact_person'] == '-' || $row['contact_person'] == '' ? NULL : $row['contact_person'];
        $city = $row['city'] == '-' || $row['city'] == '' ? NULL : $row['city'];
        $province = $row['province'] == '-' || $row['province'] == '' ? NULL : $row['province'];
        $church_address = trim(str_replace('_x000D_', "\n", $row['church_address'] ?? ''));
        $office_address = trim(str_replace('_x000D_', "\n", $row['office_address'] ?? ''));
        $phone = trim(str_replace('_x000D_', "\n", $row['phone'] ?? ''));
        $fax = trim(str_replace('_x000D_', "\n", $row['fax'] ?? ''));
        $postal_code = $row['postal_code'] == '-' || $row['postal_code'] == '' ? NULL : $row['postal_code'];
        $first_email = trim(str_replace('_x000D_', "\n", $row['first_email'] ?? ''));
        $service_time_church = $row['service_time_church'] == ',' ? NULL : $row['service_time_church'];

        $exists_church = Church::where('church_name', $row['church_name'])
                            ->where('phone', $phone)
                            ->where('postal_code', $postal_code)
                            ->exists();

        $church = new Church([
            'founded_on'     => $date,
            'rc_dpw_id'      => ($rcdpw['id'] ?? null),
            'church_type_id' => ($church_type->id ?? null),
            'church_name'    => $row['church_name'],
            // 'lead_pastor_name' => json_encode($this->handlePastorName($row['lead_pastor_name'])),
            'contact_person'   => $contact_person,
            'church_address'   => $church_address,
            'office_address' => $office_address,
            'city'           => $city,
            'province'    => $province,
            'postal_code' => $postal_code,
            'country_id'  => ($country->id ?? null),
            'first_email'           => $first_email,
            'phone'                 => $phone,
            'fax'                   => $fax,
            'service_time_church'   => $service_time_church,
            'notes'           => $row['notes'],
        ]);

        if (!$exists_church) {
            $church->save();

            foreach ($this->handlePastorName($row['lead_pastor_name']) as $key => $hpn) {
                $structure_church = new StructureChurch([
                    'personel_id'  => $hpn['pastor_id'],
                    'title_structure_id' => $hpn['ministry_id'],
                    'churches_id' => $church->id,
                ]);
                $structure_church->save();
            }

            $status_history = new StatusHistoryChurch([
                'status'  => $row['church_status'],
                'date_status' => Carbon::now(),
                'churches_id' => $church->id,
            ]);
            $status_history->save();
        }
    }

    public function rules(): array
    {
        return [
            'lead_pastor_name' => function($attribute, $value, $onFailure) {
                $lead_pastor_name = $this->handlePastorName($value);
                if (sizeof($lead_pastor_name) == 0) {
                    $onFailure('Invalid Lead Pastor Format :: Firstname Lastname (Title)');
                }
            },
            'rc_dpw' => function($attribute, $value, $onFailure) {
                if (!RcDpwList::where('rc_dpw_name', $value)->first()) {
                     $onFailure('Invalid RC DPW');
                }
            },
            'country' => function($attribute, $value, $onFailure) {
                if (!CountryList::where('country_name',$value)->first()) {
                     $onFailure('Invalid Country');
                }
            },
            'church_type' => function($attribute, $value, $onFailure) {
                if (!ChurchEntityType::where('entities_type', $value)->first()) {
                     $onFailure('Invalid Church Type');
                }
            },
        ];
    }

    public function customValidationMessages()
    {
        return [];
    }

    function formatDateExcel($dateExcel){
        if (is_numeric($dateExcel)) {
            return Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($dateExcel))->toDateString();
        }
        return Carbon::parse($dateExcel)->toDateString();
    }

    private function handlePastorName($value){
        $lead_pastor_name = str_replace('\n', "\n", $value ?? '');
        $arr_pastor_names = [];
        $count_pastor = 0;
        $count_valid_pastor = 0;
        if (strpos( $lead_pastor_name, "\n") !== false) {
            foreach(explode("\n",$lead_pastor_name) as $lead_pastore) {  
                $arr_pastor_name = $this->unitPastorName($lead_pastore);
                if($arr_pastor_name != []){
                    $arr_pastor_names[] = $arr_pastor_name;
                    $count_valid_pastor ++;
                }
                $count_pastor++;
            }
        }else{
            $arr_pastor_names[] = $this->unitPastorName($value);
        }
        return ($count_pastor == $count_valid_pastor) ? $arr_pastor_names :[];
    }


    private function unitPastorName($lead_pastore){
        $arr_pastor_name = [];
        if (strpos( $lead_pastore, '(') !== false) {
            $col_pastor = explode("(",$lead_pastore);
            
            $first_name = "";
            $last_name = "";
            $filter_personel = [];
            if (strpos( $col_pastor[0], ' ') !== false) {
                foreach (explode(" ",$col_pastor[0]) as $key => $fln) {
                    if ($key == 0) {
                        $first_name = $fln;
                    }else{
                        $last_name .= $fln." ";
                    }
                }
                $filter_personel[] = ['first_name', $first_name];
                if($last_name != ""){
                    $filter_personel[] = ['last_name', rtrim($last_name, " ")];
                }
            }

            $personel = Personel::where($filter_personel)->first();
            $ministry_role = MinistryRole::where('ministry_role', str_replace(")", "", $col_pastor[1]))->first();

            if ($personel->id && $ministry_role->id) {
                $arr_pastor_name = [
                    'pastor_id' =>  ($personel->id ?? null),
                    'ministry_id' =>  $ministry_role->id,
                    'pastor_name' =>  $col_pastor[0],
                    'pastor_ministry_role' =>  str_replace(")", "", $col_pastor[1]),
                ];
            }
        } 
        return $arr_pastor_name;
    }
}