<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
//    const ACTIVE = 1;
//    const INACTIVE = 2;

    protected $fillable = [
        'invoice_number',
        'date_issue',
        'customer_id',
        'type_invoice_id',
        'state_id',
        'value_total',
        'date_payment_client',
        'electronic_invoice_number',
        'expiration_date',
        'date_received_client',
        'invoice_date_house_manufacturer',
        'commission_receipt_date',
        'new_agreed_payment_date',
        'invoice_number_house_representative',
        'commission_value',
        'comments',
    ];

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function typeInvoice()
    {
        return $this->belongsTo(TypeInvoice::class, 'type_invoice_id');
    }

    public function state()
    {
        return $this->belongsTo(StateInvoice::class, 'state_id');
    }
}