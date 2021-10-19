<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Conveyor;
use Illuminate\Http\Request;

class ConveyorController extends Controller
{

    public function index()
    {
        return view('admin.conveyor.list-conveyors');
    }


    public function getApiConveyors()
    {
        $conveyors = Conveyor::get();
        return datatables()->of($conveyors)->toJson();
    }

    public function getApiDataConveyors($id)
    {
        $conveyor = Conveyor::findOrFail($id);
        return response()->json($conveyor);
    }

    public function storeApiConveyor(Request $request)
    {
        $conveyor = new Conveyor();
        $conveyor->name = $request->input('name');
        $conveyor->description = $request->input('description');
        $conveyor->save();

        return response()->json($conveyor, 201);
    }

    public function updateApiConveyor(Request $request)
    {
        $conveyor = Conveyor::findOrFail($request->input('id'));
        $conveyor->name = $request->input('name');
        $conveyor->description = $request->input('description');
        $conveyor->save();
        return response()->json($conveyor);
    }

}
