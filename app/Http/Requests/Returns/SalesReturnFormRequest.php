<?php

namespace App\Http\Requests\Returns;

use Illuminate\Foundation\Http\FormRequest;

class SalesReturnFormRequest extends FormRequest
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
            'sales_order_return_number' => 'required|max:255',
            'return_from' => 'required|integer',
            'sales_order_id' => 'nullable|integer', // Change required to nullable since it's null in the JSON
            'return_date' => 'required|date',
            'to_warehouse_id' => 'required|integer',
            'status' => 'required|integer',
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
            'payment_details.payment_type_id.*' => 'nullable|integer',
            'payment_details.transaction_datetime.*' => 'nullable|date',
            'payment_details.transaction_amount.*' => 'nullable|numeric',
            'payment_details.remark.*' => 'nullable|string|max:255',
            'submission_type' => 'required|integer',
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


