<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class PastorAnnualDesignerView extends Model
{
    use CrudTrait;

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport(this,AnnualReport)" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function scopeYear($query, $year){
        return $query->whereYear('first_licensed_on', $year);
    }
}
