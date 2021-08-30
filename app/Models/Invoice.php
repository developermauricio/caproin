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
        'payment_type_id',
        'state_id',
        'value_total',
        'value_payment',
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

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function state()
    {
        return $this->belongsTo(StateInvoice::class, 'state_id');
    }

    public function archive(){
        return $this->morphMany(Archives::class, 'archivable');
    }

    public function purchaseOrder(){
        return $this->hasOne(PurchaseOrder::class, 'invoice_id');
    }
}
