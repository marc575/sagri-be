<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserActivity;
use App\Models\User;
use App\Models\Activity;

class UserActivitySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $activity = Activity::first();

        UserActivity::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
        ]);
    }
}
