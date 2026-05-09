<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Automatically seed doctors for users with the DOCTOR role
        $users = User::where('role', Role::DOCTOR)->get();

        $specialists = ['General Practitioner', 'Cardiologist', 'Pediatrician', 'Neurologist'];

        foreach ($users as $index => $user) {
            Doctor::create([
                'doctor_id' => 'DOC-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'nik' => '32011' . str_pad($index + 1, 11, '0', STR_PAD_LEFT),
                'sip' => 'SIP/2026/00' . ($index + 1),
                'str' => 'STR/2026/00' . ($index + 1),
                'full_name' => $user->name,
                'specialist' => $specialists[$index % count($specialists)],
                'phone' => '0812345678' . (9 - $index),
                'is_bpjs' => ($index % 2 == 0), // Alternates true and false
                'is_active' => true,
                'user_id' => $user->id,
            ]);
        }
    }
}
