<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /**
     * Get the parent commentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

}
