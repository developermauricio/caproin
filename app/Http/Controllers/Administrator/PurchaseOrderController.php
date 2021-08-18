<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Conveyor;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\TypeCoin;
use App\Models\Zone;
use App\OrderType;
use App\User;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('admin.purchase-order.list-purchase-orders');
    }

    public function getApiPurchaseOrdes()
    {
        $purchase_ordes = PurchaseOrder::get();
        return datatables()->of($purchase_ordes)->toJson();
    }

    public function getAllCustomers() {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function getAllOrderTypes() {
        $orderTypes = OrderType::all();
        return response()->json($orderTypes);
    }

    public function getAllZone() {
        $zones = Zone::all();
        return response()->json($zones);
    }

    public function getAllSeller() {
        $sellers = User::all();
        return response()->json($sellers);
    }

    public function getAllTypeCoin() {
        $typeCoins = TypeCoin::all();
        return response()->json($typeCoins);
    }

    public function getAllConveyor() {
        $conveyors = Conveyor::all();
        return response()->json($conveyors);
    }
}
