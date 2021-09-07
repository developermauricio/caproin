<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'title',
        'body'
    ];

    protected $hidden = [
        'commentable_type',
        'commentable_id',
    ];

    /**
     * Get the parent commentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function scopeWhereBy($query, $commentable_type, $commentable_id)
    {
        return $query->where('commentable_type', $commentable_type)->where('commentable_id', $commentable_id);
    }

    public function scopeWhereByIn($query, $commentable_type, $commentable_ids)
    {
        return $query->where('commentable_type', $commentable_type)->whereIn('commentable_id', $commentable_ids);
    }


}
