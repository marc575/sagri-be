<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompleteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'profile_picture' => 'nullable|image|max:2048', // 2MB max
            'bio' => 'nullable|string|max:500',
            'land_size' => 'nullable|numeric|min:0',
            'gps_coordinates' => 'nullable|string|max:100',
            'farming_since' => 'nullable|integer|min:1900|max:' . date('Y'),
            'language_id' => 'nullable|numeric|exists:languages,id',
        ];
    }
}
