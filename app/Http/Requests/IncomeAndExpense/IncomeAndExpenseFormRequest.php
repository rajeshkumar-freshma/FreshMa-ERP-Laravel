<?php

namespace App\Http\Requests\IncomeAndExpense;

use Illuminate\Foundation\Http\FormRequest;

class IncomeAndExpenseFormRequest extends FormRequest
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
            '_token' => 'required|string',
            '_method' => 'required|string',
            'date' => 'required|date',
            'related_to' => 'required|integer',
            'store_id' => 'nullable|integer',
            'warehouse_id' => 'nullable|integer',
            'income_expense_type_id' => 'required|integer',
            'income_expense_invoice_number' => 'required|string',
            // 'expense' => 'required|array',
            // 'expense.expense_id' => 'nullable|array',
            // 'expense.expense_type_id' => 'required|array',
            // 'expense.expense_type_id.*' => 'required|integer',
            // 'expense.expense_amount' => 'required|array',
            // 'expense.expense_amount.*' => 'required|numeric',
            // 'expense.remarks' => 'nullable|array',
            // 'expense.remarks.*' => 'nullable|string',
            // 'total_expense_amount_display_val' => 'required|string',
            // 'payment_details' => 'required|array',
            // 'payment_details.payment_type_id' => 'required|array',
            // 'payment_details.payment_type_id.*' => 'required|integer',
            // 'payment_details.transaction_datetime' => 'required|array',
            // 'payment_details.transaction_datetime.*' => 'required|date',
            // 'payment_details.transaction_amount' => 'required|array',
            // 'payment_details.transaction_amount.*' => 'required|numeric',
            // 'payment_details.remark' => 'nullable|array',
            // 'payment_details.remark.*' => 'nullable|string',
            // 'submission_type' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.integer' => 'This field must be an integer',
            '*.numeric' => 'This field must be a number',
            '*.date' => 'This field must be a valid date',
            '*.string' => 'This field must be a string',
        ];
    }
}
