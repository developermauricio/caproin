<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorySendPaymetClient extends Model
{

    protected $fillable = ['invoice_id', 'type_send', 'detail'];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
