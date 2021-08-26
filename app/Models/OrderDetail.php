<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
