<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'Document_' . $this->faker->word,
            'file_path' => 'documents/' . $this->faker->uuid . '.pdf',
        ];
    }
}
