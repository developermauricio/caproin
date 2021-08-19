<?php

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
    Route::get('/customers', 'CustomerController@index')->name('admin.customer.customers');
    Route::get('/create-customer', 'CustomerController@indexCreateCustomer')->name('admin.customer.create.customer');
    Route::post('/import-data-customer', 'CustomerController@importDataCustomer')->name('import.data.customers');

    /*=============================================
          RUTAS PARA LOS MODULOS PROVEEDOR
        =============================================*/
    Route::get('/providers', 'ProviderController@index')->name('admin.provider.providers');

    /*=============================================
          RUTAS PARA LOS MODULOS SUSCURSALES
        =============================================*/
    Route::get('/sucursales', 'BranchOfficesController@index')->name('admin.branch_offices');

    /*=============================================
          RUTAS PARA LOS MODULOS ZONAS
        =============================================*/
    Route::get('/zonas', 'ZonesController@index')->name('admin.zones');

    /*=============================================
          RUTAS PARA LOS MODULOS USUARIOS
        =============================================*/
    Route::get('/usuarios', 'UserController@index')->name('admin.user.users');

    /*=============================================
          RUTAS PARA LOS MODULOS FACTURAS
        =============================================*/
    Route::get('/facturas', 'InvoiceController@index')->name('admin.invoice');

    /*=============================================
         RUTAS PARA LOS MODULOS DE PRODUCTOS Y SERVICIOS
    =============================================*/
    Route::get('/productos-servicios', 'ProductServiceController@index')->name('admin.products,services');


});
