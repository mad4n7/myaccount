<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    
  protected $primaryKey = 'product_id';
    
  public static function getAll()
  {
        $result = DB::table('products')
                            ->get();         
        return $result;
  }    
    
  
 public static function getAllHosting()
 {
    $r = DB::table('products')
                    ->where('prod_code_yearly', 'like', 'hosting%')
                    ->get();  
    return $r;
 }
}
