<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('users')->get(['id', 'email', 'password']);
foreach($users as $u) {
    echo "ID: {$u->id} | Email: {$u->email} | Length: " . strlen($u->password) . " | Hash: " . substr($u->password, 0, 15) . "...\n";
}
