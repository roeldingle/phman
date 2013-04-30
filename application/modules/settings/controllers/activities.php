<?php

class Activities extends MX_Controller
{
   private $_ilimit = 20;
   
   public function __construct()
   {
      parent::__construct();
      
      $this->load->module('core/app');
      $this->load->module('site/template');
      $this->load->module('settings/logs');
      $this->load->model('user_activities_model');
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>false));
      $this->app->use_js(array("source" => "settings/activities/activities"));
   }
   
   public function index()
   {
      $adata = array();
      $aresult = array();
      $itotal_rows = $this->user_activities_model->get_list_count();
      $alimit = $this->common->sql_limit( $itotal_rows, $this->_ilimit);
      $alist = $this->user_activities_model->get_logs_list($alimit);
      
      foreach( $alist as $rows ) {
         $aresult[] = array(
            'user' => $rows->user,
            'user_level' => $rows->user_level,
            'message_log' => $rows->message_log,
            'message_from' => $rows->message_from,
            'message_to' => $rows->message_to,
            'date_created' => $rows->date_created,
         );
      }
      $adata['alist'] = $aresult;
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content('settings/activities',$adata);
      $this->template->footer();
   }
   
   public function check_logs()
   {
      $this->logs->set_expense_log_update("Expense #00000021","tbl_logs",array("tl_message_log"=>"test","tl_message_from" =>"t"),"tl_idx = '0000000035'");
   }
}