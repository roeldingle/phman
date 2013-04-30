<?php

class Ajax_photo_asset extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model("photo/photo_asset_get_model");
      $this->load->model("photo/photo_asset_exec_model");
      $this->load->module('settings/logs');
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function remove_photo()
   {
      $astatus = array();
      $aidx = $this->input->post('ids');
      if( $aidx ) {
         $afilter_ids = array();
         foreach( $aidx as $rows ) {
            if(is_numeric($rows)) {
               $afilter_ids[] = array(
                  'tpal_idx' => $rows,
                  'tpal_active' => 0
               );
               $this->logs->set_log("Request #{$rows}","DELETE");
            }
         }
         $bresult = $this->photo_asset_exec_model->delete_photo($afilter_ids);
         // if( $bresult ) {
            $this->common->set_message("Record has been deleted succesfully!", "photo-message", "success");
         // } else  {
            // $this->common->set_message("Sorry, there is an error deleting the record.", "photo-message", "success");            
         // }
      }
   }
}