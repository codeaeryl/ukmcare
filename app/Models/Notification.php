<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\NotificationStatus;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'status' => NotificationStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
