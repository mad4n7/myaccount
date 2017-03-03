<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User_detail;
use App\Product;

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
        $data['tmp_user_details'] = User_detail::where('user_id', Auth::user()->id)->first();
          
        $data['page_title'] = 'Dashboard';
        $data['products'] = Product::all();    
        //return view('home', $data);
        return view('home', $data);
    }

}
