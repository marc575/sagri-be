<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::insert([
            ['name' => 'Français', 'code' => 'fr'],
            ['name' => 'Anglais', 'code' => 'en'],
        ]);
    }
}
