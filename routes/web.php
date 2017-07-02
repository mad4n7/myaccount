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

Route::get('/', 'PublicController@login');

Route::get('/order', function () {   
    return view('products');
});

Auth::routes();

Route::get('/terms_conditions', 'PublicController@termsConditions');

Route::get('/hosting', 'PublicController@hosting');
Route::get('/hosting/order', 'PublicController@order');
Route::post('/hosting/order', 'PublicController@addOrder');

Route::get('/home', 'HomeController@index');
Route::get('safe/token/confirm/{token}', 'HelperController@confirmToken');
Route::get('safe/token/resend', 'HelperController@tokenResend');


Route::get('invoice/status', 'InvoiceController@getPayPalPaymentStatus');
Route::get('invoice/cancel', 'InvoiceController@showPaymentCancel');


Route::get('json/invoices/get_prices_by_product', 'InvoiceController@jsonGetPricesByProduct');
Route::get('json/invoices/get_renews_on_by_billing_cycle', 'InvoiceController@jsonGetCycleByType');


/* Robots = this must run every 30 min for example in a CRONJOB */
Route::get('robot/check_all_charges_status', 'InvoiceController@checkAllChargesStatus');
Route::get('robot/check_all_subscriptions_status', 'InvoiceController@checkAllSubscriptionsStatus');


Route::group(['middleware' => 'auth'], function()
{
    Route::resource('orders', 'OrderController');
    Route::get('/orders/server/details/{id}', 'OrderController@showServerDetailsByOrderId'); 
    Route::get('/orders/cancel/{id}', 'OrderController@confirmCancel');
<<<<<<< HEAD
    Route::get('/orders/cancel/{id}/now', 'OrderController@cancelNow');     
=======
    Route::get('/orders/cancel/{id}/now', 'OrderController@cancelNow');

>>>>>>> admin
    /* Hosting */
    
    
    Route::get('/profile', 'UserController@showProfile');   
    Route::post('/profile', 'UserController@updateProfile'); 
    Route::post('/profile/password', 'UserController@updateProfilePassword'); 
    Route::post('/profile/cc_update', 'UserController@updateStripeCreditCard');
    Route::post('/profile/cc_delete', 'UserController@updateStripeCreditCard');
    
    Route::get('/invoices', 'InvoiceController@showClientInvoices'); 
    Route::get('/invoices/{id}', 'InvoiceController@showClientInvoiceById'); 
    Route::get('/invoices/receipt/{id}', 'InvoiceController@showReceipt'); 
    
    Route::get('invoice/paypal/checkout/{id}', 'InvoiceController@clientShowPayPalCheckout');   
});

Route::group(['middleware' => 'admin'], function()
{   
    Route::get('admin/invoices/{status}', 'AdminController@showInvoices'); 
    Route::get('admin/invoices/receipt/{id}', 'AdminController@showReceipt');

    Route::get('admin/clients', 'AdminController@showClients');   
});