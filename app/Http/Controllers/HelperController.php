<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DateInterval;
use DateTime;

use App\User;
use App\User_detail;


class HelperController extends Controller
{

    
      
    public function confirmToken($token)
    {
        try {
            $user_detail_db = User_detail::where('activation_token', $token)
                    ->update(['activated' => 1]);
            if($user_detail_db == 0 || is_null($user_detail_db) || empty($user_detail_db) ){
                Session::flash('msg_error', 'Sorry, we can not find your confirmation code.<br /> Please try again.');
                return redirect('/login');                
            }
            
            $user_detail = User_detail::where('activation_token', $token)->first();                    
            //login the user
            Auth::loginUsingId($user_detail->user_id, true);            
            Session::flash('msg', 'Your e-mail has been confirmed. Thank you!');
            return redirect('/home');            
                
        } catch (Exception $ex) {
            Session::flash('msg_error', 'Sorry, we got an unexpected error. Please, try again.'.var_dump($ex));
            return redirect('/login');
        }

    } 

    public function tokenResend()
    {
        try {
            $user = User::find(Auth::user()->id);
            $user_detail = User_detail::where('user_id', Auth::user()->id)->first();             
            
            //send e-mail
            $data_email = [                    
                "user" => $user,
                "email" => $user->email,
                "name" => $user->name,
                "token" => $user_detail->activation_token
            ];

            Mail::send('emails.welcome', $data_email, function($message) use ($data_email)
            {
                $message->to($data_email['email'], $data_email['name'])->subject('Welcome to Cat & Mouse');
            });  
            
            Session::flash('msg', 'A new e-mail with your confirmation code has been sent.');
            return redirect('/home');            
                
        } catch (Exception $ex) {
            Session::flash('msg_error', 'Sorry, we got an unexpected error. Please, try again.'.var_dump($ex));
            return redirect('/login');
        }

    }        
    
    
        /**
         * Convert MM/DD/YYYY to YYYY-MM-DD
         * @param type $date
         * @return string
         */
        static function funcDateUSAToMysql($date)
        {
            if($date == "" || $date == '0.00' || $date === null)
            {
                return null;
            }
            else {
                $dt = explode('/', $date);
                $new_date = $dt[2].'-'.$dt[0].'-'.$dt[1];

                return $new_date;
            }
        }

        /**
         * Convert YYYY-MM-DD to MM/DD/YYYY
         * @param type $date
         * @return string
         */
        static function funcDateMysqlToUSA($date)
        {

            if($date == "" || $date == '0.00' || $date === null)
            {
                return null;
            }
            else {
                $dt = explode('-', $date);
                $new_date = $dt[1].'/'.$dt[2].'/'.$dt[0];

                return $new_date;
            }
        }

        /**
         * Convert YYYY-MM-DD 00:00:00 to MM/DD/YYYY
         * @param type $date
         * @return string
         */        
        static function funcDateTimeMysqlToUSA($date)
        {

            if($date == "" || $date == '0.00' || $date === null)
            {
                return null;
            }
            else {
                
                $date = new DateTime($date);
                return $date->format('m/d/Y');                
       
            }
        }        
        


        /**
         * Convert a decimal number, for example 900.00 to USD 900.00
         * @param type $number
         * @return money
         */
        static function funcConvertDecimalToCurrency($number)
        {
            setlocale(LC_MONETARY, 'en_US');
            /* return money_format('%(#10n', $number) . "\n"; */
            return money_format('%#10n', $number) . "\n";
        }

        /**
         * @example funcConvertCurrencyToDecimal('$ 800.00')
         * @param type $string
         */
        static function funcConvertCurrencyToDecimal($string)
        {
            //"US$ 360.00"
            $n = str_replace('$', '', $string);
            $n2 = str_replace(',', '', $n);

            return trim($n2);
        }
        
        
        /**
         * Convert YYYY-MM-DD to October XX, 2016
         * @param type $date
         * @return string
         */
        static function funcDateMysqlToUSAStr($date)
        {

            if($date == "" || $date == '0.00' || $date === null)
            {
                return null;
            }
            else {
                $dt = explode('-', $date);
                $date2 = $dt[1].'/'.$dt[2].'/'.$dt[0];


                $myDateTime = new DateTime($date2);
                $new_date = $myDateTime->format("F j, Y");


                return $new_date;
            }
        }


        function calculateOrdinal($number) {
            $ends = array('th','st','nd','rd','th','th','th','th','th','th');
            if ((($number % 100) >= 11) && (($number%100) <= 13))
                return $number. 'th';
            else
                return $number. $ends[$number % 10];
        }


        /**
         * Return the next month
         * Example:
         * Month 1: 3/31 to 4/30 (because there is no 4/31)
         * Month 2: 4/30 to 5/31 (because there is the 31st)
         * 23 Days: 5/31 to 6/23 (please count nights)
         * returnNextMonth(YYYY-mm-dd)
         * 
         * @param type $month
         * @return DateTime(YYYY-mm-dd)
         */
        public static function returnNextMonth($string_date,$format){
            $date = new DateTime($string_date);
            $date->add(new DateInterval('P1M'));            
            
            if($format == 1 ) {
                $new_date = $date->format('Y-m-d');
            }
            else {
                $new_date = $date->format('F dS, Y');
            }            
              
            return $new_date;
        }        
        
        public static function returnNextYear($string_date, $format){
            
            
            $date = new DateTime($string_date);
            $date->add(new DateInterval('P1Y'));
            
            if($format == 1 ) {
                $new_date = $date->format('Y-m-d');
            }
            else {
                $new_date = $date->format('F dS, Y');
            }
              
            return $new_date;
        }
        
        //////////////////////////////////////////////////////////////////////
        //PARA: Date Should In YYYY-MM-DD Format
        //RESULT FORMAT:
        // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
        // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
        // '%m Month %d Day'                                            =>  3 Month 14 Day
        // '%d Day %h Hours'                                            =>  14 Day 11 Hours
        // '%d Day'                                                        =>  14 Days
        // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
        // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
        // '%h Hours                                                    =>  11 Hours
        // '%a Days                                                        =>  468 Days
        //////////////////////////////////////////////////////////////////////
        static function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
        {
            $datetime1 = date_create($date_1);
            $datetime2 = date_create($date_2);

            $interval = date_diff($datetime1, $datetime2);

            return $interval->format($differenceFormat);

        }        
        

        static function returnPymtStatusByChar($chars)
        {
            if($chars == 'p') { return '<span class="label label-success">paid</span>'; }
            elseif($chars == 'i') { return '<span class="label label-warning">incomplete</span>'; }
            else { return '<span class="label label-danger">unpaid</span>'; }

        }

        static function returnStatusByInteger($integer)
          {
              if($integer == 1) { return 'Yes'; }
              else { return 'No'; }

          }        
        
        /**
         * 
         * @param type $date
         * @return Y-m-d H:i:s
         */  
        public static function fundDateUnixTimeToDateTime($date, $return_type)
        {
            $unix_time = $date;
            $dt = new DateTime("@$unix_time");  // convert UNIX timestamp to PHP DateTime
            
            if($return_type == 'date'){
                return $dt->format('Y-m-d'); // output = 2017-01-01
            }
            else {
                return $dt->format('Y-m-d H:i:s'); // output = 2017-01-01 00:00:00            
            }
            
            
        }
          
          
}
