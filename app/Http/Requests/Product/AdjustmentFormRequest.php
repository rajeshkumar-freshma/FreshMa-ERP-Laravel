<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class AdjustmentFormRequest extends FormRequest
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
        if ($this->route('adjustment') != null) {
            $id = $this->route('adjustment');
            $track_number = 'required|max:191|min:3|' . Rule::unique('adjustments', 'adjustment_track_number')->ignore($id);
        } else {
            $track_number = 'required|max:191|min:3|' . Rule::unique('adjustments', 'adjustment_track_number');
        }

        return [
            'adjustment_track_number' => $track_number,
            'warehouse_id' => 'sometimes|nullable|integer',
            'store_id' => 'sometimes|nullable|integer',
            'remarks' => 'nullable|max:191|min:3',
            'adjustment_date' => 'required|date',
            'total_request_quantity' => 'required',
            'status' => 'required|integer',
            'products.type.*' => 'required|integer',
            'products.quantity.*' => 'required|numeric|min:0|max:10000',
            // 'products.remarks.*' => 'required|max:191|min:3',
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
