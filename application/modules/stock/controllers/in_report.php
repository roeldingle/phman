<?php

class In_report extends MX_Controller
{
   private $_ilimit = 20;
   public function __construct()
   {
      parent::__construct();
      $this->load->module('site/template');
      $this->load->model('stock/in_report_get_model');
      $this->load->module('settings/logs');
      $this->app->use_css(array('source' => 'stock/stock-others',"cache"=>true));
      $this->app->use_js(array('source' => 'stock/in-report/in-report',"cache"=>true));
   }
   
   public function index()
   {
      $adata = array();
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));        
      $atype = array("office", "others");
      $shtml_list = "";
      $stype = ( in_array( $this->input->get('type'), $atype ) && $this->input->get('type') ) ? $this->input->get('type') : 'office';
      
      $sstart_date = $this->input->get('start');
      $send_date = $this->input->get('end');
      $ipage = $this->input->get('page');
      
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
      
      // Category id type
      $adata['icategory_id'] = $this->input->get("category");
      $acategory = $this->in_report_get_model->get_category_by_id($adata['icategory_id']);
      if(!$acategory) {
         $adata['icategory_id'] = "";
      }
      
      $adata['ipage'] = $ipage;
      $adata['qry_param'] = ( isset($_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != "" ) ? "&" . $_SERVER['QUERY_STRING'] : ""; 
      $adata['ilimit'] = $this->_ilimit;
      if($stype== 'office') {
         $shtml_list = $this->_get_office_equipments_list_html($adata);
      } else { 
         $shtml_list = $this->_get_others_list_html($adata);
      }
      $adata['shtml_list'] = $shtml_list;
      $adata['stype'] = $stype;
      $this->load->vars($adata);
      $this->logs->set_log("Incident Report","READ");
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("stock/in-report/incident-report-list");
      $this->template->footer();
   }
   
   private function _get_office_equipments_list_html($adata = array())
   {
      $acategory_list = array();
      $this->app->use_js(array('source' => 'stock/in-report/in-list',"cache"=>true));
      $aoffice_equipments_list= array();
      $acategory_color = array(
         'Hardware' => '#FDFF72;',
         'Accessories' => '#DDF5D5',
         'Software' => '#CB8AFF',
         'Furnitures/Appliances' => '#C5C5C5'         
      );
      
      // Get category list and total sub category
      $acategory = $this->in_report_get_model->get_category();
      // Get total rows of office equipments list
      $itotal_rows = $this->in_report_get_model->get_office_equipments_list_count();
      
      $alimit = $this->common->sql_limit( $itotal_rows, $this->_ilimit);
      // Get list of office equipments
      $aoffice_equipments = $this->in_report_get_model->get_office_equipments_list($alimit);
      
      foreach($acategory as $rows) {
         $itotal_items = $this->in_report_get_model->get_sub_category_count_by_main($rows->tsmc_smcid);
         $acategory_list[] = array(
            'id' => $rows->tsmc_smcid,
            'name' => $rows->tsmc_name,
            'total_items' => $itotal_items
         );
      }
      $i = 0;
      foreach( $aoffice_equipments as $rows ) {
         $aoffice_equipments_list[] = array(
            'row' => ( $adata['ipage'] == 1 ) ? $itotal_rows - $i : $itotal_rows - $alimit['offset'] - $i,
            'idx' => $rows->tsin_idx,
            'category_name' => $rows->tssc_name,
            'model' => $rows->tsit_model,
            'serial' => $rows->tsit_serial_number,
            'assign_to' => "{$rows->te_fname} {$rows->te_mname} {$rows->te_lname}",
            'remarks' => $rows->tsin_remarks,
            'date_purchased' => @date('Y-m-d', $rows->tsit_purchased_date),
            'date_reported' => @date('Y-m-d', $rows->tsin_date_reported)
         );
         $i++;
      }
      $adata['itotal_office'] = $this->in_report_get_model->get_office_total();
      $adata['acategory'] = $acategory_list;
      $adata['acategory_color'] = $acategory_color;
      $adata['aoffice_equipments_list'] = $aoffice_equipments_list;
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      return $this->load->view("stock/in-report/office-equipments-list", $adata, TRUE);
   }
   
   private function _get_others_list_html($adata = array())
   {
      $aothers_list = array();
      $this->app->use_js(array('source' => 'stock/in-report/in-others-list',"cache"=>true));
      $ipage = $this->input->get('page');

      $itotal_rows = $this->in_report_get_model->get_others_list_count();
      $alimit = $this->common->sql_limit($itotal_rows, $this->_ilimit);
      $aothers = $this->in_report_get_model->get_others_list($alimit);
      $i = 0;
      foreach( $aothers as $rows ) {
         $aothers_list[] = array(
            'row' => ( $adata['ipage'] == 1 ) ? $itotal_rows - $i : $itotal_rows - $alimit['offset'] - $i,
            'idx' => $rows->tsio_idx,
            'model' => $rows->tsio_model,
            'remarks' => $rows->tsio_remarks,
            'date_reported' => @date("Y-m-d", $rows->tsio_date_reported)
         );
         $i++;
      };
      $adata['aothers_list'] = $aothers_list;      
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      $adata['qry_param'] = ( isset($_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != "" ) ? "&" . $_SERVER['QUERY_STRING'] : ""; 
      return $this->load->view("stock/in-report/others-list", $adata, TRUE);
   }
   
   public function add_incident_report()
   {
      $adata = array();
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      $this->app->use_js(array('source' => 'stock/in-report/in-form',"cache"=>true));

      $adata['icategory_id'] = $this->input->get("category");
      $acategory = $this->in_report_get_model->get_category_by_id($adata['icategory_id']);
      if(!$acategory) {
         $adata['icategory_id'] = "";
      }
      $acategory = $this->in_report_get_model->get_category();
      $atype = array('office', 'others');
      $adata['stype'] = (in_array($this->input->get('type'), $atype) ) ? $this->input->get('type') : 'office';
      $adata['acategory'] = $acategory;
      
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("stock/in-report/add-incident-report");
      $this->template->footer();  
   }
   
   public function edit_incident_report()
   {
      $adata = array();
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      $this->app->use_js(array('source' => 'stock/in-report/in-form',"cache"=>true));
      
      $adata['icategory_id'] = $this->input->get("category");
      $acategory = $this->in_report_get_model->get_category_by_id($adata['icategory_id']);
      if(!$acategory) {
         $adata['icategory_id'] = "";
      }
      $stype = $this->input->get('type');
      $iidx = $this->input->get('id');
      $atype = array('office', 'others');
      if( !in_array( $stype, $atype ) || !is_numeric( $iidx ) ) {
         show_404();
      }
      $adata['iidx'] = $iidx;
      $adata['stype'] = $stype;
      $this->logs->set_log("Incident Report #{$iidx}","READ");      
      if( $stype == 'office') {
         $this->_edit_office($adata);
      } elseif( $stype=='others' ) {
         $this->_edit_others($adata);
      }      
   }
   
   private function _edit_office($adata = array())
   {
      $aoffice = $this->in_report_get_model->get_incident_office_by_id($adata['iidx']);
      if( !$aoffice ) {
         show_404();
      }
      $acategory = $this->in_report_get_model->get_category();
      $atype = array('office', 'others');
      $adata['stype'] = (in_array($this->input->get('type'), $atype) ) ? $this->input->get('type') : 'office';
      $adata['acategory'] = $acategory;
      $adata['aoffice'] = $aoffice;
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("stock/in-report/edit-incident-report-office");
      $this->template->footer(); 
      
   }
   
   private function _edit_others($adata = array())
   {
      $aothers = $this->in_report_get_model->get_incident_others_by_id( $adata['iidx']);
      if( !$aothers ) {
         show_404();
      }
      $adata['aothers'] = $aothers;
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("stock/in-report/edit-incident-report-others");
      $this->template->footer(); 
   }
}