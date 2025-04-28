<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        Activity::insert([
            ['name' => 'Culture du maïs', 'category' => 'Céréales', 'description' => 'Production de maïs pour l\'alimentation humaine et animale.'],
            ['name' => 'Culture du cacao', 'category' => 'Culture industrielle', 'description' => 'Production de cacao pour exportation.'],
            ['name' => 'Élevage de volailles', 'category' => 'Élevage', 'description' => 'Élevage de poulets, canards et dindes.'],
            ['name' => 'Maraîchage', 'category' => 'Légumes', 'description' => 'Culture de légumes frais destinés au marché local.'],
        ]);
    }
}
