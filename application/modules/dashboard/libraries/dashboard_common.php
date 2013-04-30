<?php

class Dashboard_common
{
   public function __construct()
   {
   
   }
   
   public function get_sequence( $sequence )
   {
      $asequence_list = array();
      $asequence = json_decode( $sequence, true );
      foreach( $asequence as $key => $val ) {
         $akeys = array_keys( $val );
         $asequence_list[ $akeys[0] ] = $val[ $akeys[0] ];
      }
      return $asequence_list;
   }   
   
   public function get_given_month( $inum )
   {
      // Initialize month array
      $amonths = array();
      // Initialize counter
      $iadjust = $inum - 1;      
      // Get last month for 6 month range
      $ilast_month = date("n", strtotime("-{$iadjust} months"));
      // Get last year of 6 montg range
      $ilast_year = date("Y", strtotime("-{$iadjust} months"));
      // Loop for each last six month range
      for( $i = 1; $i <= ( $iadjust + 1 ); $i++ ) {
         // If month is greater than 12 months, reset to one the iterate again
         if( $ilast_month > 12 ) { 
            // If year is greater that 12, increment year by 1
            $ilast_year += 1;
            $ilast_month = 1;
         }
         $imktime = mktime(0, 0, 0,$ilast_month,1, $ilast_year );
         // Assign each month to array
         $amonth[] = array(
            "year" => $ilast_year, 
            "month" => str_pad( $ilast_month, 2, 0, STR_PAD_LEFT ),
            "month_num" => $ilast_month,
            "month_str" => date("M",$imktime),
            "month_str_comp" =>  date("F",$imktime)
         );
         $ilast_month++;
      }
      // Return last six month in array format
      return $amonth;
   }   
}