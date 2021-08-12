<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
//    const ACTIVE = 1;
//    const INACTIVE = 2;

    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function typeInvoice(){
        return $this->belongsTo(TypeInvoice::class, 'type_invoice_id');
    }

    public function state(){
        return $this->belongsTo(StateInvoice::class, 'state_id');
    }
}
