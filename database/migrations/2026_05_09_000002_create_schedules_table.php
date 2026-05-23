<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\DayName;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id', 20);
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->enum('schedule_day', array_column(DayName::cases(), 'value'));
            $table->time('start_hour');
            $table->time('end_hour');
            $table->integer('quota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
