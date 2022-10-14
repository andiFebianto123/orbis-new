<?php

namespace App\Rules;

use App\Models\Personel;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueUserOnPersonel implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $existsOnPersonel = Personel::where('email', $value)->exists();
        return !$existsOnPersonel;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has been used by personel';
    }
}