<?php

namespace App\Imports;

use App\Models\Church;
use App\Models\CountryList;
use App\Models\RcDpwList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChurchImport implements ToModel, WithHeadingRow

{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        $country  = CountryList::where('country_name', $row['country'])->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row['rc_dpw'])->first();
        $date =  $row['founded_on'] == '-' ? NULL : Carbon::parse($row['founded_on'])->toDateString();

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
           'rc_dpw_id'      => $rcdpw['id'],
           'church_name'    => $row['church_name'],
           'lead_pastor_name' => $row['lead_pastor_name'],
           'contact_person'   => $row['contact_person'],
           'church_address'   => $row['church_address'],
           'office_address' => $row['office_address'],
           'city'           => $row['city'],
           'province'    => $row['province'],
           'postal_code' => $row['postal_code'],
           'country_id'  => $country['id'],
           'first_email'    => $row['first_email'],
           'phone' => $row['phone'],
           'fax'    => $row['fax'],
           'service_time_church' => $row['service_time_church'],
        ]);
       }

       
    }
}