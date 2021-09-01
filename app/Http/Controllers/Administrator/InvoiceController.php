<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentType;
use App\Models\StateInvoice;
use App\Models\TypeInvoice;
use App\Traits\MessagesException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    use MessagesException;

    public function index()
    {
        $states = StateInvoice::all();
        $typeInvoices = TypeInvoice::all();
        $paymentTypes = PaymentType::all(['id', 'name', 'description']);
        return view('admin.invoice.list-invoice', compact('states', 'typeInvoices', 'paymentTypes'));
    }

    public function getApiInvoices()
    {
        $rol = auth()->user()->roles->first()->name; // Rol del Usuario
        $user = auth()->user()->id; // id del Usuario

        if ($rol === "Vendedor") {
            $invoice = Invoice::whereHas('purchaseOrder.seller', function ($q) use ($user) {
                return $q->where('user_id', $user);
            })->with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
        }
        elseif ($rol === "Cliente") {
            $invoice = Invoice::whereHas('customers', function ($q) use ($user){
                return $q->where('user_id', $user);
            })->with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
        } else {
            $invoice = Invoice::with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
        }
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

    public function getApiGetPaymentType()
    {
        $paymentType = PaymentType::all();
        return response()->json(['data' => $paymentType]);
    }

    public function getApiDataInvoice($id)
    {
        $invoice = Invoice::where('id', $id)->with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->first();
        return response()->json(['data' => $invoice]);
    }
    //    public function getApiDataInvoice($id)
    //    {
    //        $invoice = Invoice::where('id', $id)->with('customers', 'typeInvoice', 'state')->first();
    //        return response()->json(['data' => $invoice]);
    //    }

    public function storeApiInvoice(Request $request)
    {

        $invoice_number = $request->invoice_number;
        $state = json_decode($request->state);
        $electronic_invoice_number = $request->electronic_invoice_number;
        $date_issue = $request->date_issue;
        $expiration_date = $request->expiration_date;
        $invoice_type = json_decode($request->invoice_type);
        $paymentType = json_decode($request->paymentType);
        $customer = json_decode($request->customer);
        $archives = json_decode($request->archives);
        $value = $request->value;
        $valuePaymentParcial = $request->valuePaymentParcial;
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
        $invoice->payment_type_id = $paymentType->id;
        $invoice->state_id = $state->id;
        $invoice->value_total = $value;
        $invoice->value_payment = $valuePaymentParcial;
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

        for ($i = 0; $i < count($archives); $i++) {
            $invoice->archive()->create([
                'nameArchive' => $archives[$i]->nameArchive,
                'uuid' => $archives[$i]->uuid,
                'type' => $archives[$i]->extension,
                'archive' => $archives[$i]->urlArchive,
                'archivable_id' => $invoice->id,
                'archivable_type' => get_class($invoice)
            ]);
        }

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
        $paymentType = json_decode($request->paymentType);
        $customer = json_decode($request->customer);
        $archives = json_decode($request->archives);
        $value = $request->value;
        $valuePaymentParcial = $request->valuePaymentParcial;
        $date_received_client = $request->date_received_client;
        $date_payment_client = $request->date_payment_client;
        $invoice_date_house_manufacturer = $request->invoice_date_house_manufacturer;
        $invoice_number_house_representative = $request->invoice_number_house_representative;
        $commission_value = $request->commission_value;
        $commission_receipt_date = $request->commission_receipt_date;
        $new_agreed_payment_date = $request->new_agreed_payment_date;
        $comments = $request->comments;

        $invoice = Invoice::findOrFail($request->idInvoice);
        $invoice->invoice_number = $invoice_number;
        $invoice->date_issue = $date_issue;
        $invoice->customer_id = $customer->id;
        $invoice->type_invoice_id = $invoice_type->id;
        $invoice->payment_type_id = $paymentType->id;
        $invoice->state_id = $state->id;
        $invoice->value_total = $value;
        if ($valuePaymentParcial == 'null') {
            $invoice->value_payment = null;
        } else {
            $invoice->value_payment = $valuePaymentParcial;
        }
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

        $invoice->update();

        for ($i = 0; $i < count($archives); $i++) {
            $invoice->archive()->create([
                'nameArchive' => $archives[$i]->nameArchive,
                'uuid' => $archives[$i]->uuid,
                'type' => $archives[$i]->extension,
                'archive' => $archives[$i]->urlArchive,
                'archivable_id' => $invoice->id,
                'archivable_type' => get_class($invoice)
            ]);
        }
        return response()->json('Actualizado Correctamente!');
    }

    public function uploadArchiveInvoice(Request $request)
    {
        $nameCompany = $request->input('nameInvoice');
        $uuid = $request->input('nameId');
        $archive = $request->file('archive');
        $archiveExtension = $archive->getClientOriginalExtension();
        $ramdon = Str::random(10);
        $nameArchive = $ramdon . '-' . strtolower($archive->getClientOriginalName());
        $path = Storage::disk('public')->put('/archives/' . $nameArchive, file_get_contents($archive));
        $urlFinal = '/storage/archives/' . $nameArchive;
        return response()->json(['data' => $urlFinal, 'uuid' => $uuid, 'extension' => $archiveExtension]);
    }

    public function removedArchiveInvoice(Request $request)
    {
        $pathArchive = $request->get('archiveInvoice');
        $partes_ruta = pathinfo($pathArchive);
        Storage::delete('archives/' . $partes_ruta['basename']);
        dd($pathArchive);
    }

    public function removedArchiveInvoiceDb(Request $request)
    {
        $pathArchive = $request->archiveInvoiceUrl;
        $uuidArchive = $request->archiveInvoiceUuid;

        $partes_ruta = pathinfo($pathArchive);
        Storage::delete('archives/' . $partes_ruta['basename']);

        DB::table('archives')->where('uuid', $uuidArchive)->delete();
        return response()->json('Se eliminó correctamente');
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

                $paymentTypeId = $line['Tipo de pago'];
                $valuePaymentParcial = $line['Valor Pagado'];

                $invoice = new Invoice();
                $invoice->payment_type_id = $paymentTypeId;
                $invoice->value_payment = $valuePaymentParcial;
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
