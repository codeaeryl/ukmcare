<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Str;

$patientUsers = User::where('role', 'patient')->get();

$created = 0;
foreach($patientUsers as $user) {
    if (!$user->patient) {
        Patient::create([
            'id' => 'P-' . strtoupper(Str::random(6)),
            'user_id' => $user->id,
            'nik' => '320' . rand(1000000000000, 9999999999999),
            'full_name' => $user->name,
            'dob' => '1990-01-01',
            'gender' => 'male',
            'address' => 'Auto Generated Address',
            'phone' => '08' . rand(1000000000, 9999999999),
        ]);
        $created++;
    }
}

echo "Created $created missing patient records.\n";
