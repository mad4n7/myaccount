<?php
/**
 * catandmouse.co
 *
 * @package  MyAccount
 * @author   Arthur Silva <arthur@catandmouse.co>
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
    

    public function showProfile()
    {
        $data['user'] = User::find(Auth::user()->id);
        $data['user_details'] = User::find(Auth::user()->id)->details;
        $data['countries'] = Country::all();
        $data['us_states'] = UsState::all();
        
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
        $user = User::find(Auth::user()->id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        
        //update user details
        $user_details = User_detail::where('user_id', Auth::user()->id)->first();
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

        //update user
        $user = User::find(Auth::user()->id);

        if (Hash::check($request->input('current_password'), $user->password)) {
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
