<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\TypeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductServiceController extends Controller
{
    public function index()
    {
        return view('admin.product.list-products');
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
}
