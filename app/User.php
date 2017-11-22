<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public function details()
    {
        return $this->hasOne('App\User_detail', 'user_id');
    }    
    
    public function orders()
    {
        return $this->hasMany('App\User_detail', 'user_id');
    }   

    public static function getUsersList()
    {
        $r = DB::table('users')
                    ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
                    ->select('users.id', 'users.name', 'user_details.user_id', 
                    'user_details.company', 'users.stripe_id')
                    ->orderBy('users.id', 'desc')
                    ->get();
        $test = $r;                    
        return $r;
    }      

}
