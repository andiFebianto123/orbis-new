<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ChurchAnnualDesignerView extends Model
{
    use CrudTrait;

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport(this,AnnualReport)" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function scopeYear($query, $year){
        return $query->whereYear('founded_on', $year);
    }
}
