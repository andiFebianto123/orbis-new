<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Church;
use App\Models\Personel;
use App\Models\RcDpwList;
use App\Models\TitleList;
use Maatwebsite\Excel\Row;
use App\Models\PersonelsRcdpw;
use App\Models\CountryList;
use App\Models\MinistryRole;
use App\Models\Accountstatus;
use App\Models\StatusHistory;
use App\Models\StructureChurch;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Helpers\LeadershipSyncHelper;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\OnEachRow;
use App\Helpers\HitCompare;

// HeadingRowFormatter::default('none');

class PersonelImport implements OnEachRow/* ToCollection */, WithHeadingRow, WithValidation
{
    use Importable;
    public $ids_update = [];
    public $ids_create = [];

    public function  __construct($attrs)
    {
      $this->filename = $attrs['filename'];
    }

    

    // public function collection(Collection $rows)
    // {
    //     foreach ($rows as $key => $row) {
    //        $this->singleRow($row);
    //     }
    // }

    public function onRow(Row $row){
        $row = $row->toArray();
        $this->singleRow($row);
    }
    
    private function singleRow($row)
    {
        $row_rc_dpw = $row['rc_dpw']; // $row['RC / DPW'];
        $row_title = $row['title'];
        $row_first_name = $row['first_name']; // $row['First Name'];
        $row_last_name = $row['last_name']; //$row['Last Name'];
        $row_gender = $row['gender']; // $row['Gender'];
        $row_church_name = $row['church_name']; // $row['Church Name'];
        $row_address = $row['address']; // $row['Address'];
        $row_city = $row['city'];
        $row_province = $row['state'];
        $row_postal_code = $row['postcode'];
        $row_country = $row['country'];
        $row_phone = $row['phone'];
        $row_fax = $row['mobile_phone']; //$row['Mobile Phone'];
        $language = $row['language'];
        $row_email = $row['email'];
        $row_secondary_email = $row['secondary_email']; // $row['Secondary Email'];
        $row_marital_status = $row['marital_status']; // $row['Marital Status'];
        $row_date_of_birth = $row['date_of_birth']; //$row['Date of Birth'];
        $row_spouse_name = $row['spouse_name'];// $row['Spouse Name'];
        $row_spouse_date_of_birth = $row['spouse_date_of_birth']; //$row['Spouse Date of Birth'];
        $row_anniversary = $row['anniversary'];
        $row_acc_status = $row['status'];
        $row_first_licensed_on = $row['first_licensed_on']; // $row['First Licensed On'];
        $row_card = $row['card'];
        $row_valid_card_start = $row['valid_card_start']; // $row['Valid Card Start'];
        $row_valid_card_end = $row['valid_card_end'];// $row['Valid Card End'];
        $row_current_certificate_number = $row['current_certificate_number']; // $row['Current Certificate Number'];
        $row_notes = $row['notes'];
        
        
        $acc_status  = Accountstatus::where('acc_status', $row_acc_status)->first();
        $country = CountryList::where('country_name', $row_country)->first();
        // $rcdpw  =  RcDpwList::where('rc_dpw_name', $row_rc_dpw)->first();
        $title  =  TitleList::where('short_desc', $row_title)->first();
        $date_of_birth = $row_date_of_birth == '-' || $row_date_of_birth == '' ? NULL : $this->formatDateExcel($row_date_of_birth);
        $spouse_date_of_birth = $row_spouse_date_of_birth == '-' || $row_spouse_date_of_birth == '' ? NULL : $this->formatDateExcel($row_spouse_date_of_birth);
        $row_anniversary = trim($row_anniversary ?? '');
        $anniversary = $row_anniversary == '-' || $row_anniversary == '' ? NULL : $this->formatDateExcel($row_anniversary);
        $first_licensed_on = $row_first_licensed_on == '-' || $row_first_licensed_on == '' ? NULL : $this->formatDateExcel($row_first_licensed_on);
        $valid_card_start = $row_valid_card_start == '-' || $row_valid_card_start == '' ? NULL : $this->formatDateExcel($row_valid_card_start);
        $valid_card_end = $row_valid_card_end == '-' || $row_valid_card_end == '' || $row_valid_card_end == '(expired)' || strtolower($row_valid_card_end) == 'lifetime' ? NULL : $this->formatDateExcel($row_valid_card_end);
        $is_lifetime = strtolower($row_valid_card_end ?? '') == 'lifetime' ? "1" : "0";
        $address = trim(str_replace('_x000D_', "\n", $row_address ?? ''));
        $phone = trim(str_replace('_x000D_', "\n", $row_phone ?? ''));
        $church_name = trim(str_replace('_x000D_', "\n", $row_church_name ?? ""));
        $str_json_church = json_encode($this->handleChurchName($church_name));
        $email = (!isset($row_email) || strlen($row_email) == 0) ? null : $row_email;
        $secondary_email = (!isset($row_secondary_email) || strlen($row_secondary_email) == 0) ? null : $row_secondary_email;
    
        $check_exist_personel = Personel::where('first_name', $row_first_name)
                                ->where('last_name', $row_last_name)
                                ->where('date_of_birth', $date_of_birth)
                                ->exists();

        if ($check_exist_personel) {
            $update_personel = Personel::where('first_name', $row_first_name)
                                ->where('last_name', $row_last_name)
                                ->where('date_of_birth', $date_of_birth)
                                ->first();
            // $update_personel->acc_status_id = ($acc_status['id'] ?? null);
            // $update_personel->rc_dpw_id = ($rcdpw['id'] ?? null);

            $hitCompare = new HitCompare;
            $hitCompare->addFieldCompare(
                [
                    'first_name' => 'first_name',
                    'last_name' => 'last_name',
                    // 'profile_image' => 'profile_image',
                    'phone' => 'phone',
                    'email' => 'email',
                ], 
            $row);

            $com = $hitCompare->compareData($update_personel->toArray());

            $update_personel->title_id = $title['id'];
            $update_personel->first_name = $row_first_name;
            $update_personel->last_name = $row_last_name;
            $update_personel->gender = $row_gender;
            // $update_personel->church_name = $str_json_church;
            $update_personel->street_address = $address;
            $update_personel->city = $row_city;
            $update_personel->province = $row_province;
            $update_personel->postal_code = $row_postal_code;
            $update_personel->country_id = ($country['id'] ?? null);
            $update_personel->phone = $phone;
            $update_personel->fax = $row_fax;
            $update_personel->language = $language;
            $update_personel->email = $email;
            $update_personel->second_email = $secondary_email;
            $update_personel->marital_status = $row_marital_status;
            $update_personel->date_of_birth = $date_of_birth;
            $update_personel->spouse_name = $row_spouse_name;
            $update_personel->spouse_date_of_birth = $spouse_date_of_birth;
            $update_personel->anniversary = $anniversary;
            $update_personel->first_licensed_on = $first_licensed_on;
            $update_personel->card = $row_card;
            $update_personel->valid_card_start = $valid_card_start;
            $update_personel->valid_card_end = $valid_card_end;
            $update_personel->current_certificate_number = $row_current_certificate_number;
            $update_personel->notes = $row_notes;
            $update_personel->is_lifetime = $is_lifetime;
            $update_personel->save(); 

            if($com){
                $this->ids_update[] = $update_personel->id;
            }

            // $this->handleRcdpw($update_personel->id, $row_rc_dpw, 'update');

            if (StatusHistory::where('personel_id', $update_personel->id)->exists()) {
                $status_history = StatusHistory::where('personel_id', $update_personel->id)
                ->orderBy('date_status','desc')
                ->orderBy('created_at','desc')
                ->first();

                if($status_history->status != $acc_status['acc_status']){
                    if($com === FALSE){
                        $this->ids_update[] = $update_personel->id;
                    }
                }

                // if($status_history->status_histories_id != $acc_status['id']){
                //     if($com === FALSE){
                //         $this->ids_update[] = $update_personel->id;
                //     }
                // }

                // $status_history->status_histories_id = ($acc_status['id'] ?? null);
                $status_history->status = ($acc_status['acc_status'] ?? null);
                $status_history->date_status = Carbon::now();
                $status_history->save();
            }else{
                // $status_history = new StatusHistory([
                //     'status_histories_id'  => ($acc_status['id'] ?? null),
                //     'date_status' => Carbon::now(),
                //     'personel_id' => $update_personel->id,
                // ]);
                // $status_history->save();
                $status_history = new StatusHistory([
                    // 'status_histories_id'  => ($acc_status['id'] ?? null),
                    'status' => ($acc_status['acc_status'] ?? null),
                    'date_status' => Carbon::now(),
                    'personel_id' => $update_personel->id,
                ]);
                $status_history->save();
            }

            if (sizeof($this->handleChurchName($church_name)) > 0) {
                StructureChurch::where("personel_id", $update_personel->id)->delete();
                foreach ($this->handleChurchName($church_name) as $key => $cn) {
                    $insert_p = new StructureChurch();
                    $insert_p->title_structure_id = $cn['title_structure_id'];
                    $insert_p->churches_id = $cn['church_id'];
                    $insert_p->personel_id = $update_personel->id;
                    $insert_p->save();
                }
                (new LeadershipSyncHelper())->sync($update_personel->id);
            }
            
        }else{
            $new_personel = new Personel();
            // $new_personel->acc_status_id = ($acc_status['id'] ?? null);
            $new_personel->rc_dpw_id = ($rcdpw['id'] ?? null);
            $new_personel->title_id = $title['id'];
            $new_personel->first_name = $row_first_name;
            $new_personel->last_name = $row_last_name;
            $new_personel->gender = $row_gender;
            // $new_personel->church_name = $str_json_church;
            $new_personel->street_address = $address;
            $new_personel->city = $row_city;
            $new_personel->province = $row_province;
            $new_personel->postal_code = $row_postal_code;
            $new_personel->country_id = ($country['id'] ?? null);
            $new_personel->phone = $phone;
            $new_personel->fax = $row_fax;
            $new_personel->language = $language;
            $new_personel->email = $email;
            $new_personel->second_email = $secondary_email;
            $new_personel->marital_status = $row_marital_status;
            $new_personel->date_of_birth = $date_of_birth;
            $new_personel->spouse_name = $row_spouse_name;
            $new_personel->spouse_date_of_birth = $spouse_date_of_birth;
            $new_personel->anniversary = $anniversary;
            $new_personel->first_licensed_on = $first_licensed_on;
            $new_personel->card = $row_card;
            $new_personel->valid_card_start = $valid_card_start;
            $new_personel->valid_card_end = $valid_card_end;
            $new_personel->current_certificate_number = $row_current_certificate_number;
            $new_personel->notes = $row_notes;
            $new_personel->is_lifetime = $is_lifetime;
            $new_personel->save();

            $this->ids_create[] = $new_personel->id;

            $this->handleRcdpw($new_personel->id, $row_rc_dpw, 'create');

            // $status_history = new StatusHistory([
            //     'status_histories_id'  => ($acc_status['id'] ?? null),
            //     'date_status' => Carbon::now(),
            //     'personel_id' => $new_personel->id,
            // ]);
            // $status_history->save();

            $status_history = new StatusHistory([
                // 'status_histories_id'  => ($acc_status['id'] ?? null),
                'status' => ($acc_status['acc_status'] ?? null),
                'date_status' => Carbon::now(),
                'personel_id' => $new_personel->id,
            ]);
            $status_history->save();

            if (sizeof($this->handleChurchName($church_name)) > 0) {
                foreach ($this->handleChurchName($church_name) as $key => $cn) {
                    $insert_p = new StructureChurch();
                    $insert_p->title_structure_id = $cn['title_structure_id'];
                    $insert_p->churches_id = $cn['church_id'];
                    $insert_p->personel_id = $new_personel->id;
                    $insert_p->save();
                }
                (new LeadershipSyncHelper())->sync($new_personel->id);
            }
        }

    }

