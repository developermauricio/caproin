<?php

use App\Http\Controllers\Administrator\PurchaseOrderController;
use App\Mail\Customer\SendOverdueInvoice;
use App\Models\Comment;
use App\Models\HistorySendPaymetClient;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', 'Administrator\PurchaseOrderController@importPurchaseOrders');

Route::get('/data-invoice-customer', function () {
    $rol = auth()->user()->roles->first()->name; // Rol del Usuario
    $user = auth()->user()->id; // id del Usuario
    if ($rol === "Vendedor") {
        $invoice = Invoice::whereHas('purchaseOrder.seller', function ($q) use ($user) {
            return $q->where('user_id', $user);
        })->with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
    } elseif ($rol === "Cliente") {
        $invoice = Invoice::whereHas('customers', function ($q) use ($user) {
            return $q->where('user_id', $user)->orWhereHas('sede', function ($q) use ($user) {
                return $q->where('user_id', $user);
            });
        })->with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
    } else {
        $invoice = Invoice::with('customers', 'typeInvoice', 'state', 'paymentType', 'archive')->get();
    }
    return datatables()->of($invoice)->toJson();
});

Route::get('/ruta-prueba', function () {
    $success = false;
    //    $rowsCustomers = \App\Models\Customer::query()->with('invoices.state', 'user')->get();
    //
    //    foreach ($rowsCustomers as $row){
    //        if (count($row->invoices) > 0)
    //        foreach ($row->invoices as $invoice){
    //
    //            $dateNow = Carbon\Carbon::now(); //Fecha actual
    //            $datePayment = Carbon\Carbon::parse($invoice->expiration_date); // Fecha de vencimiento de la factura
    //            $diff = $datePayment->diffInDays($dateNow); // Diferencia entre la fecha de hoy y la fecha de pago por parte del cliente
    //
    //
    //            if ($row->number_of_days_after_invoice_overdue === $diff && $invoice->send_overdue_cuertomer_state === 0 && $dateNow->greaterThan($datePayment) && $invoice->state->id === 1){
    //                Mail::to($row->user->email)->send(new \App\Mail\Customer\SendPaymenInvoice('121312', 'Mauricio Gutierrez', '2021-06-21'));
    //                $invoice->send_payment_cuertomer_state = 1;
    //                $invoice->save();
    //
    //                \App\Models\HistorySendPaymetClient::create([
    //                   'invoice_id' =>  $invoice->id,
    //                   'type_send' =>  1, //Enviado automaticamente
    //                ]);
    //            }
    //        }
    //    }

    /*=============================================
            ENVIAR CORREO ELECTRÓNICO LUEGO DE VENCERSE LA FACTURA
        =============================================*/
    $rowsCustomers = \App\Models\Customer::query()->with('invoices.state', 'user')->get();

    foreach ($rowsCustomers as $row) {
        if (count($row->invoices) > 0) {
            foreach ($row->invoices as $invoice) {
                $dateNow = Carbon::now(); //Fecha actual
                $datePayment = Carbon::parse($invoice->expiration_date); // Fecha de vencimiento de la factura
                $diff = $datePayment->diffInDays($dateNow); // Diferencia entre la fecha de hoy y la fecha de vencimiento de la factura

                if ($row->number_of_days_after_invoice_overdue === $diff && $invoice->send_overdue_cuertomer_state === 0 && $dateNow->greaterThan($datePayment) && $invoice->state->id === 1) {

                    Mail::to($row->user->email)->send(new SendOverdueInvoice(
                        $invoice->invoice_number,
                        $row->user->name,
                        $invoice->expiration_date
                    )); // Envio de correo electrónico
                    dd($invoice->id);
                    $invoice->send_overdue_cuertomer_state = 1;
                    $invoice->save(); //Guardamos que hemos enviado el correo electrónico

                    HistorySendPaymetClient::create([
                        'invoice_id' =>  $invoice->id,
                        'type_send' =>  1, //Enviado automaticamente
                        'detail' =>  2, // El detalle es para saber si es recordatorio o vencimiento factura
                    ]);
                }
            }
        }
    }

    return $rowsCustomers;
});

Route::get('testing', function () {
    $response = PurchaseOrder::selectRaw('count(state_order_id) as total, state_order_id')
    ->join('purchase_order_state_histories', 'current_status_id', '=', 'purchase_order_state_histories.id')
    ->groupBy('state_order_id')
    ->get()->keyBy(function ($item) {
        return $item['state_order_id'];
    });
    return response()->json($response);
});

