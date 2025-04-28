<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use App\Models\Activity;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Admin ou premier user
        $activity = Activity::first(); // Première activité

        Project::create([
            'user_id' => $user->id,
            'name' => 'Projet Maraîchage 2025',
            'description' => 'Production de légumes bio pour le marché local.',
            'activity_id' => $activity->id,
            'start_date' => now()->subMonths(2),
            'end_date' => now()->addMonths(4),
            'status' => 'in_progress',
            'total_surface' => 2.5,
        ]);
    }
}
