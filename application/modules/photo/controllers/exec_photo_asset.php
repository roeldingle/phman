<?php

class Exec_photo_asset extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model("photo/photo_asset_get_model");
      $this->load->model("photo/photo_asset_exec_model");
      $this->load->module('settings/logs');
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function save_photo()
   {
      $icategory = $this->input->post('category');
      $sitem = $this->input->post('item');
      $sdescription = $this->input->post('description');
      $sremarks = $this->input->post('remarks');
      $istatus = $this->input->post('status');
      
      $acategory = $this->photo_asset_get_model->get_category_row( $icategory );
      $astatus = $this->photo_asset_get_model->get_status_row( $icategory );
      
      if( $acategory && $astatus ) {
         if( !$icategory || !$sitem || !$sdescription || !$sremarks || !$istatus ) {
            show_404();
         } else {
            $adata = array(
               'tpal_tpac_idx' => $icategory,
               'tpal_item_name' => $sitem,
               'tpal_description' => $sdescription,
               'tpal_remarks' => $sremarks,
               'tpal_tpas_idx' => $istatus,
               'tpal_date_updated' => time(),
               'tpal_date_created' => time(),
               'tpal_active' => 1,
            );
            $bresult = $this->photo_asset_exec_model->insert_photo( $adata );         
            if( $bresult ) {
               $this->logs->set_log("New Photo Asset","CREATE");
               $this->common->set_message("Record has been saved succesfully!", "photo-message", "success");
            } else  {
               $this->common->set_message("Record has been saved succesfully!", "photo-message", "success");            
            }
            redirect(base_url() . 'photo/photo_assets');
         }
      } else {
         show_404();
      }
   }
   
   public function edit_photo()
   {
   
      $iidx = $this->input->post('id');
      
      // Check if the id is not numeric then show 404 page
      if( !is_numeric( $iidx ) ) {
         show_404();
      }
      $aasset = $this->photo_asset_get_model->get_photo_asset_row( $iidx );
      
      // Check if there is no record in the database then show 404 page
      if( !$aasset ) {
         show_404();
      }
      
      $icategory = $this->input->post('category');
      $sitem = $this->input->post('item');
      $sdescription = $this->input->post('description');
      $sremarks = $this->input->post('remarks');
      $istatus = $this->input->post('status');
      
      $acategory = $this->photo_asset_get_model->get_category_row( $icategory );
      $astatus = $this->photo_asset_get_model->get_status_row( $icategory );
      
      if( $acategory && $astatus ) {
         if( !$iidx || !$icategory || !$sitem || !$sdescription || !$sremarks || !$istatus ) {
            show_404();
         } else {
            $adata = array(
               'tpal_tpac_idx' => $icategory,
               'tpal_item_name' => $sitem,
               'tpal_description' => $sdescription,
               'tpal_remarks' => $sremarks,
               'tpal_tpas_idx' => $istatus,
               'tpal_date_updated' => time()
            );
            $bresult = $this->photo_asset_exec_model->update_photo( $adata, $iidx );         
            if( $bresult ) {
               $this->logs->set_log("Photo Asset #{$iidx}","UPDATE");
               $this->common->set_message("Record has been updated succesfully!", "photo-message", "success");
            } else  {
               $this->common->set_message("Record has been saved succesfully!", "photo-message", "success");            
            }
            redirect($_SERVER['HTTP_REFERER']);
         }
      } else {
         show_404();
      }
   }
}