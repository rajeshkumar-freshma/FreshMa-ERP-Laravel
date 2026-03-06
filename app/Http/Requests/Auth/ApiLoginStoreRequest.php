<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ApiLoginStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string', 'required_without:phone_number', 'max:255'],
            'password' => ['nullable', 'string', 'required_with:email', 'min:4', 'max:255'],
            'phone_number' => ['nullable', 'string', 'required_without:email', 'min:10', 'max:12'],
            'remember' => ['nullable', 'boolean'],
        ];
    }
}
