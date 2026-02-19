<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;

class PayrollFormRequest extends FormRequest
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
            'employee_id' => 'required|integer',
            // 'payroll_data.*.payroll_type_id' => 'required|integer',
            // 'payroll_data.*.amount' => 'required|numeric',
            'payroll_month' => 'required',
            'payroll_year' => 'required',
            'status' => 'required',
            'remarks' => 'nullable',
            'loss_of_pay_days' => 'nullable',
            'number_of_working_days' => 'required',
            'gross_salary' => 'required',
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
