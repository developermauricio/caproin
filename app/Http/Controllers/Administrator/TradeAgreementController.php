<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Product;
use App\Models\TradeAgreement;
use App\Traits\MessagesException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class TradeAgreementController extends Controller
{

    use MessagesException;

    public function index(){
        $currencies = Currency::all(['id', 'code']);
        return view('admin.tradeAgreement.list-trade-agreement', compact('currencies'));
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

    public function importDataTrade(Request $request)
    {
        $file = $request->file('archive');
        $sheets = (new FastExcel)->importSheets($file);

        $encabezado = collect();
        $detalle = collect();
        $total = 0;

        collect($sheets->get(0))->each(function ($row) use (&$encabezado, &$total) {
            $total++;
            try {
                DB::beginTransaction();
                $customer = Customer::whereHas('user', function ($query) use ($row) {
                    return $query->query($row["Cliente"]);
                })->orWhere('id', $row['Cliente'])->first();
                $stateId = $row['Estado'];
                $currency = Currency::find($row['Moneda']);
                $version = $row['Versión'];
                $consecutive = $row['Consecutivo de Oferta'];
                $creation_date = $row['Fecha de Creación'];
                $final_date = $row['Fecha final'];
                $delivery_time = $row['Tiempo de Entrega'];

                $short_description = $row['Descripción'];
                $trm = $row['TRM'];

                $trade = TradeAgreement::where('consecutive_Offer', $consecutive)->first();

                if ($trade) {
                    throw new \Exception("El consecutivo de oferta ya esta registrado", "-1");
                }

                if (!$customer) {
                    throw new \Exception("Cliente no encontrado", "-1");
                }

                if (!$currency) {
                    throw new \Exception("Tipo de moneda no encontrado", "-1");
                }

                if (
                    $row['Estado'] != TradeAgreement::VIGENTE &&
                    $row['Estado'] != TradeAgreement::FINALIZADO
                ){
                    throw new \Exception("Estado invalido", "-1");
                }


                $tradeAgreement = new TradeAgreement();
                $tradeAgreement->consecutive_Offer = $consecutive;
                $tradeAgreement->version = $version;
                $tradeAgreement->state = $stateId;
                $tradeAgreement->customer_id = $customer->id;
                $tradeAgreement->short_description = $short_description;
                $tradeAgreement->currency_id = $currency->id;
                $tradeAgreement->creation_date = $creation_date;
                $tradeAgreement->final_date = $final_date;
                $tradeAgreement->delivery_time = $delivery_time;
                $tradeAgreement->TRM = $currency->id !== 2 ? $trm : (is_numeric($trm) ? $trm : null);
                $tradeAgreement->save();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $row['error'] = $this->parseException($exception, $row);
                $encabezado->add($row);
            }
        });


        $details = collect($sheets->get(1));

        $details->groupBy('Consecutivo Oferta')->each(function ($details, $consecutive) use (&$detalle, &$total) {
            $trade = TradeAgreement::select('id')->where('consecutive_Offer', $consecutive)->first();
            if ($trade) {
                $details->each(function ($detail) use ($trade, &$detalle, &$total) {
                    $total++;
                    DB::beginTransaction();
                    try {
                        $product = Product::where('code', $detail['Codigo de Producto Interno'])->first();
                        if (!$product) {
                            throw new \Exception("Producto no encontrado (Codigo de Producto Interno) ", "-1");
                        }

                        $check = DB::table('product_tradeeagreement')->where('tradeagreement_id', $trade->id)->where('product_id', $product->id)->first();

                        if($check){
                            // dd($check);
                            throw new \Exception("Producto ya registrado ", "-1");
                        }

                        $min = $detail['Cantidad Minima'];
                        $client_product_code = $detail['Codigo Producto Cliente'];
                        $unit_value = $detail['Valor Unitario'];
                        $description = $detail['Descripción'];

                        $datos = [
                            'product_id' => $product->id,
                            'minimum_amount' => $min,
                            'tradeagreement_id' => $trade->id,
                            'client_product_code' => $client_product_code,
                            'internal_product_code' => $product->code,
                            'unit_value' => $unit_value,
                            'description' => $description
                        ];

                        $saved = DB::table('product_tradeeagreement')->insert($datos);

                        if (!$saved){
                            throw new \Exception("Error al guardar", "-1");
                        }
                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollBack();
                        $detail['error'] = $this->parseException($exception, $detail);
                        $detalle->add($detail);
                    }
                });
            } else {
                $details->each(function ($detail) use (&$detalle, &$total) {
                    $total++;
                    $detail['error'] = "No se encuentra el consecutivo oferta";
                    $detalle->add($detail);
                });
            }
        });



        $errors = $encabezado->count() + $detalle->count();
        $success = $total - $errors;
        if ($errors < 1 && $errors !== $total) {
            return back()->with('status', "Transacción realizada existosamente");
        } else {
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', json_encode([
                        'encabezado' => $encabezado,
                        'detalle' => $detalle
                    ]));
            } else {
                return back()
                    ->with('error', "Ningún dato se ha importado correctamente")
                    ->with('lines', json_encode([
                        'encabezado' => $encabezado,
                        'detalle' => $detalle
                    ]));
            }
        }
    }

}
