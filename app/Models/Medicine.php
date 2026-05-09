<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'price',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function billMedicines()
    {
        return $this->hasMany(BillMedicine::class);
    }

    public function bills()
    {
        return $this->belongsToMany(Bill::class, 'bill_medicines')->withPivot('quantity', 'price')->withTimestamps();
    }
}
