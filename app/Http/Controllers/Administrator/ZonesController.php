<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

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
        $id = $request->id;

        Zone::where('id', $id)->update([
            'name' => $name,
            'code' => $code
        ]);

        return response()->json('¡Registro Exitoso!');
    }
}