    public function headingRow(): int
    {
        return 2;
    }

    private function handleRcdpw($id, $value, $type){

        $rc_dpw = str_replace('\n', "\n", $value ?? '');

        if($type === 'update'){
            if(PersonelsRcdpw::where('personels_id', $id)->exists()){
                // hapus semua data churches rcd
                PersonelsRcdpw::where('personels_id', $id)->delete();
            }
        }

        if(strpos($rc_dpw, "\n") !== false){
            $e = explode("\n", $rc_dpw);
            foreach($e as $rc_){
                $id_rcdpw = RcDpwList::where('rc_dpw_name', $rc_)->first();
                $rc = new PersonelsRcdpw;
                $rc->personels_id = $id;
                $rc->rc_dpwlists_id = $id_rcdpw->id;
                $rc->save();
            }
        }else{
            $id_rcdpw = RcDpwList::where('rc_dpw_name', $rc_dpw)->first();
            $rc = new PersonelsRcdpw;
            $rc->personels_id = $id;
            $rc->rc_dpwlists_id = $id_rcdpw->id;
            $rc->save();
        }
    }

    private function handleChurchName($value){
        $arr_datas = [];
        $count_valid_data = 0;
        $total_data = 0;
        foreach(explode("\n",$value) as $sc) {
            $total_data++;
            if (strpos( $sc, "-") !== false) {
                $expl_dash = explode("-",$sc);
                $last_dash = substr_count($sc, "-");
                $last_dash = substr_count($sc, "-");

                $church_name = Church::where('church_name','like', '%'.rtrim($expl_dash[0]).'%')->first();
                $ministry_role = MinistryRole::where('ministry_role','like', '%'.trim($expl_dash[$last_dash]).'%')->first();

                if (isset($church_name) && isset($ministry_role)) {
                    $count_valid_data++;
                    $arr_datas[] = ['church_id' => $church_name->id, 'title_structure_id' => $ministry_role->id];
                }
            }  
        }

        return ($count_valid_data == $total_data) ? $arr_datas :[];
    }

