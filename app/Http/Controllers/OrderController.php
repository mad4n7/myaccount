<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Order;
use App\Invoice;
use App\Invoices_item;
use Illuminate\Support\Facades\Auth;

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
        $data['page_title'] = 'Pricing';
        //return view('orders.add', $data);
        return 'Orders list here...';
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
        if($request->has('product_id')){
            try {
                $total_itens = 0;
                if($request->has('ckb_add_ssl')){
                    $total_itens = $total_itens + $request->input('ckb_add_ssl');
                }
                if($request->has('ckb_add_migrate')){
                    $total_itens = $total_itens + $request->input('ckb_add_migrate');
                }                
               
                $selected_product = Product::find($request->input('product_id'));
                
                //add orders
                $order_plan = new Order;
                $order_plan->user_id = Auth::user()->id;
                $order_plan->product_id = $request->input('product_id');
                $order_plan->domain_name = $request->input('domain_name');
                $order_plan->periodicity = $request->input('product_periodicity');                
                $order_plan->save();                                                
                
                $invoice_1 = new Invoice;
                $invoice_1->user_id = Auth::user()->id;
                $invoice_1->order_id = $order_plan->order_id;
                $invoice_1->amount = $total_itens;
                $invoice_1->inv_status = 'u';
                $invoice_1->save();
                
                $invoice_item_1 = new Invoice_item;
                $invoice_item_1->invoice_id = $invoice_1->invoice_id;;
                $invoice_item_1->order_id = $order_plan->order_id;
                $invoice_item_1->item_description = $selected_product->prod_description;
                
                if($request->input('product_periodicity') == 'month'){
                    $invoice_item_1->item_total = $selected_product->price_month;
                }else {
                    $invoice_item_1->item_total = $selected_product->price_year;
                }
                $invoice_item_1->save();
                
                
                //check extra items
                if($request->has('ckb_add_ssl')){
                    $invoice_item_parent = new Invoice_item;
                    $invoice_item_parent->invoice_id = $invoice_1->invoice_id;;
                    $invoice_item_parent->order_id = $order_plan->order_id;
                    $invoice_item_parent->item_description = '1 year of SSL Certificate';
                    $invoice_item_parent->save();                     
                }
                if($request->has('ckb_add_migrate')){
                    $invoice_item_migrate = new Invoice_item;
                    $invoice_item_migrate->invoice_id = $invoice_1->invoice_id;;
                    $invoice_item_migrate->order_id = $order_plan->order_id;
                    $invoice_item_migrate->item_description = 'Website migration (up to 3)';
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
        
        $data['page_title'] = 'Payment';
        return view('orders.payment', $data); 
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
