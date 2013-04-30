<?php

class Seat_plan extends MX_Controller
{
   private $module_name ='hr';

   public function __construct()
   {
      
      parent::__construct();
      $this->load->model("seatplan_model");
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->module('settings/logs');
      $this->app->use_js(array("source"=>"site/libs/jquery.Jcrop","cache"=>false));
      $this->app->use_js(array("source"=>$this->module_name ."/seatplan/setcoords","cache"=>false));
      $this->app->use_js(array("source"=>$this->module_name ."/seatplan/seat_plan","cache"=>false));
      $this->app->use_css(array("source"=>$this->module_name ."/jquery.Jcrop","cache"=>false));
      $this->app->use_css(array("source"=>$this->module_name ."/seatplan","cache"=>false));
   }

    public function index()
    { 
      $adata = array();
      $adata['aresult'] = $this->seatplan_model->get_data_seatplan_src();
      
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      if(isset($_POST['viewas'])){
         if($_POST['viewas']=='vcoords'){
            $this->app->content($this->module_name .'/seatplan/seatplan_jcrop',$adata);
         }else{
            $this->app->content($this->module_name .'/seatplan/seatplan',$adata);
         }
      }else{
            $this->app->content($this->module_name .'/seatplan/seatplan',$adata);
      }
      $this->template->footer();
      $this->logs->set_log("Seat Plan","READ");  
    }
    
    public function Map()
   {
      if($this->environment->module_path .'seat_plan/view_map'==isset($_SERVER["HTTP_REFERER"]))
      {
         $this->app->use_js(array("source"=>$this->module_name . "/seatplan/ready_tag","cache"=>false));
         $this->app->use_js(array("source"=>$this->module_name . "/seatplan/map_functions","cache"=>false));
         $this->app->use_css(array("source"=>$this->module_name . "/seatplan","cache"=>false));
         $adata = array();
         $adata['title'] = "View Map";
         $adata['module_name'] = $this->module_name;
         $adata['aresult'] = $adata['aresult'] = $this->seatplan_model->get_data_seatplan_src();
         $adata['acoords'] = $this->seatplan_model->get_data_seatplan_coordinates();
         $this->app->header($this->module_name . '/seatplan/map_template/map_header',$adata);
         $this->app->content($this->module_name . '/seatplan/map_template/map_body',$adata);
         $this->app->footer($this->module_name . '/seatplan/map_template/map_footer',$adata);
         
      }else{
         show_404();
      }      
   }



}
