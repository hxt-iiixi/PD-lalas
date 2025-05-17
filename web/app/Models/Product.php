<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

  protected $fillable = ['name', 'brand', 'supplier_price', 'selling_price', 'stock', 'category', 'expiry_date'];


}
