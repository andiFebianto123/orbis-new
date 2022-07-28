<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Church;
use App\Models\RcDpwList;
 
class ChurchesRcdpw extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'churches_rcdpw';
    protected $fillable = [
        'churches_id',
        'rc_dpwlists_id'
    ];
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function churches(){
        return $this->belongsTo(Church::class, 'churches_id');
    }

    public function rcdpwlists(){
        return $this->belongsTo(RcDpwList::class, 'rc_dpwlists_id');
    }

}
