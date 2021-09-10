<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;

    protected $fillable = ['name', 'code', 'description', 'short_description', 'type_products_id', 'state', 'user_id'];

    public function productType()
    {
        return $this->belongsTo(TypeProduct::class, 'type_products_id');
    }

    public function productPrices(){
        return $this->hasMany(ProductPrice::class);
    }
}
