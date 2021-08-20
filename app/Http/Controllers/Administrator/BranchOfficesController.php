<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchOfficesController extends Controller
{
    public function index()
    {
        return view('admin.branchOffice.list-brach-offices');
    }

    public function getApiBranchOffices()
    {
        $branchOffices = BranchOffice::all();
        return response()->json(['data' => $branchOffices]);
    }

    public function storeApiBranchOffice(Request $request)
    {
        $name = $request->name;
        $code = $request->code;

        BranchOffice::create([
            'name' => $name,
            'code' => $code
        ]);

        return response()->json('Registro Exitoso!');
    }

    public function getApiBranchOffice($id){
        $branchOffice = BranchOffice::where('id', $id)->first();
        return response()->json(['data' => $branchOffice]);
    }
    public function updateApiBranchOffice(Request $request){
        $name = $request->name;
        $code = $request->code;
        $id = $request->id;
        $state = json_decode($request->state);

        BranchOffice::where('id', $id)->update([
            'name' => $name,
            'code' => $code,
            'state' => $state->id
        ]);

        return response()->json('Registro Exitoso!');
    }

    public function validateCode($code)
    {
        $check = BranchOffice::whereCode($code)->first();
        if ($check) {
            return response()->json('El código ya ha sido registrado, por favor ingrese otro', 200);
        }
    }

    public function deleteBranchOffice(Request $request){
        $branch = BranchOffice::where('id', $request->id)->with('employee')->first();

        if (count($branch->employee) > 0){
            return response()->json('No se eliminó por que tiene registros asociados', 301);
        }else{
            DB::table('branch_offices')->where('id', $request->id)->delete();
            return response()->json('Se eliminó correctamente');

        }
    }

}
