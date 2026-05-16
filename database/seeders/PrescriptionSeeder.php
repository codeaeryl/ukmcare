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
        $records = MedicalRecord::all();
        $medicines = Medicine::all();

        if ($medicines->isEmpty()) return;

        foreach ($records as $record) {
            $medsToPrescribe = $medicines->random(rand(1, 2));
            foreach ($medsToPrescribe as $medicine) {
                Prescription::create([
                    'medical_record_id' => $record->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => rand(5, 15),
                    'dosage' => '3x1 after meals',
                ]);
            }
        }
    }
}
