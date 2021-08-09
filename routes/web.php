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


Auth::routes();

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

        /*=============================================
          RUTAS PARA LOS MODULOS PROVEEDOR
        =============================================*/
        Route::get('/providers', 'ProviderController@index')->name('admin.provider.providers');

        /*=============================================
          RUTAS PARA LOS MODULOS ORDENES DE COMPRA
        =============================================*/
        Route::get('/purchase_order', 'PurchaseOrderController@index')->name('admin.purchase_order.purchase_orders');
});
