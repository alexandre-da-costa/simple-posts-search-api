<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $email
 * @property mixed $password
 * @property mixed $device_name
 * @property mixed $remember_me
 */
class GenerateTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
            'device_name' => 'required|string|max:255',
            'remember_me' => 'required|boolean'
        ];
    }
}
