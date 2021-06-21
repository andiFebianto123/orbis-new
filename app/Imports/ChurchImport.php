<?php

namespace App\Imports;

use App\Models\Church;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\ChurchEntityType;
use App\Models\Personel;
use App\Models\StructureChurch;
use App\Models\MinistryRole;
use App\Models\StatusHistoryChurch;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithStartRow;
HeadingRowFormatter::default('none');

class ChurchImport implements ToCollection,  WithValidation, WithHeadingRow
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
        $row_rc_dpw = $row['RC / DPW'];
        $row_church_name = $row['Church Name'];
        $row_church_type = $row['Church Type'];
        $row_lead_pastor_name = $row['Lead Pastor Name'];
        $row_contact_person = $row['Contact Person'];
        $row_church_address = $row['Church Address'];
        $row_office_address = $row['Office Address'];
        $row_city = $row['City'];
        $row_province = $row['Province / State'];
        $row_postal_code = $row['Postal Code'];
        $row_country = $row['Country'];
        $row_phone = $row['Phone'];
        $row_fax = $row['Fax'];
        $row_email = $row['Email'];
        $row_church_status = $row['Church Status'];
        $row_founded_on = $row['Founded On'];
        $row_service_time_church = $row['Service Time Church'];
        $row_notes = $row['Notes'];

        // $row_rc_dpw = $row[0];
        // $row_church_name = $row[1];
        // $row_church_type = $row[2];
        // $row_lead_pastor_name = $row[3];
        // $row_contact_person = $row[4];
        // $row_church_address = $row[5];
        // $row_office_address = $row[6];
        // $row_city = $row[7];
        // $row_province = $row[8];
        // $row_postal_code = $row[9];
        // $row_country = $row[10];
        // $row_phone = $row[11];
        // $row_fax = $row[12];
        // $row_email = $row[13];
        // $row_church_status = $row[14];
        // $row_founded_on = $row[15];
        // $row_service_time_church = $row[16];
        // $row_notes = $row[17];
        
        $country  = CountryList::where('country_name', $row_country)->first();
        $church_type  =  ChurchEntityType::where('entities_type', $row_church_type)->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row_rc_dpw)->first();
        $row_founded_on = trim($row_founded_on ?? '');
        $date =  $row_founded_on == '-' || $row_founded_on == '' ? NULL : $this->formatDateExcel($row_founded_on);
        
        $contact_person = $row_contact_person == '-' || $row_contact_person == '' ? NULL : $row_contact_person;
        $city = $row_city == '-' || $row_city == '' ? NULL : $row_city;
        $province = $row_province == '-' || $row_province == '' ? NULL : $row_province;
        $church_address = trim(str_replace('_x000D_', "\n", $row_church_address ?? ''));
        $office_address = trim(str_replace('_x000D_', "\n", $row_office_address ?? ''));
        $phone = trim(str_replace('_x000D_', "\n", $row_phone ?? ''));
        $fax = trim(str_replace('_x000D_', "\n", $row_fax ?? ''));
        $postal_code = $row_postal_code == '-' || $row_postal_code == '' ? NULL : $row_postal_code;
        $first_email = trim(str_replace('_x000D_', "\n", $row_email ?? ''));
        $service_time_church = $row_service_time_church == ',' ? NULL : $row_service_time_church;

        $exists_church = Church::where('church_name', $row_church_name)
                            ->where('phone', $phone)
                            ->where('postal_code', $postal_code)
                            ->exists();

        if ($exists_church) {
            $update_church = Church::where('church_name', $row_church_name)
                        ->where('phone', $phone)
                        ->where('postal_code', $postal_code)->first();
            $update_church->founded_on = $date;
            $update_church->rc_dpw_id = ($rcdpw['id'] ?? null);
            $update_church->church_type_id = ($church_type->id ?? null);
            $update_church->church_name = $row_church_name;
            $update_church->contact_person = $contact_person;
            $update_church->church_address = $church_address;
            $update_church->office_address = $office_address;
            $update_church->city = $city;
            $update_church->province = $province;
            $update_church->postal_code = $postal_code;
            $update_church->country_id = ($country->id ?? null);
            $update_church->first_email = $first_email;
            $update_church->phone = $phone;
            $update_church->fax = $fax;
            $update_church->service_time_church = $service_time_church;
            $update_church->notes = $row_notes;
            $update_church->save();
            StructureChurch::where('churches_id', $update_church->id)->delete();

            foreach ($this->handlePastorName($row_lead_pastor_name) as $key => $hpn) {
                if ($hpn != []) {
                    $structure_church = new StructureChurch();
                    $structure_church->churches_id = $update_church->id;
                    $structure_church->personel_id = $hpn['pastor_id'];
                    $structure_church->title_structure_id = $hpn['ministry_id'];
                    $structure_church->save();
                }
            }

            $status_history =  StatusHistoryChurch::where('churches_id',  $update_church->id)->first();
            $status_history->status = $row_church_status;
            $status_history->date_status = Carbon::now();
            $status_history->save();

        }else {
            $new_church = new Church();
            $new_church->founded_on = $date;
            $new_church->rc_dpw_id = ($rcdpw['id'] ?? null);
            $new_church->church_type_id = ($church_type->id ?? null);
            $new_church->church_name = $row_church_name;
            $new_church->contact_person = $contact_person;
            $new_church->church_address = $church_address;
            $new_church->office_address = $office_address;
            $new_church->city = $city;
            $new_church->province = $province;
            $new_church->postal_code = $postal_code;
            $new_church->country_id = ($country->id ?? null);
            $new_church->first_email = $first_email;
            $new_church->phone = $phone;
            $new_church->fax = $fax;
            $new_church->service_time_church = $service_time_church;
            $new_church->notes = $row_notes;
            $new_church->save();

            foreach ($this->handlePastorName($row_lead_pastor_name) as $key => $hpn) {
                if ($hpn != []) {
                    $structure_church = new StructureChurch([
                        'personel_id'  => $hpn['pastor_id'],
                        'title_structure_id' => $hpn['ministry_id'],
                        'churches_id' => $new_church->id,
                    ]);
                    $structure_church->save();
                }
               
            }

            $status_history = new StatusHistoryChurch([
                'status'  => $row_church_status,
                'date_status' => Carbon::now(),
                'churches_id' => $new_church->id,
            ]);
            $status_history->save();
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            'Lead Pastor Name' => function($attribute, $value, $onFailure) {
                $lead_pastor_name = $this->handlePastorName($value);
                if (sizeof($lead_pastor_name) == 0) {
                    $onFailure('Not Exist Pastor or Invalid Lead Pastor Format :: Firstname Lastname (Title)');
                }
            },
            // 'rc_dpw' => function($attribute, $value, $onFailure) {
            //     if (!RcDpwList::where('rc_dpw_name', $value)->first()) {
            //          $onFailure('Invalid RC DPW');
            //     }
            // },
            // 'country' => function($attribute, $value, $onFailure) {
            //     if (!CountryList::where('country_name',$value)->first()) {
            //          $onFailure('Invalid Country');
            //     }
            // },
            // 'church_type' => function($attribute, $value, $onFailure) {
            //     if (!ChurchEntityType::where('entities_type', $value)->first()) {
            //          $onFailure('Invalid Church Type');
            //     }
            // },
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

            if (isset($personel) && isset($ministry_role)) {
                $arr_pastor_name = [
                    'pastor_id' =>  $personel->id,
                    'ministry_id' =>  $ministry_role->id,
                    'pastor_name' =>  $col_pastor[0],
                    'pastor_ministry_role' =>  str_replace(")", "", $col_pastor[1]),
                ];
            }
        } 
        return $arr_pastor_name;
    }
}