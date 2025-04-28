<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $buyer = User::factory();
        $farmer = User::factory();

        return [
            'buyer_id' => $buyer,
            'farmer_id' => $farmer,
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'delivery_type' => $this->faker->randomElement(['pickup', 'delivery']),
            'delivery_address' => $this->faker->address,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'notes' => $this->faker->sentence,
        ];
    }
}
