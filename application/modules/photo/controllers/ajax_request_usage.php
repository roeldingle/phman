<?php

class Ajax_request_usage extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('photo/req_usage_exec_model');
      $this->load->module('settings/logs');
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function remove_request()
   {
      $astatus = array();
      $aidx = $this->input->post('ids');
      if( $aidx ) {
         $afilter_ids = array();
         foreach( $aidx as $rows ) {
            if(is_numeric($rows)) {
               $afilter_ids[] = array(
                  'tprl_idx' => $rows,
                  'tprl_active' => 0
               );
                $this->logs->set_log("Request #{$rows}","DELETE");
            }
         }
         $bresult = $this->req_usage_exec_model->delete_request($afilter_ids);
         // if( $bresult ) {
            $this->common->set_message("Record has been deleted succesfully!", "request-message", "success");
         // } else  {
            // $this->common->set_message("Sorry, there is an error deleting the record1.", "request-message", "warning");            
         // }
      }
   }
}