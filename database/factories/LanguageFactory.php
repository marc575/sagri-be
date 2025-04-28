<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => $this->faker->languageCode,
            'name' => ucfirst($this->faker->word),
        ];
    }
}
