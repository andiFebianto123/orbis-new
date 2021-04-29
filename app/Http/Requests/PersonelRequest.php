<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'required|min:5|max:255'
        'acc_status_id' => 'required',
        'rc_dpw_id' => 'required',
        'title_id' => 'required',
        'first_name'=> 'required',
        'last_name' => 'required',
        'gender'=> 'required',
        'date_of_birth' => 'required',
        'marital_status' => 'required',
        'ministry_background' => 'required',
        'career_background' => 'required',
        'password' => Rule::requiredIf($this->method() == 'POST'),
        'street_address' => 'required',
        'city' => 'required',
        'province' => 'required',
        'postal_code' => 'required',
        'country_id' => 'required',
        'email' => 'required|email',
        'first_licensed_on' => 'required',
        'card' => 'required',
        'valid_card_start' => 'required',
        'valid_card_end'=> 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
