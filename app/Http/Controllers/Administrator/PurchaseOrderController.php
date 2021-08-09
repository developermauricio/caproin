<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index() {
        return view('admin.purchase-order.list-purchase-orders');
    }
}
