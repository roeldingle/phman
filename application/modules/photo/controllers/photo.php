<?php

class Photo extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model("photo/req_usage_get_model");
      $this->load->model("photo/photo_asset_get_model");
      $this->load->module('core/app');
      $this->load->module('site/template');

      // Load photo bootstrap controller library
      $this->load->module('photo/photo_bootstrap');
      // Initialize default table values if empty
      $this->photo_bootstrap->initialize_default_tables();
      
      $this->app->use_css(array("source" => "photo/photo-extra","cache"=>true));
      // Load table.sorter.js
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));  
      $this->app->use_js(array("source" => "photo/summary","cache"=>true));
   }
   
   public function index()
   {
      $adata = array();
      $adata['html_request_usage_list'] = $this->_get_request_usage_list_html();
      $adata['html_photo_assets_list'] = $this->_get_photo_assets_list_html();
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/summary/summary-list.php");
      $this->template->footer();     
   }
   
   private function _get_request_usage_list_html()
   {
      $adata = array();
      $arequest_list = array();
      $alimit = array("limit" => 10, "offset" => 0);
      $arequest = $this->req_usage_get_model->get_request_list( $alimit );
      if( $arequest ) {
         foreach( $arequest as $rows ) {
            $aitems = $this->req_usage_get_model->get_request_items_by_person($rows->tprl_idx);
            $arequest_list[] = array(
               'idx' => $rows->tprl_idx,
               'activity_date' => date("Y-m-d", $rows->tprl_activity_date),
               'requested_by' => $rows->tprl_requested_by,
               'location' => $rows->tprl_location_shoot,
               'purpose' => $rows->tprl_purpose_theme,
               'returned_date' => $rows->tprl_returned_date,
               'aitems' => $aitems
            );
         }
      }
      $adata['arequest_list'] = $arequest_list;
      return $this->load->view("photo/summary/request-usage-list", $adata, TRUE);
   }
   
   
   private function _get_photo_assets_list_html()
   {
      $adata = array();
      $aphoto_assets_list = array();
      $alimit = array("limit" => 10, "offset" => 0);
      
      $aphoto_assets_result = $this->photo_asset_get_model->get_photo_assets_list($alimit);
      foreach( $aphoto_assets_result as $rows ) {
         $aphoto_assets_list[] = array(
            'idx' => $rows->tpal_idx,
            'category' => $rows->tpac_category,
            'item_name' => $rows->tpal_item_name,
            'description' => $rows->tpal_description,
            'status' => $rows->tpas_status
         );
      }
      $adata['aphoto_assets_list'] = $aphoto_assets_list;
      return $this->load->view("photo/summary/photo-assets-list", $adata, TRUE);
   }
}