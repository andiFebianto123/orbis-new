<?php

namespace App\Imports;

use App\Models\Personel;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\TitleList;
use App\Models\Accountstatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PersonelImport implements ToModel, WithHeadingRow

{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        $acc_status  = Accountstatus::where('acc_status', $row['acc_status'])->first();
        $country  = CountryList::where('country_name', $row['country'])->first();
        $rcdpw  =  RcDpwList::where('rc_dpw_name', $row['dpw'])->first();
        $title  =  TitleList::where('short_desc', $row['title'])->first();
        $date_of_birth = $row['date_of_birth'] == '-' ? NULL : Carbon::parse($row['date_of_birth'])->toDateString();
        $spouse_date_of_birth = $row['spouse_date_of_birth'] == '-' ? NULL : Carbon::parse($row['spouse_date_of_birth'])->toDateString();
        $anniversary = $row['anniversary'] == '-' ? NULL : Carbon::parse($row['anniversary'])->toDateString();

        // if ($rcdpw == NULL) {
        //     dd($row['rc_dpw']);
        // }

        // if ($country == NULL) {
        //     dd($row['country']);
        // }

        // if ($acc_status == NULL) {
        //     dd($row['acc_status']);
        // }

       if (isset($country)) {
        
        return new Personel([
           'acc_status_id'  => $acc_status['id'],
           'rc_dpw_id'      => $rcdpw['id'],
           'title_id'      => $title['id'],
           'first_name'    => $row['first_name'],
           'last_name'      => $row['last_name'],
           'church_name'    => $row['church_name'],
           'street_address' => $row['address'],
           'city'           => $row['city'],
           'province'       => $row['province'],
           'postal_code'    => $row['postal_code'],
           'country_id'     => $country['id'],
           'phone'          => $row['phone'],
           'fax'            => $row['fax'],
           'email'          => $row['email'],
           'date_of_birth'  => $date_of_birth,
           'spouse_name'    => $row['spouse_name'],
           'spouse_date_of_birth'  => $spouse_date_of_birth,
           'anniversary'  => $anniversary,
           'card'           => $row['card'],
        ]);
       }
    }
}