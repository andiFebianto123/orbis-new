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
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\ChurchesRcdpw;
use App\Helpers\HitCompare;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

// HeadingRowFormatter::default('none');

class ChurchImport implements OnEachRow, SkipsEmptyRows, WithValidation, WithHeadingRow
{
    use Importable;

    public $ids_create = [];
    public $ids_update = [];

    public function  __construct($attrs)
    {
      $this->filename = $attrs['filename'];
    }


    public function onRow(Row $row){
        // $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        if (array_key_exists('rc_dpw', $row)) {
            $this->singleRow($row);
        }
    }

    private function singleRow($row)
    {
        $row_rc_dpw = $row['rc_dpw']; // $row['RC / DPW'];
        $row_church_name = $row['church_name']; // $row['Church Name'];
        $row_church_type = $row['church_type']; // $row['Church Type'];
        // $row_lead_pastor_name = $row['lead_pastor_name']; // $row['Lead Pastor Name'];
        $row_local_church = $row['church_name']; // $row['Local Church'];
        $row_leadership_structure = $row['leadership_structure']; //$row['Leadership Structure'];
        $row_coordinator = $row['coordinator'];
        $row_contact_person = $row['contact_person'];// $row['Contact Person'];
        $row_church_address = $row['church_address']; //$row['Church Address'];
        $row_office_address = $row['office_address']; // $row['Office Address'];
        $row_city = $row['city'];
        $row_province = $row['state'];
        $row_postal_code = $row['postcode'];
        $row_country = $row['country'];
        $row_phone = $row['phone'];
        $row_fax = $row['fax'];
        $row_email = $row['email'];
        $row_secondary_email = $row['secondary_email'] ?? null; // $row['Secondary Email'];
        $row_church_status = $row['church_status'] ?? null; // $row['Church Status'];
        $row_status_date = $row['status_date'] ?? null; // $row['Church Status'];
        $row_founded_on = $row['founded_on']; // $row['Founded On'];
        $row_service_time_church = $row['service_time_church']; // $row['Service Time Church'];
        $row_color = $row['task_color'] ?? null;
        $row_latitude = $row['latitude'] ?? null;
        $row_longitude = $row['longitude'] ?? null;
        $row_notes = $row['notes'];

        $dataset = $row;

        
        $country  = CountryList::where('country_name', $row_country)->first();
        $church_type  =  ChurchEntityType::where('entities_type', $row_church_type)->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row_rc_dpw)->first();

        $dataset['rc_dpw'] = $rcdpw['id'] ?? null;

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
        $secondary_email = (!isset($row_secondary_email) || strlen($row_secondary_email) == 0) ? null : $row_secondary_email;

        $exists_church = Church::where('church_name', $row_church_name)
                            ->where('phone', $phone)
                            ->where('postal_code', $postal_code)
                            ->exists();
        
        $church_local = Church::where('church_name', $row_local_church)
                                ->first();

        $dataset['church_local_id'] = ($church_local)? $church_local->id : null;

        if ($exists_church) {
            // bila update data
            $update_church = Church::where('church_name', $row_church_name)
                        ->where('phone', $phone)
                        ->where('postal_code', $postal_code)->first();

            $hitCompare = new HitCompare;
            $hitCompare->addFieldCompare(
                [
                    'church_name' => 'church_name',
                    'rc_dpw_id' => 'rc_dpw',
                    'church_local_id' => 'church_local_id',
                    'task_color' => 'task_color',
                    'church_address' => 'church_address',
                    'latitude' => 'latitude',
                    'longitude' => 'longitude',
                    'notes' => 'notes',
                ], 
            $dataset);
            $com = $hitCompare->compareData($update_church->toArray());

            $update_church->founded_on = $date;
            $update_church->rc_dpw_id = ($rcdpw['id'] ?? null);
            $update_church->church_type_id = ($church_type->id ?? null);
            $update_church->church_name = $row_church_name;
            $update_church->contact_person = $contact_person;
            $update_church->church_address = $church_address;
            $update_church->church_local_id = ($church_local)? $church_local->id : null;
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
            $update_church->task_color = $row_color ?? null;
            $update_church->latitude = $row_latitude ?? null;
            $update_church->longitude = $row_longitude ?? null;
            $update_church->notes = $row_notes;
            $update_church->save();

            if($com){
                $this->ids_update[] = $com;
            }


            $id_church = $update_church->id;
            

            // $this->handleRcdpw($id_church, $row_rc_dpw, 'update');


            // StructureChurch::where('churches_id', $update_church->id)->delete();

            // foreach ($this->handlePastorName($row_lead_pastor_name) as $key => $hpn) {
            //     if ($hpn != []) {
            //         $structure_church = new StructureChurch();
            //         $structure_church->churches_id = $update_church->id;
            //         $structure_church->personel_id = $hpn['pastor_id'];
            //         $structure_church->title_structure_id = $hpn['ministry_id'];
            //         $structure_church->save();
            //     }
            // }

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

            if($row_church_status != null){
                $status_history =  StatusHistoryChurch::where('churches_id',  $update_church->id)
                                    ->orderBy('id', 'desc')
                                    ->first();


                                    Log::info($status_history);
                if($status_history->status != $row_church_status){
                    if($com === FALSE){
                        $this->ids_update[] = $com;
                    }
                }
                if (isset($status_history)) {
                    $changeHistory = StatusHistoryChurch::where('id',  $status_history->id)->first();
                    $changeHistory->status = $row_church_status;
                    $changeHistory->date_status = $row_status_date;
                    $changeHistory->save();
                }else{
                    $insertShc = new StatusHistoryChurch();
                    $insertShc->status = $row_church_status;
                    $insertShc->date_status = $row_status_date;
                    $insertShc->churches_id = $update_church->id;
                    $insertShc->save();
                }
            }
            

        }else {
            $new_church = new Church();
            $new_church->founded_on = $date;
            $new_church->rc_dpw_id = ($rcdpw['id'] ?? null);
            $new_church->church_type_id = ($church_type->id ?? null);
            $new_church->church_name = $row_church_name;
            $new_church->contact_person = $contact_person;
            $new_church->church_address = $church_address;
            $new_church->office_address = $office_address;
            $new_church->church_local_id = ($church_local)? $church_local->id : null;
            $new_church->city = $city;
            $new_church->province = $province;
            $new_church->postal_code = $postal_code;
            $new_church->country_id = ($country->id ?? null);
            $new_church->first_email = $first_email;
            $new_church->second_email = $secondary_email;
            $new_church->phone = $phone;
            $new_church->fax = $fax;
            $new_church->service_time_church = $service_time_church;
            $new_church->task_color = ($row_color ?? null);
            $new_church->latitude = ($row_latitude ?? null);
            $new_church->longitude = ($row_longitude ?? null);
            $new_church->notes = $row_notes;
            $new_church->save();
            
            $this->ids_create[] = $new_church->id;

            // $this->handleRcdpw($new_church->id, $row_rc_dpw, 'create');


            // foreach ($this->handlePastorName($row_lead_pastor_name) as $key => $hpn) {
            //     if ($hpn != []) {
            //         $structure_church = new StructureChurch([
            //             'personel_id'  => $hpn['pastor_id'],
            //             'title_structure_id' => $hpn['ministry_id'],
            //             'churches_id' => $new_church->id,
            //         ]);
            //         $structure_church->save();
            //     }
               
            // }

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

            if($row_church_status != null){
                $status_history =  StatusHistoryChurch::where('churches_id',  $new_church->id)
                                    ->orderBy('id', 'desc')
                                    ->first();

                if (isset($status_history)) {
                    $changeHistory = StatusHistoryChurch::where('id',  $status_history->id)->first();
                    $changeHistory->status = $row_church_status;
                    $changeHistory->date_status = $row_status_date;
                    $changeHistory->save();
                }else{
                    $insertShc = new StatusHistoryChurch();
                    $insertShc->status = $row_church_status;
                    $insertShc->date_status = $row_status_date;
                    $insertShc->churches_id = $new_church->id;
                    $insertShc->save();
                }
            }
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            'church_status' => ['required'],
            'status_date' => ['required'],
            'rc_dpw' => function($attribute, $value, $onFailure){
                if(strlen($value) > 0){
                    $rc_dpw = trim($value);
                    $rc_dpw = str_replace('\n', "\n", $rc_dpw ?? '');
                    $is_fail = [];
                    if (strpos( $rc_dpw, "\n") !== false) {
                        $rc_dpws = explode("\n", $rc_dpw);
                        foreach($rc_dpws as $data){
                            $d = RcDpwList::where('rc_dpw_name', $data)->first();
                            if($d == null){
                                $is_fail[] = $data;
                            }
                        }
                    }else{
                        $d = RcDpwList::where('rc_dpw_name', $rc_dpw)->first();
                        if($d == null){
                            $is_fail[] = $rc_dpw;
                        }
                    }
    
                    if(count($is_fail) > 0){
                        $str_rc_dpw = implode(", ", $is_fail);
                        $onFailure('RC / DPW are not invalid for ' . $str_rc_dpw);
                    }
    
                }
            },
            // 'lead_pastor_name' => function($attribute, $value, $onFailure) {
            //     $lead_pastor_name = $this->handlePastorName($value);
            //     if (sizeof($lead_pastor_name) == 0) {
            //         $onFailure('(Lead Pastor)) Not Exist Pastor or Invalid Format :: Firstname Lastname (Title)');
            //     }
            // },
            'leadership_structure' => function($attribute, $value, $onFailure) {
                $leadership_name = $this->handleLeadershipName($value);
                if ($value != "" && sizeof($leadership_name) == 0) {
                    $onFailure('(Leadership Structure) Not Exist Name - Role or Invalid Format :: Pastor Name - Role');
                }
            },
        ];
    }

    public function customValidationMessages()
    {
        return [
            'last_church_status.required' => 'Row Last Church Status is required',
            'last_status_date.required' => 'Row Last Status Date is required',
        ];
    }

    function formatDateExcel($dateExcel){
        if (is_numeric($dateExcel)) {
            return Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($dateExcel))->toDateString();
        }
        return Carbon::parse($dateExcel)->toDateString();
    }

    private function handleRcdpw($id, $value, $type){

        $rc_dpw = str_replace('\n', "\n", $value ?? '');

        if($type === 'update'){
            if(ChurchesRcdpw::where('churches_id', $id)->exists()){
                // hapus semua data churches rcd
                ChurchesRcdpw::where('churches_id', $id)->delete();
            }
        }

        if(strpos($rc_dpw, "\n") !== false){
            $e = explode("\n", $rc_dpw);
            foreach($e as $rc_){
                $id_rcdpw = RcDpwList::where('rc_dpw_name', $rc_)->first();
                $rc = new ChurchesRcdpw;
                $rc->churches_id = $id;
                $rc->rc_dpwlists_id = $id_rcdpw->id;
                $rc->save();
            }
        }else{
            $id_rcdpw = RcDpwList::where('rc_dpw_name', $rc_dpw)->first();
            $rc = new ChurchesRcdpw;
            $rc->churches_id = $id;
            $rc->rc_dpwlists_id = $id_rcdpw->id;
            $rc->save();
        }
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
            
            $fullname = rtrim($col_pastor[0]);

            $personel = Personel::where(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'like',  "%".$fullname."%")->first();
            if (!isset($personel)) {
                $personel = Personel::where('first_name', 'like',  "%".$fullname."%")->first();
            }
            
            $validPersonel = false;

            if (isset($personel)) {
                $foundName = $personel->first_name;
                if(isset($personel->last_name)){
                    $foundName .= " ".$personel->last_name;
                }
                
                if ($fullname == $foundName) {
                    $validPersonel = true;
                }
            }

            $ministry_role = MinistryRole::where('ministry_role', str_replace(")", "", $col_pastor[1]))->first();

            if ($validPersonel && isset($ministry_role)) {
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
            if (strpos( $sc, "(") !== false) {
                $explSeparator = explode("(",$sc);
    
                $fullname = rtrim($explSeparator[0]);

                $personel = Personel::where(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'like',  "%".$fullname."%")->first();
                if (!isset($personel)) {
                    $personel = Personel::where('first_name', 'like',  "%".$fullname."%")->first();
                }
                
                $validPersonel = false;

                if (isset($personel)) {
                    $foundName = $personel->first_name;
                    if(isset($personel->last_name)){
                        $foundName .= " ".$personel->last_name;
                    }
                    
                    if ($fullname == $foundName) {
                        $validPersonel = true;
                    }
                }
                
                $ministry_role = MinistryRole::where('ministry_role', str_replace(")", "", $explSeparator[1]))->first();

                if ($validPersonel  && isset($ministry_role)) {
                    $count_valid_data++;
                    $arr_datas[] = [
                        'personel_id' => $personel->id, 
                        'title_structure_id' => $ministry_role->id
                    ];
                }
            }  
        }

        return ($count_valid_data == $total_data) ? $arr_datas :[];
    }
}