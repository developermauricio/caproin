<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Auth\ActivateAccountController;
use App\Http\Controllers\Controller;
use App\Mail\Customer\NewCustomer;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Traits\MessagesException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class CustomerController extends Controller
{
    use MessagesException;

    public function index()
    {
        return view('admin.customer.list-customers');
    }

    public function indexCreateCustomer()
    {
        return view('admin.customer.create-customer');
    }

    public function getApiCustomersType()
    {
        $getCustomerType = CustomerType::all();
        return response()->json(['data' => $getCustomerType]);
    }

    public function getApiCustomers()
    {
        $customer = Customer::with('user.identificationType')->get();
        return datatables()->of($customer)->toJson();
    }

    public function getApiDataCustomer($id)
    {
        $customer = Customer::where('id', $id)->with('user.identificationType')->first();
        return response()->json(['data' => $customer]);
    }

    public function storeApiCustomer(Request $request)
    {
        $ramdon = Str::random(10);
        $password = Str::random(8);
        $pass = bcrypt($password);

        $businessName = $request->businessName;
        $email = $request->email;
        $phone = $request->phone;
        $typeIdentification = json_decode($request->typeIdentification);
        $identification = $request->identification;

        $slug = Str::slug($businessName . '-' . $ramdon, '-');

        $user = User::create([
            "name" => $businessName,
            "email" => $email,
            "password" => $pass,
            "slug" => $slug,
            "picture" => '/images/user-profile.png',
            "phone" => $phone,
            "identification_type_id" => $typeIdentification->id,
            "identification" => $identification
        ]);
        $user->roles()->attach([7]);

        $customer = Customer::create([
            "business_name" => $businessName,
            "user_id" => $user->id,
        ]);
        Mail::to($user->email)->send(new NewCustomer($user->name, $password, $user->email));
        return response()->json('Registro Exitoso!');
    }

    public function updateApiCustomer(Request $request)
    {

        $ramdon = Str::random(10);
        $businessName = $request->businessName;
        $typeIdentification = json_decode($request->typeIdentification);
        $identification = $request->identification;
        $email = $request->email;
        $phone = $request->phone;
        $stateCustomer = json_decode($request->state);
        $idCustomer = $request->idCustomer;
        $idUser = $request->idUser;
        $slug = Str::slug($businessName . '-' . $ramdon, '-');

        $user = User::where('id', $idUser)->update([
            "name" => $businessName,
            "slug" => $slug,
            "identification_type_id" => $typeIdentification->id,
            "identification" => $identification,
            "email" => $email,
            "phone" => $phone,
            "state" => $stateCustomer->id
        ]);

        $customer = Customer::where('id', $idCustomer)->update([
            "business_name" => $businessName,
        ]);
    }

    public function importDataCustomer(Request $request)
    {
        $file = $request->file('archive');
        $total = 0;
        $lines = collect();
        (new FastExcel())->import($file, function ($line) use (&$lines, &$total) {
            $total += 1;
            DB::beginTransaction();
            try {
                $ramdon = Str::random(10);
                $slug = Str::slug($line['nombre o razon social'] . '-' . $ramdon, '-');
                $user = User::create([
                    'name' => $line['nombre o razon social'],
                    'email' => $line['email'],
                    'phone' => $line['telefono'],
                    'identification' => $line['identificacion'],
                    'slug' => $slug,
                    "picture" => '/images/user-profile.png',
                    "identification_type_id" => $line['tipo de identificacion'],
                    'password' => ''
                ]);

                $user->roles()->attach([7]);
                $customer = Customer::create([
                    "business_name" => $line['nombre o razon social'],
                    "user_id" => $user->id,
                ]);

                $activate = new ActivateAccountController();
                $activate->sendResetLinkEmail($line['email']);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $line['error'] = $this->parseException($exception, $line);
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
