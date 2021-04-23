<?php

namespace App\Imports;

use App\Models\CountryList;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CountryListImport implements ToModel, WithHeadingRow

{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new CountryList([
           'iso_two'     => $row['iso_two'],
           'iso_three'    => $row['iso_three'],
           'country_name' => $row['country_name'],
        ]);
    }
}