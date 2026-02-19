<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class TransactionFormRequest extends FormRequest
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
            'transaction_account' => 'required|integer',
            'transaction_type' => 'required|integer',
            'available_balance' =>  'required|numeric',
            'transaction_amount' => 'required|numeric',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|integer',
        ];


    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
        ];
    }
}
