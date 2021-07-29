<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function country(){
        return $this->hasOne(Country::class,'alpha2Code','country_code');
    }
}
