<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class BulkTransactionUploadFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,csv|max:2048',
            'bank_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            // '*.required' => 'This field is required.',
            // '*.integer' => 'Please enter a valid integer.',
            // '*.numeric' => 'Please enter a valid number.',
            // '*.date' => 'Please enter a valid date.',
            // '*.in' => 'Invalid value provided.',
            // '*.string' => 'Please enter a valid string.',

            '*.required' => 'Please Fill This Place.',
            '*.mimes' => 'The file must be of type Excel, CSV.',
            '*.max' => 'The file size must not exceed 2048 KB.',
            '*.integer' => 'Invalid bank ID.',
        ];
    }
}
