<?php

namespace App\Imports;

use App\Models\RcdpwList;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RcdpwListImport implements ToModel, WithHeadingRow

{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new RcdpwList([
           'rc_dpw_name'     => $row['dpw'],
        ]);
    }
}