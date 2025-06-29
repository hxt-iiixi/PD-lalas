<?php

// app/Models/SalesItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    protected $fillable = [
        'sales_invoice_id', 'sale_id', 'product_id', 'quantity', 'price', 'drug_name', 'brand', 'quantity', 'unit_price', 'total_price'
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

