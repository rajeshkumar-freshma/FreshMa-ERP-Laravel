<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SystemSiteSettingFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'site_name' => 'required|string|max:255',
            'language' => 'required|integer', // Assuming language is an integer representing language code
            'currency' => 'required|integer', // Assuming currency is an integer representing currency code
            'accounting_method' => 'required|integer',
            'email' => 'required|email|max:255',
            'customer_group' => 'required|integer',
            'price_group' => 'required|integer',
            'mmode' => 'required|integer',
            'theme' => 'required|integer',
            // 'rtl' => 'required|integer',
            'captcha' => 'required|integer',
            'disable_editing' => 'required|integer',
            'rows_per_page' => 'required|integer',
            'dateformat' => 'required|integer',
            'timezone' => 'required|string|max:255',
            // 'restrict_calendar' => 'required|integer',
            'warehouse' => 'required|integer',
            // 'biller' => 'required|integer',
            'pdf_lib' => 'required|integer',
            'apis' => 'required|integer',
            'use_code_for_slug' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.string' => 'This field must be a string',
            '*.integer' => 'This field must be an integer',
            'email.email' => 'Please enter a valid email address',
        ];
    }
}
