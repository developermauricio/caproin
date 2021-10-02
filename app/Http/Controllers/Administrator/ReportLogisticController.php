<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderStateHistory;
use App\Models\StateOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportLogisticController extends Controller
{
    //

    public function actividadLogisticaEnvios(Request $request)
    {

        $to = $request->input('to');
        $from = $request->input('from');

        $purchaseOrdersProcessing = PurchaseOrder::processing()->dateFromTo('order_receipt_date', $from, $to)->count();
        $purchaseOrdersDispatched = PurchaseOrder::dispatched()->dateFromTo('order_receipt_date', $from, $to)->count();
        $purchaseOrdersDelivered = PurchaseOrder::delivered()->dateFromTo('order_receipt_date', $from, $to)->count();

        $purchaseOrdersTotal = $purchaseOrdersProcessing + $purchaseOrdersDispatched + $purchaseOrdersDelivered;
        return response()->json([
            'purchaseOrdersProcessing' => $purchaseOrdersProcessing,
            'purchaseOrdersDispatched' => $purchaseOrdersDispatched,
            'purchaseOrdersDelivered' => $purchaseOrdersDelivered,
            'purchaseOrdersTotal' => $purchaseOrdersTotal
        ]);
    }

    public function actividadLogisticaEnviosByPeriodo(Request $request)
    {

        $to = $request->input('to');
        $from = $request->input('from');

        $purchaseOrdersTotal = PurchaseOrder::select(DB::raw('DATE_FORMAT(order_receipt_date, "%Y-%m") as label, count(id) as total'))
            ->dateFromTo('order_receipt_date', $from, $to)
            ->groupBy('label')
            ->get();

        // DB::enableQueryLog();

        $purchaseOrdersDelayed = PurchaseOrder::select(DB::raw('DATE_FORMAT(order_receipt_date, "%Y-%m") as label, count(id) as total'))
            ->delayed()
            ->dateFromTo('order_receipt_date', $from, $to)
            ->groupBy('label')
            ->get();
        // dd(DB::getQueryLog());

        $purchaseOrdersDelivered = PurchaseOrder::select(DB::raw('DATE_FORMAT(order_receipt_date, "%Y-%m") as label, count(id) as total'))
            ->delivered()
            ->dateFromTo('order_receipt_date', $from, $to)
            ->groupBy('label')
            ->get();

        return response()->json([
            'purchaseOrdersTotal' => $purchaseOrdersTotal,
            'purchaseOrdersDelayed' => $purchaseOrdersDelayed,
            'purchaseOrdersDelivered' => $purchaseOrdersDelivered,
        ]);
    }


    public function clientesEstadosPedido(Request $request)
    {
        $to = $request->input('to');
        $from = $request->input('from');

        $response = PurchaseOrder::selectRaw('customer_id, count(state_order_id) as total, state_order_id')
            ->join('purchase_order_state_histories', 'current_status_id', '=', 'purchase_order_state_histories.id')
            ->dateFromTo('order_receipt_date', $from, $to)
            ->groupBy('customer_id')
            ->groupBy('state_order_id')
            ->get()
            ->groupBy(function ($item) {
                return $item['customer_id'];
            })->map(function ($item) {
                return $item->keyBy(function ($item) {
                    return $item['state_order_id'];
                });
            });

        return response()->json($response);
    }

    public function pedidosPorEstatus(Request $request)
    {
        $to = $request->input('to');
        $from = $request->input('from');

        $response = PurchaseOrder::selectRaw('count(state_order_id) as total, state_order_id')
            ->join('purchase_order_state_histories', 'current_status_id', '=', 'purchase_order_state_histories.id')
            ->dateFromTo('order_receipt_date', $from, $to)
            ->groupBy('state_order_id')
            ->get()->keyBy(function ($item) {
                return $item['state_order_id'];
            });

        return response()->json($response);
    }

    public function getAllStatusOrder()
    {
        $states = StateOrder::all(['id', 'name'])->keyBy(function ($item) {
            return $item['id'];
        });
        return response()->json($states);
    }

    public function getAllCustomers(){
        $customers = Customer::all(['id', 'business_name'])->keyBy(function ($item) {
            return $item['id'];
        });
        return response()->json($customers);
    }
}
