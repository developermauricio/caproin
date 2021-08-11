<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Administrator'], function () {

    /*=============================================
      API LISTA COMPLETA DE CLIENTES
    =============================================*/
    Route::get('all-customers', 'CustomerController@getApiCustomers')->name('api.all.customers');
    Route::get('get-customer-type', 'CustomerController@getApiCustomersType')->name('api.customers.type');
    Route::post('register/store-customer', 'CustomerController@storeApiCustomer')->name('api.store.customer');
    Route::get('data-customer/{id}', 'CustomerController@getApiDataCustomer')->name('api.data.customer');
    Route::post('update-customer', 'CustomerController@updateApiCustomer')->name('api.update.customer');
    Route::post('import-data-customers', 'CustomerController@importDataCustomer')->name('api.import.data.customer');


    /*=============================================
      API LISTA COMPLETA DE PROVEEDORES
    =============================================*/
    Route::get('all-providers', 'ProviderController@getApiProviders')->name('api.all.providers');
    Route::get('data-provider/{id}', 'ProviderController@getApiDataProviders')->name('api.data.providers');
    Route::post('register/store-provider', 'ProviderController@storeApiProvider')->name('api.store.provider');
    Route::post('update-provider', 'ProviderController@updateApiProvider')->name('api.update.provider');
    Route::get('all-type-providers', 'ProviderController@getApiTypeProviders')->name('api.all.type.providers');
    Route::get('/verify-code-provider/{code}', 'ProviderController@validateCode')->name('api.validate.code.provider');

    /*=============================================
      API PARA LAS SUCURSALES
    =============================================*/
    Route::get('all-branch-offices', 'BranchOfficesController@getApiBranchOffices')->name('api.all.branch.offices');
    Route::post('register/store-branch-office', 'BranchOfficesController@storeApiBranchOffice')->name('api.store.brach.office');
    Route::post('update-branch-office', 'BranchOfficesController@updateApiBranchOffice')->name('api.update.brach.office');
    Route::get('get-branch-office/{id}', 'BranchOfficesController@getApiBranchOffice')->name('api.get.brach.office');
    Route::get('/verify-code-branch-office/{code}', 'BranchOfficesController@validateCode')->name('api.validate.code.branch.office');

});

/*=============================================
      API RUTAS GLOBALES
=============================================*/
Route::get('get-countries', 'Controller@getCountries')->name('api.get.countries');
Route::get('get-cities/{country}', 'Controller@getCities')->name('api.get.cities');
Route::get('/verify-email-user/{email}', 'Controller@validateEmail')->name('api.validate.email');
Route::get('/verify-identification-user/{indentification}', 'Controller@validateIdentification')->name('api.validate.identification');
Route::get('/verify-email-company/{email}', 'Controller@validateEmailCompany')->name('api.validate.email.company');
Route::get('/get-identificationtype', 'Controller@getIdentificationType')->name('api.get.identification.type');
Route::get('/get-customer-category', 'Controller@customerCategory')->name('api.get.customer.category');
Route::get('/get-customer-position', 'Controller@customerPosition')->name('api.get.customer.position');

