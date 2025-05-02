<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'buyer_id' => 'required|exists:users,id',
            'farmer_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'delivery_type' => 'required|in:pickup,buyer_delivery,farmer_delivery',
            'delivery_address' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,delivered',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ];
    }
}

