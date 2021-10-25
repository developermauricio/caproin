<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\IdentificationType;
use App\Models\Provider;
use App\Models\TypeProvider;
use App\Traits\MessagesException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProviderController extends Controller
{
    use MessagesException;

    public function index()
    {
        $identificationTypes = IdentificationType::all('id', 'name');
        $providerTypes = TypeProvider::all('id', 'name');
        return view('admin.provider.list-providers', compact('identificationTypes', 'providerTypes'));
    }

    public function getApiProviders()
    {
        $providers = Provider::with('users.identificationType', 'typeProviders')->get();
        return datatables()->of($providers)->toJson();
    }

    public function getApiTypeProviders()
    {
        $getTypeProvider = TypeProvider::all();
        return response()->json(['data' => $getTypeProvider]);
    }

    public function getApiDataProviders($id)
    {
        $provider = Provider::where('id', $id)->with('users.identificationType', 'typeProviders')->first();
        return response()->json(['data' => $provider]);
    }

    public function storeApiProvider(Request $request)
    {
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
            "picture" => '/images/user-profile.png',
            "identification_type_id" => $typeIdentification->id,
            "identification" => $identification
        ]);
        $user->roles()->attach([8]);
        $provider = Provider::create([
            "business_name" => $businessName,
            "code" => $code,
            "user_id" => $user->id,
            "type_providers_id" => $typeProvider->id
        ]);

        return response()->json('Registro Exitoso!');
    }

    public function updateApiProvider(Request $request)
    {

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

    public function importDataProvider(Request $request)
    {
        $file = $request->file('archive');
        $total = 0;
        $lines = collect();
        (new FastExcel())->import($file, function ($line) use (&$lines, &$total) {
            $total += 1;
            DB::beginTransaction();
            try {

                $ramdon = Str::random(10);

                $code = $line['código'];
                $businessName = $line['nombre'];
                $identificationTypeId = $line['tipo identificación'];
                $identification = $line['numero de identificación'];
                $providerTypeId = $line['tipo proveedor'];
                $slug = Str::slug($businessName . '-' . $code . '-' . $ramdon, '-');

                if (!IdentificationType::find($identificationTypeId)) {
                    throw new \Exception("Tipo de identificación invalida", "-1");
                }

                if (!TypeProvider::find($providerTypeId)) {
                    throw new \Exception("Tipo de proveedor invalido", "-1");
                }

                if (User::where('identification', '=', $identification)->first()) {
                    throw new \Exception("La identificación del proveedor ya se encuentra registrada", "-1");
                }

                if (Provider::where('code', '=', $code)->first()) {
                    throw new \Exception("El codigo del proveedor ya se encuentra registrado", "-1");
                }


                $user = new User();
                $user->name =  $businessName;
                $user->slug = $slug;
                $user->identification_type_id = $identificationTypeId;
                $user->identification = $identification;
                $user->save();

                $provider = new Provider();
                $provider->business_name = $businessName;
                $provider->code = $code;
                $provider->state = Provider::ACTIVE;
                $provider->type_providers_id = $providerTypeId;
                $provider->user_id = $user->id;
                $provider->save();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $line['error'] = $this->parseException($exception, $line);
                // $line['fullError'] = $exception->getMessage();
                $lines->add($line);
            }
        });

        if (!isset($lines[0])) {
            return back()->with('status', "Transacción realizada exitosamente");
        } else {
            $errors = $lines->count();
            $success = $total - $errors;
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente. Quizás ya estan registrados o el correo electrónico y identificación ya se encuentra registrado. Asegurate que los datos del reporte o tabla, no esten registrados o no esten duplicados.")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', $lines);
            } else {
                return back()
                    ->with('error', "Ningún dato se ha importado correctamente. Quizás ya estan registrados o el correo electrónico y identificación ya se encuentra registrado. Asegurate que los datos del reporte o tabla, no esten registrados o no esten duplicados.")
                    ->with('lines', $lines);
            }
        }
    }
}
