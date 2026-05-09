<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillMedicine extends Model
{
    protected $fillable = [
        'bill_id',
        'medicine_id',
        'quantity',
        'price',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
