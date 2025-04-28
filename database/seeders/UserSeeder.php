<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@sagritm.com',
            'phone' => '+237620000000',
            'password' => Hash::make('password'), // Toujours hasher les mots de passe
            'role' => 'admin',
            'address' => 'Douala, Cameroun',
            'region' => 'Littoral',
            'bio' => 'Administrateur de la plateforme SAGRI & TM.',
            'status' => true,
        ]);
    }
}
