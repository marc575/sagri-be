<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Changez ceci selon votre logique d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'quantity_available' => 'integer|min:0',
            'unit' => 'string|max:50',
            'price_per_unit' => 'numeric|min:0',
            'category' => 'string|max:255',
            'status' => 'nullable|string',
            'is_organic' => 'integer|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image.max' => "L'image ne doit pas dépasser 2 Mo",
            'price_per_unit.min' => "Le prix ne peut pas être négatif",
            'is_organic.in' => "La valeur bio doit être 0 ou 1",
        ];
    }
}