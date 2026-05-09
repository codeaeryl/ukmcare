<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $primaryKey = 'mrn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mrn',
        'nik',
        'full_name',
        'pob',
        'dob',
        'gender',
        'address',
        'phone',
        'blood_type',
        'bpjs_number',
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
}
