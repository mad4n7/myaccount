<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use App\Invoice;

class InvoiceController extends Controller
{
    private $_apiContext;
    
    public function __construct()
    {
            $this->middleware('auth');



            $this->_apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    env('PAYPAL_CLIENT_ID'),     // ClientID
                    env('PAYPAL_SECRET')      // ClientSecret
                )
            );

            /* LOG levels
             * 
             * Sandbox Mode
                DEBUG, INFO, WARN, ERROR.
                Please note that, DEBUG is only allowed in sandbox mode. It will throw a warning, and reduce the level to INFO if set in live mode.
                Live Mode
                INFO, WARN, ERROR
                DEBUG mode is not allowed in live environment. It will throw a warning, and reduce the level to INFO if set in live mode.
             */    

            
            $this->_apiContext->setConfig(
                array(
                    'mode' => 'sandbox',
                    'log.LogEnabled' => true,
                    'log.FileName' => storage_path('paypal.log'),
                    'http.ConnectionTimeOut' => 30,
                    'log.LogLevel' => 'DEBUG', // PLEASE USE FINE LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                    'cache.enabled' => true,
                    // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                    // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                )
            );
             

            /*
            $this->_apiContext->setConfig(
                array(
                    'mode' => 'live',
                    'log.LogEnabled' => true,
                    'log.FileName' => storage_path('paypal.log'),
                    'log.LogLevel' => 'DEBUG',
                    'validation.level' => 'log',
                    'cache.enabled' => true,
                )
            );
            */

    }    
    
    
 
        /**
         * Redirects a user to PayPal
         * The instance of this Payment is into the "_constructor"
         * @param type $id
         * @return type
         */
        public function clientShowPayPalCheckout($id)
        {
            //validate an accesss
            if( Invoice::checkClientOwner($id, Auth::user()->id) === false || !isset($id) )
            {
                Session::flash('msg_error', 'Sorry, the invoice that you are trying to access does not belong to you.');
                return redirect('/home');
            }

            $payment_db = Invoice::find($id);            
            $items_invoice = Invoice::find($payment_db->invoice_id)->payment_item;
            
            /* Update Payment Method to PayPal */
            $payment_db_updt = DbPayment::find($id);
            $payment_db_updt->method = 'paypal';
            $payment_db_updt->save();
            /* END Update Payment Method to PayPal */
            
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");            
            
            //load items for the invoice
            
            $count_items = 0;
            $items = [];
            foreach ($items_invoice as $item_invoice) {
                
                
                $items[$count_items] =  new Item();
                $items[$count_items]->setName($item_invoice->description)
                    ->setCurrency('USD')
                    ->setQuantity(1)
                    ->setSku($item_invoice->id) // Similar to `item_number` in Classic API
                    ->setPrice($item_invoice->total);
                $count_items = $count_items + 1;
            }
          
            //end items  
                           
                     
            $itemList = new ItemList();
            $itemList->setItems($items);

            $details = new Details();
            $details->setShipping(0) 
               ->setTax(0)
                ->setSubtotal($payment_db->amount);

            $amount = new Amount();
            $amount->setCurrency("USD")
                ->setTotal($payment_db->amount)
                ->setDetails($details);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription("Housing Rental")
                ->setInvoiceNumber(uniqid());

            //$baseUrl = url('/');
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(url("api/payment/status"))
                ->setCancelUrl(url("api/payment/cancel"));

            $payment = new Payment();
            $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

            $request = clone $payment;

            try {
                $payment->create($this->_apiContext);
            } catch (Exception $ex) {
                //echo "Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex;
                return Redirect::to($request);
            }
            $approvalUrl = $payment->getApprovalLink();
            echo "Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment;
            //echo "HERE:::::::::: .".$payment->getId();
            // add payment ID to session
            $tmp_pmt_id = $payment->getId();    
            Session::put('paypal_payment_id', $tmp_pmt_id);
            Session::put('paypal_dbinvoice_id', $payment_db->payment_id);
            
            /* return $payment; */
            return Redirect::to($approvalUrl);
            
            //end
            
        }        
            
            
    
}
