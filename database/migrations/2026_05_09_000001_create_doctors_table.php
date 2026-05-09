<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->string('doctor_id', 20)->primary();
            $table->char('nik', 16)->unique();
            $table->string('sip', 50)->unique();
            $table->string('str', 50)->unique();
            $table->string('full_name', 100);
            $table->string('specialist', 50);
            $table->string('phone', 15)->nullable();
            $table->boolean('is_bpjs')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Adding user_id to link doctor to their user account
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
