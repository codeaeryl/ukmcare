<?php

namespace App\Factories;

use App\Models\User;
use App\Models\Doctor;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorFactory implements UserFactoryInterface
{
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => Role::DOCTOR,
            ]);

            $latestDoctor = Doctor::lockForUpdate()->orderBy('created_at', 'desc')->first();
            $nextIdNumber = 1;
            if ($latestDoctor && preg_match('/(\d+)$/', $latestDoctor->id, $matches)) {
                $nextIdNumber = intval($matches[1]) + 1;
            }
            $docId = 'DOC-' . str_pad($nextIdNumber, 4, '0', STR_PAD_LEFT);

            Doctor::create([
                'id' => $docId,
                'user_id' => $user->id,
                'nik' => $data['nik'] ?? $data['nik_doctor'] ?? null,
                'sip' => $data['sip'] ?? null,
                'str' => $data['str'] ?? null,
                'full_name' => $data['name'],
                'specialist' => $data['specialist'] ?? null,
                'phone' => $data['phone'] ?? $data['phone_doctor'] ?? null,
                'is_bpjs' => isset($data['is_bpjs']) ? (bool) $data['is_bpjs'] : false,
            ]);

            return $user;
        });
    }
}
