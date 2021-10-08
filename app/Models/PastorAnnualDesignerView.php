<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PastorAnnualDesignerView extends Model
{
    use CrudTrait;

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport()" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function scopeYear($query, $year){
        return $query->whereYear('first_licensed_on', $year);
    }

    public function scopeRcDpw($query, $value){
        return $value == null ? $query : $query->where('rc_dpw_name',$value); 
    }

    public function scopeCountry($query, $value){
        return $value == null ? $query : $query->where('country_name',$value); 
    }

    public function scopeShortDesc($query, $value){
        return $value == null ? $query : $query->where('short_desc',$value); 
    }

    public function scopeStatus($query, $value){
        return $value == null ? $query : $query->where('status',$value); 
    }

    public function scopeCard($query, $value){
        return $value == null ? $query : $query->where('card',$value); 
    }

    public function scopeDayValid($query, $value){
        $todayNow = Carbon::now();
        $maximumValid = $todayNow->copy()->subDays(90);
        
        return $value == 'all' || $value == null ? $query : $query->whereDate('valid_card_end', '<=', $todayNow->toDateString())->whereDate('valid_card_end', '>=', $maximumValid->toDateString()); 
    }

    public function getChurchNameAttribute($value)
    {
        $churhes  = json_decode($value);
        $str_role_church = "";
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($churhes as $key => $church) {
                    $church_name = Church::where('id', $church->church_id)->first()->church_name ?? '';
                    $ministry_role = MinistryRole::where('id', $church->title_structure_id)->first()->ministry_role ?? '';
                    $str_role_church .= $church_name." - ".$ministry_role."<br>";
                }
            }
                
        return $str_role_church;
    }
}
