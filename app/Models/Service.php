<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'doctor_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function billServices()
    {
        return $this->hasMany(BillService::class);
    }

    public function bills()
    {
        return $this->belongsToMany(Bill::class, 'bill_services')->withPivot('quantity', 'price')->withTimestamps();
    }
}
