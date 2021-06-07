<?php

namespace App\Rules;

use Illuminate\Http\File;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Base64Rule implements Rule
{
    private $errorImage = false;
    private $size = 0;
    private $mimes = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($size, $mimes)
    {
        $this->size = $size;
        $this->mimes = $mimes;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!request()->{$attribute . '_change'}){
            return true;
        }

        $base64data = $value;
        // strip out data uri scheme information (see RFC 2397)
        if (strpos($base64data, ';base64') !== false) {
            list(, $base64data) = explode(';', $base64data);
            list(, $base64data) = explode(',', $base64data);
        }

        // strict mode filters for non-base64 alphabet characters
        if (base64_decode($base64data, true) === false) {
            $this->errorImage = true;
            return false;
        }

        // decoding and then reeconding should not change the data
        if (base64_encode(base64_decode($base64data)) !== $base64data) {
            $this->errorImage = true;
            return false;
        }

        $binaryData = base64_decode($base64data);

        // temporarily store the decoded data on the filesystem to be able to pass it to the fileAdder
        $tmpFile = tempnam(sys_get_temp_dir(), 'medialibrary');
        file_put_contents($tmpFile, $binaryData);

        // guard Against Invalid MimeType
        $allowedMime = $this->mimes;

        // no allowedMimeTypes, then any type would be ok
        if (empty($allowedMime)) {
            return true;
        }

        // Check the MimeTypes
        $validation = Validator::make(
            ['file' => new File($tmpFile)],
            ['file' => 'mimes:' . implode(',', $allowedMime)]
        );
        $this->errorImage = $validation->fails();
        if($this->errorImage){
            return !$this->errorImage;
        }
        $sizeInMb = (strlen($base64data) - 814) / 1.37 / 1000000;
        return $sizeInMb <= $this->size;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorImage ? trans('validation.base64_image', ['mimes' => strtoupper(implode(', ', $this->mimes))]) : trans('validation.base64_image_size', ['size' => $this->size]);
    }
}
