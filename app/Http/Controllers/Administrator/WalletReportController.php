<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\StateInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletReportController extends Controller
{
    public function facturasVencidas(Request $request)
    {
        // $trm  = $request->input('trm');
        $from = $request->input('from');
        $to   = $request->input('to');

        // DB::enableQueryLog();
        $vencidas_invoices = Invoice::select(DB::raw('sum(value_total) as valor_total, sum(value_payment) as valor_pagado'))
            ->expired()
            ->dateFromTo('date_issue', $from, $to)
            ->first();


        $total_invoices = Invoice::select(DB::raw('sum(value_total) as valor_total'))
            ->dateFromTo('date_issue', $from, $to)
            ->first();

        // $queries = DB::getQueryLog();

        if ($vencidas_invoices->valor_pagado) {
            $vencidas_invoices->valor_total -= $vencidas_invoices->valor_pagado;
        }

        return response()->json([
            'total_vencidas' => $vencidas_invoices->valor_total,
            'total' => $total_invoices->valor_total,
        ]);
    }

    public function morosidadTotal(Request $request)
    {
        // $trm  = $request->input('trm');
        $from = $request->input('from');
        $to   = $request->input('to');

        DB::enableQueryLog();
        $vencidas_invoices = Invoice::select(DB::raw('DATE_FORMAT(date_issue, "%Y-%m") as date_group, sum(value_total) as valor_total, sum(value_payment) as valor_pagado'))
            ->expired()
            ->dateFromTo('date_issue', $from, $to)
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get();


        $total_invoices =  Invoice::select(DB::raw('DATE_FORMAT(date_issue, "%Y-%m") as date_group, sum(value_total) as valor_total'))
            ->dateFromTo('date_issue', $from, $to)
            ->groupBy('date_group')
            ->orderBy('date_group')
            ->get();

        $queries = DB::getQueryLog();

        $vencidas_invoices = $vencidas_invoices->map(function ($data) {
            if ($data->valor_pagado) {
                $data->valor_total -= $data->valor_pagado;
            }
            return $data;
        });
        // if ($vencidas_invoices->valor_pagado) {
        //     $vencidas_invoices->valor_total -= $vencidas_invoices->valor_pagado;
        // }


        $total_invoices = $total_invoices->keyBy(function ($item) {
            return $item['date_group'];
        });

        $vencidas_invoices = $vencidas_invoices->keyBy(function ($item) {
            return $item['date_group'];
        });


        $response = [
            'total_vencidas' => $vencidas_invoices,
            'total' => $total_invoices,
            'q' => $queries
        ];


        // $response = $total_invoices->join($vencidas_invoices);

        return response()->json($response);
    }

    public function facturasVencidasPorCliente(Request $request)
    {
        // $trm  = $request->input('trm');
        $from = $request->input('from');
        $to   = $request->input('to');

        $vencidas_invoices = Invoice::select(DB::raw('customer_id, sum(value_total) as valor_total, sum(value_payment) as valor_pagado'))
            ->with('customer:id,business_name')
            ->expired()
            ->dateFromTo('date_issue', $from, $to)
            ->groupBy('customer_id')
            ->get();

        $response = $vencidas_invoices->map(function ($invoice) {
            $data = new \stdClass();
            if ($invoice->valor_pagado) {
                $data->value = $invoice->valor_total - $invoice->valor_pagado;
            } else {
                $data->value = $invoice->valor_total;
            }
            $data->label = $invoice->customer->business_name;
            return $data;
        });

        return response()->json($response);
    }

    public function rankingDeudores(Request $request)
    {
        // $trm  = $request->input('trm');
        $from = $request->input('from');
        $to   = $request->input('to');

        $vencidas_invoices = Invoice::select(DB::raw('customer_id, sum(value_total) as valor_total, sum(value_payment) as valor_pagado'))
            ->expired()
            ->dateFromTo('date_issue', $from, $to)
            ->groupBy('customer_id')
            ->get();

        $total_invoices = Invoice::select(DB::raw('customer_id, sum(value_total) as valor_total'))
            ->with('customer:id,business_name')
            ->dateFromTo('date_issue', $from, $to)
            ->groupBy('customer_id')
            ->get();

        $total_invoices = $total_invoices->map(function ($invoice) {
            $invoice->label = $invoice->customer->business_name;
            unset($invoice->customer);
            return $invoice;
        })->keyBy(function ($item) {
            return $item->customer_id;
        });

        $vencidas_invoices = $vencidas_invoices->map(function ($invoice) {
            $data = new \stdClass();
            if ($invoice->valor_pagado) {
                $data->value = $invoice->valor_total - $invoice->valor_pagado;
            } else {
                $data->value = $invoice->valor_total;
            }
            $data->customer_id = $invoice->customer->id;
            return $data;
        })->keyBy(function ($item) {
            return $item->customer_id;
        });

        $response = [
            'total_invoices' => $total_invoices,
            'vencidas_invoices' => $vencidas_invoices,
        ];

        return response()->json($response);
    }
}
