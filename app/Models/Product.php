<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'code', 'description', 'short_description', 'type_products_id'];
    public function productType(){
        return $this->belongsTo(TypeProduct::class, 'type_products_id');
    }
}
