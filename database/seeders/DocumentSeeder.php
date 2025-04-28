<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        Document::create([
            'user_id' => $user->id,
            'name' => 'Certificat Agricole',
            'file_path' => 'documents/certificat_agricole.pdf',
        ]);
    }
}
