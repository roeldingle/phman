<?php

class Photo_bootstrap extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model("photo/photo_bootstrap_model");
   }
   
   public function _remap()
   {
   }
   
   public function initialize_default_tables()
   {
      $this->_install_category_defaults();
      $this->_install_status_defaults();
   }
   
   private function _install_category_defaults()
   {
      $acategory = $this->photo_bootstrap_model->get_category();
      if( !$acategory ) {
         $this->photo_bootstrap_model->insert_category_defaults();
      }   
   }
   
   private function _install_status_defaults()
   {
      $astatus = $this->photo_bootstrap_model->get_status();
      if( !$astatus ) {
         $this->photo_bootstrap_model->insert_status_defaults();
      }   
   }   
}