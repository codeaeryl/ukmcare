<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('doctor_id', 20)->nullable();
            $table->string('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
