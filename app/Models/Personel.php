<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Personel extends Authenticatable
{
    use CrudTrait, HasApiTokens;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'personels';
    protected $fillable = [
        // 'acc_status_id',
        'rc_dpw_id',
        'title_id',
        'first_name',
        'last_name',
        'church_name',
        'gender',
        'date_of_birth',
        'marital_status',
        'spouse_name',
        'spouse_date_of_birth',
        'anniversary',
        'child_name',
        'ministry_background',
        'career_background',
        'image',
        'street_address',
        'city',
        'province',
        'postal_code',
        'country_id',
        'email',
        'second_email',
        'phone',
        'fax',
        'first_licensed_on',
        'card',
        'valid_card_start',
        'valid_card_end',
        'current_certificate_number',
        'certificate',
        'id_card',
        'notes',
        'is_lifetime',
        'password',
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $dates = [];

    protected $hidden = [
        'password',
    ];

    // public function accountstatus()
    // {
    //     return $this->belongsTo('App\Models\Accountstatus', 'acc_status_id', 'id');
    // }

    public function rc_dpw()
    {
        return $this->belongsTo('App\Models\RcDpwList', 'rc_dpw_id', 'id');
    }

    public function title()
    {
        return $this->belongsTo('App\Models\TitleList', 'title_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\CountryList', 'country_id', 'id');
    }

    public function appointment_history()
    {
        return $this->hasMany('App\Models\Appointment_history', 'personel_id', 'id');
    }

    public function related_entity()
    {
        return $this->hasMany('App\Models\Relatedentity', 'personel_id', 'id');
    }

    public function education_background()
    {
        return $this->hasMany('App\Models\EducationBackground', 'personel_id', 'id');
    }

    public function status_history()
    {
        return $this->hasMany('App\Models\StatusHistory', 'personel_id', 'id');
    }

    public function special_role_personel()
    {
        return $this->hasMany('App\Models\SpecialRolePersonel', 'personel_id', 'id');
    }

    public function child_name_pastor()
    {
        return $this->hasMany('App\Models\ChildNamePastors', 'personel_id', 'id');
    }

    public function ministry_background_pastor()
    {
        return $this->hasMany('App\Models\MinistryBackgroundPastor', 'personel_id', 'id');
    }

    public function career_background_pastor()
    {
        return $this->hasMany('App\Models\CareerBackgroundPastors', 'personel_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
    parent::boot();
    static::deleting(function($obj) {
        Storage::delete(Str::replaceFirst('storage/','public/', $obj->image));
        Storage::delete(Str::replaceFirst('storage/','public/', $obj->certificate));
        Storage::delete(Str::replaceFirst('storage/','public/', $obj->id_card));
    });
    }


    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        // destination path relative to the disk above
        $destination_path = "public/images_personel";

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

    public function setCertificateAttribute($value)
    {
        $attribute_name = "certificate";
        // destination path relative to the disk above
        $destination_path = "public/images_personel_certificate";

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

    public function setIdCardAttribute($value)
    {
        $attribute_name = "id_card";
        // destination path relative to the disk above
        $destination_path = "public/images_personel_id_card";

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
