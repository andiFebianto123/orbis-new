<?php

namespace App\Imports;

use App\Helpers\LeadershipSyncHelper;
use App\Models\Church;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\ChurchEntityType;
use App\Models\Personel;
use App\Models\StructureChurch;
use App\Models\CoordinatorChurch;
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
        $row_local_church = $row['Local Church'];
        $row_leadership_structure = $row['Leadership Structure'];
        $row_coordinator = $row['Coordinator'];
        $row_contact_person = $row['Contact Person'];
        $row_church_address = $row['Church Address'];
        $row_office_address = $row['Office Address'];
        $row_city = $row['City'];
        $row_province = $row['State'];
        $row_postal_code = $row['Postcode'];
        $row_country = $row['Country'];
        $row_phone = $row['Phone'];
        $row_fax = $row['Fax'];
        $row_email = $row['Email'];
        $row_secondary_email = $row['Secondary Email'];
        $row_church_status = $row['Church Status'];
        $row_founded_on = $row['Founded On'];
        $row_service_time_church = $row['Service Time Church'];
        $row_notes = $row['Notes'];
        
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
        $leadership_structure = trim(str_replace('_x000D_', "\n", $row_leadership_structure ?? ""));
        $str_json_leadership = json_encode($this->handleLeadershipName($leadership_structure));
        $secondary_email = (!isset($row_secondary_email) || strlen($row_secondary_email) == 0) ? null : $row_secondary_email;

        $exists_church = Church::where('church_name', $row_church_name)
                            ->where('phone', $phone)
                            ->where('postal_code', $postal_code)
                            ->exists();
        
        $church_local = Church::where('church_name', $row_local_church)
                                ->first();
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
            $update_church->church_local_id = ($church_local)? $church_local->id : '-';
            $update_church->office_address = $office_address;
            $update_church->city = $city;
            $update_church->province = $province;
            $update_church->postal_code = $postal_code;
            $update_church->country_id = ($country->id ?? null);
            $update_church->first_email = $first_email;
            $update_church->second_email = $secondary_email;
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

            foreach ($this->handleCoordinator($row_coordinator) as $key => $hc) {
                if ($hc != []) {
                    $coordinator_church = new CoordinatorChurch([
                        'coordinator_name'  => $hc['coordinator_name'],
                        'coordinator_title'  => $hc['coordinator_title'],
                        'churches_id' => $update_church->id,
                    ]);
                    $coordinator_church->save();
                }
            }

            if (sizeof($this->handleLeadershipName($leadership_structure)) > 0) {
                StructureChurch::where("churches_id", $update_church->id)->delete();
                foreach ($this->handleLeadershipName($leadership_structure) as $key => $cn) {
                    $insert_p = new StructureChurch();
                    $insert_p->title_structure_id = $cn['title_structure_id'];
                    $insert_p->churches_id = $update_church->id;
                    $insert_p->personel_id = $cn['personel_id'];
                    $insert_p->save();

                    (new LeadershipSyncHelper())->sync($cn['personel_id']);
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
            $new_church->church_local_id = ($church_local)? $church_local->id : '-';
            $new_church->city = $city;
            $new_church->province = $province;
            $new_church->postal_code = $postal_code;
            $new_church->country_id = ($country->id ?? null);
            $new_church->first_email = $first_email;
            $new_church->second_email = $secondary_email;
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

            foreach ($this->handleCoordinator($row_coordinator) as $key => $hc) {
                if ($hc != []) {
                    $coordinator_church = new CoordinatorChurch([
                        'coordinator_name'  => $hc['coordinator_name'],
                        'coordinator_title'  => $hc['coordinator_title'],
                        'churches_id' => $new_church->id,
                    ]);
                    $coordinator_church->save();
                }
            }

            if (sizeof($this->handleLeadershipName($leadership_structure)) > 0) {
                StructureChurch::where("churches_id", $new_church->id)->delete();
                foreach ($this->handleLeadershipName($leadership_structure) as $key => $cn) {
                    $insert_p = new StructureChurch();
                    $insert_p->title_structure_id = $cn['title_structure_id'];
                    $insert_p->churches_id = $new_church->id;
                    $insert_p->personel_id = $cn['personel_id'];
                    $insert_p->save();

                    (new LeadershipSyncHelper())->sync($cn['personel_id']);
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
            'Leadership Structure' => function($attribute, $value, $onFailure) {
                $leadership_name = $this->handleLeadershipName($value);
                if ($value != "" && sizeof($leadership_name) == 0) {
                    $onFailure('Not Exist Name - Role or Invalid Format :: Pastor Name - Role');
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


    private function handleCoordinator($value){
        $coordinator_name = str_replace('\n', "\n", $value ?? '');
        $arr_coordinator_names = [];
        $count_coordinator = 0;
        $count_valid_coordinator = 0;
        if (strpos( $coordinator_name, "\n") !== false) {
            foreach(explode("\n",$coordinator_name) as $coordinator) {  
                $arr_coordinator_name = $this->unitCoordinator($coordinator);
                if($arr_coordinator_name != []){
                    $arr_coordinator_names[] = $arr_coordinator_name;
                    $count_valid_coordinator ++;
                }
                $count_coordinator++;
            }
        }else{
            $arr_coordinator_names[] = $this->unitCoordinator($value);
        }
        return ($count_coordinator == $count_valid_coordinator) ? $arr_coordinator_names :[];
    }


    private function unitCoordinator($coordinator){
        $arr_coordinator = [];
        if (strpos( $coordinator, '(') !== false) {
            $col_coordinator = explode("(",$coordinator);
            
            $coordinator_name = $col_coordinator[0];
            $coordinator_title = $col_coordinator[1];
            

            $coordinator_data = CoordinatorChurch::where('coordinator_title', $coordinator_title )
                        ->where('coordinator_name', $coordinator_name )
                        ->first();

            if (isset($coordinator_data)) {
                $arr_coordinator = [
                    'coordinator_name' =>  $coordinator_data->coordinator_name,
                    'coordinator_title' =>  str_replace(")", "", $coordinator_data->coordinator_title),
                ];
            }
        } 
        return $arr_coordinator;
    }

    private function handleLeadershipName($value){
        $arr_datas = [];
        $count_valid_data = 0;
        $total_data = 0;
        foreach(explode("\n",$value) as $sc) {
            $total_data++;
            if (strpos( $sc, "-") !== false) {
                $expl_dash = explode("-",$sc);
                $last_dash = substr_count($sc, "-");
    
                $per_name = rtrim($expl_dash[0]);
                $first_name = $per_name;
                $last_name = "";
                if (strpos( $per_name, " ") !== false) {
                    $expl_space = explode(" ",$per_name);
                    $first_name = $expl_space[0];
                    $last_space = substr_count($per_name, " ");
                    $last_name = trim($expl_space[$last_space]);
                }
    
                $personel_name = Personel::where('first_name','like', '%'.$first_name.'%')
                                 ->where("last_name",'like', '%'.$last_name.'%')->first();
    
                $ministry_role = MinistryRole::where('ministry_role','like', '%'.trim($expl_dash[$last_dash]).'%')->first();
    
                if (isset($personel_name) && isset($ministry_role)) {
                    $count_valid_data++;
                    $arr_datas[] = ['personel_id' => $personel_name->id, 'title_structure_id' => $ministry_role->id];
                }
            }  
        }

        return ($count_valid_data == $total_data) ? $arr_datas :[];
    }
}