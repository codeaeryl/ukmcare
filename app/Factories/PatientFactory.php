<?php

namespace App\Factories;

use App\Models\User;
use App\Models\Patient;
use App\Enums\Role;
use App\Enums\Gender;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PatientFactory implements UserFactoryInterface
{
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => Role::PATIENT,
            ]);

            $latestPatient = Patient::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $nextIdNumber = 1;
            if ($latestPatient && preg_match('/(\d+)$/', $latestPatient->id, $matches)) {
                $nextIdNumber = intval($matches[1]) + 1;
            }
            $mrn = 'MRN-' . date('Y') . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

            $nik = $data['nik'] ?? $data['nik_patient'] ?? null;
            $phone = $data['phone'] ?? $data['phone_patient'] ?? null;
            $bpjsStatus = !empty($data['bpjs_number']) ? 'pending' : 'unverified';

            $genderValue = $data['gender'] ?? null;
            if ($genderValue instanceof Gender) {
                $gender = $genderValue;
            } else {
                // If it's a string from request
                $gender = $genderValue ? Gender::from($genderValue) : null;
            }

            Patient::create([
                'id' => $mrn,
                'user_id' => $user->id,
                'nik' => $nik,
                'full_name' => $data['name'],
                'pob' => $data['pob'] ?? null,
                'dob' => $data['dob'] ?? null,
                'gender' => $gender,
                'address' => $data['address'] ?? null,
                'phone' => $phone,
                'blood_type' => $data['blood_type'] ?? null,
                'bpjs_number' => $data['bpjs_number'] ?? null,
                'bpjs_status' => $bpjsStatus,
            ]);

            return $user;
        });
    }
}
