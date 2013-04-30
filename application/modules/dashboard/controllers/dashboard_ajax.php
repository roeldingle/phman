<?php

class Dashboard_ajax extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->library('dashboard/dashboard_common');
      $this->load->model("dashboard_model");
   }
   
   private function _remap()
   {
      show_404();
   }      
   
   public function save_state()
   {
      $aorder = $this->input->post('order');
      $ssequence = json_encode($aorder,false);      
      $auser_settings = $this->dashboard_model->get_user_settings();
      $adashboard_settings = $this->dashboard_model->get_dashboard_settings();
      
      $adashboard = $this->dashboard_common->get_sequence($adashboard_settings->tds_sequence);
      $asequence = $this->dashboard_common->get_sequence($ssequence);
      foreach( $asequence as  $key => $val ) {
         if( !array_key_exists( $key, $adashboard ) ) {
            return false;
         }
      }      
      if( !$auser_settings ) {
         $this->dashboard_model->insert_dashboard_user_settings($ssequence);
      } else {
         $this->dashboard_model->update_dashboard_user_settings($ssequence);     
      }
   }
}