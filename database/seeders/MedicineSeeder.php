<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        Medicine::create(['name' => 'Paracetamol', 'stock' => 100, 'price' => 5000]);
        Medicine::create(['name' => 'Amoxicillin', 'stock' => 50, 'price' => 15000]);
    }
}
