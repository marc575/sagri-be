<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'region' => 'sometimes|string|max:100',
            'profile_picture' => 'sometimes|image|max:2048',
            'bio' => 'sometimes|string|max:500',
            'land_size' => 'sometimes|numeric|min:0',
            'gps_coordinates' => 'sometimes|string|max:100',
            'farming_since' => 'sometimes|integer|min:1900|max:' . date('Y'),
            'language_id' => 'sometimes|exists:languages,id'
        ];
    }
}