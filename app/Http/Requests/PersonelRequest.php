<?php

namespace App\Http\Requests;

use App\Rules\Base64Rule;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
        // 'acc_status_id' => 'required',
        'rc_dpw_id' => 'required',
        'title_id' => 'required',
        'first_name'=> 'required',
        'last_name' => 'required',
        'gender'=> 'nullable',
        'date_of_birth' => 'nullable',
        'marital_status' => 'nullable',
        'ministry_background' => 'nullable',
        'career_background' => 'nullable',
        'password' => Rule::requiredIf($this->method() == 'POST'),
        'street_address' => 'nullable',
        'city' => 'nullable',
        'province' => 'nullable',
        'postal_code' => 'nullable',
        'country_id' => 'nullable',
        'email' => 'nullable|email',
        'first_licensed_on' => 'required',
        'card' => 'required',
        'valid_card_start' => 'required',
        'valid_card_end'=> 'required',
        'current_certificate_number'=> 'required',
        'image' => ['nullable', new Base64Rule(3, ['png', 'jpg', 'jpeg'])],
        'certificate' => ['nullable', new Base64Rule(3, ['png', 'jpg', 'jpeg'])],
        'id_card' => ['nullable', new Base64Rule(3, ['png', 'jpg', 'jpeg'])]
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
            'email.email'      => 'The email must be a valid email address.'
        ];
    }
}
