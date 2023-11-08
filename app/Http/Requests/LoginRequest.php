<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar-name' => ['required_if:email,""', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'callback' => ['nullable', 'string'],
        ];
    }
}
