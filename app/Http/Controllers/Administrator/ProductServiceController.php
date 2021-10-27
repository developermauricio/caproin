<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\TypeProduct;
use App\Traits\MessagesException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductServiceController extends Controller
{
    use MessagesException;

    public function index()
    {
        $productTypes = TypeProduct::all(['id', 'name']);
        $status = [
            \App\Models\Product::ACTIVE => "Activo",
            \App\Models\Product::INACTIVE => "Inactivo"
        ];
        $currencies = Currency::all('id', 'code');
        return view('admin.product.list-products', compact('productTypes', 'status', 'currencies'));
    }

    public function getApiInvoices()
    {
        $products = Product::with('productType')->get();
        return datatables()->of($products)->toJson();
    }

    public function validateCode($code)
    {
        $check = Product::whereCode($code)->first();
        if ($check) {
            return response()->json('El código ya ha sido registrado, por favor ingrese otro', 200);
        }
    }

    public function getApiTypeProductService()
    {
        $typeProductService = TypeProduct::all();
        return response()->json(['data' => $typeProductService]);
    }

    public function storeApiProductService(Request $request)
    {

        $typeProduct = json_decode($request->typeProductService);
        $state = json_decode($request->state);
        $product_prices = json_decode($request->product_prices);
        try {
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'code' => $request->code,
                'state' => $state->id,
                'user_id' => auth()->user()->id,
                'short_description' => $request->descriptionShort,
                'description' => $request->description,
                'type_products_id' => $typeProduct->id,
            ]);

            $product_prices = collect($product_prices);

            $product_prices->each(function ($productPrice) use (&$response, $product) {
                $newProductPrice = new ProductPrice();
                $newProductPrice->currency_id = $productPrice->currency_id;
                $newProductPrice->price = $productPrice->price;
                $newProductPrice->product_id = $product->id;
                $newProductPrice->save();
            });

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['msg' => $th->getMessage(), 'trace' => $th->getTrace()], 500);
        }

        return response()->json('Registro Exitoso!');
    }

    public function updateApiProductService(Request $request)
    {
        $typeProduct = json_decode($request->typeProductService);
        $state = json_decode($request->state);
        $product_prices = json_decode($request->product_prices);

        try {
            DB::beginTransaction();
            Product::where('id', $request->idProductService)->update([
                'name' => $request->name,
                'code' => $request->code,
                'state' => $state->id,
                'short_description' => $request->descriptionShort,
                'description' => $request->description,
                'type_products_id' => $typeProduct->id,
            ]);

            $product_prices = collect($product_prices);

            $product_prices->each(function ($productPrice) use ($request) {
                if (isset($productPrice->id)) {
                    $newProductPrice = ProductPrice::find($productPrice->id);
                } else {
                    $newProductPrice = new ProductPrice();
                    $newProductPrice->product_id = $request->idProductService;
                }
                $newProductPrice->currency_id = $productPrice->currency_id;
                $newProductPrice->price = $productPrice->price;
                $newProductPrice->save();
            });

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['msg' => $th->getMessage(), 'trace' => $th->getTrace()], 500);
        }

        return response()->json('Se actualizó correctamente!');
    }

    public function getApiDataProductService($id)
    {
        $productService = Product::where('id', $id)->with('productType', 'productPrices')->first();
        return response()->json(['data' => $productService]);
    }


    public function importDataProduct(Request $request)
    {
        $file = $request->file('archive');
        $sheets = (new FastExcel)->importSheets($file);

        $productTypes = ",".TypeProduct::all('id')->map(function ($item){
            return $item->id;
        })->join(',').",";
        $status = ",".\App\Models\Product::ACTIVE.','.\App\Models\Product::INACTIVE.",";
        $currencies = ",".Currency::all('id')->map(function ($item){
            return $item->id;
        })->join(',').",";
        $encabezado = collect();
        $detalle = collect();
        $total = 0;

        collect($sheets->get(0))->each(function ($row) use (&$encabezado, &$total, $productTypes, $status) {
            $total++;
            try {
                DB::beginTransaction();

                $state = $row['Estado'];
                $productType = $row['Tipo Producto'];
                $code = $row['Código Producto'];
                $name = $row['Nombre Producto o Servicio'];
                $descriptionShort = $row['Descripción corta'];
                $description = $row['Descripción'];


                if (strpos($status, ",".$state) === false){
                    throw new \Exception("Estado no encontrado ", "-1");
                }

                if (strpos($productTypes, ",".$productType) === false){
                    throw new \Exception("Tipo de producto no encontrado ", "-1");
                }

                $product = Product::where('code', $code)->first();

                if ($product){
                    throw new \Exception("Este código ya se encuentra registrado ", "-1");
                }

                $product = new Product();
                $product->name = $name;
                $product->code = $code;
                $product->state = $state;
                $product->short_description = $descriptionShort;
                $product->description = $description;
                $product->type_products_id = $productType;
                $product->save();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $row['error'] = $this->parseException($exception, $row);
                $encabezado->add($row);
            }
        });

        $details = collect($sheets->get(1));

        $details->groupBy('Codigo Producto')->each(function ($details, $code) use (&$detalle, &$total) {
            $product = Product::select('id')->where('code', $code)->first();
            if ($product) {
                $details->each(function ($detail) use ($product, &$detalle, &$total) {
                    $total++;
                    try {
                        DB::beginTransaction();
                        $moneda = $detail['Moneda'];
                        $valor = $detail['Valor'];

                        $productPrice = ProductPrice::where('currency_id', $moneda)
                            ->where('product_id', $product->id)
                            ->first();

                        if (!$productPrice){
                            $productPrice = new ProductPrice();
                            $productPrice->product_id = $product->id;
                            $productPrice->currency_id = $moneda;
                        }

                        $productPrice->price = $valor;
                        $productPrice->save();

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
                    $detail['error'] = "No se encuentra el codigo de producto";
                    $detalle->add($detail);
                });
            }
        });

        $errors = $encabezado->count() + $detalle->count();
        $success = $total - $errors;

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
