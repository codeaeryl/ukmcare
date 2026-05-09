<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Bill;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Doctor Consultation',
            'description' => 'General checkup',
            'price' => 150000,
        ]);
    }
}
