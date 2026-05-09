<?php

namespace Database\Seeders;

use App\Models\BillService;
use App\Models\Bill;
use App\Models\Service;
use Illuminate\Database\Seeder;

class BillServiceSeeder extends Seeder
{
    public function run(): void
    {
        $bill = Bill::first();
        $service = Service::first();

        if ($bill && $service) {
            BillService::create([
                'bill_id' => $bill->id,
                'service_id' => $service->id,
                'quantity' => 1,
                'price' => $service->price,
            ]);
        }
    }
}
