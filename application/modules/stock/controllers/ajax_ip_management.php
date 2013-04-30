<?php

class Ajax_ip_management extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
      $this->load->model("stock/ip_management_exec_model");
      $this->load->model("stock/ip_management_get_model");
      $this->load->model("stock/in_report_get_model");
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function remove_ip()
   {
      $aidx = $this->input->post('ids');
      $afilter_ids = array();
      foreach( $aidx as $rows ) {
         if(is_numeric($rows)) {
            $afilter_ids[] = array(
               'tsai_idx' => $rows,
               'tsai_active' => 0
            );
            $this->logs->set_log("IP Management #{$rows} ","DELETE");
         }
      }
      
      $bresult = $this->ip_management_exec_model->delete_ip($afilter_ids);
      $this->common->set_message("Record has been deleted succesfully!", "php-message", "success");    
   }
}