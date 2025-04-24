<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(3)->create()->each(function ($user) {
            $activityCount = rand(1, 3);

            for ($i = 0; $i < $activityCount; $i++) {
                Activity::create([
                    'user_id' => $user->id,
                    'performed_at' => $this->generateRandomTimestamp(),
                    'points' => 20,
                    'name' => $this->generateRandomActivityName(),
                ]);
            }
        });
    }

    private function generateRandomActivityName()
    {
        $activityNames = [
            'Running', 'Cycling', 'Swimming', 'Reading', 'Writing',
            'Cooking', 'Gaming', 'Shopping', 'Painting', 'Jogging'
        ];

        return $activityNames[array_rand($activityNames)];
    }

    private function generateRandomTimestamp()
    {
        return Carbon::now()
            ->subDays(rand(0, 60))
            ->subHours(rand(0, 23))
            ->subMinutes(rand(0, 59));
    }
}
