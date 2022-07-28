<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
 
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
        // 'church_status',
        'founded_on',
        'church_type_id',
        'church_local_id',
        // 'rc_dpw_id',
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
        'certificate',
        'date_of_certificate',
        'notes',
        'task_color',
        'latitude',
        'longitude',
    ];
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    protected $appends = ['rdpw'];

    public function getRdpwAttribute()
    {
        return $this->churches_rcdpw->map(function($entry){
            return $entry->rcdpwlists;
        });
    }


    public function rdpw_pivot(){
        return $this->belongsToMany('App\Models\RcDpwList', 'App\Models\ChurchesRcdpw', 'churches_id', 'rc_dpwlists_id');
    }

    public function churches_rcdpw(){
        return $this->hasMany('App\Models\ChurchesRcdpw', 'churches_id');
    }

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

    public function last_status() {
        return $this->hasOne('App\Models\StatusHistoryChurch', 'churches_id')
             ->select('*')
            //  ->orderBy('id', 'desc');
            ->orderBy(DB::raw("DATE_FORMAT(date_status,'%Y-%m-%d')"), 'DESC');

    }

    public function related_entity_church()
    {
        return $this->hasMany('App\Models\RelatedEntityChurch', 'churches_id', 'id');
    }

    public function ministry_role_church()
    {
        return $this->hasMany('App\Models\StructureChurch', 'churches_id', 'id');
    }

    public function title_list_personel() {
        return $this->belongsToMany('App\Models\Personel', 'title_lists', 'title_id', 'personel_id');
    }

    public function coordinator_church()
    {
        return $this->hasMany('App\Models\CoordinatorChurch', 'churches_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            Storage::delete(Str::replaceFirst('storage/','public/', $obj->certificate));
        });
    }
    
    public function setCertificateAttribute($value)
    {
        $attribute_name = "certificate";
        // destination path relative to the disk above
        $destination_path = "public/images_church_certificate";

        if(request()->{$attribute_name . '_change'}){
             // if the image was erased
            if ($value==null) {
                // delete the image from disk
                Storage::delete(Str::replaceFirst('storage/','public/', $this->{$attribute_name}));

                // set null in the database column
                $this->attributes[$attribute_name] = null;
            }

            // if a base64 was sent, store it in the db
            if (Str::startsWith($value, 'data:image'))
            {
                // 0. Make the image
                $image = Image::make($value)->encode('jpg', 75);

                //1. Resize Image
                $width = $image->width();
                $height = $image->height();
                if($width > 750 || $height > 750){
                    $image->resize(750, 750, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // 2. Generate a filename.
                $filename = md5($value.time()).'.jpg';

                // 3. Store the image on disk.
                Storage::put($destination_path.'/'.$filename, $image->stream());

                // 4. Delete the previous image, if there was one.
                Storage::delete(Str::replaceFirst('storage/','public/', $this->{$attribute_name}));

                // 5. Save the public path to the database
                // but first, remove "public/" from the path, since we're pointing to it
                // from the root folder; that way, what gets saved in the db
                // is the public URL (everything that comes after the domain name)
                $public_destination_path = Str::replaceFirst('public/', 'storage/', $destination_path);
                $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
            }
        }   
    }
}
