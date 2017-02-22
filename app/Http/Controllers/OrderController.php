<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Product;
use App\Order;
use App\Invoice;
use App\Invoices_item;


class OrderController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['orders'] = Order::getAllByUserId(Auth::user()->id);
        $data['page_title'] = 'Orders';        
        return view('orders.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['products'] = Product::all();        
        $data['page_title'] = 'Pricing';
        return view('orders.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Request::has('product_id')){
            try {
                $total_itens = 0;
                if(Request::has('ckb_add_ssl')){
                    $total_itens = $total_itens + Request::input('ckb_add_ssl');
                }
                if(Request::has('ckb_add_migrate')){
                    $total_itens = $total_itens + Request::input('ckb_add_migrate');
                }                               
                $selected_product = Product::find(Request::input('product_id'));
                if(Request::input('product_periodicity') == 'month'){
                    $total_itens = $total_itens + $selected_product->price_month;
                }else {
                    $total_itens = $total_itens + $selected_product->price_year;
                }                
                
                //add orders
                $order_plan = new Order;
                $order_plan->user_id = Auth::user()->id;
                $order_plan->product_id = Request::input('product_id');
                $order_plan->domain_name = Request::input('domain_name');
                $order_plan->periodicity = Request::input('product_periodicity');                
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
                
                return redirect('/orders/'.$order_plan->order_id);          
                
                
            } catch (Exception $ex) {
                Session::flash('msg_error', 'Sorry, we got an unexpected error. Please, try again.'.var_dump($ex));
                return redirect('/home');
            }
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        //validate an accesss
        if( Invoice::checkClientOwner($id, Auth::user()->id) === false || !isset($id) )
        {
            Session::flash('msg_error', 'Sorry, the invoice that you are trying to access does not belong to you.');
            return redirect('/home');
        }   
        
        $data['order_itens'] = Invoices_item::where('order_id', $id)->get();
        $data['invoice'] = Invoice::where('order_id', $id)->first();
        $data['order'] = Order::where('order_id', $id)->first();
                
        $data['page_title'] = 'Order';
        return view('orders.view', $data); 
    }

    public function showServerDetailsByOrderId($id)
    {
        
        //validate an accesss
        if( Invoice::checkClientOwner($id, Auth::user()->id) === false || !isset($id) )
        {
            Session::flash('msg_error', 'Sorry, the invoice that you are trying to access does not belong to you.');
            return redirect('/home');
        }   
        
        $data['order_itens'] = Invoices_item::where('order_id', $id)->get();
        $data['invoice'] = Invoice::where('order_id', $id)->first();
        $data['order'] = Order::where('order_id', $id)->first();
                
        $data['page_title'] = 'Server Details';
        return view('orders.view_server', $data); 
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
        //
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
}
