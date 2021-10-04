<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZonesController extends Controller
{
    public function index()
    {
        return view('admin.zone.list-zone');
    }

    public function getApiBranchZones()
    {
        $zones = Zone::all();
        return response()->json(['data' => $zones]);
    }

    public function getApiZone($id)
    {
        $zone = Zone::where('id', $id)->first();
        return response()->json(['data' => $zone]);
    }

    public function storeApiZone(Request $request)
    {
        $name = $request->name;
        $code = $request->code;

        Zone::create([
            'name' => $name,
            'code' => $code
        ]);

        return response()->json('Registro Exitoso!');
    }

    public function validateCode($code)
    {
        $check = Zone::whereCode($code)->first();
        if ($check) {
            return response()->json('El código ya ha sido registrado, por favor ingrese otro', 200);

        }
    }

    public function updateApiZone(Request $request){
        $name = $request->name;
        $code = $request->code;
        $state = json_decode($request->state);
        $id = $request->id;

        Zone::where('id', $id)->update([
            'name' => $name,
            'code' => $code,
            'state' => $state->id
        ]);

        return response()->json('¡Registro Exitoso!');
    }

    public function deleteZone(Request $request){
//        DB::table('zones')->where('id', $request->id)->delete();
//        return response()->json('Se eliminó correctamente');
        $zones = Zone::where('id', $request->id)->with('purchaseorder')->first();

        if (count($zones->purchaseorder) > 0){
            return response()->json('No se eliminó por que tiene registros asociados', 301);
        }else{
            DB::table('zones')->where('id', $request->id)->delete();
            return response()->json('Se eliminó correctamente');

        }
    }
}
