<?php

class Ip_management extends MX_Controller
{
   private $_ilimit = 20;
   public function __construct()
   {
      parent::__construct();
      $this->load->model('stock/ip_management_get_model');
      $this->load->module('site/template');
      $this->load->module('settings/logs');
      $this->app->use_css(array('source' => 'stock/stock-others',"cache"=>true));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
            $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));
      $this->app->use_js(array('source' => 'stock/ip-management/ip-management', "cache" => true));
      $this->app->use_js(array('source' => 'stock/ip-management/ip-management-list',"cache" => true));
      $this->app->use_js(array('source' => 'stock/ip-management/ip-management-form',"cache" => true));
   }
   
   public function index()
   {
      $adata = array();
      $aip_list = array();
      
      $sstart_date = $this->input->get('start');
      $send_date = $this->input->get('end');
      $sdepartment = $this->input->get('department');
      $ipage = $this->input->get('ipage');
      
      if($sstart_date != '' && $send_date != '') {
         if(!valid_date($sstart_date, 'Y-m-d') || !valid_date($send_date, 'Y-m-d')) {
            $sstart_date = "";
            $send_date = "";
            $this->common->set_message("Please enter a valid date range.", "php-message", 'warning');
         }
      }
      
      // Search, start date, end date
      $adata['search'] = $this->input->get("search");
      $adata['start_date'] = $sstart_date;
      $adata['end_date'] = $send_date;      
      $adata['department'] = $sdepartment;
      $adata['ilimit'] = $this->_ilimit;
      
      $ipage = $this->input->get('page');
      
      $aemployees = $this->ip_management_get_model->get_employees();
      $adepartment = $this->ip_management_get_model->get_department();
      
      $itotal_rows = $this->ip_management_get_model->get_ip_list_count();
      $alimit = $this->common->sql_limit( $itotal_rows, $this->_ilimit);
      $aip = $this->ip_management_get_model->get_ip_list( $alimit );
      
      $i = 0;
      foreach( $aip as $rows ) {
         $ainfo = array(
            'idx' => $rows->tsai_idx,  
            'employee_id' => $rows->te_idx,  
            'seat_no' => $rows->ts_tsc_seatno,  
            'fname' => $rows->te_fname,  
            'lname' => $rows->te_lname,  
            'department' => $rows->td_dept_name,  
            'assign_ip' => $rows->tsai_assign_ip,  
            'gateway' => $rows->tsai_gateway,
            'external_ip' => $rows->tsai_external_ip
         );
         $aip_list[] = array(
            'row' => ( $ipage == 1 ) ? $itotal_rows - $i : $itotal_rows - $alimit['offset'] - $i,
            'idx' => $rows->tsai_idx,  
            'seat_no' => $rows->ts_tsc_seatno,  
            'fname' => $rows->te_fname,  
            'lname' => $rows->te_lname,  
            'department' => $rows->td_dept_name,  
            'assign_ip' => $rows->tsai_assign_ip,  
            'gateway' => $rows->tsai_gateway,
            'external_ip' => $rows->tsai_external_ip,
            'sinfo' => json_encode($ainfo)
         );
         $i++;
      };
      $adata['aemployees'] = $aemployees;
      $adata['adepartment'] = $adepartment;
      $adata['aip_list'] = $aip_list;
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      $adata['qry_param'] = ( isset($_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != "" ) ? "&" . $_SERVER['QUERY_STRING'] : ""; 

      $this->load->vars($adata);
      $this->logs->set_log("IP Management","READ");  
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("stock/ip-management/ip-management-list");
      $this->app->content("stock/ip-management/hidden-dialogs");
      $this->template->footer();  
   }
}