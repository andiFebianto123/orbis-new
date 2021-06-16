<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ChurchAnnualDesignerView extends Model
{
    use CrudTrait;

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport()" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function scopeYear($query, $year){
        return $query->whereYear('founded_on', $year);
    }

    public function scopeRcDpw($query, $value){
        return $value == null ? $query : $query->where('rc_dpw_name',$value); 
    }

    public function scopeType($query, $value){
        return $value == null ? $query : $query->where('entities_type',$value); 
    }

    public function scopeCountry($query, $value){
        return $value == null ? $query : $query->where('country_name',$value); 
    }

    public function scopeStatus($query, $value){
        return $value == null ? $query : $query->where('status',$value); 
    }
}

