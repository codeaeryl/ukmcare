<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$users = DB::table('users')->get(['id', 'password']);
$fixedCount = 0;

foreach($users as $user) {
    if (str_starts_with($user->password, '\\$')) {
        $fixedPassword = str_replace('\\$', '$', $user->password);
        DB::table('users')->where('id', $user->id)->update(['password' => $fixedPassword]);
        $fixedCount++;
    } elseif (!str_starts_with($user->password, '$2y$') && !str_starts_with($user->password, '$2a$')) {
        // If it's completely unhashed, hash it to 'password'
        DB::table('users')->where('id', $user->id)->update(['password' => Hash::make('password')]);
        $fixedCount++;
    }
}

echo "Fixed $fixedCount invalid password hashes.\n";
