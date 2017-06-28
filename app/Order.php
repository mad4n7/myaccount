<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Product;

class Order extends Model
{
    
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    public function user()
    {
      return $this->belongsTo('App\User');    
    }     

  public function invoices()
  {
      return $this->hasMany('App\Invoice', 'order_id');
  }     
        
    
    public static function checkClientOwner($id, $user_id)
    {
          $result = DB::table('orders')
                              ->where('order_id', '=', $id)
                              ->where('user_id', '=', $user_id)                                
                              ->count();
          if( $result > 0 ){
              return true;
          }
          else {
              return false;
          }
    }

    
    public static function getAllByUserId($user_id)
    {
        try{
          $result = DB::table('orders')                        
                        ->where('orders.user_id', '=', $user_id)                                                        
                        ->get();
          
                foreach ($result as $r) {
                    $product = Product::find($r->product_id);
                    $r->product_name = $product->prod_name;
                }
     
          return $result;  
          
        } catch (Exception $ex) {
            return 'Error Orders.getAllByUserId: '. $ex;
        }

    }    
    
    
}
