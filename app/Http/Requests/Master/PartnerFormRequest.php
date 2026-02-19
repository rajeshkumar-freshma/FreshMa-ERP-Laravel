<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class PartnerFormRequest extends FormRequest
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
        if ($this->route('partner')) {
            $id = $this->route('partner');
            $phone_number_rule = 'required|max:15|min:10|' . Rule::unique('admins', 'phone_number')->ignore($id);
            $email_rule = 'nullable|email|unique:admins,email,' . $id;
            $password_rule = 'nullable';
        } else {
            $phone_number_rule = 'required|max:15|min:10|' . Rule::unique('admins', 'phone_number');
            $email_rule = 'nullable|email|unique:admins,email';
            $password_rule = 'required_with:partner|min:6';
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
            'joined_at' => 'nullable',
            'image.*' => 'bail|mimes:' . config('app.imageattachmentfiletype') . '|nullable|max:' . config('app.attachmentfilesize'),
            // 'partnership_store.*.partnership_type_id' => 'nullable|integer',
            // 'partnership_store.*.store_id' => 'required|integer',
            // 'partnership_store.*.status' => 'required|integer',
            // 'partnership_store.*.joined_at' => 'required|date',
            'warehouse_assign.*.warehouse_id' => 'nullable|integer',
            'warehouse_assign.*.status' => 'nullable|integer',
            'warehouse_assign.*.joined_at' => 'nullable|date',
            'warehouse_assign.*.remarks' => 'nullable',
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
            'image.*.max' => 'Maximum file size to upload is ' . config('app.attachmentfilesizeinmb'),
        ];
    }
}
