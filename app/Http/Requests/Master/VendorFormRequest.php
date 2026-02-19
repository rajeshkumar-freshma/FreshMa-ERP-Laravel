<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Request;

class VendorFormRequest extends FormRequest
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

        if ($this->route('customer')) {
            $id = $this->route('customer');
            $phone_number_rule = 'required|max:15|min:10|' . Rule::unique('users', 'phone_number')->ignore($id);
            // $password_rule = 'nullable';
        } else {
            $parent_id = Request::post('parent_id');
            $phone_number_rule = 'required|max:15|min:10|' . Rule::unique('users', 'phone_number');
            // $password_rule = 'required|min:6';
        }
        // $imagePath = null;
        // if ($this->hasFile('image')) {
        //     $imagePath = $this->file('image')->store('images');
        //     // Save the uploaded image path in the session
        //     $this->session()->put('uploaded_image', $imagePath);
        // }

        return [
            'first_name' => 'required|max:191|min:3',
            'last_name' => 'nullable|min:1',
            'user_code' => 'nullable|min:3',
            'email' => 'nullable|email|max:191|min:3',
            'phone_number' => $phone_number_rule,
            'password' => 'nullable',
            'company' => 'nullable',
            'website' => 'nullable',
            'address' => 'nullable',
            'country_id' => 'nullable',
            'state_id' => 'nullable',
            'city_id' => 'nullable',
            'currency_id' => 'nullable',
            'gst_number' => 'nullable',
            'joined_at' => 'nullable|date',
            'vendor_commission' => 'nullable',
            'image.*' => 'bail|mimes:' . config('app.imageattachmentfiletype') . '|nullable|max:' . config('app.attachmentfilesize'),
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
