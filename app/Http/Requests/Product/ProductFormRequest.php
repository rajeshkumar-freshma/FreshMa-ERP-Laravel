<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProductFormRequest extends FormRequest
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
        if ($this->route('product')) {
            $id = $this->route('product');
            $name_rule = 'required|max:191|min:3|'.Rule::unique('products', 'name')->ignore($id);
            $slug_rule = 'nullable|max:191|min:3|'.Rule::unique('products', 'slug')->ignore($id);
            $sku_code_rule = 'nullable|max:191|min:3|'.Rule::unique('products', 'sku_code')->whereNotNull('sku_code')->ignore($id);
        } else {
            $name_rule = 'required|max:191|min:3|'.Rule::unique('products', 'name');
            $slug_rule = 'nullable|max:191|min:3|'.Rule::unique('products', 'slug');
            $sku_code_rule = 'nullable|max:191|min:3|'.Rule::unique('products', 'sku_code')->whereNotNull('sku_code');
        }
        
        return [
            'name' => $name_rule,
            'slug' => $slug_rule,
            'sku_code' => $sku_code_rule,
            'hsn_code' => 'nullable|min:3',
            'product_description' => 'nullable|max:191|min:3',
            'item_type_id' => 'nullable|integer',
            'unit_id' => 'nullable|integer',
            'tax_id' => 'nullable|integer',
            'tax_type' => 'nullable|integer',
            'meta_title' => 'nullable|max:191|min:3',
            'meta_description' => 'nullable|max:191|min:3',
            'meta_keywords' => 'nullable|max:191|min:3',
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
