<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use App\Models\Personel;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class PastorAnnualDesignerView extends Model
{
    use CrudTrait;

    protected $appends = ['rdpw', 'church_name'];

    public function getRdpwAttribute(){
        $id = $this->id;
        $d = Personel::where('id', $id)->first()->pivod_rcdpw;
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

    public function ExportExcelButton(){
        return '<a href="javascript:void(0)"  onclick="exportReport()" class="btn btn-xs btn-success"><i class="la la-file-excel-o"></i> Export Button</a>';
    }

    public function scopeYear($query, $year){
        return $query->whereYear('first_licensed_on', $year);
    }

    public function scopeRcDpw($query, $value){
        if($value != null){
            try{
                // return $query->whereIn('rc_dpw_name', json_decode($value)); 
                $value = json_decode($value);
                $value = array_map(function($d){
                    return "'$d'";
               }, $value);
               $value = implode(',', $value);
               $subQuery = "SELECT 1 FROM personels_rcdpw 
               INNER JOIN rc_dpwlists ON rc_dpwlists.id = personels_rcdpw.rc_dpwlists_id
               WHERE personels_rcdpw.personels_id = pastor_annual_designer_views.id AND rc_dpwlists.rc_dpw_name IN ({$value})";
               return $query->whereRaw("EXISTS ({$subQuery})");
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
        // return $value == null ? $query : $query->where('country_name',$value); 
    }

    public function scopeShortDesc($query, $value){
        if($value != null){
            try{
                return $query->whereIn('short_desc', json_decode($value)); 
            }
            catch(Exception $e){
                return $query->whereRaw(0);
            }
        }
        return $query;
        // return $value == null ? $query : $query->where('short_desc',$value); 
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
        // return $value == null ? $query : $query->where('status',$value); 
    }

    public function scopeCard($query, $value){
        if($value != null){
            try{
                return $query->whereIn('card', json_decode($value)); 
            }
            catch(Exception $e){
                return $query->whereRaw(0);
            }
        }
        return $query;
        // return $value == null ? $query : $query->where('card',$value); 
    }

    public function scopeDayValid($query, $value){
        $todayNow = Carbon::now();
        $maximumValid = $todayNow->copy()->addDays(90);

        if($value == 'd90'){
            return $query->whereDate('valid_card_end', '>', $todayNow->toDateString())->whereDate('valid_card_end', '<=', $maximumValid->toDateString()); 
        }
        else if($value == 'expired'){
            return $query->whereDate('valid_card_end', '<=', $todayNow->toDateString());
        }
        else if($value == 'd90andexpired'){
            return $query->where(function($innerQuery) use($todayNow, $maximumValid){
                $innerQuery->whereDate('valid_card_end', '>', $todayNow->toDateString())
                ->whereDate('valid_card_end', '<=', $maximumValid->toDateString())
                ->orWhereDate('valid_card_end', '<=', $todayNow->toDateString());
            });
        }
        else if($value == 'all' || $value == null){
            return $query;
        }
        else{
            return $query->whereRaw(0);
        }
    }

    // public function getChurchNameAttribute($value)
    // {
    //     $str_role_church = "";
    //     $churhes  = json_decode($value);
    //         if (json_last_error() === JSON_ERROR_NONE) {
    //             $total = sizeof($churhes) - 1;
    //             foreach ($churhes as $key => $church) {
    //                 $church_name = Church::where('id', $church->church_id)->first();
    //                 $ministry_role = MinistryRole::where('id', $church->title_structure_id)->first();
    //                 if ( isset($church_name) && isset($ministry_role) ) {
    //                     $str_role_church .= $church_name->church_name." - ".$ministry_role->ministry_role;
    //                     if ($key < $total) {
    //                         $str_role_church .= "<br>";
    //                     }
    //                 }
    //             }
    //         }
                
    //     return $str_role_church;
    // }

    public function getChurchNameAttribute($value)
    {
        $str_role_church = "";
        $structureChurches = StructureChurch::join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                            ->join('churches', 'churches.id', 'structure_churches.churches_id')
                            ->where('personel_id', $this->id)
                            ->get(['churches.church_name', 'ministry_roles.ministry_role']);

        $total = sizeof($structureChurches);
        foreach ($structureChurches as $key => $sc) {
            $str_role_church .= $sc->church_name." - ".$sc->ministry_role;
            if ($key < $total) {
                $str_role_church .= "<br>";
            }
        }

        return $str_role_church;
    }

}
