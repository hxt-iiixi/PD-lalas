<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_address',
        'discount_type',
        'total',
        'grand_total'
    ];

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }
}
