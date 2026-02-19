<?php

namespace App\Http\Requests\SupplierBulkPayment;

use Illuminate\Foundation\Http\FormRequest;

class SupplierBulkPaymentFormRequest extends FormRequest
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
            'supplier_id' => 'required|integer',
            // 'supplier_id' => 'required|exists:stores,id',
            'purchase_order_id.*' => 'integer', // Validate each element in the array as an integer
            'purchase_order_id' => 'required|array', // Ensure it's an array
            'transaction_date' => 'required|date',
            'user_advance_amount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'pending_amount' => 'nullable|numeric',
            'amount' => 'required|numeric|min:0',
            'advance_amount_included' => 'nullable|in:1,2',
            'remarks' => 'nullable|string',
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
