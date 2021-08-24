<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderStateHistory extends Model
{

    /**
     * Get all of the post's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
