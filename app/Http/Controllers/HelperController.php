<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{

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
        public static function returnNextMonth($string_date){
            $date = new DateTime($string_date);
            $date->add(new DateInterval('P1M'));
            $new_date = $date->format('Y-m-d');

            if($date->format('m') == idate('m', strtotime($string_date))+2){
                    $new_month = idate('m', strtotime($new_date))-1;            
                    $year1 = idate('Y', strtotime($string_date));                                    
                    $lastday = cal_days_in_month(CAL_GREGORIAN, $new_month, $year1); // 31
                    $date2 = new DateTime($year1.'-'.$new_month.'-'.$lastday);
                    $date_ok = $date2->format('Y-m-d');
            }
            else {
                $date_ok = $new_date;
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
            if($chars == 'p') { return 'paid'; }
            elseif($chars == 'i') { return 'incomplete'; }
            else { return 'unpaid'; }

        }

        static function returnStatusByInteger($integer)
          {
              if($integer == 1) { return 'Yes'; }
              else { return 'No'; }

          }        
        
}
