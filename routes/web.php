<?php

use Illuminate\Support\Facades\Auth;
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
Route::get('/ruta-prueba', function (){
//   $seller = \App\Models\PurchaseOrder::where('seller_id', 32)->whereHas('invoice')->get();
//   $data = \App\Models\PurchaseOrder::where('seller_id', 32)->whereHas('invoice')->with('invoice')->get();

    $user = Auth::user()->id;

   $invoice = \App\Models\Invoice::whereHas('purchaseOrder.seller', function ($q) use ($user){
       return $q->where('user_id', $user);
   })->with('customers', 'typeInvoice', 'state', 'paymentType', 'archive' )->get();
   return datatables()->of($invoice)->toJson();
});

Route::get('password/activate/{token}', 'Auth\ResetPasswordController@showActivateForm');

Auth::routes();
Route::get('/usuarios', function () {
    $user = \App\User::role(['Logistica', 'Asistente Sucursal', 'Gerencia', 'Vendedor', 'Finanzas'])->with('roles')->get();
    return $user;
});
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth', 'namespace' => 'Administrator'], function () {

    /*=============================================
      RUTAS DEL LADO ADMINISTRADOR
    =============================================*/

    Route::get('/', function () {
        return view('layouts.app');
    });

    /*=============================================
          RUTAS PARA LOS MODULOS CLIENTE
        =============================================*/
    Route::get('/customers', 'CustomerController@index')->name('admin.customer.customers')->middleware('ModeleCustomer');
    Route::get('/create-customer', 'CustomerController@indexCreateCustomer')->name('admin.customer.create.customer');
    Route::post('/import-data-customer', 'CustomerController@importDataCustomer')->name('import.data.customers');

    /*=============================================
          RUTAS PARA LOS MODULOS PROVEEDOR
        =============================================*/
    Route::get('/providers', 'ProviderController@index')->name('admin.provider.providers')->middleware('ModeleProvider');;

    /*=============================================
          RUTAS PARA LOS MODULOS SUSCURSALES
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
    Route::get('/facturas', 'InvoiceController@index')->name('admin.invoice');

    /*=============================================
          ORDENES DE COMPRA
        =============================================*/
    Route::get('/ordenes-compra', 'PurchaseOrderController@index')->name('admin.purchase_order.purchase_orders');
    Route::get('/ordenes-compra/crear', 'PurchaseOrderController@create')->name('admin.purchase_order.create');
    Route::post('/import-data-invoice', 'InvoiceController@importDataInvoice')->name('import.data.invoices');

    /*=============================================
         RUTAS PARA LOS MODULOS DE PRODUCTOS Y SERVICIOS
    =============================================*/
    Route::get('/productos-servicios', 'ProductServiceController@index')->name('admin.products,services');

    /*=============================================
     RUTAS PARA LOS MODULOS DE ACUERDOS COMERCIALES
    =============================================*/
    Route::get('/acuerdos-comerciales', 'TradeAgreementController@index')->name('admin.trade.agreement');

});
