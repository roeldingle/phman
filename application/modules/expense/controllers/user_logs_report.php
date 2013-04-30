<?php

class User_logs_report extends MX_Controller
{
   private $_ilimit = 20;
   public function __construct()
   {
      parent::__construct();
      $this->load->module('core/app');
      $this->load->module('site/template');
      $this->load->module('expense/expense_common');
      $this->load->module('settings/logs');
      $this->load->model('expense/user_logs_model');
      // Import validate.mod
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.mod","cache"=>false));
      // Import table sorter
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));
      // Import defaults.js
      $this->app->use_js(array("source"=>"expense/defaults"));
      // Import userlogs.js
      $this->app->use_js(array("source"=>"expense/user_logs_report/userlogs"));
   }
   
   public function index()
   {
      // Initialize $adata
      $adata = array();
      // Initialize $alist
      $alist = array();
      // Initialize $sdaterange_where
      $sdaterange_where = "";
      // Initialize $ssearch_where
      $ssearch_where = "";
      $sfrom = $this->input->get('from');
      $sto = $this->input->get('to');
      $ssearch = $this->input->get('search');
      // Date range where clause
      if( ( $sfrom && $sto ) ){
         if( ( $this->_date_is_valid( $sfrom ) && $this->_date_is_valid( $sto ) ) && ( strtotime( $sfrom ) <= strtotime( $sto ) ) ) {
            $sdaterange_where = " AND DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y' ) BETWEEN '{$sfrom}' AND '{$sto}'";
         // Check date range if it has valid date format
         } elseif( !$this->_date_is_valid( $sfrom ) || !$this->_date_is_valid( $sto ) ) {
            // Display error message
            $this->common->set_message("The given date range is in invalid format.","error-message", "warning" );
         // Check start date if it is greater that end date
         } elseif( strtotime( $sfrom ) > strtotime( $sto ) ) {
            // Display error message
            $this->common->set_message("Start date must be less than end date","error-message", "warning");
         }
      }
      // Search where clause
      if( $ssearch ) {
         $ssearch_where = " AND CONCAT_WS(' ',te.te_fname, tug.tug_name, tl.tl_message_log, tl.tl_message_from, tl.tl_message_to, tp.tp_position) LIKE '%{$this->db->escape_str($ssearch)}%'";
      }
      // Get total rows of logs
      $itotal_rows = $this->user_logs_model->get_list_count($sdaterange_where, $ssearch_where );
      // Set limit rows
      $alimit = $this->common->sql_limit( $itotal_rows, $this->_ilimit);
      // Get list result
      $aresult = $this->user_logs_model->get_expense_logs_list( $sdaterange_where, $ssearch_where, $alimit );
      foreach( $aresult as $rows ) {
         $alist[] = array(
            'date_created' => $rows->date_created,
            'user' => $rows->user,
            'position' => $rows->position,
            'message_log' => $rows->message_log,
            'message_from' => $rows->message_from,
            'message_to' => $rows->message_to,
            'transact_type' => $rows->transact_type
         );
      }
      $adata['alist'] = $alist;
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      $adata['from'] = $sfrom;
      $adata['to'] = $sto;
      $adata['search'] = $ssearch;
      $adata['qry_param'] = ( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != "") ? "&" . $_SERVER['QUERY_STRING'] : ""; 
      $this->template->header();
      $this->expense_common->sidebar();
      $this->template->breadcrumbs();
      $this->app->content('expense/user_logs_report/user_logs', $adata );
      $this->template->footer();
   }
   
   private function _date_is_valid( $date )   
   {   
      return preg_match( "'^\d{1,2}/\d{1,2}/\d{4}$'", $date );
   }
}