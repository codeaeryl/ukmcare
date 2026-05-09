<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Bill;
use App\Enums\BillStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $bill = Bill::first();

        if ($bill) {
            Payment::create([
                'bill_id' => $bill->id,
                'paid_amount' => 150000,
                'payment_method' => 'Cash',
                'payment_date' => Carbon::now(),
            ]);
            $bill->update(['status' => BillStatus::COMPLETE]);
        }
    }
}
