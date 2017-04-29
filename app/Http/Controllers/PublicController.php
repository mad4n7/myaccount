<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Product;
use App\Order;
use App\Invoice;
use App\Invoices_item;
use App\User;
use App\User_detail;
use App\Country;
use App\UsState;

class PublicController extends Controller
{
    
    public function login()
    {
        
        
        $data['page_title'] = 'Login';
        
        if (Auth::check()) {
            return view('home', $data);
        }        
        else {
            return view('terms_conditions', $data);
        }
    }
    
    public function termsConditions()
    {
        $data['page_title'] = 'Terms & Agreements';
        return view('terms_conditions', $data);
    }
    
    
    public function hosting()
    {       
        $data['products'] = Product::getAllSharedHosting();       
        $data['page_title'] = 'Plans';
        return view('hosting.plans', $data);
    }
    
    public function order()
    {
        if(Request::has("product")){
           $selected_product = Request::input("product");
        }
        else {
            $selected_product = "";
        }
            
        /* check if its authenticated because auth users and not auth can access */
        if(isset(Auth::user()->id) ){
            $user_tmp = User::find(Auth::user()->id);
            $data['user_tmp'] = $user_tmp;
        }
        
        $data['countries'] = Country::all();
        $data['us_states'] = UsState::all();
        
        $data['selected_product'] = $selected_product;
        $data['products'] = Product::getAllHosting();        
        $data['page_title'] = 'Pricing';
        return view('hosting.add', $data);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addOrder()
    {
        if(Request::has('product_id')){
            try {
                $total_itens = 0;
                if(Request::has('ckb_add_ssl')){
                    $total_itens = $total_itens + 24;
                }
                if(Request::has('ckb_add_migrate')){
                    $total_itens = $total_itens + 60;
                }                               
                $selected_product = Product::find(Request::input('product_id'));
                if(Request::input('product_periodicity') == 'monthly'){
                    $total_itens = $total_itens + $selected_product->price_month;
                    $prod_code_now = $selected_product->prod_code_monthly;
                }else {
                    $total_itens = $total_itens + $selected_product->price_year;
                    $prod_code_now = $selected_product->prod_code_yearly;
                }                
                
                /* check if its authenticated because auth users and not auth can access */
                if(isset(Auth::user()->id) ){
                    $user_tmp = User::find(Auth::user()->id);
                    $add_user = 0;
                }
                else {
                    $add_user = 1;
                }
                
                // not authenticated
                if (!Auth::check() || 
                        (isset($user_tmp) && 
                        empty($user_tmp->card_id) ) ) {
                    
                    if($add_user == 1){
                        $user = new User;
                        $user->name = Request::input('name');
                        $user->email = Request::input('email');
                        $user->password = bcrypt(Request::input('password'));                
                        $user->save();
                        
                        //save the stripe ID
                        $user_update = User::find($user->id);
                        $stripe_user = StripeController::createUser($user->id);
                        $user_update->stripe_id = $stripe_user->id;   
                        $user_update->save();
                        
                        Auth::login($user);
                    }
                    else {
                        $user = User::find(Auth::user()->id);
                        $stripe_user = StripeController::retriveUser($user->stripe_id);
                    }


                    //create a credit cart token

                    $extra_options = array(
                        "address_city" => Request::input('city'),
                        "address_state" => Request::input('us_state_code'),
                        "address_country" => Request::input('country'),
                        "address_line1" => Request::input('address'),            
                        "address_zip" => Request::input('zip_code'));

                    $credit_card = StripeController::createCard($stripe_user->id, 
                            $user->id, Request::input('cc_name'),
                            Request::input('cc_number'), 
                            Request::input('cc_ex_month'), 
                            Request::input('cc_ex_year'), Request::input('cc_ccv'),
                            $extra_options);
                    
                    //update the card ID
                    $user_update_cc = User::find($user->id);
                    $user_update_cc->card_id = $credit_card->card->id;
                    $user_update_cc->save();
                            
                    //create user details
                    $generated_token = md5( date('mdYhis').'user_email'.$user->email );
                    $user_detail = new User_detail;
                    $user_detail->user_id = $user->id;
                    $user_detail->activation_token = $generated_token;
                    $user_detail->activated = 0;
                    $user_detail->phone_number = Request::input('phone_number');
                    $user_detail->address = Request::input('address');
                    $user_detail->city = Request::input('city');
                    $user_detail->zip_code = Request::input('zip_code');
                    $user_detail->country_code = Request::input('country');
                    $user_detail->us_state_code = Request::input('us_state_code');
                    $user_detail->save();

                    
                
                }
                //end not authenticated

                // do subscription
                $subscription = StripeController::subscribeToAPlan(Auth::user()->id, $prod_code_now);
                                
                //add orders
                $order_plan = new Order;
                $order_plan->user_id = Auth::user()->id;
                $order_plan->product_id = Request::input('product_id');
                $order_plan->domain_name = Request::input('domain_name');
                $order_plan->periodicity = Request::input('product_periodicity');                
                $order_plan->stripe_subscription_id = $subscription->id;                   
                $order_plan->migration_domains = Request::input('migration_domains');
                $order_plan->save();                                                
                
                $invoice_1 = new Invoice;
                $invoice_1->user_id = Auth::user()->id;
                $invoice_1->order_id = $order_plan->order_id;                
                $invoice_1->inv_status = 'u';
                $invoice_1->plan_id = $prod_code_now;
                $invoice_1->stripe_subscription_id = $subscription->id;
                
                if(Request::input('product_periodicity') == 'monthly'){                    
                    $invoice_1->amount = $selected_product->price_month;
                }else {
                    $invoice_1->amount = $selected_product->price_year;
                }
                $invoice_1->inv_description = $selected_product->prod_name;
                $invoice_1->save();
                
                
                //check extra items
                if(Request::has('ckb_add_ssl')){
                    
                   
                    $subscription2 = StripeController::subscribeToAPlan(Auth::user()->id,
                            'domain-ssl-certificate');
                    
                    $order_plan = new Order;
                    $order_plan->user_id = Auth::user()->id;
                    $order_plan->product_id = 4;
                    $order_plan->domain_name = Request::input('domain_name');
                    $order_plan->periodicity = 'annually';                
                    $order_plan->stripe_subscription_id = $subscription2->id;                
                    $order_plan->save();   
                
                    $invoice_1 = new Invoice;
                    $invoice_1->user_id = Auth::user()->id;
                    $invoice_1->order_id = $order_plan->order_id;
                    $invoice_1->inv_status = 'u';
                    $invoice_1->amount = Request::input('ckb_add_ssl');                    
                    $invoice_1->inv_description = '1 year of SSL Certificate';
                    $invoice_1->stripe_subscription_id = $subscription2->id;
                    $invoice_1->save();

                    
                }
                
                if(Request::has('ckb_add_backuppro')){
                    
                   
                    $subscription3 = StripeController::subscribeToAPlan(Auth::user()->id,
                            'files-backuppro-yearly');
                    
                    $order_plan = new Order;
                    $order_plan->user_id = Auth::user()->id;
                    $order_plan->product_id = 5;
                    $order_plan->domain_name = Request::input('domain_name');
                    $order_plan->periodicity = 'annually';                
                    $order_plan->stripe_subscription_id = $subscription3->id;                
                    $order_plan->save();   
                
                    $invoice_1 = new Invoice;
                    $invoice_1->user_id = Auth::user()->id;
                    $invoice_1->order_id = $order_plan->order_id;
                    $invoice_1->inv_status = 'u';
                    $invoice_1->amount = Request::input('ckb_add_backuppro');                    
                    $invoice_1->inv_description = '1 year of SSL Certificate';
                    $invoice_1->stripe_subscription_id = $subscription3->id;
                    $invoice_1->save();

                    
                }                
                
                if(Request::has('ckb_add_migrate')){
          
                    // add subitem
                    $user_db = User::find(Auth::user()->id);

                    // Charge it separated
                    $charge = StripeController::createCharge(6000, 'usd', $user_db->stripe_id, $invoice_1->inv_description);                    
                    
                    $invoice_1 = new Invoice;
                    $invoice_1->user_id = Auth::user()->id;
                    $invoice_1->inv_status = 'u';
                    $invoice_1->stripe_si_id = null;
                    $invoice_1->plan_id = null;
                    $invoice_1->amount = Request::input('ckb_add_migrate');                    
                    $invoice_1->inv_description = 'Website migration (up to 3)';
                    $invoice_1->stripe_charger_id = $charge->id;
                    $invoice_1->save();    

                }           
                
                //if not logged in
                if (!Auth::check() || ! isset($user_tmp)) {
                    //send e-mail
                    $data_email = [                    
                        "user" => $user,
                        "email" => $user->email,
                        "name" => $user->name,
                        "token" => $generated_token
                    ];
                    //send email
                    Mail::send('emails.welcome', $data_email, function($message) use ($data_email)
                    {
                        $message->to($data_email['email'], $data_email['name'])->subject('Welcome to Cat & Mouse');
                    });                 
                }
                return redirect('/orders');          
                
                
            } catch (Exception $ex) {
                Session::flash('msg_error', 'Sorry, we got an unexpected error. Please, try again.'.var_dump($ex));
                return redirect('/home');
            }
        }
        
    }
    
    
    
    
}
