<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\Patient;
use App\Models\Schedule;
use App\Enums\RegistrationStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $schedules = Schedule::all()->groupBy('doctor_id')->take(2);

        if ($patients->isEmpty() || $schedules->isEmpty()) {
            return;
        }

        foreach ($patients as $patient) {
            foreach ($schedules as $doctorId => $doctorSchedules) {
                $schedule = $doctorSchedules->first();
                Registration::create([
                    'patient_id' => $patient->id,
                    'schedule_id' => $schedule->id,
                    'queue_number' => rand(1, 20),
                    'status' => RegistrationStatus::COMPLETED,
                    'registration_date' => Carbon::today()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
