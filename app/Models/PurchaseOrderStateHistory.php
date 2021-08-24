<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderStateHistory extends Model
{

    public function state_order() {
        return $this->belongsTo(StateOrder::class, 'state_order_id');
    }

    /**
     * Get all of the comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
