<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterInitialRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:farmer,buyer,admin', // Exemple de rôles
        ];
    }
}