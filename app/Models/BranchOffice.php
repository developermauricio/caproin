<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    protected $fillable = ['name', 'code'];

    public function employee(){
        return $this->hasMany(Employee::class, 'branch_offices_id');
    }
}
