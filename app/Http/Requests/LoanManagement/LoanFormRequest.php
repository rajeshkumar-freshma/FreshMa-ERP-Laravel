<?php

namespace App\Http\Requests\LoanManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LoanFormRequest extends FormRequest
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
            'employee_id' => 'nullable|integer',
            'bank_id' => 'nullable|integer',
            'loan_category_id' => 'required|integer',
            'phone_number' => 'required|max:15|min:10|',
            'applied_amount' => 'required|numeric|min:0',
            'applied_on' => 'required|date',
            'deduct_form_salary' => 'required|integer',
            'guarantors' => 'nullable|integer',
            'remarks' => 'nullable|string|max:255',
            'principal_amount' => 'required|numeric|min:0',
            'first_payment_date' => 'required|date',
            'loan_tenure' => 'required|integer|min:0',
            'loan_term' => 'required|integer|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'interest_frequency' => 'required|integer',
            'repayment_frequency' => 'required|integer',
            'repayment_amount' => 'required|numeric|min:0',
            // 'late_payment_penalty_rate' => 'required|numeric|min:0|max:100', // Fix the typo
            'description' => 'nullable|string|max:255',
            'disburse_method' => 'required|numeric',
            'distributed_date' => 'required|date',
            'documents' => 'nullable|file',
            'loan_status' => 'required|integer',
            'disburse_notes' => 'nullable|string|max:255',

        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.numeric' => 'Numeric values are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.integer' => 'Invalid data',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            '*.unique' => 'This value already exists',
            '*.phone_number' => 'minimum ten numbers are required',
        ];
    }
}
