<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillService extends Model
{
    protected $fillable = [
        'bill_id',
        'service_id',
        'quantity',
        'price',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
