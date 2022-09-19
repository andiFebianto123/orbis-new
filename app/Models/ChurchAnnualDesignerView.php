<?php

namespace App\Models;

use Exception;
use App\Models\Church;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ChurchAnnualDesignerView extends Model
{
    use CrudTrait;
            
    protected $appends = ['leadership_structure', 'local_church', 'rdpw'];

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport()" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function getRdpwAttribute(){
        $id = $this->id;
        $d = Church::where('id', $id)->first()->rdpw_pivot;
        if($d !== null){
            $rdpw = '';
            foreach($d as $index => $st){
                if($index >= ($d->count() - 1)){
                    $rdpw .= $st->rc_dpw_name;
                }else{
                    $rdpw .= $st->rc_dpw_name.'<br>';
                }
            }
            return $rdpw;
        }
        return null;
    }

    public function scopeYear($query, $year){
        return $query->whereYear('founded_on', $year);
    }

    public function scopeRcDpw($query, $value){
        if($value != null){
            try{
                return $query->whereIn('rc_dpw_name', json_decode($value)); 
            //     $value = json_decode($value);
            //     $value = array_map(function($d){
            //         return "'$d'";
            //    }, $value);
            //    $value = implode(',', $value);
            //    $subQuery = "SELECT 1 FROM churches_rcdpw 
            //    INNER JOIN rc_dpwlists ON rc_dpwlists.id = churches_rcdpw.rc_dpwlists_id
            //    WHERE churches_rcdpw.churches_id = church_annual_designer_views.id AND rc_dpwlists.rc_dpw_name IN ({$value})";
            //    return $query->whereRaw("EXISTS ({$subQuery})");
            }
            catch(Exception $e){
                return $query->whereRaw(0);
            }
        }
        return $query;
    }

    public function getLocalChurchAttribute()
    {
        $lc = null;
        $church = Church::where('id' , $this->church_local_id)->first();
        if ($church) {
            $lc = $church->church_name;
        }
        return $lc;
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
        if($value != null){
            try{
                return $query->whereIn('entities_type', json_decode($value)); 
            }
            catch(Exception $e){
                return $query->whereRaw(0);
            }
        }
        return $query;
    }

    public function scopeCountry($query, $value){
        if($value != null){
            try{
                return $query->whereIn('country_name', json_decode($value)); 
            }
            catch(Exception $e){
                return $query->whereRaw(0);
            }
        }
        return $query;
    }

    public function scopeStatus($query, $value){
        if($value != null){
            try{
                return $query->whereIn('status', json_decode($value)); 
            }
            catch(Exception $e){
                return $query->whereRaw(0);
            }
        }
        return $query;
    }
}

