<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
  public static function getAll()
  {
        $result = DB::table('products')
                            ->get();         
        return $result;
  }    
    
}
