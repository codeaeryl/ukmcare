<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    protected $primaryKey = 'doctor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'doctor_id',
        'nik',
        'sip',
        'str',
        'full_name',
        'specialist',
        'phone',
        'is_bpjs',
        'is_active',
        'user_id',
    ];

    protected $casts = [
        'is_bpjs' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
