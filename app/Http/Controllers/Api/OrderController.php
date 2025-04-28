<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'farmer_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'delivery_type' => 'required|in:pickup,buyer_delivery,farmer_delivery',
            'delivery_address' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,delivered',
        ]);

        $order = Order::create($validated);

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,delivered',
        ]);

        $order->update($validated);

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 204);
    }
}
