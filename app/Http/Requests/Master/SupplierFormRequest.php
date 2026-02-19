<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class SupplierFormRequest extends FormRequest
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
        if ($this->route('supplier')) {
            $id = $this->route('supplier');
            $phone_number_rule = 'required|max:15|min:10|' . Rule::unique('users', 'phone_number')->ignore($id);
            $email_rule = 'nullable|email|unique:users,email,' . $id;
            $password_rule = 'nullable';
        } else {
            $phone_number_rule = 'required|max:15|min:10|' . Rule::unique('users', 'phone_number');
            $email_rule = 'nullable|email|unique:users,email';
            $password_rule = 'required_with:supplier|min:6';
        }

        return [
            'first_name' => 'required|max:191|min:3',
            'last_name' => 'nullable|min:1',
            'user_code' => 'nullable|min:3',
            'email' =>  $email_rule,
            'phone_number' => $phone_number_rule,
            'password' => $password_rule,
            'company' => 'nullable',
            'website' => 'nullable',
            'address' => 'nullable',
            'country_id' => 'nullable',
            'state_id' => 'nullable',
            'city_id' => 'nullable',
            'currency_id' => 'nullable',
            'gst_number' => 'nullable',
            'joined_at' => 'nullable|date',
            'image.*' => 'bail|mimes:' . config('app.imageattachmentfiletype') . '|nullable|max:' . config('app.attachmentfilesize'),
            'salary_type' => 'required|integer',
            'amount_type' => 'required|integer',
            'amount' => 'nullable|required_if:amount_type,1',
            'percentage' => 'nullable|required_if:amount_type,2',
            'remarks' => 'sometimes|nullable|min:3|max:191',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            'image.*.max' => 'Maximum file size to upload is ' . config('app.attachmentfilesizeinmb'),
        ];
    }
}
