<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Project;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $project = Project::first();

        Product::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'name' => 'Tomates Bio',
            'description' => 'Tomates fraîches et biologiques.',
            'quantity_available' => 500,
            'unit' => 'kg',
            'price_per_unit' => 1.75,
            'category' => 'Légumes',
            'is_organic' => true,
            'status' => 'available',
        ]);
    }
}
