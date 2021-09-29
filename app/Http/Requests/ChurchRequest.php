<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ChurchRequest extends FormRequest
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
        // 'church_status' => 'required',
        // 'founded_on' => 'required',
        // 'church_id' => 'required',
        // 'church_type_id'=> 'required',
        'rc_dpw_id' => 'required',
        'church_name'=> 'required',
        'contact_person' => 'required',
        // 'building_name' => 'required',
        'church_address' => 'required',
        'office_address' => 'required',
        'city' => 'required',
        'province' => 'required',
        'postal_code' => 'required',
        'country_id' => 'required',
        'first_email' => 'required|email' ,
        'phone' => 'required',
        'fax' => 'required',
        "certificate" => "required_if:check_certificate,==,1",
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