    function formatDateExcel($dateExcel){
        if (is_numeric($dateExcel)) {
            return Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($dateExcel))->toDateString();
        }
        return Carbon::parse($dateExcel)->toDateString();
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'title' => 'required',
            // 'rc_dpw' => ['required',function($attribute, $value, $onFailure){
            //     if(strlen($value) > 0){
            //         $rc_dpw = trim($value);
            //         $rc_dpw = str_replace('\n', "\n", $rc_dpw ?? '');
            //         $is_fail = [];
            //         if (strpos( $rc_dpw, "\n") !== false) {
            //             $rc_dpws = explode("\n", $rc_dpw);
            //             foreach($rc_dpws as $data){
            //                 $d = RcDpwList::where('rc_dpw_name', $data)->first();
            //                 if($d == null){
            //                     $is_fail[] = $data;
            //                 }
            //             }
            //         }else{
            //             $d = RcDpwList::where('rc_dpw_name', $rc_dpw)->first();
            //             if(!isset($d)){
            //                 $is_fail[] = $rc_dpw;
            //             }
            //         }
    
            //         if(count($is_fail) > 0){
            //             $str_rc_dpw = implode(", ", $is_fail);
            //             $onFailure('RC / DPW are not invalid for ' . $str_rc_dpw);
            //         }
    
            //     }
            // }],
            'church_name' => function($attribute, $value, $onFailure) {
                $church_name = $this->handleChurchName($value);
                if ($value != "" && sizeof($church_name) == 0) {
                    $onFailure('Not Exist Church - Role or Invalid Format :: Church Name - Role');
                }
            },
            'language' => ['nullable', Rule::in(Personel::$arrayLanguage)]
        ];
    }

    public function customValidationMessages()
    {
        return [];
    }

}