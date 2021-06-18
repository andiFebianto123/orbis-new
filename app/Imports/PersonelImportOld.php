<?php

namespace App\Imports;

use App\Models\Personel;
use App\Models\LogErrorExcel;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\TitleList;
use App\Models\Accountstatus;
use App\Models\StatusHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class PersonelImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsOnFailure, WithCalculatedFormulas

{
    use Importable, SkipsFailures;

    public function __construct($code, $filename)
    {
        $this->code = $code;
        $this->filename = $filename;
        $this->failures = [];
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        
        $acc_status  = Accountstatus::where('acc_status', $row['acc_status'])->first();
        $country = CountryList::where('country_name', $row['country'])->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row['dpw'])->first();
        $title  =  TitleList::where('short_desc', $row['title'])->first();
        $date_of_birth = $row['date_of_birth'] == '-' || $row['date_of_birth'] == '' ? NULL : $this->formatDateExcel($row['date_of_birth']);
        $spouse_date_of_birth = $row['spouse_date_of_birth'] == '-' || $row['spouse_date_of_birth'] == '' ? NULL : $this->formatDateExcel($row['spouse_date_of_birth']);
        $row['anniversary'] = trim($row['anniversary'] ?? '');
        $anniversary = $row['anniversary'] == '-' || $row['anniversary'] == '' ? NULL : $this->formatDateExcel($row['anniversary']);
        $first_licensed_on = $row['first_licensed_on'] == '-' || $row['first_licensed_on'] == '' ? NULL : $this->formatDateExcel($row['first_licensed_on']);
        $valid_card_start = $row['valid_card_start'] == '-' || $row['valid_card_start'] == '' ? NULL : $this->formatDateExcel($row['valid_card_start']);
        $valid_card_end = $row['valid_card_end'] == '-' || $row['valid_card_end'] == '' || $row['valid_card_end'] == '(expired)' || strtolower($row['valid_card_end']) == 'lifetime' ? NULL : $this->formatDateExcel($row['valid_card_end']);
        $is_lifetime = strtolower($row['valid_card_end'] ?? '') == 'lifetime' ? "1" : "0";
        $address = trim(str_replace('_x000D_', "\n", $row['address'] ?? ''));
        $phone = trim(str_replace('_x000D_', "\n", $row['phone'] ?? ''));
        $email = (!isset($row['email']) || strlen($row['email']) == 0) ? null : $row['email'];
        // $address = trim(str_replace('_x005F_x000D_', " ", $row['address'] ?? ''));
        // $address = preg_replace('/\s+/', ' ', $address);

        // if ($rcdpw == NULL) {
        //     dd($row['rc_dpw']);
        // }

        // if ($country == NULL) {
        //     dd($row['country']);
        // }

        // if ($acc_status == NULL) {
        //     dd($row['acc_status']);
        // }

        //    if (isset($country)) {
        
        $personel = new Personel([
        'acc_status_id'  => ($acc_status['id'] ?? null),
        'rc_dpw_id'      => ($rcdpw['id'] ?? null),
        'title_id'      => $title['id'],
        'first_name'    => $row['first_name'],
        'last_name'      => $row['last_name'],
        'gender'         => $row['gender'],
        'church_name'    => $row['church_name'],
        'street_address' => $address,
        'city'           => $row['city'],
        'province'       => $row['province'],
        'postal_code'    => $row['postal_code'],
        'country_id'     => ($country['id'] ?? null),
        'phone'          => $phone,
        'fax'            => $row['fax'],
        'email'          => $email,
        'marital_status' => $row['marital_status'],
        'date_of_birth'  => $date_of_birth,
        'spouse_name'    => $row['spouse_name'],
        'spouse_date_of_birth'          => $spouse_date_of_birth,
        'anniversary'                   => $anniversary,
        'first_licensed_on'             => $first_licensed_on,
        'card'                          => $row['card'],
        'valid_card_start'              => $valid_card_start,
        'valid_card_end'                => $valid_card_end,
        'current_certificate_number'    => $row['current_certificate_number'],
        'notes'           => $row['notes'],
        'is_lifetime'     => $is_lifetime,
        ]);

        $personel->save();

        $status_history = new StatusHistory([
            'status_histories_id'  => ($acc_status['id'] ?? null),
            'date_status' => Carbon::now(),
            'personel_id' => $personel->id,
        ]);

        $status_history->save();
    }
    // }

    public function rules(): array
    {
        return [
            // 'email' => 'unique:personels,email',
            // 'acc_status_id' => 'required',
            // 'dpw' => 'required|exists:rc_dpwlists,rc_dpw_name',
            // 'country' => 'required|exists:country_lists,country_name'
        ];
    }

    // public function customValidationMessages()
    // {
    //     return [
    //         'email.required'    => 'Email must not be empty!',
    //         'email.unique'      => 'The Personel email has already been used',
    //     ];
    // }

    function formatDateExcel($dateExcel){
        if (is_numeric($dateExcel)) {
            return Carbon::createFromTimestamp(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($dateExcel))->toDateString();
        }
        return Carbon::parse($dateExcel)->toDateString();
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = $failures;
        foreach ($failures as $failure) {
            $insert = new LogErrorExcel();
            $insert->row = $failure->row();
            $insert->type = 'Personel';
            $insert->code = $this->code;
            $insert->file_name = $this->filename;
            $insert->description = json_encode($failure->errors());

            $insert->save();
        }
    }

    public function failures()
    {
        return $this->failures;
    }
}