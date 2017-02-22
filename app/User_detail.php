<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_detail extends Model
{
    protected $table = 'user_details';
    protected $primaryKey = 'user_details_id';
    
    public function user()
    {
      return $this->belongsTo('App\User');    
    }      
}
