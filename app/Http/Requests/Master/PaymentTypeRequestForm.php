<?php

namespace App\Http\Requests\Master;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentTypeRequestForm extends FormRequest
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
        if ($this->route('payment_type')) {
            $id = $this->route('payment_type');
            $slug_rule = 'nullable|max:191|min:3|'.Rule::unique('payment_types', 'slug')->ignore($id);
        } else {
            $slug_rule = 'nullable|max:191|min:3|'.Rule::unique('payment_types', 'slug');
        }
        
        return [
            'payment_type' => 'required|min:3',
            'slug' => $slug_rule,
            'status' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.numeric' => 'Numeric are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.integer' => 'Invalid data',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            '*.unique' => 'This value is already exists',
        ];
    }
}
