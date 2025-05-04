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
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'status' => 'in:available,sold_out',
            'description' => 'nullable|string',
            'quantity_available' => 'integer|min:0',
            'unit' => 'string|max:20',
            'price_per_unit' => 'numeric|min:0',
            'category' => 'string|max:100',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_organic' => 'integer|in:0,1',
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