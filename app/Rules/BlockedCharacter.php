<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BlockedCharacter implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->blockedCharackter = ["-", "(", ")"];
        $this->currentChar = "";
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
        $passed = true;
        foreach ($this->blockedCharackter as $key => $bc) {
            if (str_contains($value, $bc)) {
                $passed = false;
                $this->currentChar = $bc;
            }
        }
        return $passed;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // $strBlockedChar = "";
        // foreach ($this->blockedCharackter as $key => $bc) {
        //     $strBlockedChar .= " ".$bc;
        // }

        return 'The :attribute must not contains '.$this->currentChar;
    }
}
