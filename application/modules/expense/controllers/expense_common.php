<?php

class Expense_common extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module("core/app");
      $this->load->model("expense_model");      
   }
   
   public function _remap()
   {
      
   }
   
   public function sidebar()
   {
      $adata = array();
      $adata['year'] = $this->expense_model->get_sidebar_year();
      $adata['month'] = $this->expense_model->get_sidebar_month();
      $adata['main_menu'] = $this->expense_model->get_sidebar_main_menu();
      $this->app->content('expense/sidebar',$adata);
   }
}