<?php
/**
 * arthursilva.com
 *
 * @package  MyAccount
 * @author   Arthur Silva <arthur@arthursilva.com>
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\User_detail;
use App\Country;
use App\UsState;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    

    public function showProfile(Request $request)
    {
        #validade which user to open and permissions
        $user_id = AdminController::returnAnyUserId($request->query('userid'));
        $data['user'] = User::find($user_id);
        $data['user_details'] = User::find($user_id)->details;
        $data['countries'] = Country::all();
        $data['us_states'] = UsState::all();
        
        $data['list_cc'] = StripeController::getAllCardsByCustomer($data['user']->stripe_id);
        
        $data['page_title'] = 'Profile';  
        //return view('home', $data);
        return view('auth.profile', $data);   
    }    
    
    public function updateProfile(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
                Session::flash('msg_error', 'Sorry we can not update your profile. Please try again. ');
                return redirect('/profile');
        }

        //update user
        # check if is an admin
        if ($request->session()->has('adm_client_userid')) {
            $userid = AdminController::returnAnyUserId($request->session()->get('adm_client_userid'));
        }
        else {
            $userid = Auth::user()->id;
        }        
        $user = User::where('id', $userid)->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        
        //update user details
        $user_details = User_detail::where('user_id', $userid)->first();
        $user_details->phone_number = $request->input('phone_number');
        $user_details->address = $request->input('address');
        $user_details->address2 = $request->input('address2');
        $user_details->city = $request->input('city');
        $user_details->zip_code = $request->input('zip_code');
        $user_details->country_code = $request->input('country');
        $user_details->us_state_code = $request->input('us_state_code');
        $user_details->company = $request->input('company');
        $user_details->save();
                       
        Session::flash('msg', 'Profile updated.');
        return redirect('/profile');
    }
    
    public function updateProfilePassword(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
                Session::flash('msg_error', 'Sorry we can not update your password. Please try again. ');
                return redirect('/profile');
        }

        # update user
        # check if is an admin
        if ($request->session()->has('adm_client_userid')) {
            $userid = AdminController::returnAnyUserId($request->session()->get('adm_client_userid'));
            $skip_pass_veri = 1;
        }
        else {
            $userid = Auth::user()->id;
            $skip_pass_veri = 0;
        }
        
        $user = User::where('id', $userid)->first();

        if (Hash::check($request->input('current_password'), $user->password) 
            || $skip_pass_veri == 1 ) {
            $user->password = bcrypt($request->input('password'));
            $user->save();
 
            Session::flash('msg', 'Password updated.');
            return redirect('/profile');           
        }
        else {
            Session::flash('msg_error', 'Sorry the current password is not correct.');
            return redirect('/profile');  
        }                

    }    
    
    public function updateStripeCreditCard(Request $request)
    {        

        $validator = Validator::make($request->all(), [
            'cc_number' => 'required',
            'cc_ex_month' => 'required',
            'cc_ex_year' => 'required',
            'cc_cvv' => 'required',
            'cc_name' => 'required'
        ]);

        if ($validator->fails()) {
                Session::flash('msg_error', 'Sorry we can not add a new credit card now, some data is missing. ');
                return redirect('/profile');
        }

        # check if is an admin        
        if ($request->session()->has('adm_client_userid')) {
            $userid = AdminController::returnAnyUserId($request->session()->get('adm_client_userid'));
        }
        else {
            $userid = Auth::user()->id;
        }
        
        $user = User::where('id', $userid)->first();
        if(User_detail::where('user_id', $userid)->count() == 0){
            Session::flash('msg_error', 'Sorry you first need to complete your profile details.');
            return redirect('/profile');            
        }
        $user_details = User_detail::where('user_id', $userid)->first();

        $extra_options = array(
            "address_city" => $user_details->city,
            "address_state" => $user_details->us_state_code,
            "address_country" => $user_details->country_code,
            "address_line1" => $user_details->address,            
            "address_zip" => $user_details->zip_code);

        StripeController::createCard($user->stripe_id, 
                $user->id, $request->input('cc_name'),
                $request->input('cc_number'), 
                $request->input('cc_ex_month'), 
                $request->input('cc_ex_year'), $request->input('cc_ccv'),
                $extra_options);
            Session::flash('msg', 'Credit Card added. Thank you.');
            return redirect('/profile');         
    }       
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Session::flash('msg_error', 'Sorry, we got an unexpected error. Please, try again.'.var_dump($ex));
        return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }    
    

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL     
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public static function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val ) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }

    
}
