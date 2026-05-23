<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nik',
        'full_name',
        'pob',
        'dob',
        'gender',
        'address',
        'phone',
        'blood_type',
        'bpjs_number',
        'bpjs_status',
        'user_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'gender' => Gender::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
