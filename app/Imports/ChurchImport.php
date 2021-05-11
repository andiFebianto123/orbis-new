<?php

namespace App\Imports;

use App\Models\Church;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\LogErrorExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ChurchImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure

{
    use Importable, SkipsFailures;

    public function __construct($code, $filename)
    {
        $this->code = $code;
        $this->filename = $filename;
        $this->failures = [];
    }

    public function model(array $row)
    {
        $country  = CountryList::where('country_name', $row['country'])->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row['rc_dpw'])->first();
        $row['founded_on'] = trim($row['founded_on'] ?? '');
        $date =  $row['founded_on'] == '-' || $row['founded_on'] == '' ? NULL : $this->formatDateExcel($row['founded_on']);
        $lead_pastor_name = $row['lead_pastor_name'] == '. ' ? NULL : $row['lead_pastor_name'];
        $contact_person = $row['contact_person'] == '-' || $row['contact_person'] == '' ? NULL : $row['contact_person'];
        $church_address = $row['church_address'] == '-' || $row['church_address'] == '' ? NULL : $row['church_address'];
        $office_address = $row['office_address'] == '-' || $row['office_address'] == '' ? NULL : $row['office_address'];
        $city = $row['city'] == '-' || $row['city'] == '' ? NULL : $row['city'];
        $province = $row['province'] == '-' || $row['province'] == '' ? NULL : $row['province'];
        $phone = $row['phone'] == '-' || $row['phone'] == '' ? NULL : $row['phone'];
        $fax = $row['fax'] == '-' || $row['fax'] == '' ? NULL : $row['fax'];
        $postal_code = $row['postal_code'] == '-' || $row['postal_code'] == '' ? NULL : $row['postal_code'];
        $first_email = $row['first_email'] == '-' || $row['first_email'] == '' ? NULL : $row['first_email'];
        $service_time_church = $row['service_time_church'] == ',' ? NULL : $row['service_time_church'];

        // if ($rcdpw == NULL) {
        //     dd($row['rc_dpw']);
        // }

        // if ($country == NULL) {
        //     dd($row['country']);
        // }

       if (isset($country) && isset($rcdpw) ) {
        
        return new Church([
           'church_status'  => $row['church_status'],
           'founded_on'     => $date,
           'rc_dpw_id'      => ($rcdpw['id'] ?? null),
           'church_name'    => $row['church_name'],
           'lead_pastor_name' => $lead_pastor_name,
           'contact_person'   => $contact_person,
           'church_address'   => $church_address,
           'office_address' => $office_address,
           'city'           => $city,
           'province'    => $province,
           'postal_code' => $postal_code,
           'country_id'  => ($country['id'] ?? null),
           'first_email'    => $first_email,
           'phone' => $phone,
           'fax'    => $fax,
           'service_time_church' => $service_time_church,
        ]);
       }      
    }

    public function rules(): array
    {
        return [
            // 'rc_dpw' => 'required|exists:rc_dpwlists,rc_dpw_name',
            // 'country' => 'required|exists:country_lists,country_name',
            // 'first_email' => 'required|email|unique:churches,first_email',
        ];
    }

    // public function customValidationMessages()
    // {
    //     return [
    //         'first_email.required'    => 'Email must not be empty!',
    //         'first_email.email'       => 'Incorrect Church Email Address!',
    //         'first_email.unique'      => 'The Church email has already been used',
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
            $insert->type = 'Church';
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