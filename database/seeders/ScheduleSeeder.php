<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
        foreach ($doctors as $doctor) {
            Schedule::create([
                'doctor_id' => $doctor->id,
                'schedule_day' => 'Monday',
                'start_hour' => '08:00:00',
                'end_hour' => '12:00:00',
                'quota' => 20,
            ]);
        }
    }
}
