<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\RegistrationStatus;

class Registration extends Model
{
    protected $fillable = [
        'patient_mrn',
        'schedule_id',
        'queue_number',
        'status',
        'registration_date',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'status' => RegistrationStatus::class,
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_mrn', 'mrn');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }
}
