<?php

namespace App\Models;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonelImage extends Model
{
    use HasFactory;
    protected $fillable = ['personel_id', 'image', 'label'];


    public static $imageLabels = ['Profile Photo', 'Family Photo'];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            Storage::delete(Str::replaceFirst('storage/','public/', $obj->image));
        });
    }

   public function setImagePersonel($destination_path, $value, $attribute_name, $suffix){

        // // if the image was erased
        if ($value == null) {
            // delete the image from disk
            Storage::delete(Str::replaceFirst('storage/','public/', $this->{$attribute_name}));

            // set null in the database column
            return null;
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
            $filename = md5($value.time()) . $suffix .'.jpg';

            // 3. Store the image on disk.
            Storage::put($destination_path.'/'.$filename, $image->stream());

            // 4. Delete the previous image, if there was one.
            Storage::delete(Str::replaceFirst('storage/','public/', $this->{$attribute_name}));

            // 5. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = Str::replaceFirst('public/', 'storage/', $destination_path);
            return $public_destination_path.'/'.$filename;
        }

        return null;
   }
}
