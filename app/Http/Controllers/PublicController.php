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
    
    public function hosting()
    {       
        $data['products'] = Product::all();        
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
            
        $data['countries'] = Country::all();
        $data['us_states'] = UsState::all();
        
        $data['selected_product'] = $selected_product;
        $data['products'] = Product::all();        
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
                if(Request::input('product_periodicity') == 'month'){
                    $total_itens = $total_itens + $selected_product->price_month;
                    $prod_code_now = $selected_product->prod_code_monthly;
                }else {
                    $total_itens = $total_itens + $selected_product->price_year;
                    $prod_code_now = $selected_product->prod_code_yearly;
                }                
                
                
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
                
                //create a credit cart token
                
                $extra_options = array(
                    "address_city" => Request::input('city'),
                    "address_state" => Request::input('us_state_code'),
                    "address_country" => Request::input('country'),
                    "address_line1" => Request::input('address'),            
                    "address_zip" => Request::input('zip_code'));
                
                StripeController::createCard($stripe_user->id, 
                        $user->id, Request::input('cc_name'),
                        Request::input('cc_number'), 
                        Request::input('cc_ex_month'), 
                        Request::input('cc_ex_year'), Request::input('cc_ccv'),
                        $extra_options);
             
                // do subscription
                $subscription = StripeController::subscribeToAPlan(Auth::user()->id, $prod_code_now);
                
                //create user details
                $generated_token = md5( date('mdYhis').'user_email'.$user->email );
                $user_detail = new User_detail;
                $user_detail->user_id = $user->id;
                $user_detail->activation_token = $generated_token;
                $user_detail->activated = 0;
                $user_detail->save();

        

                Auth::login($user);
                
                //add orders
                $order_plan = new Order;
                $order_plan->user_id = Auth::user()->id;
                $order_plan->product_id = Request::input('product_id');
                $order_plan->domain_name = Request::input('domain_name');
                $order_plan->periodicity = Request::input('product_periodicity');                
                $order_plan->stripe_subscription_id = $subscription->id;                
                $order_plan->save();                                                
                
                $invoice_1 = new Invoice;
                $invoice_1->user_id = Auth::user()->id;
                $invoice_1->order_id = $order_plan->order_id;
                $invoice_1->amount = $total_itens;
                $invoice_1->inv_status = 'u';
                $invoice_1->save();
                
                $invoice_item_1 = new Invoices_item;
                $invoice_item_1->invoice_id = $invoice_1->invoice_id;;
                $invoice_item_1->order_id = $order_plan->order_id;
                $invoice_item_1->item_description = $selected_product->prod_name;
                
                if(Request::input('product_periodicity') == 'month'){
                    $invoice_item_1->item_total = $selected_product->price_month;
                }else {
                    $invoice_item_1->item_total = $selected_product->price_year;
                }
                $invoice_item_1->save();
                
                
                //check extra items
                if(Request::has('ckb_add_ssl')){
                    $invoice_item_parent = new Invoices_item;
                    $invoice_item_parent->invoice_id = $invoice_1->invoice_id;;
                    $invoice_item_parent->order_id = $order_plan->order_id;
                    $invoice_item_parent->item_description = '1 year of SSL Certificate';
                    $invoice_item_parent->item_total = Request::input('ckb_add_ssl');
                    $invoice_item_parent->save();                     
                }
                if(Request::has('ckb_add_migrate')){
                    $invoice_item_migrate = new Invoices_item;
                    $invoice_item_migrate->invoice_id = $invoice_1->invoice_id;;
                    $invoice_item_migrate->order_id = $order_plan->order_id;
                    $invoice_item_migrate->item_description = 'Website migration (up to 3)';
                    $invoice_item_migrate->item_total = Request::input('ckb_add_migrate');
                    $invoice_item_migrate->save();                     
                }                
                
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
                
                return redirect('/orders/'.$order_plan->order_id);          
                
                
            } catch (Exception $ex) {
                Session::flash('msg_error', 'Sorry, we got an unexpected error. Please, try again.'.var_dump($ex));
                return redirect('/home');
            }
        }
        
    }
    
    
    
    
}
