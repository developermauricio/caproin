<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Conveyor;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\OrderType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\StateOrder;
use App\Models\TypeProduct;
use App\Models\Zone;
use App\User;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('admin.purchase-order.list-purchase-orders');
    }

    public function create()
    {
        return view('admin.purchase-order.create-purchase-order');
    }

    public function getApiPurchaseOrdes()
    {
        $purchase_ordes = PurchaseOrder::get();
        return datatables()->of($purchase_ordes)->toJson();
    }

    public function getAllCustomers()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function getAllOrderTypes()
    {
        $orderTypes = OrderType::all();
        return response()->json($orderTypes);
    }

    public function getAllStateOrders()
    {
        $stateOrders = StateOrder::all();
        return response()->json($stateOrders);
    }

    public function getAllProductTypes()
    {
        $productTypes = TypeProduct::all();
        return $productTypes;
    }

    public function getAllZone()
    {
        $zones = Zone::all();
        return response()->json($zones);
    }

    public function getAllSeller()
    {
        $sellers = User::all();
        return response()->json($sellers);
    }

    public function getAllTypeCoin()
    {
        $typeCoins = Currency::all();
        return response()->json($typeCoins);
    }

    public function getAllConveyor()
    {
        $conveyors = Conveyor::all();
        return response()->json($conveyors);
    }

    public function getAllProductByType(Request $request)
    {
        $products = Product::where('type_products_id', $request->input('type'))->get();
        return $products;
    }

    public function getPurchaseOrderById($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'order_type',
            'customer',
            'zone',
            'seller.user',
            'currency',
            'conveyor',
            'payment',
            'invoice.state',
            'provider',
            'order_details' => function ($q) {
                return $q->with('product', 'currency');
            },
            'purchase_order_state_histories.state_order'
        ])->find($id);

        return response()->json($purchaseOrder);
    }
}
