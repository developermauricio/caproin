<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Conveyor;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\OrderDetail;
use App\Models\OrderType;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderStateHistory;
use App\Models\StateOrder;
use App\Models\TypeProduct;
use App\Models\Zone;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getAllInvoices()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
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
        $sellers = Employee::with('user')->whereHas('user.roles', function ($q) {
            return $q->where("name", User::ROL_VENDEDOR);
        })->get();
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
            'order_details' => function ($q) {
                return $q->with('product', 'currency');
            },
            'purchase_order_state_histories.state_order'
        ])->find($id);

        return response()->json($purchaseOrder);
    }

    public function savePurchaseOrder(Request $request)
    {

        try {
            DB::beginTransaction();
            $purchaseOrder = new PurchaseOrder();

            $purchaseOrder->customer_order_number = $request->input('customer_order_number');
            $purchaseOrder->internal_order_number = $request->input('internal_order_number');
            $purchaseOrder->final_user = $request->input('final_user');

            $purchaseOrder->customer_id = $request->input('customer.id');
            $purchaseOrder->order_type_id = $request->input('order_type.id');
            $purchaseOrder->zone_id = $request->input('zone.id');
            $purchaseOrder->seller_id = $request->input('seller.id');

            $purchaseOrder->house = $request->input('house');
            $purchaseOrder->description = $request->input('description');
            $purchaseOrder->has_blueprint = $request->input('has_blueprint');

            $purchaseOrder->currency_id = $request->input('currency.id');
            $purchaseOrder->total_value = $request->input('total_value');

            $purchaseOrder->internal_quote_number = $request->input('internal_quote_number');
            $purchaseOrder->manufacturer_house_quotation_number = $request->input('manufacturer_house_quotation_number');
            $purchaseOrder->dispatch_guide_number = $request->input('dispatch_guide_number');

            $purchaseOrder->conveyor_id = $request->input('conveyor.id');
            $purchaseOrder->order_receipt_date = \Carbon\Carbon::parse($request->input('order_receipt_date'));
            $purchaseOrder->offer_delivery_date = \Carbon\Carbon::parse($request->input('offer_delivery_date'));
            $purchaseOrder->delivery_date_required_customer = \Carbon\Carbon::parse($request->input('delivery_date_required_customer'));
            $purchaseOrder->expected_dispatch_date = \Carbon\Carbon::parse($request->input('expected_dispatch_date'));
            $purchaseOrder->actual_dispatch_date = \Carbon\Carbon::parse($request->input('actual_dispatch_date'));
            $purchaseOrder->actual_delivery_date = \Carbon\Carbon::parse($request->input('actual_delivery_date'));
            $purchaseOrder->payment_id = $request->input('payment.id');

            $invoice = $request->input('invoice');
            if (isset($invoice) && $invoice !== null) {
                $purchaseOrder->invoice_id = $request->input('invoice.id');
                $purchaseOrder->invoice_number = $request->input('invoice.invoice_number');
                $purchaseOrder->invoice_state = PurchaseOrder::NO_ENTREGADA;
            } else {
                $purchaseOrder->invoice_state = PurchaseOrder::NO_SUBIDA;
            }

            $purchaseOrder->contact_number = $request->input('contact_number');

            $purchaseOrder->save();


            $history = collect($request->input('purchase_order_state_histories'));
            $history->each(function ($history) use ($purchaseOrder) {
                $purchase_order = new PurchaseOrderStateHistory();
                $purchase_order->purchase_order_id = $purchaseOrder->id;
                $purchase_order->state_order_id = $history['state_order']['id'];
                $purchase_order->description = $history['description'];
                $purchase_order->estimated_date = \Carbon\Carbon::parse($history['estimated_date']);
                $purchase_order->save();
            });

            $details = collect($request->input('order_details'));
            $details->each(function ($detail) use ($purchaseOrder) {
                $newDetail = new OrderDetail();

                $newDetail->purchase_order_id = $purchaseOrder->id;
                $newDetail->manufacturer = $detail['manufacturer'];

                $newDetail->product_id = $detail['product']['id'];
                $newDetail->internal_product_code = $detail['product']['code'];

                $newDetail->client_product_code = $detail['client_product_code'];
                $newDetail->customer_product_description = $detail['customer_product_description'];

                $newDetail->application = $detail['application'];

                $newDetail->blueprint_number = $detail['blueprint_number'];

                $newDetail->currency_id = $detail['currency']['id'];
                $newDetail->value = $detail['value'];

                $newDetail->save();
            });
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'msg' => $th->getMessage(),
                'error' => $th->getTrace()
            ], 500);
        }
    }
}
