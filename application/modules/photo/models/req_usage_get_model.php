<?php

class Req_usage_get_model extends CI_Model
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
   
   public function get_request_list($alimit)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_REQUEST_LIST);
      if( $this->input->get('search') ) {
         $this->db->like("CONCAT(tprl_activity_date, ' ', tprl_requested_by,' ', tprl_location_shoot, ' ', tprl_purpose_theme, ' ', tprl_returned_date)", $this->input->get('search'));
      }
      $this->db->where("tprl_active", 1);
      $this->db->order_by('tprl_date_created','DESC');
      $this->db->limit($alimit['limit'], $alimit['offset']);
      $query = $this->db->get();
      return $query->result();  
   }
   
   public function get_request_list_total()
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from($this->_TBL_PHOTO_REQUEST_LIST);
      if( $this->input->get('search') ) {
         $this->db->like("CONCAT(tprl_activity_date, ' ', tprl_requested_by,' ', tprl_location_shoot, ' ', tprl_purpose_theme, ' ', tprl_returned_date)", $this->input->get('search'));
      }
      $this->db->where("tprl_active", 1);
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_request_items_by_person($iidx)
   {
      $this->db->select("*");
      $this->db->from("{$this->_TBL_PHOTO_REQUEST_ITEMS} AS tpri");
      $this->db->join("{$this->_TBL_PHOTO_ASSETS_LIST} AS tpal", "tpri.tpri_tpal_idx = tpal_idx","INNER");
      $this->db->where("tpri_tprl_idx", $iidx);
      $this->db->where("tpal_active", 1);
      $query = $this->db->get();
      return $query->result();
   }   
   
   public function get_category()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_CATEGORY);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_assets_list()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_LIST);
      $this->db->where("tpal_active", 1);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_photo_assets_by_id( $iidx )
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_ASSETS_LIST);
      $this->db->where("tpal_tpac_idx", $iidx);
      $this->db->where("tpal_active", 1);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_request_row($iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_PHOTO_REQUEST_LIST);
      $this->db->where("tprl_idx", $iidx);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_request_items_by_person_category($iidx, $icat_idx)
   {
      $this->db->select("*");
      $this->db->from("{$this->_TBL_PHOTO_REQUEST_ITEMS} AS tpri");
      $this->db->join("{$this->_TBL_PHOTO_ASSETS_LIST} AS tpal", "tpri.tpri_tpal_idx = tpal.tpal_idx", "INNER" );
      $this->db->where("tpri.tpri_tprl_idx", $iidx);
      $this->db->where("tpal.tpal_tpac_idx", $icat_idx);
      $this->db->where("tpal.tpal_active", 1);
      $query = $this->db->get();
      return $query->result();
   }
}