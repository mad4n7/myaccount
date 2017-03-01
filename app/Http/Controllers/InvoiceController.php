<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;



use App\Product;
use App\Invoice;

class InvoiceController extends Controller
{
    private $_apiContext;
    
    public function __construct()
    {
            //$this->middleware('auth');



    }    
    
    
 
        public function clientShowStripeCheckout($id)
        {
            \Stripe\Stripe::setApiKey("sk_test_xhdhj9uJZ1xqhc3h8gIavlxs");

            $plan = \Stripe\Plan::create(array(
              "name" => "Basic Plan",
              "id" => "basic-monthly",
              "interval" => "month",
              "currency" => "usd",
              "amount" => 0,
            ));
            
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
            $items_invoice = Invoice::find($payment_db->invoice_id)->invoice_itens;
                        

            $payer = new Payer();
            $payer->setPaymentMethod("paypal");            
            
            //load items for the invoice
            
            $count_items = 0;
            $items = [];
            foreach ($items_invoice as $item_invoice) {
                
                
                $items[$count_items] =  new Item();
                $items[$count_items]->setName($item_invoice->item_description)
                    ->setCurrency('USD')
                    ->setQuantity(1)
                    ->setSku($item_invoice->inv_item_id) // Similar to `item_number` in Classic API
                    ->setPrice($item_invoice->item_total);
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
                ->setDescription("Computer Services")
                ->setInvoiceNumber(uniqid());

            //$baseUrl = url('/');
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(url("invoice/status"))
                ->setCancelUrl(url("invoice/cancel"));

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
            Session::put('paypal_invoice_id', $tmp_pmt_id);
            Session::put('paypal_dbinvoice_id', $payment_db->invoice_id);            
            
            /* return $payment; */
            return Redirect::to($approvalUrl);
            
            

            //end
            
        }        
            
        
        /* PayPal */


        public function getPayPalPaymentStatus()
        {
            // Get the payment ID before session clear
            $payment_id = Session::get('paypal_invoice_id');
            $dbinvoice_id = Session::get('paypal_dbinvoice_id');

            // clear the session payment ID
            Session::forget('paypal_payment_id');
            Session::forget('paypal_dbinvoice_id');

            if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

                $data['message'] = 'Payment failed, nothing was posted.';
                $data['page_title'] = 'Payment Status';
                $data['approved'] = 0;
                return view('invoices/status', $data);

                /* return Redirect::route('api/payment/status/show')
                    ->with('error', 'Payment failed'); */
            }

            $payment = Payment::get($payment_id, $this->_apiContext);

            // PaymentExecution object includes information necessary
            // to execute a PayPal account payment.
            // The payer_id is added to the request query parameters
            // when the user is redirected from paypal back to your site
            $execution = new PaymentExecution();
            $execution->setPayerId(Input::get('PayerID'));

            //Execute the payment
            $result = $payment->execute($execution, $this->_apiContext);

            //echo '<pre>';print_r($result);echo '</pre>'; // DEBUG RESULT, remove it later

            if ($result->getState() == 'approved') { // payment made

                /* change Paypal status */
                $tmp_pmt = Invoice::find($dbinvoice_id);
                $tmp_pmt->inv_status = 'p';
                $tmp_pmt->paid_date = date('Y-m-d');
                $tmp_pmt->save();
                /* END change Paypal status */

                
                /*
                 * 
                 *  TODO: cPanel integration here 
                 * 
                 */                
                
                //send e-mail
                $data_email = [                    
                    "invoice_id" => $tmp_pmt->invoice_id
                ];

                Mail::send('emails.new_receipt', $data_email, function($message) use ($data_email)
                {
                    $message->to('arthur@catandmouse.co', 'Arthur')->subject('New Payment Confirmed');
                    $message->to('elle@catandmouse.co', 'Elle')->subject('New Payment Confirmed');
                });
                

                 
                /* END Send e-mail to managers */
                $data['message'] = 'We processed your payment successfully. Our team will contact you soon.';
                $data['page_title'] = 'Payment Status';
                $data['approved'] = 1;
                return view('invoices/status', $data);
            }
                $data['approved'] = 0;
                $data['message'] = 'Payment failed';
                $data['page_title'] = 'Payment Status';
                return view('invoices/status', $data);
        }        
            
        public function showPaymentCancel()
        {
            $data['page_title'] = 'Invoice - Canceled';
            return view('invoices/canceled', $data);
        }    
        
        
    public function showClientInvoices()
    {
        $data['invoices'] = Invoice::where('user_id', Auth::user()->id)->orderBy('invoice_id', 'desc')->get();
        $data['page_title'] = 'Invoices';        
        return view('invoices.list', $data);
    }    

    public function showClientInvoiceById($id)
    {
        //validate an accesss
        if( Invoice::checkClientOwner($id, Auth::user()->id) === false || !isset($id) )
        {
            Session::flash('msg_error', 'Sorry, the invoice that you are trying to access does not belong to you.');
            return redirect('/home');
        }        
        
        $data['invoice'] = Invoice::find($id);
        $data['invoice_itens'] = Invoice::find($id)->invoice_itens;
        $data['page_title'] = 'Invoices';        
        return view('invoices.view', $data);
    }    
    
    public function jsonGetPricesByProduct()
    {
        $product = Product::find(Request::query('id'));


        $data["anually_price"] = $product->price_year;
        $data["anually_monthly_price"] =  round($product->price_year/12,2);
        $data["monthly_price"] = $product->price_month;
        $data["product_name"] = $product->prod_name;

        return json_encode($data);
    }  
    
    public function jsonGetCycleByType()
    {
        if(Request::query('type') == 'monthly'){
            $date = HelperController::returnNextMonth(date('Y-m-d'), 2);
        }
        else {
            $date = HelperController::returnNextYear(date('Y-m-d'), 2);
        }


        return json_encode($date);
    }     
        
}
