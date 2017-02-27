<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/order', function () {   
    return view('products');
});

Auth::routes();

Route::get('/products', 'PublicController@products');
Route::get('/products/order', 'PublicController@order');
Route::post('/products/order', 'PublicController@addOrder');

Route::get('/home', 'HomeController@index');
Route::get('safe/token/confirm/{token}', 'HelperController@confirmToken');
Route::get('safe/token/resend', 'HelperController@tokenResend');


Route::get('invoice/status', 'InvoiceController@getPayPalPaymentStatus');
Route::get('invoice/cancel', 'InvoiceController@showPaymentCancel');


Route::get('json/invoices/get_prices_by_product', 'InvoiceController@jsonGetPricesByProduct');
Route::get('json/invoices/get_renews_on_by_billing_cycle', 'InvoiceController@jsonGetCycleByType');


Route::group(['middleware' => 'auth'], function()
{
    Route::resource('orders', 'OrderController');
    Route::get('/orders/server/details/{id}', 'OrderController@showServerDetailsByOrderId'); 
    
    
    Route::get('/profile', 'UserController@showProfile');   
    Route::post('/profile', 'UserController@updateProfile'); 
    Route::post('/profile/password', 'UserController@updateProfilePassword'); 
    
    Route::get('/invoices', 'InvoiceController@showClientInvoices'); 
    Route::get('/invoices/{id}', 'InvoiceController@showClientInvoiceById'); 
    
    Route::get('invoice/paypal/checkout/{id}', 'InvoiceController@clientShowPayPalCheckout');   
});