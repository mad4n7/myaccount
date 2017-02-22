<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    public function user()
    {
      return $this->belongsTo('App\User');    
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
                        ->join('invoices', 'invoices.order_id', '=', 'orders.order_id')                        
                        ->get();
          
     
          return $result;  
          
        } catch (Exception $ex) {
            return 'Error Orders.getAllByUserId: '. $ex;
        }

    }    
    
    
}
