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
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Rap2hpoutre\FastExcel\FastExcel;

class ServiceController extends Controller
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
        return view('admin.product.list-services', compact('productTypes', 'status', 'currencies'));
    }

    public function getAllServices()
    {
        $products = Product::with('productType')->where('state', '=', PRODUCT::ACTIVE)->get();
        return datatables()->of($products)->toJson();
    }
}
