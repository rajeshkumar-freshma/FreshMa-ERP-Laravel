<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class MachineDetailFormRequest extends FormRequest
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
            'machine_name' => 'required|max:191|min:3',
            'port' => 'required|integer',
            'capacity' => 'required',
            'status' => 'required|integer',
            'plu_master_code' => 'required',
            'machine_status' => 'required|integer',
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
