<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\User;

class Invoice extends Model
{
    
protected $table = 'invoices';
protected $primaryKey = 'invoice_id';

  public function order()
  {
      return $this->belongsTo('App\Order');    
  } 
  /**
   * Check if is the owner
   * @param type $id
   * @param type $user_id
   * @return boolean
   */
  public static function checkClientOwner($id, $user_id)
  {
        $result = DB::table('invoices')
                            ->where('invoice_id', '=', $id)
                            ->where('user_id', '=', $user_id)
                            ->count();
        if( $result > 0 ){
            return true;
        }
        else {
            return false;
        }
  } 

  public static function pendingChargesByStatus()
  {
    $r = DB::table('invoices')
                        ->where('inv_status', 'u')
                        ->whereNotNull('stripe_charger_id')
                        ->get();
    return $r;
  }
  
  public static function pendingSubscriptionsByStatus()
  {
    $r = DB::table('invoices')
                        ->where('inv_status', 'u')
                        ->whereNotNull('stripe_subscription_id')
                        ->get();
    return $r;
  }  
  public static function getAllByStatus($status)
  {
    if(empty($status)){
      $status = 'u';
    }
    $r = DB::table('invoices')
                        ->where('inv_status', $status)
                        ->get();
             
    foreach($r as $item){
      $u = User::where('id', $item->user_id)->first();
      $item->user = $u;
    }
    
    return $r;
  }  
  
}