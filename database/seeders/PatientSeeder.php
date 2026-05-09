<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Enums\Gender;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Automatically seed patients for users with the PATIENT role
        $users = User::where('role', Role::PATIENT)->get();

        $genders = [Gender::MALE, Gender::FEMALE];
        $bloodTypes = ['A', 'B', 'AB', 'O'];

        foreach ($users as $index => $user) {
            Patient::create([
                'mrn' => 'MRN-2026' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'nik' => '32010' . str_pad($index + 1, 11, '0', STR_PAD_LEFT),
                'full_name' => $user->name,
                'pob' => 'Jakarta',
                'dob' => '1990-01-01',
                'gender' => $genders[$index % count($genders)],
                'address' => 'Jl. Kesehatan No. ' . ($index + 1),
                'phone' => '0812345678' . $index,
                'blood_type' => $bloodTypes[$index % count($bloodTypes)],
                'bpjs_number' => '000123456789' . $index,
                'user_id' => $user->id,
            ]);
        }
    }
}
