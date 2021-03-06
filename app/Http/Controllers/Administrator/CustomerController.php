<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Auth\ActivateAccountController;
use App\Http\Controllers\Controller;
use App\Mail\Customer\NewCustomer;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\HistorySendPaymetClient;
use App\Models\IdentificationType;
use App\Models\Invoice;
use App\Traits\MessagesException;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class CustomerController extends Controller
{
    use MessagesException;

    public function index()
    {
        $identificationTypes = IdentificationType::all('id', 'name');
        return view('admin.customer.list-customers', compact('identificationTypes'));
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
        $customer = Customer::where('id', $id)->with('user.identificationType', 'invoices.paymentType', 'invoices.typeInvoice', 'invoices.state')->first();
        $dataHistory = collect();
        if (count($customer->invoices) > 0) {
            foreach ($customer->invoices as $invoices) {
                $histories = HistorySendPaymetClient::where('invoice_id', $invoices->id)->get();

                foreach ($histories as $history) {
                    $dataHistory->push([
                        "invoice_id" => $invoices->id,
                        "invoice_number" => $invoices->invoice_number,
                        "type_send" => $history->type_send,
                        "detail" => $history->detail,
                        "date_register" => Carbon::parse($history->created_at)->format('Y-m-d H:i'),
                    ]);
                }
            }
        }

        return response()->json(['data' => $customer, 'historySendPaymentClient' => $dataHistory]);
    }

    public function storeApiCustomer(Request $request)
    {
        try {
            DB::beginTransaction();
            $ramdon = Str::random(10);
            $password = Str::random(8);
            $pass = bcrypt($password);

            $businessName = $request->businessName;
            $email = $request->email;
            $phone = $request->phone;
            $idTypeIndentification = $request->idTypeIndentification;
            $principal = $request->principal;
            $numberDaysAfterInvoice = $request->numberDaysAfterInvoice;
            $numberDaysOverdueInvoice = $request->numberDaysOverdueInvoice;
            //        $typeIdentificationData = json_decode($request->typeIdentification);
            $typeCustomerData = json_decode($request->typeCustomer);

            if ($idTypeIndentification !== "null") {
                $idTypeIndeti = $idTypeIndentification;
            } else {
                $typeIdentificationData = json_decode($request->typeIdentification);
                $idTypeIndeti = $typeIdentificationData->id;
            }

            if ($request->typeCustomer != "null") {
                $typeCustomer = $typeCustomerData->id;
            } else {
                $typeCustomer = null;
            }

            $identification = $request->identification;

            $slug = Str::slug($businessName . '-' . $ramdon, '-');

            $user = User::create([
                "name" => $businessName,
                "email" => $email,
                "password" => $pass,
                "slug" => $slug,
                "picture" => '/images/user-profile.png',
                "phone" => $phone,
                "identification_type_id" => $idTypeIndeti,
                "identification" => $identification
            ]);
            $user->roles()->attach([7]);

            $customer = Customer::create([
                "business_name" => $businessName,
                "user_id" => $user->id,
                "principal" => $principal,
                "sub_sede_of" => $typeCustomer,
                "number_of_days_after_generating_invoice" => $numberDaysAfterInvoice,
                "number_of_days_after_invoice_overdue" => $numberDaysOverdueInvoice,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'msg' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ];

            Log::error('Error al crear cliente.', $response);
            return response()->json($response, 501);
        }
        try {
            Mail::to($user->email)->send(new NewCustomer($user->name, $password, $user->email));
        } catch (\Throwable $th) {
            $response = [
                'msg' => 'Registro Exitoso',
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ];
            Log::error('Error al enviar correos.', $response);
            return response()->json($response, 501);
        }
        return response()->json('Registro Exitoso!', 201);
    }

    public function updateApiCustomer(Request $request)
    {

        $ramdon = Str::random(10);
        $businessName = $request->businessName;
        $typeIdentification = json_decode($request->typeIdentification);
        $identification = $request->identification;
        $numberDaysAfterInvoice = $request->numberDaysAfterInvoice;
        $numberDaysOverdueInvoice = $request->numberDaysOverdueInvoice;
        $email = $request->email;
        $phone = $request->phone;
        $stateCustomer = json_decode($request->state);
        $idCustomer = $request->idCustomer;
        $idUser = $request->idUser;
        $slug = Str::slug($businessName . '-' . $ramdon, '-');

        $sedes = Customer::where('sub_sede_of', $idCustomer)->with('user')->get();

        foreach ($sedes as $sede) {
            $sede->user->update([
                "identification_type_id" => $typeIdentification->id,
                "identification" => $identification
            ]);
        }

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
            "number_of_days_after_generating_invoice" => $numberDaysAfterInvoice,
            "number_of_days_after_invoice_overdue" => $numberDaysOverdueInvoice,
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
                $name = $line['nombre o razon social'];

                if (!isset($name[2])) {
                    throw new \Exception("Nombre de usuario invalido", "-1");
                }

                if (!strpos($line['email'], "@")) {
                    throw new \Exception("El correo es invalido", "-1");
                }

                if (!isset(($line['telefono'] . "")[2])) {
                    throw new \Exception("Telefono invalido", "-1");
                }

                $slug = Str::slug($name . '-' . $ramdon, '-');

                $tipoIdentificacion = IdentificationType::select('id')->find($line['tipo de identificacion']);
                if (!$tipoIdentificacion) {
                    throw new \Exception("Tipo de identificaci??n invalida", "-1");
                }

                $user = User::create([
                    'name' => $name,
                    'email' => $line['email'],
                    'phone' => $line['telefono'],
                    'identification' => $line['identificacion'],
                    'slug' => $slug,
                    "picture" => '/images/user-profile.png',
                    "identification_type_id" => $tipoIdentificacion->id,
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
                // $line['fullError'] = $exception->getMessage();
                $lines->add($line);
            }
        });

        if (!isset($lines[0])) {
            return back()->with('status', "Transacci??n realizada exitosamente");
        } else {
            $errors = $lines->count();
            $success = $total - $errors;
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente. Quiz??s ya estan registrados o el correo electr??nico Y identificaci??n ya se encuentra registrado. Aseg??rate que los datos del reporte o tabla, no est??n registrados o no est??n duplicados.")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', $lines);
            } else {
                return back()
                    ->with('error', "Ning??n dato se ha importado correctamente. Quiz??s ya estan registrados o el correo electr??nico Y identificaci??n ya se encuentra registrado. Aseg??rate que los datos del reporte o tabla, no est??n registrados o no est??n duplicados.")
                    ->with('lines', $lines);
            }
        }
    }
}
