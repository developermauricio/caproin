<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;

    protected $fillable = ['business_name', 'code', 'user_id', 'type_providers_id'];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function typeProviders(){
        return $this->belongsTo(TypeProvider::class, 'type_providers_id');
    }
}
