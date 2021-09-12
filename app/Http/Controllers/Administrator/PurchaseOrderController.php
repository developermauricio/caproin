<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Comment;
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

    public function update($id)
    {
        return view('admin.purchase-order.update-purchase-order', compact('id'));
    }

    public function getApiPurchaseOrdes()
    {
        $rol = auth()->user()->roles->first()->name; // Rol del Usuario
        $user = auth()->user()->id; // id del Usuario

        $purchase_ordes = PurchaseOrder::with([
            'customer',
            'purchase_order_state_histories.state_order'
        ]);

        if ($rol === "Vendedor") {
            $purchase_ordes->whereHas('seller', function ($q) use ($user) {
                return $q->where('user_id', $user);
            });
        } elseif ($rol === "Cliente") {
            $purchase_ordes->whereHas('customer', function ($q) use ($user) {
                return $q->where('user_id', $user)->orWhereHas('sede', function ($q) use ($user) {
                    return $q->where('user_id', $user);
                });
            });
        }

        $purchase_ordes = $purchase_ordes->get();
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
        $products = Product::with('productPrices')->where('type_products_id', $request->input('type'))->get();
        return $products;
    }

    public function getAllSeguimiento(Request $request)
    {
        $seguimientos = Comment::with('commentable.state_order')
            ->whereByIn(PurchaseOrderStateHistory::class, collect($request->input('ids')))
            ->orderBy('created_at', 'DESC')
            ->get();
        return $seguimientos;
    }

    public function saveNewSeguimiento(Request $request)
    {
        $commentable = $request->get('commentable');
        $comment = new Comment();
        $comment->title = $request->get('title');
        $comment->body = $request->get('body');
        $stateHistory = PurchaseOrderStateHistory::find($commentable['id']);
        $stateHistory->comments()->save($comment);

        return response()->json($stateHistory, 201);
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
                return $q->with('product.productPrices', 'currency');
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
            $this->storePurchaseOrder($purchaseOrder, $request);
            $this->updateStateHistory($purchaseOrder, $request);
            $this->updateOrderDetails($purchaseOrder, $request);
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

    public function updatePurchaseOrder($id, Request $request)
    {

        try {
            DB::beginTransaction();
            $purchaseOrder = PurchaseOrder::findOrFail($id);
            $this->storePurchaseOrder($purchaseOrder, $request);
            $this->updateStateHistory($purchaseOrder, $request);
            $this->updateOrderDetails($purchaseOrder, $request);
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


    private function storePurchaseOrder(&$purchaseOrder, $request)
    {
        try {
            $purchaseOrder->customer_order_number = $request->input('customer_order_number');
            $purchaseOrder->internal_order_number = $request->input('internal_order_number');
            $purchaseOrder->final_user = $request->input('final_user');

            $purchaseOrder->customer_id = $request->input('customer.id');
            $purchaseOrder->order_type_id = $request->input('order_type.id');
            $purchaseOrder->zone_id = $request->input('zone.id');
            $purchaseOrder->seller_id = $request->input('seller.id');

            $purchaseOrder->house = $request->input('house');
            $purchaseOrder->description = $request->input('description');
            // $purchaseOrder->has_blueprint = $request->input('has_blueprint');

            $purchaseOrder->currency_id = $request->input('currency.id');
            $purchaseOrder->total_value = $request->input('total_value');

            $purchaseOrder->internal_quote_number = $request->input('internal_quote_number');
            $purchaseOrder->manufacturer_house_quotation_number = $request->input('manufacturer_house_quotation_number');
            $purchaseOrder->dispatch_guide_number = $request->input('dispatch_guide_number');

            $purchaseOrder->conveyor_id = $request->input('conveyor.id');
            $purchaseOrder->order_receipt_date = $this->getDate($request->input('order_receipt_date'));
            $purchaseOrder->offer_delivery_date = $this->getDate($request->input('offer_delivery_date'));
            $purchaseOrder->delivery_date_required_customer = $this->getDate($request->input('delivery_date_required_customer'));
            $purchaseOrder->expected_dispatch_date = $this->getDate($request->input('expected_dispatch_date'));
            $purchaseOrder->actual_dispatch_date = $this->getDate($request->input('actual_dispatch_date'));
            $purchaseOrder->actual_delivery_date = $this->getDate($request->input('actual_delivery_date'));
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
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getDate($dateTxt){
        if (trim($dateTxt) !== '') {
            return \Carbon\Carbon::parse($dateTxt);
        } else {
            return null;
        }
    }

    private function updateStateHistory($purchaseOrder, $request)
    {
        $idsHistory = collect();
        try {
            $history = collect($request->input('purchase_order_state_histories'));
            $history->each(function ($history) use ($purchaseOrder, &$idsHistory) {
                $purchase_order = new PurchaseOrderStateHistory();
                if (isset($history['id']) && is_numeric($history['id'])) {
                    $purchase_order = PurchaseOrderStateHistory::findOrFail($history['id']);
                }
                $purchase_order->purchase_order_id = $purchaseOrder->id;
                $purchase_order->state_order_id = $history['state_order']['id'];
                $purchase_order->description = $history['description'];
                if (trim($history['estimated_date']) !== '') {
                    $purchase_order->estimated_date = \Carbon\Carbon::parse($history['estimated_date']);
                } else {
                    $purchase_order->estimated_date = null;
                }
                $purchase_order->save();
                $idsHistory->add($purchase_order->id);
            });
            PurchaseOrderStateHistory::where('purchase_order_id', $purchaseOrder->id)->whereNotIn('id', $idsHistory->toArray())->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function updateOrderDetails($purchaseOrder, $request)
    {
        $idsDetails = collect();
        try {
            $details = collect($request->input('order_details'));
            $details->each(function ($detail) use ($purchaseOrder, &$idsDetails) {
                $newDetail = new OrderDetail();

                if (isset($detail['id']) && is_numeric($detail['id'])) {
                    $newDetail = OrderDetail::find($detail['id']);
                }

                $newDetail->purchase_order_id = $purchaseOrder->id;
                $newDetail->manufacturer = $detail['manufacturer'];

                $newDetail->product_id = $detail['product']['id'];
                $newDetail->internal_product_code = $detail['product']['code'];

                $newDetail->client_product_code = $detail['client_product_code'];
                $newDetail->customer_product_description = $detail['customer_product_description'];

                $newDetail->application = $detail['application'];

                $newDetail->blueprint_number = $detail['blueprint_number'];
                // $newDetail->internal_quote_number = $detail['internal_quote_number'];
                $newDetail->house_quote_number = $detail['house_quote_number'];

                $newDetail->currency_id = $detail['currency']['id'];
                $newDetail->value = $detail['value'];

                $newDetail->save();
                $idsDetails->add($newDetail->id);
            });
            OrderDetail::where('purchase_order_id', $purchaseOrder->id)->whereNotIn('id', $idsDetails->toArray())->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
