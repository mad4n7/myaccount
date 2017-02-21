<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    public function getTable()
    {
        return $this->table;
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

}
