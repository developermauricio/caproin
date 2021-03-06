<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    protected $fillable = ['name', 'code'];

    public function purchaseorder(){
        return $this->hasMany(PurchaseOrder::class, 'zone_id');
    }
}
