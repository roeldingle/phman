<?php

class Photo_asset_exec_model extends CI_Model
{
   private $_TBL_PHOTO_ASSETS_LIST = 'tbl_photo_assets_list';
   private $_TBL_PHOTO_ASSETS_CATEGORY = 'tbl_photo_assets_category';
   private $_TBL_PHOTO_ASSETS_STATUS = 'tbl_photo_assets_status';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function insert_photo( $adata )
   {
      return $this->db->insert($this->_TBL_PHOTO_ASSETS_LIST, $adata);
   }
   
   public function update_photo( $adata, $iidx )
   {
      return $this->db->update($this->_TBL_PHOTO_ASSETS_LIST, $adata, "tpal_idx = '{$iidx}'");
   }
   
   public function delete_photo( $adata )
   {
      // return $this->db->update_batch($this->_TBL_PHOTO_REQUEST_LIST, $adata, 'tprl_idx'); 
      return $this->db->update_batch($this->_TBL_PHOTO_ASSETS_LIST, $adata, 'tpal_idx');
   }  
}