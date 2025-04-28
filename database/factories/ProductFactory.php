<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Project;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'name' => ucfirst($this->faker->words(2, true)),
            'description' => $this->faker->sentence,
            'quantity_available' => $this->faker->numberBetween(50, 1000),
            'unit' => 'kg',
            'price_per_unit' => $this->faker->randomFloat(2, 1, 10),
            'category' => $this->faker->word,
            'is_organic' => $this->faker->boolean,
            'status' => $this->faker->randomElement(['available', 'out_of_stock']),
        ];
    }
}
