<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    protected $fillable = ['nameArchive','uuid', 'archive', 'archivable_id', 'archivable_type', 'type'];
    public function archivable(){
        return $this->morphTo();
    }
}
