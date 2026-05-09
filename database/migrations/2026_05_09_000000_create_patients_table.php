<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Gender;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->string('mrn', 20)->primary();
            $table->char('nik', 16)->unique();
            $table->string('full_name', 100);
            $table->string('pob', 50)->nullable();
            $table->date('dob');
            $table->enum('gender', array_column(Gender::cases(), 'value'));
            $table->string('address', 255)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('blood_type', 3)->nullable();
            $table->string('bpjs_number', 20)->unique()->nullable();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
