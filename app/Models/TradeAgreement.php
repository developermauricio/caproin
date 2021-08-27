<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeAgreement extends Model
{
    const VIGENTE = 1;
    const FINALIZADO = 2;

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_tradeeagreement',  'tradeagreement_id', 'product_id')->withPivot('minimum_amount', 'client_product_code', 'internal_product_code', 'unit_value', 'description');
    }
}
