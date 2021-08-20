<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\StateInvoice;
use App\Models\TypeInvoice;
use App\Traits\MessagesException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class InvoiceController extends Controller
{
    use MessagesException;

    public function index()
    {
        $states = StateInvoice::all();
        $typeInvoices = TypeInvoice::all();
        return view('admin.invoice.list-invoice', compact('states', 'typeInvoices'));
    }

    public function getApiInvoices()
    {


        //        $invoice = null;
        //        if (User::roleUserVendedor()){
        //            $user = User::where('id', User::user()->id)->with('employes.branchOffices')->first();
        //            $user->employes->branchOffices->id;
        //            $invoice = Invoice::with('customers', 'typeInvoice', 'state')->get();
        //        }

        $invoice = Invoice::with('customers', 'typeInvoice', 'state')->get();
        return datatables()->of($invoice)->toJson();
    }

    public function getApiGetStateInvoices()
    {
        $state = StateInvoice::all();
        return response()->json(['data' => $state]);
    }

    public function getApiGetTypeInvoices()
    {
        $typeInvoice = TypeInvoice::all();
        return response()->json(['data' => $typeInvoice]);
    }

    public function getApiDataInvoice($id)
    {
        $invoice = Invoice::where('id', $id)->with('customers', 'typeInvoice', 'state')->first();
        return response()->json(['data' => $invoice]);
    }

    public function storeApiInvoice(Request $request)
    {

        $invoice_number = $request->invoice_number;
        $state = json_decode($request->state);
        $electronic_invoice_number = $request->electronic_invoice_number;
        $date_issue = $request->date_issue;
        $expiration_date = $request->expiration_date;
        $invoice_type = json_decode($request->invoice_type);
        $customer = json_decode($request->customer);
        $value = $request->value;
        $date_received_client = $request->date_received_client;
        $date_payment_client = $request->date_payment_client;
        $invoice_date_house_manufacturer = $request->invoice_date_house_manufacturer;
        $invoice_number_house_representative = $request->invoice_number_house_representative;
        $commission_value = $request->commission_value;
        $commission_receipt_date = $request->commission_receipt_date;
        $new_agreed_payment_date = $request->new_agreed_payment_date;
        $comments = $request->comments;

        $invoice = new Invoice;
        $invoice->invoice_number = $invoice_number;
        $invoice->date_issue = $date_issue;
        $invoice->customer_id = $customer->id;
        $invoice->type_invoice_id = $invoice_type->id;
        $invoice->state_id = $state->id;
        $invoice->value_total = $value;
        $invoice->date_payment_client = $date_payment_client;
        $invoice->electronic_invoice_number = $electronic_invoice_number;
        $invoice->expiration_date = $expiration_date;
        $invoice->date_received_client = $date_received_client;
        $invoice->invoice_date_house_manufacturer = $invoice_date_house_manufacturer;
        $invoice->commission_receipt_date = $commission_receipt_date;
        $invoice->new_agreed_payment_date = $new_agreed_payment_date;
        $invoice->invoice_number_house_representative = $invoice_number_house_representative;
        $invoice->commission_value = $commission_value;
        $invoice->comments = $comments;

        $invoice->save();

        return response()->json('Registro Exitoso!');
    }

    public function updateApiInvoice(Request $request)
    {

        $invoice_number = $request->invoice_number;
        $state = json_decode($request->state);
        $electronic_invoice_number = $request->electronic_invoice_number;
        $date_issue = $request->date_issue;
        $expiration_date = $request->expiration_date;
        $invoice_type = json_decode($request->invoice_type);
        $customer = json_decode($request->customer);
        $value = $request->value;
        $date_received_client = $request->date_received_client;
        $date_payment_client = $request->date_payment_client;
        $invoice_date_house_manufacturer = $request->invoice_date_house_manufacturer;
        $invoice_number_house_representative = $request->invoice_number_house_representative;
        $commission_value = $request->commission_value;
        $commission_receipt_date = $request->commission_receipt_date;
        $new_agreed_payment_date = $request->new_agreed_payment_date;
        $comments = $request->comments;

        $invoice = Invoice::where('id', $request->idInvoice)->update([
            "invoice_number" => $invoice_number,
            "date_issue" => $date_issue,
            "customer_id" => $customer->id,
            "type_invoice_id" => $invoice_type->id,
            "state_id" => $state->id,
            "value_total" => $value,
            "date_payment_client" => $date_payment_client,
            "electronic_invoice_number" => $electronic_invoice_number,
            "expiration_date" => $expiration_date,
            "date_received_client" => $date_received_client,
            "invoice_date_house_manufacturer" => $invoice_date_house_manufacturer,
            "commission_receipt_date" => $commission_receipt_date,
            "new_agreed_payment_date" => $new_agreed_payment_date,
            "invoice_number_house_representative" => $invoice_number_house_representative,
            "commission_value" => $commission_value,
            "comments" => $comments,
        ]);
        return response()->json('Actualizado Correctamente!');
    }


    public function importDataInvoice(Request $request)
    {
        $file = $request->file('archive');
        $total = 0;
        $lines = collect();
        (new FastExcel())->import($file, function ($line) use (&$lines, &$total) {
            $total += 1;
            DB::beginTransaction();
            try {
                $invoice_number = $line["Número de Factura"];
                $electronic_invoice_number = $line["Número Factura electrónica"];
                $state = $line["Estado"];
                $date_issue = $line["Fecha de emisión"];
                $expiration_date = $line["Fecha de expiración"];
                $invoice_type = $line["Tipo de Factura"];
                $customer = $customer = Customer::whereHas('user', function ($q) use ($line) {
                    $query = $line["Cliente"];
                    return $q->where('id', $query)
                        ->orWhere('email', $query)
                        ->orWhere('identification', $query);
                })->first();
                if (!$customer) {
                    throw new \Exception("Cliente no encontrado", "-1");
                }
                $value = $line["Valor"];
                $date_received_client = $line["Fecha de recibo por parte del cliente"];
                $date_payment_client = $line["Fecha de pago por parte del cliente"];
                $invoice_date_house_manufacturer = $line["Fecha de factura casa representante"];
                $invoice_number_house_representative = $line["Número de factura casa representante"];
                $commission_value = $line["Valor de la comisión"];
                $commission_receipt_date = $line["Fecha de recibo de comisión"];
                $new_agreed_payment_date = $line["Nueva fecha concertada de pago"];
                $comments = $line["Comentarios u observaciones"];

                $invoice = new Invoice;
                $invoice->invoice_number = $invoice_number;
                $invoice->date_issue = $date_issue;
                $invoice->customer_id = $customer->id;
                $invoice->type_invoice_id = $invoice_type;
                $invoice->state_id = $state;
                $invoice->value_total = $value;
                $invoice->date_payment_client = $date_payment_client;
                $invoice->electronic_invoice_number = $electronic_invoice_number;
                $invoice->expiration_date = $expiration_date;
                $invoice->date_received_client = $date_received_client;
                $invoice->invoice_date_house_manufacturer = $invoice_date_house_manufacturer;
                $invoice->commission_receipt_date = $commission_receipt_date;
                $invoice->new_agreed_payment_date = $new_agreed_payment_date;
                $invoice->invoice_number_house_representative = $invoice_number_house_representative;
                $invoice->commission_value = $commission_value;
                $invoice->comments = $comments;
                $invoice->save();

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                $line['error'] = $this->parseException($exception, $line);
                $lines->add($line);
            }
        });

        if (!isset($lines[0])) {
            return back()->with('status', "Transacción realizada existosamente");
        } else {
            $errors = $lines->count();
            $success = $total - $errors;
            if ($success > 0) {
                return back()
                    ->with('error', $errors . " datos no se importaron correctamente")
                    ->with('status', $success . " datos se importaron correctamente")
                    ->with('lines', $lines);
            } else {
                return back()
                    ->with('error', "Ningún dato se ha importado correctamente")
                    ->with('lines', $lines);
            }
        }
    }
}
