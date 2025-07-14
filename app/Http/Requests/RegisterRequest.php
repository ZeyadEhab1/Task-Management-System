<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:50'],
            'email'       => ['required', 'string', 'email', 'max:100', 'unique:users',],
            'password'    => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
        ];
    }
}
