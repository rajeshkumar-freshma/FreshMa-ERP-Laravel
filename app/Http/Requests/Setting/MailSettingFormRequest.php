<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Request;

class MailSettingFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'mailer_type' => 'required|integer',
            'from_name' => 'required|string',
            'from_email' => 'required|email',
            'smtp_host' => 'required|string',
            'smtp_user_name' => 'required|string',
            'smtp_password' => 'required|string|min:6',
            'smtp_port' => 'required|integer',
            'status' => 'required|integer',
            'smtp_encryption_type' => 'required|string',
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
