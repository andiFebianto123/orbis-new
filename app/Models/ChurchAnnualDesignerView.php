<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ChurchAnnualDesignerView extends Model
{
    use CrudTrait;
            
    protected $appends = ['leadership_structure'];

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport()" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function scopeYear($query, $year){
        return $query->whereYear('founded_on', $year);
    }

    public function scopeRcDpw($query, $value){
        return $value == null ? $query : $query->where('rc_dpw_name',$value); 
    }

    public function getLeadershipStructureAttribute(){
        $current_id = $this->attributes['id'];
        $str_leadership = ""; 
        $leaderships = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                            ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                            ->join('title_lists', 'title_lists.id', 'personels.title_id')
                            ->where('structure_churches.churches_id', $current_id)
                            ->get(['structure_churches.id as id', 'ministry_roles.ministry_role as ministry_role', 
                            'title_lists.short_desc', 'title_lists.long_desc','personels.first_name', 'personels.last_name']);
        $total = sizeof($leaderships) - 1;                    
        foreach ($leaderships as $key => $leadership) {
            $str_leadership .= $leadership->first_name." ".$leadership->last_name. " - ".$leadership->ministry_role;
            if ($key < $total) {
                $str_leadership .= "<br>";
            }
        }
        return $str_leadership;
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

