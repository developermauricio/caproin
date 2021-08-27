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

Route::post('download-excel', "ExcelController@download");

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
    Route::post('/delete-branchoffices', 'BranchOfficesController@deleteBranchOffice')->name('api.delete.branch.offices');

    /*=============================================
      API PARA LOS USUARIOS
    =============================================*/
    Route::get('all-users', 'UserController@getApiUsers')->name('api.all.users');
    Route::get('/get-branchofficetype', 'UserController@getBranchOfficeType')->name('api.get.branch.office.type');
    Route::get('/get-rol', 'UserController@getRol')->name('api.get.rol.type');
    Route::get('/get-type-user', 'UserController@getTypeUser')->name('api.get.type.user');
    Route::post('register/store-user', 'UserController@storeApiUser')->name('api.store.user');
    Route::get('data-user/{id}', 'UserController@getApiDataUser')->name('api.data.user');
    Route::post('update-user', 'UserController@updateApiUser')->name('api.update.user');
    Route::post('import-data-users', 'UserController@importDataUser')->name('import.data.users');
    Route::post('register/update-password', 'UserController@updateApiPasswordUser')->name('api.update.user.password');

    /*=============================================
      API PARA LAS ZONAS
    =============================================*/
    Route::get('all-zones', 'ZonesController@getApiBranchZones')->name('api.all.zones');
    Route::get('get-zone/{id}', 'ZonesController@getApiZone')->name('api.get.zone');
    Route::post('register/store-zone', 'ZonesController@storeApiZone')->name('api.store.zone');
    Route::get('/verify-code-zone/{code}', 'ZonesController@validateCode')->name('api.validate.code.zone');
    Route::post('update-zone', 'ZonesController@updateApiZone')->name('api.update.zone');
    Route::post('/delete-zone', 'ZonesController@deleteZone')->name('api.delete.zone');

    /*=============================================
      API PARA LAS FACTURAS
    =============================================*/
    Route::get('/all-invoices', 'InvoiceController@getApiInvoices')->name('api.all.invoices');
    Route::get('/get-state-invoice', 'InvoiceController@getApiGetStateInvoices')->name('api.all.state.invoices');
    Route::get('/get-type-invoice', 'InvoiceController@getApiGetTypeInvoices')->name('api.all.type.invoices');
    Route::get('/get-payment-type', 'InvoiceController@getApiGetPaymentType')->name('api.all.payment.type');
    Route::post('register/store-invoice', 'InvoiceController@storeApiInvoice')->name('api.store.invoice');
    Route::get('data-invoice/{id}', 'InvoiceController@getApiDataInvoice')->name('api.data.invoice');
    Route::post('/register/update-invoice', 'InvoiceController@updateApiInvoice')->name('api.update.invoice');
    Route::post('/upload-archive-invoice','InvoiceController@uploadArchiveInvoice')->name('api.upload.archive.invoice');
    Route::post('/removed-archive-invoice','InvoiceController@removedArchiveInvoice')->name('api.removed.archive.invoice');
    Route::post('/removed-archive-invoice-db','InvoiceController@removedArchiveInvoiceDb')->name('api.removed.archive.invoice');

    /*=============================================
      API PARA LAS ORDENES DE COMPRA
    =============================================*/
    Route::get('/all-purchases-ordes', 'PurchaseOrderController@getApiPurchaseOrdes')->name('api.all.purchase-orders');
    Route::get('/all-customer-list', 'PurchaseOrderController@getAllCustomers')->name('api.all.purchase-orders.customers');
    Route::get('/all-order-type-list', 'PurchaseOrderController@getAllOrderTypes');
    Route::get('/all-zone-list', 'PurchaseOrderController@getAllZone');
    Route::get('/all-seller-list', 'PurchaseOrderController@getAllSeller');
    Route::get('/all-coin-type-list', 'PurchaseOrderController@getAllTypeCoin');
    Route::get('/all-state-ordes', 'PurchaseOrderController@getAllStateOrders');
    Route::get('/all-conveyor-list', 'PurchaseOrderController@getAllConveyor');
    Route::get('/all-product-types-list', 'PurchaseOrderController@getAllProductTypes');
    Route::get('/all-products-by-type', 'PurchaseOrderController@getAllProductByType');
    Route::get('/purchase-order-state-history', 'PurchaseOrderStateHistoryController@index');
    Route::get('/get-purchase-order/{id}', "PurchaseOrderController@getPurchaseOrderById");
    // Route::get('/get-state-invoice', 'InvoiceController@getApiGetStateInvoices')->name('api.all.state.invoices');
    // Route::get('/get-type-invoice', 'InvoiceController@getApiGetTypeInvoices')->name('api.all.type.invoices');
    // Route::post('register/store-invoice', 'InvoiceController@storeApiInvoice')->name('api.store.invoice');
    // Route::get('data-invoice/{id}', 'InvoiceController@getApiDataInvoice')->name('api.data.invoice');
    // Route::post('/register/update-invoice', 'InvoiceController@updateApiInvoice')->name('api.update.invoice');
    /*=============================================
               API PARA LOS PRODUCTOS
    =============================================*/
    Route::get('/all-products-services', 'ProductServiceController@getApiInvoices')->name('api.all.products.services');
    Route::get('/verify-code-product/{code}', 'ProductServiceController@validateCode')->name('api.validate.code.product.service');
    Route::get('/get-product-service', 'ProductServiceController@getApiTypeProductService')->name('api.type.product.service');
    Route::post('register/store-product-service', 'ProductServiceController@storeApiProductService')->name('api.store.product.service');
    Route::get('data-product-service/{id}', 'ProductServiceController@getApiDataProductService')->name('api.data.product.service');
    Route::post('/register/update-product-service', 'ProductServiceController@updateApiProductService')->name('api.update.invoice');

    /*=============================================
      API PARA LOS ACUERDOS COMERCIALES.
    =============================================*/
    Route::get('/all-trade-agreement', 'TradeAgreementController@getApiTradeAgreement')->name('api.all.trade.agreemente');
    Route::get('all-currency', 'TradeAgreementController@getApiCurrency')->name('api.all.currency');
    Route::get('all-products', 'TradeAgreementController@getApiProducts')->name('api.all.products');
    Route::post('register/store-trade-agreement', 'TradeAgreementController@storeApiTradeAgreement')->name('api.store.trade.agreement');
    Route::post('register/update-trade-agreement', 'TradeAgreementController@updateApiTradeAgreement')->name('api.store.trade.agreement');
    Route::get('data-trade-agreement/{id}', 'TradeAgreementController@getApiDataTradeAgreement')->name('api.data.trade.agreement');


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


