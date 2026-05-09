<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            Log::create([
                'user_id' => $user->id,
                'activity' => 'User logged in',
                'date' => Carbon::now(),
            ]);
        }
    }
}
