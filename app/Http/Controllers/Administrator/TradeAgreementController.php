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

        $rol = auth()->user()->roles->first()->name; // Rol del Usuario
        $user = auth()->user()->id; // id del Usuario

        if ($rol === "Cliente") {
            $tradeAgreement = TradeAgreement::whereHas('customer', function ($q) use ($user){
                return $q->where('user_id', $user)->orWhereHas('sede', function ($q) use ($user) {
                    return $q->where('user_id', $user);
                });
            })->with('customer', 'currency')->get();
        }else{
            $tradeAgreement = TradeAgreement::with('customer', 'currency')->get();
        }


        return datatables()->of($tradeAgreement)->toJson();
    }

    public function getApiCurrency(){
        $currency = Currency::all();
        return response()->json(['data' => $currency]);
    }

    public function getApiProducts(){
        $products = Product::where('state', 1)->with('productType','productPrices')->get();;
        return response()->json(['data' => $products]);
    }

    public function storeApiTradeAgreement(Request $request){
        $consecutive_Offer = $request->consecutive_Offer;
        $version = $request->version;
        $customer = json_decode($request->customer);
        $short_description = $request->short_description;
        $currency = json_decode($request->currency);
        $state = json_decode($request->state);
        $creation_date = $request->creation_date;
        $final_date = $request->final_date;
        $delivery_time = $request->delivery_time;
        $TRM = $request->TRM;
        $products = json_decode($request->products);

        $tradeAgreement = new TradeAgreement;
        $tradeAgreement->consecutive_Offer = $consecutive_Offer;
        $tradeAgreement->version = $version;
        $tradeAgreement->state = $state->id;
        $tradeAgreement->customer_id = $customer->id;
        $tradeAgreement->short_description = $short_description;
        $tradeAgreement->currency_id = $currency->id;
        $tradeAgreement->creation_date = $creation_date;
        $tradeAgreement->final_date = $final_date;
        $tradeAgreement->delivery_time = $delivery_time;
        $tradeAgreement->TRM = $TRM;
        $tradeAgreement->save();

        foreach ($products as $value) {

            DB::table('product_tradeeagreement')->insert([
                'product_id' => $value->id,
                'minimum_amount' => $value->minimum,
                'tradeagreement_id' => $tradeAgreement->id,
                'client_product_code' => $value->codeInterClient,
                'internal_product_code' => $value->code,
                'unit_value' => $value->valueUnit,
                'description' => $value->descriptionProduct,
            ]);
        }

        return response()->json('Acuerdo Comercial Creado Correctamente');
    }

    public function updateApiTradeAgreement(Request $request){

        $consecutive_Offer = $request->consecutive_Offer;
        $version = $request->version;
        $customer = json_decode($request->customer);
        $short_description = $request->short_description;
        $currency = json_decode($request->currency);
        $state = json_decode($request->state);
        $creation_date = $request->creation_date;
        $final_date = $request->final_date;
        $delivery_time = $request->delivery_time;
        $TRM = $request->TRM;
        $products = json_decode($request->products);
        $idTradeAgreement = $request->idTradeAgreement;

        $tradeAgreement = TradeAgreement::findOrFail($idTradeAgreement);
        $tradeAgreement->consecutive_Offer = $consecutive_Offer;
        $tradeAgreement->version = $version;
        $tradeAgreement->state = $state->id;
        $tradeAgreement->customer_id = $customer->id;
        $tradeAgreement->short_description = $short_description;
        $tradeAgreement->currency_id = $currency->id;
        $tradeAgreement->creation_date = $creation_date;
        $tradeAgreement->final_date = $final_date;
        $tradeAgreement->delivery_time = $delivery_time;
        $tradeAgreement->TRM = $TRM;
        $tradeAgreement->update();

//        dd($products->pivot->minimum_amount);
        $arrayProducts = [];
        foreach ($products as $product) {
            array_push($arrayProducts,[
                'product_id' => $product->id,
                'minimum_amount' => $product->pivot->minimum_amount,
                'client_product_code' => $product->pivot->client_product_code,
                'internal_product_code' => $product->code,
                'unit_value' => $product->pivot->unit_value,
                'description' => $product->pivot->description,
            ]);
        }

        $tradeAgreement->products()->sync($arrayProducts);
        foreach ($products as $value) {

            DB::table('product_tradeeagreement')->where('id', $value->id)->update([
                'minimum_amount' => $value->pivot->minimum_amount,
                'client_product_code' => $value->pivot->client_product_code,
                'internal_product_code' => $value->code,
                'unit_value' => $value->pivot->unit_value,
                'description' => $value->pivot->description,
            ]);
        }

        return response()->json('Acuerdo Comercial Actualizado Correctamente');
    }

    public function getApiDataTradeAgreement($id)
    {
        $tradeAgreement = TradeAgreement::where('id', $id)->with('customer', 'currency', 'products.productType')->first();
        return response()->json(['data' => $tradeAgreement]);
    }

    public function validateConsecutivoOfert($consecutivo){
        $check = TradeAgreement::where('consecutive_Offer', $consecutivo)->first();
        if ($check) {
            return response()->json('El consecutivo de oferta ya ha sido registrado, por favor ingrese otro', 200);
        }
    }
}
