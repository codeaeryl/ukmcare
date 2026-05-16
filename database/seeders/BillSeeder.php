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
        $registrations = Registration::all();

        foreach ($registrations as $registration) {
            Bill::create([
                'registration_id' => $registration->id,
                'date' => clone $registration->registration_date, // copy date
                'status' => collect(BillStatus::cases())->random(),
            ]);
        }
    }
}
