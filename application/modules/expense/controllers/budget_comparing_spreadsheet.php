<?php

class Budget_comparing_spreadsheet extends MX_Controller
{
   private $module_name ='expense';
   
   public function __construct()
   {
      parent::__construct();
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->model("bcomparing_view_model");
      $this->app->use_js(array("source"=>"expense/budget_comparing/detailed_view"));      
      $this->app->use_js(array("source"=>"expense/budget_planning/calendar_month_year"));
      
      $this->app->use_js(array("source"=>"expense/defaults"));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      
      $this->load->module("expense/expense_common");
      $this->load->module('settings/logs');      
   }
   
   public function index()
   {
      $this->logs->set_log("Budget Comparing Spread Sheet View Page","READ");
      $this->template->header();
      $this->expense_common->sidebar();
      $this->template->breadcrumbs();
      
      /*Get total planned -- Insert total planned to another table*/
      $aTotalPlanned = $this->bcomparing_view_model->get_budget_planning(); 
      
      $adata = array();
      $itotal_row = $this->bcomparing_view_model->get_count();
      $adata['ilimit'] = (!empty($_GET['row'])) ? $_GET['row'] : 5; 
      $alimit = $this->common->sql_limit($itotal_row,$adata['ilimit']);
      $adata['limit'] = $alimit['limit'];
      $adata['offset'] = $alimit['offset'];
      $adata['pager'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));       
      $adata['alists'] = $this->bcomparing_view_model->get_months($alimit); 
      $adata['keyword'] = $this->input->get('keyword');  
      $adata['date_to'] = (!empty($_GET['to']) && !empty($_GET['from'])) ? $_GET['to'] : ""; 
      $adata['date_from'] = (!empty($_GET['from']) && !empty($_GET['to'])) ? $_GET['from'] : ""; 
      $adata['sort'] = $this->input->get('sort');  
      $adata['today'] = date('F Y');
      
      foreach($adata['alists'] as $kmonth=>$vmonth){        
        $adata['alists'][$kmonth]->lists = $this->bcomparing_view_model->get_lists($vmonth->tel_month, $vmonth->tel_year);
        /*Number format*/
        $adata['alists'][$kmonth]->difference = number_format($adata['alists'][$kmonth]->difference, 2, '.', ',');
        $adata['alists'][$kmonth]->planned_budget = number_format($adata['alists'][$kmonth]->planned_budget, 2, '.', ',');
        $adata['alists'][$kmonth]->total = number_format($adata['alists'][$kmonth]->total, 2, '.', ',');
      }
      
      $this->app->content($this->module_name . '/budget_comparing/spreadsheet_view', $adata);
      $this->template->footer();
   }  
}