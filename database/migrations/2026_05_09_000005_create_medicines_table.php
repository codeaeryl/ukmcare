<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2);
            $table->string('unit', 20)->nullable();
            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE medicines ADD CONSTRAINT medicines_stock_non_negative CHECK (stock >= 0)');
            DB::statement('ALTER TABLE medicines ADD CONSTRAINT medicines_price_non_negative CHECK (price >= 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
