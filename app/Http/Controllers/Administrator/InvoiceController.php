<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\StateInvoice;
use App\Models\TypeInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(){
        return view('admin.invoice.list-invoice');
    }

    public function getApiInvoices(){
        $invoice = Invoice::with('customers', 'typeInvoice', 'state')->get();
        return datatables()->of($invoice)->toJson();
    }

    public function getApiGetStateInvoices(){
        $state = StateInvoice::all();
        return response()->json(['data' => $state]);
    }

    public function getApiGetTypeInvoices(){
        $typeInvoice = TypeInvoice::all();
        return response()->json(['data' => $typeInvoice]);
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
}
