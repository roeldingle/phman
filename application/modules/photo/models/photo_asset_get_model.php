<?php

class Photo_asset_get_model extends CI_Model
{
   private $_TBL_PHOTO_ASSETS_LIST = 'tbl_photo_assets_list';
   private $_TBL_PHOTO_ASSETS_CATEGORY = 'tbl_photo_assets_category';
   private $_TBL_PHOTO_ASSETS_STATUS = 'tbl_photo_assets_status';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_photo_assets_list($alimit)
   {
      $this->db->select("*");
      $this->db->from("{$this->_TBL_PHOTO_ASSETS_LIST} AS tpal");
      $this->db->join("{$this->_TBL_PHOTO_ASSETS_CATEGORY} AS tpac", "tpal.tpal_tpac_idx = tpac.tpac_idx","INNER");
      $this->db->join("{$this->_TBL_PHOTO_ASSETS_STATUS} AS tpas", "tpal.tpal_tpas_idx = tpas.tpas_idx","INNER");
      if( $this->input->get('search') ) {
         $this->db->like("CONCAT(tpac.tpac_category, ' ', tpal.tpal_item_name,' ', tpal.tpal_description, ' ', tpas.tpas_status)", $this->input->get('search'));
      }
      $this->db->where("tpal.tpal_active", 1);
      $this->db->order_by("tpal.tpal_date_created", "DESC");
      $this->db->limit($alimit['limit'], $alimit['offset']);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_photo_asset_row($iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_LIST);
      $this->db->where("tpal_idx", $iidx );
      $this->db->where("tpal_active", 1);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_photo_assets_list_total()
   {
      $this->db->select("COUNT(*) AS total_rows");
      $this->db->from("{$this->_TBL_PHOTO_ASSETS_LIST} AS tpal");
      $this->db->join("{$this->_TBL_PHOTO_ASSETS_CATEGORY} AS tpac", "tpal.tpal_tpac_idx = tpac.tpac_idx","INNER");
      $this->db->join("{$this->_TBL_PHOTO_ASSETS_STATUS} AS tpas", "tpal.tpal_tpas_idx = tpas.tpas_idx","INNER");
      if( $this->input->get('search') ) {
         $this->db->like("CONCAT(tpac.tpac_category, ' ', tpal.tpal_item_name,' ', tpal.tpal_description, ' ', tpas.tpas_status)", $this->input->get('search'));
      }
      $this->db->where("tpal.tpal_active", 1);
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_category()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_CATEGORY);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_category_row( $iidx )
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_CATEGORY);
      $this->db->where("tpac_idx", $iidx);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_status()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_STATUS);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_status_row( $iidx )
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_STATUS);
      $this->db->where("tpas_idx", $iidx);
      $query = $this->db->get();
      return $query->row();
   }
}