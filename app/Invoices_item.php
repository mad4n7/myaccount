<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoices_item extends Model
{
    protected $table = 'invoices_itens';
    protected $primaryKey = 'inv_item_id';
      
    
  public function invoice()
  {
      return $this->belongsTo('App\Invoice');    
  } 
  
static function countItemsByInvoiceID($invoice_id)
{
     // check new amount
     $total = DB::table('invoices_itens')
             ->where('payment_id', '=', $invoice_id)
             ->count();

    return $total;       
}  
  
}
