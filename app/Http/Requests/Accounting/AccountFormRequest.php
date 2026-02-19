<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountFormRequest extends FormRequest
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
            'name' => 'required|string',
            'account_number' => [
                'required',
                'integer',
                Rule::unique('accounts')->ignore($this->account),
            ],
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_type' => 'required|integer',
            'bank_ifsc_code' => 'required|string',
            'initial_balance' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.max' => 'Maximum amount limit is :max',
            '*.min' => 'Minimum :min amount  are required',
        ];
    }
}
