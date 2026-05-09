<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'schedule_day',
        'start_hour',
        'end_hour',
        'quota',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
