<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Product;
use App\Models\TradeAgreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TradeAgreementController extends Controller
{
    public function index(){
        return view('admin.tradeAgreement.list-trade-agreement');
    }

    public function getApiTradeAgreement(){
        $tradeAgreement = TradeAgreement::with('customer', 'currency')->get();
        return datatables()->of($tradeAgreement)->toJson();
    }

    public function getApiCurrency(){
        $currency = Currency::all();
        return response()->json(['data' => $currency]);
    }

    public function getApiProducts(){
        $products = Product::where('state', 1)->with('productType')->get();;
        return response()->json(['data' => $products]);
    }

    public function storeApiTradeAgreement(Request $request){
        $consecutive_Offer = $request->consecutive_Offer;
        $version = $request->version;
        $customer = json_decode($request->customer);
        $short_description = $request->short_description;
        $internal_product_code = $request->internal_product_code;
        $client_product_code = $request->client_product_code;
        $currency = json_decode($request->currency);
        $state = json_decode($request->state);
        $creation_date = $request->creation_date;
        $final_date = $request->final_date;
        $delivery_time = $request->delivery_time;
        $unit_value = $request->unit_value;
        $TRM = $request->TRM;
        $products = json_decode($request->products);

        $tradeAgreement = new TradeAgreement;
        $tradeAgreement->consecutive_Offer = $consecutive_Offer;
        $tradeAgreement->version = $version;
        $tradeAgreement->state = $state->id;
        $tradeAgreement->customer_id = $customer->id;
        $tradeAgreement->short_description = $short_description;
        $tradeAgreement->internal_product_code = $internal_product_code;
        $tradeAgreement->client_product_code = $client_product_code;
        $tradeAgreement->currency_id = $currency->id;
        $tradeAgreement->creation_date = $creation_date;
        $tradeAgreement->final_date = $final_date;
        $tradeAgreement->delivery_time = $delivery_time;
        $tradeAgreement->unit_value = $unit_value;
        $tradeAgreement->TRM = $TRM;
        $tradeAgreement->save();

        foreach ($products as $value) {

            DB::table('product_tradeeagreement')->insert([
                'product_id' => $value->id,
                'minimum_amount' => $value->minimum,
                'tradeagreement_id' => $tradeAgreement->id,
            ]);
        }

        return response()->json('Acuerdo Comercial Creado Correctamente');
    }

    public function getApiDataTradeAgreement($id)
    {
        $tradeAgreement = TradeAgreement::where('id', $id)->with('customer', 'currency', 'products.productType')->first();
        return response()->json(['data' => $tradeAgreement]);
    }
}
