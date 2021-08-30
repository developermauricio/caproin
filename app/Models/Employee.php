<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['user_id', 'type_employee_id', 'branch_offices_id'];

    public function branchOffices(){
        return $this->belongsTo(BranchOffice::class, 'branch_offices_id');
    }

    public function typeEmploye(){
        return $this->belongsTo(TypeEmployee::class, 'type_employee_id');
    }

    public function purchaseOrder(){
        return $this->hasMany(PurchaseOrder::class, 'seller_id');
    }
}
