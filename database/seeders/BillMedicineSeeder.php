<?php

namespace Database\Seeders;

use App\Models\BillMedicine;
use App\Models\Bill;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class BillMedicineSeeder extends Seeder
{
    public function run(): void
    {
        $bill = Bill::first();
        $medicine = Medicine::first();

        if ($bill && $medicine) {
            BillMedicine::create([
                'bill_id' => $bill->id,
                'medicine_id' => $medicine->id,
                'quantity' => 2,
                'price' => $medicine->price,
            ]);
        }
    }
}
