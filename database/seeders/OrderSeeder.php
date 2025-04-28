<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyer = User::first();
        $farmer = User::first(); // Pour test, même utilisateur

        Order::create([
            'buyer_id' => $buyer->id,
            'farmer_id' => $farmer->id,
            'total_amount' => 350.00,
            'delivery_type' => 'pickup',
            'delivery_address' => 'Marché Central de Douala',
            'status' => 'confirmed',
            'notes' => 'Livraison le samedi matin.',
        ]);
    }
}
