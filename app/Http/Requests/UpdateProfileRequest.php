<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; // L'autorisation est gérée par le middleware auth
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'land_size' => 'nullable|numeric|min:0',
            'gps_coordinates' => 'nullable|string|max:100',
            'farming_since' => 'nullable|integer|min:1900|max:' . date('Y'),
            'region' => 'nullable|string|max:100',
            // Ajoutez ici tous les autres champs modifiables
        ];
    }
}