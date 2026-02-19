<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailTemplateFormRequest extends FormRequest
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
        $id = $this->route('email-template');

        // Determine the validation rule for the code field based on whether it's an update or creation
        // $codeRule = $id ? 'nullable|string|unique:email_templates,code,' . $id : 'nullable|string|unique:email_templates,code';

        return [
            'subject' => 'required|string|max:255',
            'code' => 'nullable|string',
            'body' => 'nullable|string',
            'status' => 'required|integer|in:0,1',
        ];
    }


    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.string' => 'This field must be a string',
            '*.integer' => 'This field must be an integer',
            'status.in' => 'The status must be either 0 or 1',
        ];
    }
}
