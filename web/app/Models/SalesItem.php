<?php

// app/Models/SalesItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'quantity', 'price_per_unit'];
    
    public function product() { return $this->belongsTo(Product::class); }
    public function sale() { return $this->belongsTo(Sale::class); }

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }
}

