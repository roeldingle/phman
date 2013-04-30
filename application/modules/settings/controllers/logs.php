<?php

class Logs extends MX_Controller
{
   
   public function __construct()
   {
      parent::__construct();
      $this->load->model('settings/user_activities_model');
      $this->_atransact_type = array("CREATE", "READ", "UPDATE","DELETE");
   }
   
   public function set_log( $smessage_log = "", $stransact_type = "", $sapp_type = "normal" )
   {
      if( in_array( $stransact_type, $this->_atransact_type ) ) {
         $atransact_value = array("CREATE" => "Added", "READ" => "Viewed","UPDATE" => "Edited", "DELETE" => "Remove");
         $stransact_type = strtoupper($stransact_type);
         
         $smessage_log = "{$atransact_value[$stransact_type]} {$smessage_log}";
         $alog = $this->user_activities_model->get_log( $smessage_log );
         if( !$alog ) {
            $this->user_activities_model->insert_log( $smessage_log, $stransact_type, $sapp_type );
         }
      }
   }
   
   public function set_expense_log_create( $smessage_log )
   {
      $this->set_log($smessage_log,"CREATE","expense");
   }
   
   public function set_expense_log_update( $smessage_log, $stable = "", $afields, $aexcept_fields, $iidx )
   {
      // Check if there is table provided
      if( $stable == "" ) {
         return false;
      }      
      // Check if there is a table and fields provided
      if( $afields && count( $afields ) > 0 ) {
         // Get specific table on the database and get the results
         $aresult = $this->user_activities_model->get_table( $stable, $iidx );
         if( $aresult ) {
            // Get the data keys 
            $akeys = array_keys( $aresult );
            // Loop through given fields
            foreach( $afields as $key => $rows ) {
               // Loop through keys
               foreach( $akeys as $rows_field ) {
                  // Compare keys if same
                  if( $key == $rows_field && !in_array( $rows_field, $aexcept_fields ) ) { 
                     if( $rows != $aresult[ $rows_field ] && $rows !== false ) {
                        $this->user_activities_model->insert_expense_log("Edited " .$smessage_log, $aresult[$rows_field], $rows );
                     }
                  }
               }
            }       
         }
      }
   }
   
   public function set_expense_log_delete($smessage_log)
   {
      $this->set_log($smessage_log,"DELETE","expense");
   }   
}