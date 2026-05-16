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
        $registrations = Registration::with('schedule')->get();

        $diagnoses = ['Common Cold', 'Flu', 'Headache', 'Stomach Ache', 'Allergy', 'Fatigue'];

        foreach ($registrations as $registration) {
            MedicalRecord::create([
                'registration_id' => $registration->id,
                'doctor_id' => $registration->schedule->doctor_id,
                'diagnosis' => collect($diagnoses)->random(),
                'description' => 'Patient complains of mild symptoms related to the diagnosis.',
                'action' => 'Prescribed rest and medication.',
                'record_date' => $registration->registration_date,
            ]);
        }
    }
}
