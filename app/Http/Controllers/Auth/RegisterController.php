<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\User_detail;
use App\Http\Controllers\StripeController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        
        
        //save the stripe ID
        $user_update = User::find($user->id);        
        $stripe_user = StripeController::createUser($user->id);
        $user_update->stripe_id = $stripe_user->id;   
        $user_update->save();

        //create user details
        $generated_token = md5( date('mdYhis').'user_email'.$user->email );
        $user_detail = new User_detail;
        $user_detail->user_id = $user->id;
        $user_detail->activation_token = $generated_token;
        $user_detail->activated = 0;
        $user_detail->save();
        
        //send e-mail
        $data_email = [                    
            "user" => $user,
            "email" => $user->email,
            "name" => $user->name,
            "token" => $generated_token
        ];

        Mail::send('emails.welcome', $data_email, function($message) use ($data_email)
        {
            $message->to($data_email['email'], $data_email['name'])->subject('Welcome to Silvaway Solutions');
        });         
        
        
        return $user;
    }
}
