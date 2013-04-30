<?php

class Position extends MX_Controller
{
   private $module_name ='hr';

   public function __construct()
   {
      parent::__construct();
      $this->load->model("position_model");
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->module('settings/logs');
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>false));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate","cache"=>false));
      $this->app->use_js(array("source"=>$this->module_name . "/position/position_list","cache"=>false));
   }
   
   public function index()
   {
         $adata = array();
         $adata['title'] = "Hr Management | Position";
         $adata['module_name'] = $this->module_name;

         $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
         $RowPerPage = isset($_GET['row']) ? $_GET['row'] : '10';
         $Curpage = isset($_GET['page']) ? $_GET['page']:'1';
         
         $oresult = $this->position_model->getList($keyword,$RowPerPage,$Curpage);
         $adata['lists'] = $oresult->result();
         

         $itotal_row =  $this->position_model->getTotal_rows($keyword);
         $alimit = $this->common->sql_limit($itotal_row,$RowPerPage);
         $adata['pager'] = $this->common->pager($itotal_row,$RowPerPage,array('active_class'=>'current'));
         
         $this->template->header();
         $this->template->sidebar();
         $this->template->breadcrumbs();
         $this->app->content($this->module_name . '/position/index',$adata);
         $this->template->footer();
         $this->logs->set_log("Position List","READ");           
   }

}