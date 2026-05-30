<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'role' => Role::ADMIN,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Doctors
        $doctors = [
            ['name' => 'Alice Smith', 'email' => 'alice.smith@ukmcare.com'],
            ['name' => 'Bob Jones', 'email' => 'bob.jones@ukmcare.com'],
        ];

        foreach ($doctors as $doctor) {
            User::create([
                'role' => Role::DOCTOR,
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => Hash::make('password'),
            ]);
        }

        // Patients
        $patients = [
            ['name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com'],
            ['name' => 'Diana Prince', 'email' => 'diana.prince@example.com'],
        ];

        foreach ($patients as $patient) {
            User::create([
                'role' => Role::PATIENT,
                'name' => $patient['name'],
                'email' => $patient['email'],
                'password' => Hash::make('password'),
            ]);
        }

        // Pharmacist
        User::create([
            'role' => Role::PHARMACIST,
            'name' => 'Pharmacist',
            'email' => 'pharmacist@example.com',
            'password' => Hash::make('password'),
        ]);

        // Cashier
        User::create([
            'role' => Role::CASHIER,
            'name' => 'Cashier',
            'email' => 'cashier@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
