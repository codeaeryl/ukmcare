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
        $patient = Patient::first();
        $schedule = Schedule::first();

        if ($patient && $schedule) {
            Registration::create([
                'patient_id' => $patient->id,
                'schedule_id' => $schedule->id,
                'queue_number' => 1,
                'status' => RegistrationStatus::COMPLETED,
                'registration_date' => Carbon::today(),
            ]);
        }
    }
}
