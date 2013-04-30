<?php

class Req_usage_rec extends MX_Controller
{
   private $_ilimit = 20;
   public function __construct()
   {
      parent::__construct();
      $this->load->model("photo/req_usage_get_model");
      $this->load->module('core/app');
      $this->load->module('site/template');
      $this->load->module('settings/logs');
      
      // Load photo bootstrap controller library
      $this->load->module('photo/photo_bootstrap');
      // Initialize default table values if empty
      $this->photo_bootstrap->initialize_default_tables();
      
      $this->app->use_css(array("source" => "photo/photo-extra","cache"=> true));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      // Load table.sorter.js
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));  
      $this->app->use_js(array("source" => "photo/req-usage"));
   }
   
   public function index()
   {
      $adata = array();
      $arequest_list = array();
      $itotal_rows = $this->req_usage_get_model->get_request_list_total();
      $alimit = $this->common->sql_limit( $itotal_rows, $this->_ilimit);
      $arequest_result = $this->req_usage_get_model->get_request_list($alimit);
      
      foreach( $arequest_result as $rows ) {
         $aitems = $this->req_usage_get_model->get_request_items_by_person($rows->tprl_idx);
         $arequest_list[] = array(
            'idx' => $rows->tprl_idx,
            'activity_date' => $rows->tprl_activity_date,
            'requested_by' => $rows->tprl_requested_by,
            'location' => $rows->tprl_location_shoot,
            'purpose' => $rows->tprl_purpose_theme,
            'items_list' => $aitems
         );
      }
      $adata['arequest_list'] = $arequest_list;
      $adata['ssearch'] = $this->input->get('search');
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      $this->logs->set_log("Request & Usage Records","READ");
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/request/request-usage-list");
      $this->template->footer();     
   }
   
   public function request_equipment()
   {
      $adata = array();
      $aassets = $this->req_usage_get_model->get_assets_list();
      $adata['aassets'] = $aassets;
      $adata['scategory_html'] = $this->_get_categories();
      $this->logs->set_log("Request Equipment Add Page","READ");
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/request/request-equipment");
      $this->template->footer(); 
   }
   
   public function edit_request_equipment()
   {
      $adata = array();
      $aids = array();
      $iidx = $this->input->get('id');
      
      // Check if the id is not numeric then show 404 page
      if( !is_numeric( $iidx ) ) {
         show_404();
      }
      
      $arequest = $this->req_usage_get_model->get_request_row($iidx);
      
      // Check if there is no record in the database then show 404 page
      if( !$arequest ) {
         show_404();
      }
      $aassets = $this->req_usage_get_model->get_assets_list();
      $aitems = $this->req_usage_get_model->get_request_items_by_person($iidx);
      if( $aitems ) {
         foreach( $aitems as $rows ) {
            $aids[] = $rows->tpri_tpal_idx;
         }
      }

      $adata['arequest'] = $arequest;
      $adata['iidx'] = $iidx;
      $adata['aassets'] = $aassets;
      $adata['scategory_html'] = $this->_get_categories_by_user($aids);
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/request/edit-request-equipment");
      $this->template->footer();    
   }

   private function _get_categories_by_user($aids = array())
   {
      $adata = array();
      $acategory_list = array();
      $acategory = $this->req_usage_get_model->get_category();
      foreach( $acategory as $rows ) {
         $aassets_list = $this->req_usage_get_model->get_photo_assets_by_id( $rows->tpac_idx );
         $acategory_list[] = array(
            'category' => $rows->tpac_category,
            'sub_list' => $aassets_list
         );
      }
      $adata['aids'] = $aids;
      $adata['acategory_list'] = $acategory_list;
      $this->load->vars($adata);
      return $this->load->view("photo/request/user-categories-html", "", TRUE);
   }   
   
   private function _get_categories()
   {
      $adata = array();
      $acategory_list = array();
      $acategory = $this->req_usage_get_model->get_category();
      foreach( $acategory as $rows ) {
         $aassets_list = $this->req_usage_get_model->get_photo_assets_by_id( $rows->tpac_idx );
         $acategory_list[] = array(
            'category' => $rows->tpac_category,
            'sub_list' => $aassets_list
         );
      }
      $adata['acategory_list'] = $acategory_list;
      $this->load->vars($adata);
      return $this->load->view("photo/request/categories-html", "", TRUE);
   }
}