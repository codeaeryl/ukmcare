<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\DayName;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'schedule_day',
        'start_hour',
        'end_hour',
        'quota',
    ];

    protected $casts = [
        'schedule_day' => DayName::class,
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
