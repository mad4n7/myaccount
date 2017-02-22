<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    
protected $table = 'invoices';
protected $primaryKey = 'invoice_id';

  public function invoice_item()
  {
      return $this->hasMany('App\Invoices_item', 'invoice_id');
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
  
  
    public static function getAllByUserId($user_id)
    {
        try{
          $result = DB::table('invoices')                        
                        ->where('invoices.user_id', '=', $user_id)                                
                        ->join('invoices_itens', 'invoices_itens.incoice_id', '=', 'invoices.invoice_id')                        
                        ->get();
          
     
          return $result;  
          
        } catch (Exception $ex) {
            return 'Error Invoices.getAllByUserId: '. $ex;
        }

    }    
    
}
