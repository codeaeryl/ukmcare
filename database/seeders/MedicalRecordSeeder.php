<?php

namespace Database\Seeders;

use App\Models\MedicalRecord;
use App\Models\Registration;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $registration = Registration::first();

        if ($registration) {
            MedicalRecord::create([
                'registration_id' => $registration->id,
                'doctor_id' => $registration->schedule->doctor_id,
                'diagnosis' => 'Common Cold',
                'description' => 'Patient complains of runny nose and mild fever.',
                'action' => 'Prescribed rest and medication.',
                'record_date' => Carbon::now(),
            ]);
        }
    }
}
