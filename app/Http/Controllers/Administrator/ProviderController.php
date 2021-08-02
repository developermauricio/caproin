<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\TypeProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProviderController extends Controller
{
    public function index(){
        return view('admin.provider.list-providers');
    }

    public function getApiProviders(){
        $providers = Provider::with('users.identificationType', 'typeProviders')->get();
        return datatables()->of($providers)->toJson();
    }

    public function getApiTypeProviders(){
        $getTypeProvider = TypeProvider::all();
        return response()->json(['data' => $getTypeProvider]);
    }

    public function getApiDataProviders($id){
        $provider = Provider::where('id', $id)->with('users.identificationType', 'typeProviders')->first();
        return response()->json(['data' => $provider]);
    }

    public function storeApiProvider(Request $request){
        $ramdon = Str::random(10);

        $businessName = $request->businessName;
        $typeIdentification = json_decode($request->typeIdentification);
        $identification = $request->identification;
        $typeProvider = json_decode($request->typeProvider);
        $code = $request->code;
        $slug = Str::slug($businessName . '-' . $code . '-' . $ramdon, '-');

        $user = User::create([
            "name" =>  $businessName,
            "slug" => $slug,
            "identification_type_id" => $typeIdentification->id,
            "identification" => $identification
        ]);

        $provider = Provider::create([
            "business_name" => $businessName,
            "code" => $code,
            "user_id" => $user->id,
            "type_providers_id" => $typeProvider->id
        ]);

        return response()->json('Registro Exitoso!');
    }

    public function updateApiProvider(Request $request){

        $ramdon = Str::random(10);

        $businessName = $request->businessName;
        $typeIdentification = json_decode($request->typeIdentification);
        $identification = $request->identification;
        $typeProvider = json_decode($request->typeProvider);
        $stateProvider = json_decode($request->state);
        $code = $request->code;
        $idProvider = $request->idProvider;
        $idUser = $request->idUser;
        $slug = Str::slug($businessName . '-' . $code . '-' . $ramdon, '-');

        $user = User::where('id', $idUser)->update([
            "name" =>  $businessName,
            "slug" => $slug,
            "identification_type_id" => $typeIdentification->id,
            "identification" => $identification
        ]);

        $provider = Provider::where('id', $idProvider)->update([
            "business_name" => $businessName,
            "code" => $code,
            "state" => $stateProvider->id,
            "type_providers_id" => $typeProvider->id
        ]);

        return response()->json('Proveedor Actualizando Correctamente!');
    }

    public function validateCode($code)
    {
        $check = Provider::whereCode($code)->first();
        if ($check) {
            return response()->json('El número de identificación ya ha sido registrado, por favor ingrese otro', 200);
        }
    }
}
