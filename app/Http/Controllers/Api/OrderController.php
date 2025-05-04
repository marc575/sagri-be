<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['buyer', 'farmer', 'items.product'])->latest()->get();
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated) {
            // Calcul automatique du montant total
            $totalAmount = collect($validated['items'])->sum(function ($item) {
                return $item['unit_price'] * $item['quantity'];
            });

            $order = Order::create([
                'buyer_id' => $validated['buyer_id'],
                'farmer_id' => $validated['farmer_id'],
                'total_amount' => $totalAmount, // Utilisation du calcul
                'delivery_type' => $validated['delivery_type'],
                'delivery_address' => $validated['delivery_address'] ?? null,
                'status' => 'pending', // Statut par défaut
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                // Vérification du stock
                $product = Product::find($item['product_id']);
                if ($product->quantity_available < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour {$product->name}");
                }

                // Création de l'item
                $order->items()->create($item);
                
                // Mise à jour du stock
                $product->decrement('quantity_available', $item['quantity']);
            }

            return $order->load(['buyer', 'farmer', 'items.product']);
        });

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Order $order)
    {
        $order->load(['buyer', 'farmer', 'items.product']);
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return new OrderResource($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully'], 204);
    }
}
