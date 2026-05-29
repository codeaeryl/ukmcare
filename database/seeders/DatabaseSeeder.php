<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PatientSeeder::class,
            DoctorSeeder::class,
            ScheduleSeeder::class,
            MedicineSeeder::class,
            RegistrationSeeder::class,
            MedicalRecordSeeder::class,
            PrescriptionSeeder::class,
            BillSeeder::class,
            ServiceSeeder::class,
            BillServiceSeeder::class,
            BillMedicineSeeder::class,
            PaymentSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
