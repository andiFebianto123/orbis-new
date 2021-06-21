<?php

namespace App\Imports;

use App\Models\Personel;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\TitleList;
use App\Models\Accountstatus;
use App\Models\StatusHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToCollection;
HeadingRowFormatter::default('none');

class PersonelImport implements ToCollection, WithHeadingRow, WithValidation
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
        $row_title = $row['Title'];
        $row_first_name = $row['First Name'];
        $row_last_name = $row['Last Name'];
        $row_gender = $row['Gender'];
        $row_church_name = $row['Church Name'];
        $row_address = $row['Address'];
        $row_city = $row['City'];
        $row_province = $row['Province / State'];
        $row_postal_code = $row['Postal Code'];
        $row_country = $row['Country'];
        $row_phone = $row['Phone'];
        $row_fax = $row['Fax'];
        $row_email = $row['Email'];
        $row_marital_status = $row['Marital Status'];
        $row_date_of_birth = $row['Date of Birth'];
        $row_spouse_name = $row['Spouse Name'];
        $row_spouse_date_of_birth = $row['Spouse Date of Birth'];
        $row_anniversary = $row['Anniversary'];
        $row_acc_status = $row['Status'];
        $row_first_licensed_on = $row['First Licensed On'];
        $row_card = $row['Card'];
        $row_valid_card_start = $row['Valid Card Start'];
        $row_valid_card_end = $row['Valid Card End'];
        $row_current_certificate_number = $row['Current Certificate Number'];
        $row_notes = $row['Notes'];
        
        
        $acc_status  = Accountstatus::where('acc_status', $row_acc_status)->first();
        $country = CountryList::where('country_name', $row_country)->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row_rc_dpw)->first();
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
        $email = (!isset($row_email) || strlen($row_email) == 0) ? null : $row_email;
    
        $check_exist_personel = Personel::where('first_name', $row_first_name)
                                ->where('last_name', $row_last_name)
                                ->where('date_of_birth', $date_of_birth)
                                ->exists();

        if ($check_exist_personel) {
            $update_personel = Personel::where('first_name', $row_first_name)
                                ->where('last_name', $row_last_name)
                                ->where('date_of_birth', $date_of_birth)
                                ->first();
            $update_personel->acc_status_id = ($acc_status['id'] ?? null);
            $update_personel->rc_dpw_id = ($rcdpw['id'] ?? null);
            $update_personel->title_id = $title['id'];
            $update_personel->first_name = $row_first_name;
            $update_personel->last_name = $row_last_name;
            $update_personel->gender = $row_gender;
            $update_personel->church_name = $row_church_name;
            $update_personel->street_address = $address;
            $update_personel->city = $row_city;
            $update_personel->province = $row_province;
            $update_personel->postal_code = $row_postal_code;
            $update_personel->country_id = ($country['id'] ?? null);
            $update_personel->phone = $phone;
            $update_personel->fax = $row_fax;
            $update_personel->email = $email;
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

            $status_history = StatusHistory::where('personel_id', $update_personel->id)->first();
            $status_history->status_histories_id = ($acc_status['id'] ?? null);
            $status_history->date_status = Carbon::now();
            $status_history->save();

        }else{
            $new_personel = new Personel();
            $new_personel->acc_status_id = ($acc_status['id'] ?? null);
            $new_personel->rc_dpw_id = ($rcdpw['id'] ?? null);
            $new_personel->title_id = $title['id'];
            $new_personel->first_name = $row_first_name;
            $new_personel->last_name = $row_last_name;
            $new_personel->gender = $row_gender;
            $new_personel->church_name = $row_church_name;
            $new_personel->street_address = $address;
            $new_personel->city = $row_city;
            $new_personel->province = $row_province;
            $new_personel->postal_code = $row_postal_code;
            $new_personel->country_id = ($country['id'] ?? null);
            $new_personel->phone = $phone;
            $new_personel->fax = $row_fax;
            $new_personel->email = $email;
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

            $status_history = new StatusHistory([
                'status_histories_id'  => ($acc_status['id'] ?? null),
                'date_status' => Carbon::now(),
                'personel_id' => $new_personel->id,
            ]);

            $status_history->save();
        }

    }

    public function headingRow(): int
    {
        return 2;
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
            'First Name' => 'required',
            'Title' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [];
    }

}