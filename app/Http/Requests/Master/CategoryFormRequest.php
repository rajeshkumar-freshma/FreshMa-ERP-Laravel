<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CategoryFormRequest extends FormRequest
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
        if ($this->route('category')) {
            $id = $this->route('category');
            $name_rule = 'required|max:191|min:3|' . Rule::unique('categories', 'name')->ignore($id);
            $slug_rule = 'nullable|max:191|min:3|' . Rule::unique('categories', 'slug')->ignore($id);
        } else {
            $parent_id = $this->input('parent_id');
            $name_rule = 'required|max:191|min:3|' . Rule::unique('categories', 'name')->where(function ($query) use ($parent_id) {
                return $query->where('parent_id', $parent_id);
            });
            $slug_rule = 'nullable|max:191|min:3|' . Rule::unique('categories', 'slug')->where(function ($query) use ($parent_id) {
                return $query->where('parent_id', $parent_id);
            });
        }

        return [
            'name' => $name_rule,
            'slug' => $slug_rule,
            'parent_id' => 'nullable|integer',
            'meta_title' => 'nullable|max:191|min:3',
            'meta_description' => 'nullable|max:500|min:3',
            'meta_keywords' => 'nullable|max:500|min:3',
            'description' => 'nullable|max:500|min:3',
            'is_featured' => 'integer|nullable',
            'status' => 'integer|nullable',
            'image.*' => 'bail|mimes:' . config('app.attachmentfiletype') . '|nullable|max:' . config('app.attachmentfilesize'),
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.integer' => 'Invalid data',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            'image.*.max' => 'Maximum file size to upload is ' . config('app.attachmentfilesizeinmb'),
            'name.unique' => 'name is already exists',
            'slug.unique' => 'slug is already exists',
        ];
    }
}
