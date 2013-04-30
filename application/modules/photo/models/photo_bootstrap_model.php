<?php

class Photo_bootstrap_model extends CI_Model
{
   private $_TBL_PHOTO_ASSETS_LIST = 'tbl_photo_assets_list';
   private $_TBL_PHOTO_ASSETS_CATEGORY = 'tbl_photo_assets_category';
   private $_TBL_PHOTO_ASSETS_STATUS = 'tbl_photo_assets_status';
   private $_TBL_PHOTO_REQUEST_LIST = 'tbl_photo_request_list';
   private $_TBL_PHOTO_REQUEST_ITEMS = 'tbl_photo_request_items';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function insert_category_defaults()
   {
      $adefaults = array(
         array('tpac_category' => "Camera"),
         array('tpac_category' => "Camera Accessories"),
         array('tpac_category' => "Studio Equipment"),
         array('tpac_category' => "Accessories")
      );
      $this->db->insert_batch($this->_TBL_PHOTO_ASSETS_CATEGORY, $adefaults);
   }
   
   public function get_category()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_CATEGORY);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function insert_status_defaults()
   {
      $adefaults = array(
         array('tpas_status' => "Available"),
         array('tpas_status' => "In Use"),
         array('tpas_status' => "Consumable"),
         array('tpas_status' => "Under Repair"),
         array('tpas_status' => "Disposed")
      );
      $this->db->insert_batch($this->_TBL_PHOTO_ASSETS_STATUS, $adefaults);
   }
   
   public function get_status()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_STATUS);
      $query = $this->db->get();
      return $query->result();
   }
}