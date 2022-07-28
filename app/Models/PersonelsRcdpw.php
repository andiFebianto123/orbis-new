<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\RcDpwList;
use APp\Models\Personel;
 
class PersonelsRcdpw extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'personels_rcdpw';
    protected $fillable = [
        'personels_id',
        'rc_dpwlists_id'
    ];
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function personels(){
        return $this->belongsTo(Personel::class, 'personels_id');
    }

    public function rcdpwlists(){
        return $this->belongsTo(RcDpwList::class, 'rc_dpwlists_id');
    }

}
