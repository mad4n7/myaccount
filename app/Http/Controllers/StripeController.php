<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\User;

class StripeController extends Controller
{
    
    
    public function __construct()
    {
   
    }    
    
    /*
     * 
     * 
     * 
     * Returns 
     * {
     *       "id": "cus_4fdAW5ftNQow1a",
     *       "object": "customer",
     *       "account_balance": 0,
     *       "created": 1488345017,
     *       "currency": null,
     *       ...
     *       "livemode": false,
     *       "email": "jenny.rosen@example.com",
     *       ...
     *     }
     * 
     */
    public static function createUser($id)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $user = User::find($id);
        
        
        $customer = \Stripe\Customer::create(array(
          "email" => $user->email,
        )); 
        
        return $customer;
    }
    

    
    /**
     * 
     * @param type $user_id
     * @param type $card_num
     * @param type $exp_month
     * @param type $exp_year
     * @param type $cvv
     * @return Stripe\Card JSON: {
                "id": "card_19smR5EyFMMSYybSEaow3fOU",
                "object": "card",
                "address_city": null,
                "address_country": null,
                "address_line1": null,
                "address_line1_check": null,
                "address_line2": null,
                "address_state": null,
                "address_zip": null,
                "address_zip_check": null,
                "brand": "Visa",
                "country": "US",
                "customer": "cus_ADCmKHC3bg0qsi",
                "cvc_check": "unchecked",
                "dynamic_last4": null,
                "exp_month": 3,
                "exp_year": 2018,
                "funding": "credit",
                "last4": "4242",
                "metadata": {
                },
                "name": null,
                "tokenization_method": null
              }
     */
    public static function createCard($stripe_id, $user_id, $name_on_card, $card_num, $exp_month, $exp_year, $cvv, $optional )
    {
        /* Testing data
            "number" => "4242424242424242",
            "exp_month" => 3,
            "exp_year" => 2018,
            "cvc" => "314"
        */
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

       
        $user = User::find($user_id);
        
        $token = \Stripe\Token::create(array(
          "card" => array(
            "name" => $name_on_card,
            "number" => $card_num,
            "exp_month" => $exp_month,
            "exp_year" => $exp_year,
            "cvc" => $cvv,
              
            "address_city" => $optional['address_city'],
            "address_state" => $optional['address_state'],
            "address_country" => $optional['address_country'],
            "address_line1" => $optional['address_line1'],            
            "address_zip" => $optional['address_zip']
          )
        )); 
        
        $customer = \Stripe\Customer::retrieve($user->stripe_id);
        $customer->sources->create(array("source" => $token));        
        
        
        return $customer;
    }
    
    public static function subscribeToAPlan($user_id, $plan_id)
    {
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        $user = User::find($user_id);
        
        $customer = \Stripe\Subscription::create(array(
          "customer" => $user->stripe_id,
          "plan" => $plan_id,
        ));
        return $customer;
    }  
    
    /**
     * createCharge(17400 = $174.00, 'usd', 'cus_SDSJDKS28h', 'invoice XYZ')
     * @param type $amount
     * @param type $currency
     * @param type $customer_id
     * @param type $description
     * @return type
     */
    public static function createCharge($amount, $currency, $customer_id, $description)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $r = \Stripe\Charge::create(array(
          "amount" => $amount,
          "currency" => $currency,
          "customer" => $customer_id, // obtained with Stripe.js
          "description" => $description
        )); 
        return $r;
    }
    
    public static function addItemToaSubscription($sub_id, $plan_id, $quantity)
    {
        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        
        //$user = User::find($user_id);
        
        $sub = \Stripe\SubscriptionItem::create(array(
          "subscription" => $sub_id,
          "plan" => $plan_id,
          "quantity" => $quantity,
        ));
        return $sub;
    }    
    
    /**
     * Event obj: https://stripe.com/docs/api#event_object
     */
    public static function webhook(){        
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // Retrieve the request's body and parse it as JSON
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);

        // Do something with $event_json
        /*
         * 
         * Types: https://stripe.com/docs/api#event_types
         * 
         */
        // Approved invoice
        if($event_json->type == 'charge.succeeded') {
            $user = User::where('stripe_id', $event_json->data->object->id)->first();
        }

        http_response_code(200); // PHP 5.4 or greater        
    }
}
