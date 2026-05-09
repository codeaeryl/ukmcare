<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\BillStatus;

class Bill extends Model
{
    protected $fillable = [
        'registration_id',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'status' => BillStatus::class,
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function billServices()
    {
        return $this->hasMany(BillService::class);
    }

    public function billMedicines()
    {
        return $this->hasMany(BillMedicine::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'bill_services')->withPivot('quantity', 'price')->withTimestamps();
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'bill_medicines')->withPivot('quantity', 'price')->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
