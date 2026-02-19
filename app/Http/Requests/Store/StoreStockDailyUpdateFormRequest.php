<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockDailyUpdateFormRequest extends FormRequest
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
            'products' => 'required|array',
            'products.*' => 'integer',
            'openingstock' => 'required|array',
            'openingstock.*' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
            'closingstock' => 'required|array',
            'closingstock.*' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
            'store_id' => 'required|integer',
            'stock_updated_date' => 'required|date',


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
