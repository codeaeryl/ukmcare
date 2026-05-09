<?php

namespace Database\Seeders;

use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $medicalRecord = MedicalRecord::first();
        $medicine = Medicine::first();

        if ($medicalRecord && $medicine) {
            Prescription::create([
                'medical_record_id' => $medicalRecord->id,
                'medicine_id' => $medicine->id,
                'quantity' => 10,
                'dosage' => '3x1 after meals',
            ]);
        }
    }
}
