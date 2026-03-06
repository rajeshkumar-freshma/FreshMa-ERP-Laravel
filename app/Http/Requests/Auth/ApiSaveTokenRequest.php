<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ApiSaveTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'os' => ['nullable', 'string', 'in:android,ios,web'],
            'fcmToken' => ['required', 'string', 'max:2048'],
            'voipToken' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
