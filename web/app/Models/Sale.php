<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
   protected $fillable = ['product_id', 'quantity', 'total_price', 'discount_type'];

    public function product()
        {
            return $this->belongsTo(Product::class);
    }

    public function getFormattedDiscountAttribute()
        {
            return match($this->discount_type) {
                'SC' => 'Senior Citizen (20%)',
                'PWD' => 'PWD (20%)',
                default => 'None',
            };
        }

    public function setDiscountTypeAttribute($value)
    {
        $this->attributes['discount_type'] = strtoupper($value ?? 'NONE');
    }

}
