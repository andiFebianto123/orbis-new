<?php

namespace App\Imports;

use App\Models\RcdpwList;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RcdpwListImport implements OnEachRow, /* ToModel, */  WithHeadingRow

{

    public $ids = [];

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();
        $rcdpwlist = new RcdpwList;
        $rcdpwlist->rc_dpw_name = $row['dpw'];
        $rcdpwlist->save();

        // for create
        $this->ids[] = $rcdpwlist->id;
    }

    // /**
    //  * @param array $row
    //  *
    //  * @return User|null
    //  */
    // public function model(array $row)
    // {
    //     return new RcdpwList([
    //        'rc_dpw_name'     => $row['dpw'],
    //     ]);
    // }
}