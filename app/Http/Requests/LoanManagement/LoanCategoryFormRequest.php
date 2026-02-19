<?php

namespace App\Http\Requests\LoanManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LoanCategoryFormRequest extends FormRequest
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
            'name' => 'required|string|max:191|min:3',
            'short_name' => 'nullable|string|max:191|min:3',
            'amount' => 'required|numeric|min:0',
            'loan_tenure' => 'required|numeric|min:0',
            'loan_term' => 'required|numeric|min:0',
            'loan_term_method' => 'required|numeric',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'interest_type' => 'required|numeric',
            'interest_frequency' => 'required|numeric',
            'repayment_frequency' => 'required|numeric',
            'late_payment_penalty_rate' => 'nullable|numeric|min:0|max:100', // Fix the typo
            'charges' => 'nullable|array',  // 'charges' should be an array
            'charges.*' => 'nullable|integer', // Each element in 'charges' should be an integer
            'status' => 'required|integer',
            'description' => 'nullable|string|max:255',
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
        ];
    }
}
