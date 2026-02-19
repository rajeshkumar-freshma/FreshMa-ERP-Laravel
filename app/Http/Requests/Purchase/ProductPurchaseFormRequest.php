<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class ProductPurchaseFormRequest extends FormRequest
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
            'purchase_order_number' => 'required|max:191|min:3',
            'warehouse_ir_id' => 'nullable|integer',
            'delivery_date' => 'required|date',
            'warehouse_id' => 'required|integer',
            'status' => 'required|integer',
            'supplier_id' => 'required|integer',
            'total_request_quantity' => 'required',
            'remarks' => 'nullable|max:191|min:3',
            'expense.expense_id.*' => 'nullable|integer',
            'expense.expense_type_id.*' => 'nullable|integer',
            'expense.expense_amount.*' => 'nullable|numeric',
            'payment_details.payment_id.*' => 'nullable|integer',
            'payment_details.payment_type_id.*' => 'nullable|integer',
            'payment_details.transaction_amount.*' => 'nullable|numeric',
            'file.*' => 'nullable|mimes:' . config('app.attachmentfiletype') . '|max:' . config('app.attachmentfilesize'),
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.integer' => 'Please select any one value',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            'file.*.max' => 'Maximum file size to upload is ' . config('app.attachmentfilesizeinmb'),
            // 'products.*.product' => 'This field is required',
            // 'products.*.unit' => 'This field is required',
            // 'products.*.quantity' => 'This field is required',
        ];
    }
}
