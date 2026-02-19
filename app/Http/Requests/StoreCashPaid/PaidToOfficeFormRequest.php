<?php

namespace App\Http\Requests\StoreCashPaid;

use Illuminate\Foundation\Http\FormRequest;

class PaidToOfficeFormRequest extends FormRequest
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
            'store_id' => 'required|exists:stores,id',
            'amount' => 'required|numeric|min:0',
            // 'payer_id' => 'required|integer',
            'receiver_id' => 'required|integer',
            'signature' => 'nullable|string',
            'file' => 'nullable|string',
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
