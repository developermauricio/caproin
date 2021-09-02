<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['business_name', 'user_id', 'number_of_days_after_generating_invoice', 'number_of_days_after_invoice_overdue'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function customerPosition(){
        return $this->belongsTo(CustomerPosition::class, 'customer_position_id');
    }

    public function customerCategory(){
        return $this->belongsTo(CustomerCategory::class, 'customer_category_id');
    }

    public function customerType(){
        return $this->belongsTo(CustomerType::class, 'customer_type_id');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

}
