<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\User_detail;
use App\Product;
use App\Invoice;
use App\Order;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function showInvoices($status)
    {
        if($status == 'unpaid'){
            $status_db = 'u';
        }
        elseif($status == 'paid'){
            $status_db = 'p';
        }
        else {
            $status_db = 'u';
        }
        $data['invoices'] = Invoice::getAllByStatus($status_db);
        $data['page_title'] = 'Invoices - '.$status;
        return view('admin.invoices.list', $data);
    }  
    public function showClients()
    {
        $data['clients'] = User::getUsersList();
        $data['page_title'] = 'Clients';
        return view('admin.clients.list', $data);
    }      
    public function showReceipt($id)
    {
        if(Request::has('userid')){
            $userid = Request::query('userid');
        }
        else {
            Session::flash('msg_error', 'Please select the user id.');
            return redirect('/admin/invoices/paid');            
        }

        $data['user'] = User::where('id', $userid)->first();
        $data['user_details'] = User_detail::where('user_id', $userid)->first();
        $data['invoice'] = Invoice::where('invoice_id', $id)->first();
        $data['invoice_created_at'] = HelperController::funcDateMysqlToUSAStr($data['invoice']->created_at);
        $data['invoice_total'] = HelperController::funcConvertDecimalToCurrency($data['invoice']->amount);
        return view('emails.invoice_receipt', $data);       
    }       
    public static function returnAnyUserId($userid)
    {
        #check if is admin
        if(!isset($userid) 
            || empty($userid)
            || Auth::user()->chmod != 'rwxrwxrwx'){
            return Auth::user()->id;
            
        } elseif( Session::has('adm_client_userid') 
                && Auth::user()->id == Session::get('adm_client_userid') ){
            $userid = Auth::user()->id;
        } else {
            Session::push('adm_client_userid', $userid);
            return $userid;
        }      
    } 
}
