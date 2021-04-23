<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'churches';
    protected $fillable = [
        'church_status',
        'founded_on',
        'church_id',
        'church_type_id',
        'rc_dpw_id',
        'church_name',
        'lead_pastor_name',
        'contact_person',
        'building_name',
        'church_address',
        'office_address',
        'city',
        'province',
        'postal_code',
        'country_id',
        'first_email',
        'second_email',
        'phone',
        'fax',
        'website',
        'map_url',
        'service_time_church',
    ];
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public function church_type()
    {
        return $this->belongsTo('App\Models\ChurchEntityType', 'church_type_id', 'id');
    }

    public function rc_dpw()
    {
        return $this->belongsTo('App\Models\RcDpwList', 'rc_dpw_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountryList', 'country_id', 'id');
    }

    public function legal_document_church()
    {
        return $this->hasMany('App\Models\LegalDocumentChurch', 'churches_id', 'id');
    }

    public function service_type_church()
    {
        return $this->hasMany('App\Models\ServiceTimeChurch', 'churches_id', 'id');
    }

    public function status_history_church()
    {
        return $this->hasMany('App\Models\StatusHistoryChurch', 'churches_id', 'id');
    }

    public function related_entity_church()
    {
        return $this->hasMany('App\Models\RelatedEntityChurch', 'churches_id', 'id');
    }

    public function ministry_role_church()
    {
        return $this->hasMany('App\Models\StructureChurch', 'churches_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
