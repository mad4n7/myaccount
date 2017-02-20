<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
        $data['gravatar_url'] = UserController::get_gravatar( Auth::user()->email, 80, 'mm','g', false, null );
        $data['page_title'] = 'Dashboard';
        
        return view('home', $data);
    }
}
