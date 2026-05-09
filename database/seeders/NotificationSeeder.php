<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Enums\NotificationStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => 'Your appointment is confirmed.',
                'date' => Carbon::now(),
                'status' => NotificationStatus::APPOINTMENT,
            ]);
        }
    }
}
