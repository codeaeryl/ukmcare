<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\Registration;
use App\Enums\BillStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        $registration = Registration::first();

        if ($registration) {
            Bill::create([
                'registration_id' => $registration->id,
                'date' => Carbon::now(),
                'status' => BillStatus::PENDING,
            ]);
        }
    }
}
