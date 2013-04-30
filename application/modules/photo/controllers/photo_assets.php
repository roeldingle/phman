<?php

class Photo_assets extends MX_Controller
{
   private $_ilimit = 20;
   public function __construct()
   {
      parent::__construct();
      $this->load->model("photo/photo_asset_get_model");
      $this->load->module('core/app');
      $this->load->module('site/template');
      $this->load->module('settings/logs');
      
      // Load photo bootstrap controller library
      $this->load->module('photo/photo_bootstrap');
      // Initialize default table values if empty
      $this->photo_bootstrap->initialize_default_tables();
      
      $this->app->use_css(array("source" => "photo/photo-extra","cache"=>true));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      // Load table.sorter.js
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>true));  
      $this->app->use_js(array("source" => "photo/photo-asset","cache"=>true));
   }
   
   public function index()
   {
      $adata = array();
      $aphoto_assets_list = array();
      $itotal_rows = $this->photo_asset_get_model->get_photo_assets_list_total();
      $alimit = $this->common->sql_limit( $itotal_rows, $this->_ilimit);
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
      $adata['ssearch'] = $this->input->get('search');
      $adata['pager'] = $this->common->pager( $itotal_rows, $this->_ilimit, array('active_class'=>'current'));
      $this->logs->set_log("Photo Assets List","READ");
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/photo/photo-assets-list");
      $this->template->footer();     
   }
   
   public function add_photo_asset()
   {
      $adata = array();
      $acategory = $this->photo_asset_get_model->get_category();
      $astatus = $this->photo_asset_get_model->get_status();
      
      $adata['acategory'] = $acategory;
      $adata['astatus'] = $astatus;
      $this->logs->set_log("Photo Asset Add Page","READ");
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/photo/add-photo-asset");
      $this->template->footer();  
   }
   
   public function edit_photo_asset()
   {
      $adata = array();
      $iidx = $this->input->get('id');
      
      // Check if the id is not numeric then show 404 page
      if( !is_numeric( $iidx ) ) {
         show_404();
      }
      
      $aasset = $this->photo_asset_get_model->get_photo_asset_row( $iidx );
      
      // Check if there is no record in the database then show 404 page
      if( !$aasset ) {
         show_404();
      }
      
      $acategory = $this->photo_asset_get_model->get_category();
      $astatus = $this->photo_asset_get_model->get_status();
      
      $adata['iidx'] = $iidx;
      $adata['aasset'] = $aasset;
      $adata['acategory'] = $acategory;
      $adata['astatus'] = $astatus;
      
      $this->load->vars($adata);
      $this->template->header();
      $this->template->sidebar();
      $this->template->breadcrumbs();
      $this->app->content("photo/photo/edit-photo-asset");
      $this->template->footer();  
   }   
}