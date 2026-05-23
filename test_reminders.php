<?php

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$targetTimeStart = Carbon::now()->addHour()->startOfMinute()->format('H:i:s');
$targetTimeEnd = Carbon::now()->addHour()->endOfMinute()->format('H:i:s');
$today = Carbon::today()->toDateString();

echo "Target Start: $targetTimeStart \n";
echo "Target End: $targetTimeEnd \n";
echo "Today: $today \n";

// show all registrations for today
$allToday = Registration::with('schedule')->whereDate('registration_date', $today)->get();
echo "All regs today: " . count($allToday) . "\n";
foreach($allToday as $reg) {
    if ($reg->schedule) {
        echo "Reg ID: {$reg->id}, Schedule Start Hour: {$reg->schedule->start_hour}\n";
    }
}

$upcomingAppointments = Registration::with(['patient.user', 'schedule.doctor'])
    ->where('status', 'registered')
    ->whereDate('registration_date', $today)
    ->whereHas('schedule', function ($query) use ($targetTimeStart, $targetTimeEnd) {
        $query->whereTime('start_hour', '>=', $targetTimeStart)
              ->whereTime('start_hour', '<=', $targetTimeEnd);
    })
    ->get();

echo "Matched: " . count($upcomingAppointments) . "\n";
