<?php

namespace App\Http\Requests\Returns;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseReturnFormRequest extends FormRequest
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
            'purchase_order_return_number' => 'required|max:255',
            'purchase_order_id' => 'required|integer',
            'return_date' => 'required|date',
            'from_warehouse_id' => 'required|integer',
            'to_supplier_id' => 'required|integer',
            'status' => 'required|integer',
            // 'products' => 'required|array',
            // 'products.id.*' => 'nullable|integer',
            // 'products.product_id.*' => 'required|integer',
            // 'products.unit_id.*' => 'required|integer',
            // 'products.quantity.*' => 'required|numeric',
            // 'products.amount.*' => 'required|numeric',
            // 'products.per_unit_price.*' => 'required|numeric',
            // 'products.sub_total.*' => 'required|numeric',
            // 'total_request_quantity' => 'required|numeric',
            // 'sub_total_amount' => 'required|numeric',
            // 'total_amount' => 'required|numeric',
            'remarks' => 'nullable|string|max:191|min:3',
            'transport_tracking.transport_tracking_id.*' => 'nullable|integer',
            'transport_tracking.transport_type_id.*' => 'nullable|integer',
            'transport_tracking.transport_name.*' => 'nullable|string|max:255',
            'transport_tracking.transport_number.*' => 'nullable|string|max:255',
            'transport_tracking.departure_datetime.*' => 'nullable|date',
            'transport_tracking.arriving_datetime.*' => 'nullable|date',
            'transport_tracking.from_location.*' => 'nullable|string|max:255',
            'transport_tracking.to_location.*' => 'nullable|string|max:255',
            'expense.expense_id.*' => 'nullable|integer',
            'expense.expense_type_id.*' => 'nullable|integer',
            'expense.expense_amount.*' => 'nullable|numeric',
            // 'submission_type' => 'required|integer',
            'file.*' => 'nullable|mimes:jpeg,png,jpg,pdf|max:' . config('app.attachmentfilesize'),
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.integer' => 'Please select any one value',
            '*.date' => 'The :attribute must be a valid date',
            '*.numeric' => 'The :attribute must be a number',
            '*.string' => 'The :attribute must be a string',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            'file.*.max' => 'Maximum file size to upload is ' . config('app.attachmentfilesizeinmb'),
        ];
    }
}
