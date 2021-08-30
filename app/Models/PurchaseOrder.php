<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function seller()
    {
        return $this->belongsTo(Employee::class, 'seller_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function conveyor()
    {
        return $this->belongsTo(Conveyor::class, 'conveyor_id');
    }

    public function payment()
    {
        return $this->belongsTo(PaymentType::class, 'payment_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'purchase_order_id');
    }

    public function order_type() {
        return $this->belongsTo(OrderType::class, 'order_type_id');
    }

    public function purchase_order_state_histories()
    {
        return $this->hasMany(PurchaseOrderStateHistory::class, 'purchase_order_id');
    }
}
