<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    const NO_SUBIDA =  'No subida';
    const NO_ENTREGADA = 'No Entregada';

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

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'purchase_order_id');
    }

    public function order_type()
    {
        return $this->belongsTo(OrderType::class, 'order_type_id');
    }

    public function current_status(){
        return $this->belongsTo(PurchaseOrderStateHistory::class, 'current_status_id');
    }

    public function purchase_order_state_histories()
    {
        return $this->hasMany(PurchaseOrderStateHistory::class, 'purchase_order_id');
    }

    public function scopeProcessing($query)
    {
        return $query->whereNull('actual_dispatch_date')->whereNull('actual_delivery_date');
    }

    public function scopeDispatched($query)
    {
        return $query->whereNotNull('actual_dispatch_date')->whereNull('actual_delivery_date');
    }

    public function scopeDelivered($query)
    {
        return $query->whereNotNull('actual_delivery_date');
    }

    public function scopeDelayed($query)
    {
        return $query->where(function ($query) {
            $now = \Carbon\Carbon::now();
            return $query
                ->whereNull('actual_delivery_date')
                ->whereDate('offer_delivery_date', '<=', $now);
            // ->orWhereRaw('offer_delivery_date <= actual_delivery_date')
        });
    }

    public function scopeDateFromTo($query, $columnName, $from, $to)
    {
        if ($from) {
            $query->where($columnName, '>=', $from);
        }
        if ($to) {
            $query->where($columnName, '<=', $to);
        }
        return $query;
    }

    public function scopeEntregado($query)
    {
        return $query->whereNotNull('actual_dispatch_date')
            ->whereNotNull('actual_delivery_date');
    }
}
