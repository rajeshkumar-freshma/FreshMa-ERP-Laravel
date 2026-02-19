<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeFormRequest extends FormRequest
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
        if ($this->route('employee')) {
            $id = $this->route('employee');
            $uniqueRule = Rule::unique('admins')->ignore($id);
        } else {
            $uniqueRule = Rule::unique('admins');
        }

        $phone_number_rule = 'required|max:15|min:10|' . $uniqueRule->where('phone_number', request()->phone_number);
        $email_rule = 'required|email|max:255|' . $uniqueRule->where('email', request()->email);

        return [

            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone_number' => $phone_number_rule,
            'email' =>  $email_rule,
            'password' => 'nullable|string|min:8',
            'address' => 'nullable|string',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'joined_at' => 'nullable|date',
            'status' => 'required|integer',
            'pan_number' => 'nullable|string|max:255',
            'aadhar_number' => 'nullable|string|max:255',
            'esi_number' => 'nullable|string|max:255',
            'pf_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'name_as_per_record' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:255',
            'employee_store' => 'array',
            'employee_store.*.store_id' => 'nullable|integer',
            'employee_store.*.department_id' => 'nullable|integer',
            'employee_store.*.designation_id' => 'nullable|integer',
            'employee_store.*.status' => 'required|integer',
            'employee_store.*.joined_at' => 'nullable|date',
            'employee_store.*.remarks' => 'nullable|string',
            'submission_type' => 'required|in:1,2', // Assuming submission_type can only be 1 or 2
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.string' => 'This field must be a string',
            '*.email' => 'Invalid email format',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.integer' => 'This field must be an integer',
            '*.unique' => 'This value is already taken',
            '*.date' => 'Invalid date format',
            'employee_store.array' => 'Employee store data must be an array',
            // Add more custom messages as needed
        ];
    }
}
