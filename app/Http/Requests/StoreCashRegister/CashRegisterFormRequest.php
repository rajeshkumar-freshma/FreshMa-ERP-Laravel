<?php

namespace App\Http\Requests\StoreCashRegister;

use Illuminate\Foundation\Http\FormRequest;

class CashRegisterFormRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0',
            'add_dedect_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|integer',
            'is_opened' => 'required|integer',
            'open_close_time' => 'required|date',
            'verified_by' => 'required|integer',
            // 'is_notification_send_to_admin' => 'required|in:0,1',
            'status' => 'required|in:0,1',
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
