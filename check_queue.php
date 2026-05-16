<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;

$regs = Registration::all();
foreach($regs as $r) {
    echo "ID: $r->id | Sch: $r->schedule_id | Date: $r->registration_date | Q: $r->queue_number | Status: {$r->status->value}\n";
}
