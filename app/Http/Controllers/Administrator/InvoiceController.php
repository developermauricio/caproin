<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentType;
use App\Models\StateInvoice;
use App\Models\TypeInvoice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('admin.invoice.list-invoice');
    }

    public function getApiInvoices()
    {


//        $invoice = null;
//        if (User::roleUserVendedor()){
//            $user = User::where('id', User::user()->id)->with('employes.branchOffices')->first();
//            $user->employes->branchOffices->id;
//            $invoice = Invoice::with('customers', 'typeInvoice', 'state')->get();
//        }

        $invoice = Invoice::with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
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
        if ($valuePaymentParcial == 'null'){
            $invoice->value_payment = null;
        }else{
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

    public function uploadArchiveInvoice(Request $request){
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

    public function removedArchiveInvoice(Request $request){
        $pathArchive = $request->get('archiveInvoice');
        $partes_ruta = pathinfo($pathArchive);
        Storage::delete('archives/' . $partes_ruta['basename']);
        dd($pathArchive);
    }

    public function removedArchiveInvoiceDb(Request $request){
        $pathArchive = $request->archiveInvoiceUrl;
        $uuidArchive = $request->archiveInvoiceUuid;

        $partes_ruta = pathinfo($pathArchive);
        Storage::delete('archives/' . $partes_ruta['basename']);

        DB::table('archives')->where('uuid', $uuidArchive)->delete();
        return response()->json('Se eliminó correctamente');


    }
}
