<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StoreFormRequest extends FormRequest
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
        if ($this->route('store')) {
            $id = $this->route('store');
            $name_rule = 'required|max:191|min:3|' . Rule::unique('stores', 'store_name')->ignore($id);
            $slug_rule = 'nullable|max:191|min:3|' . Rule::unique('stores', 'slug')->ignore($id);
        } else {
            $name_rule = 'required|max:191|min:3|' . Rule::unique('stores', 'store_name');
            $slug_rule = 'nullable|max:191|min:3|' . Rule::unique('stores', 'slug');
        }

        return [
            'store_name' => $name_rule,
            'slug' => $slug_rule,
            'store_code' => 'required|min:3',
            'warehouse_id' => 'required|integer',
            'phone_number' => 'required|numeric|min:10',
            'email' => 'nullable|email|max:191|min:3',
            'start_date' => 'nullable|date',
            'address' => 'nullable|max:191|min:3',
            'city_id' => 'required|integer',
            'state_id' => 'required|integer',
            'country_id' => 'required|integer',
            'pincode' => 'required|numeric|min:6',
            'gst_number' => 'nullable|max:191|min:3',
            'longitude' => 'nullable|max:191|min:3',
            'latitude' => 'nullable|max:191|min:3',
            'direction' => 'nullable|max:191|min:3',
            'coordinates' => 'nullable|max:191|min:3',
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
