<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\PurchaseOrderStateHistory;
use Illuminate\Http\Request;

class PurchaseOrderStateHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $histories = OrderDetail::with('product', 'currency')->get();
        return response()->json($histories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderStateHistory  $purchaseOrderStateHistory
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrderStateHistory $purchaseOrderStateHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrderStateHistory  $purchaseOrderStateHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrderStateHistory $purchaseOrderStateHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrderStateHistory  $purchaseOrderStateHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrderStateHistory $purchaseOrderStateHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderStateHistory  $purchaseOrderStateHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrderStateHistory $purchaseOrderStateHistory)
    {
        //
    }
}