Route::get('/email-prueba', function () {
    return new \App\Mail\Customer\SendPaymenInvoice('121312', 'Mauricio Gutierrez', '2021-06-21');
});

Route::get('password/activate/{token}', 'Auth\ResetPasswordController@showActivateForm');

Auth::routes();
Route::get('/usuarios', function () {
    $user = \App\User::role(['Logistica', 'Asistente Sucursal', 'Gerencia', 'Vendedor', 'Finanzas'])->with('roles')->get();
    return $user;
});
//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'namespace' => 'Administrator'], function () {

    /*=============================================
      RUTAS DEL LADO ADMINISTRADOR
    =============================================*/

    Route::get('/', function () {
        return view('layouts.app');
    })->name('home');

    /*=============================================
          RUTAS PARA LOS MODULOS CLIENTE
        =============================================*/
    Route::get('/customers', 'CustomerController@index')->name('admin.customer.customers')->middleware('ModeleCustomer');
    Route::get('/create-customer', 'CustomerController@indexCreateCustomer')->name('admin.customer.create.customer');
    Route::post('/import-data-customer', 'CustomerController@importDataCustomer')->name('import.data.customers');

    /*=============================================
          RUTAS PARA LOS MODULOS PROVEEDOR
        =============================================*/
    Route::get('/providers', 'ProviderController@index')->name('admin.provider.providers')->middleware('ModeleProvider');
    Route::post('/import-data-providers', 'ProviderController@importDataProvider')->name('import.data.providers');

    /*=============================================
        RUTAS PARA LOS MODULOS TRANSPORTISTA
    =============================================*/
    Route::get('/conveyors', 'ConveyorController@index')->name('admin.conveyor.conveyors')->middleware('ModeleConveyor');

    /*=============================================
          RUTAS PARA LOS MODULOS SUCURSALES
        =============================================*/
    Route::get('/sucursales', 'BranchOfficesController@index')->name('admin.branch_offices')->middleware('ModeleBranchOffices');

    /*=============================================
          RUTAS PARA LOS MODULOS ZONAS
        =============================================*/
    Route::get('/zonas', 'ZonesController@index')->name('admin.zones')->middleware('ModeleZones');

    /*=============================================
          RUTAS PARA LOS MODULOS USUARIOS
        =============================================*/
    Route::get('/usuarios', 'UserController@index')->name('admin.user.users')->middleware('ModeleUser');

    /*=============================================
          RUTAS PARA LOS MODULOS FACTURAS
        =============================================*/
    Route::get('/facturas', 'InvoiceController@index')->name('admin.invoice')->middleware('ModeleInvoices');

    /*=============================================
          ORDENES DE COMPRA
        =============================================*/
    Route::post('/import-data-purchase-ordes', 'PurchaseOrderController@importPurchaseOrders')->name('import.data.purchase_ordes')->middleware('ModulePurchaseOrder');
    Route::get('/ordenes-compra', 'PurchaseOrderController@index')->name('admin.purchase_order.purchase_orders');

    Route::get('/ordenes-compra/crear', 'PurchaseOrderController@create')->name('admin.purchase_order.create')->middleware('ModelePurchaseOrder');
    Route::get('/ordenes-compra/actualizar/{id}', 'PurchaseOrderController@update')->name('admin.purchase_order.update');
    Route::post('/import-data-invoice', 'InvoiceController@importDataInvoice')->name('import.data.invoices');

    /*=============================================
         RUTAS PARA LOS MODULOS DE PRODUCTOS Y SERVICIOS
    =============================================*/
    Route::get('/productos-servicios', 'ProductServiceController@index')->name('admin.products,services');

    /*=============================================
     RUTAS PARA LOS MODULOS DE ACUERDOS COMERCIALES
    =============================================*/
    Route::get('/acuerdos-comerciales', 'TradeAgreementController@index')->name('admin.trade.agreement');

    /*=============================================
     RUTAS PARA LOS MODULOS DE REPORTES
    =============================================*/
    Route::get('/reportes-cartera', 'ReportController@index')->name('admin.report.cartera');
    Route::get('/reportes-logistica', 'ReportController@logistic')->name('admin.report.logistic');
});
