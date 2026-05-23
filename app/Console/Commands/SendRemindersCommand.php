<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Registration;
use App\Models\MedicalRecord;
use App\Models\Notification;
use Carbon\Carbon;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automatic reminders for appointments and medicine';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send reminders...');
        $count = 0;

        // 1. Jadwal Kontrol (Appointments in Exactly 1 Hour)
        $targetTimeStart = Carbon::now()->addHour()->startOfMinute()->format('H:i:s');
        $targetTimeEnd = Carbon::now()->addHour()->endOfMinute()->format('H:i:s');
        $today = Carbon::today()->toDateString();
        
        $upcomingAppointments = Registration::with(['patient.user', 'schedule.doctor'])
            ->where('status', 'registered')
            ->whereDate('registration_date', $today)
            ->whereHas('schedule', function ($query) use ($targetTimeStart, $targetTimeEnd) {
                $query->whereTime('start_hour', '>=', $targetTimeStart)
                      ->whereTime('start_hour', '<=', $targetTimeEnd);
            })
            ->get();

        foreach ($upcomingAppointments as $apt) {
            if ($apt->patient && $apt->patient->user) {
                $doctorName = $apt->schedule && $apt->schedule->doctor ? $apt->schedule->doctor->full_name : 'Dokter';
                $startTime = Carbon::parse($apt->schedule->start_hour)->format('H:i');
                $message = "Pengingat: Jadwal kontrol Anda dengan dr. {$doctorName} akan dimulai 1 jam lagi pada pukul {$startTime}.";
                
                Notification::create([
                    'user_id' => $apt->patient->user->id,
                    'message' => $message,
                    'date' => now(),
                    'status' => 'appointment',
                ]);
                $count++;
            }
        }

        // 2. Minum Obat (Prescriptions from the last 3 days)
        $recentRecords = MedicalRecord::with(['registration.patient.user', 'prescriptions.medicine'])
            ->where('record_date', '>=', Carbon::now()->subDays(3))
            ->get();

        foreach ($recentRecords as $record) {
            $patient = $record->registration->patient ?? null;
            if ($patient && $patient->user && $record->prescriptions->count() > 0) {
                foreach ($record->prescriptions as $prescription) {
                    $medName = $prescription->medicine->name ?? 'Obat';
                    $dosage = $prescription->dosage ?? 'Sesuai anjuran';
                    
                    $message = "Pengingat Minum Obat: Jangan lupa meminum {$medName} ({$dosage}) hari ini.";
                    
                    Notification::create([
                        'user_id' => $patient->user->id,
                        'message' => $message,
                        'date' => now(),
                        'status' => 'medicine',
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Successfully sent {$count} reminders.");
    }
}
