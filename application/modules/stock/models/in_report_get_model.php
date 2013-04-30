<?php

class In_report_get_model extends CI_Model
{
   private $_TBL_STOCK_ITEM = 'tbl_stock_item';
   private $_TBL_STOCK_MAIN_CATEGORY = 'tbl_stock_main_category';
   private $_TBL_STOCK_SUB_CATEGORY = 'tbl_stock_sub_category';
   private $_TBL_STOCK_INCIDENT = 'tbl_stock_incident';
   private $_TBL_STOCK_INCIDENT_OTHER = 'tbl_stock_incident_other';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_office_equipments_list($alimit)
   {
      $this->db->select("*");
      $this->db->from("{$this->_TBL_STOCK_INCIDENT} AS tsi_incident");
      $this->db->join("{$this->_TBL_STOCK_ITEM} AS tsi_stock","tsi_incident.tsin_tsit_siid = tsi_stock.tsit_siid", "LEFT");
      $this->db->join("{$this->_TBL_STOCK_SUB_CATEGORY} AS tssc","tssc.tssc_sscid = tsi_stock.tsit_tssc_sscid", "LEFT");
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te","tsi_stock.tsit_user_assigned = te.te_idx", "LEFT");
      
      if( $this->input->get('search') ) {
         $this->db->like("CONCAT(tsit_serial_number, ' ' , tsit_model )", $this->input->get('search'));
      }
      if( $this->input->get('category') ) {
         $this->db->where("tssc_tsmc_smcid", $this->input->get('category'));
      }      
      $this->db->where("tsin_active", 1);
      
      if( valid_date($this->input->get('start'), 'Y-m-d') && valid_date($this->input->get('end'), 'Y-m-d') ) {
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsin_date_reported),"%Y-%m-%d") >=', $this->input->get('start'));
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsin_date_reported),"%Y-%m-%d") <=', $this->input->get('end'));      
      }
      
      $this->db->limit($alimit['limit'], $alimit['offset']);
      $this->db->order_by("tsin_date_created", "DESC");
      $query = $this->db->get();
      return $query->result();      
   }
   
   public function get_office_equipments_list_count()
   {
      $this->db->select("COUNT(*) AS total_rows");
      $this->db->from("{$this->_TBL_STOCK_INCIDENT} AS tsi_incident");
      $this->db->join("{$this->_TBL_STOCK_ITEM} AS tsi_stock","tsi_incident.tsin_tsit_siid = tsi_stock.tsit_siid", "LEFT");
      $this->db->join("{$this->_TBL_STOCK_SUB_CATEGORY} AS tssc","tssc.tssc_sscid = tsi_stock.tsit_tssc_sscid", "LEFT");
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te","tsi_stock.tsit_user_assigned = te.te_idx", "LEFT");
      
      if( $this->input->get('search') ) {
         $this->db->like("CONCAT(tsit_serial_number, ' ' , tsit_model )", $this->input->get('search'));
      }
      if( $this->input->get('category') ) {
         $this->db->where("tssc_tsmc_smcid", $this->input->get('category'));
      }
      
      if( valid_date($this->input->get('start'), 'Y-m-d') && valid_date($this->input->get('end'), 'Y-m-d') ) {
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsin_date_reported),"%Y-%m-%d") >=', $this->input->get('start'));
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsin_date_reported),"%Y-%m-%d") <=', $this->input->get('end'));      
      }
      $this->db->where("tsin_active", 1);
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_stock_by_id($iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_ITEM);
      $this->db->where("tsit_siid", $iidx);
      $this->db->where("tsit_active", 1);
      $query = $this->db->get();      
      return $query->result();      
   }
   
   public function get_category()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_MAIN_CATEGORY);
      $this->db->where('tsmc_active', 1);
      $query = $this->db->get();      
      return $query->result();
   }
   
   public function get_category_by_id($iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_MAIN_CATEGORY);
      $this->db->where('tsmc_smcid', $iidx);
      $this->db->where('tsmc_active', 1);
      $query = $this->db->get();      
      return $query->result();
   }
   
   public function get_sub_category_count_by_main($iidx)
   {
      $this->db->select("COUNT(*) AS total_rows");
      $this->db->from($this->_TBL_STOCK_SUB_CATEGORY);
      $this->db->join($this->_TBL_STOCK_INCIDENT,"tssc_sscid = tsin_sscid", "LEFT");
      $this->db->where('tssc_tsmc_smcid', $iidx);
      $this->db->where('tsin_active', 1);
      $query = $this->db->get();      
      return $query->row()->total_rows;      
   }
   
   public function get_office_total()
   {
      $this->db->select("COUNT(*) AS total_rows");
      $this->db->from($this->_TBL_STOCK_INCIDENT);
      $this->db->where('tsin_active', 1);
      $query = $this->db->get();      
      return $query->row()->total_rows;      
   }   
   
   public function get_sub_category_by_id( $iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_SUB_CATEGORY);
      $this->db->where("tssc_tsmc_smcid", $iidx);
      $this->db->where("tssc_active", 1);
      $query = $this->db->get();      
      return $query->result();
   }
   
   public function get_sub_category_by_main_id( $iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_SUB_CATEGORY);
      $this->db->where("tssc_sscid", $iidx);
      $this->db->where("tssc_active", 1);
      $query = $this->db->get();      
      return $query->result();
   }
      
   public function get_stock_item_by_sub_id( $iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_ITEM);
      $this->db->where("tsit_tssc_sscid", $iidx);
      $this->db->where("tsit_active", 1);
      $query = $this->db->get();      
      return $query->result();
   }
   
   public function get_employee_by_id($iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_EMPLOYEE);
      $this->db->where("te_idx", $iidx );
      $this->db->where("te_active", 1 );
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_others_list($alimit)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_INCIDENT_OTHER);
      if( valid_date($this->input->get('start'), 'Y-m-d') && valid_date($this->input->get('end'), 'Y-m-d') ) {
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsio_date_reported),"%Y-%m-%d") >=', $this->input->get('start'));
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsio_date_reported),"%Y-%m-%d") <=', $this->input->get('end'));      
      }
      $this->db->where("tsio_active", 1 );
      $this->db->limit($alimit['limit'], $alimit['offset']);
      $this->db->order_by("tsio_date_created","DESC");
      $query = $this->db->get();
      return $query->result();  
   }
   
   public function get_others_list_count()
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from($this->_TBL_STOCK_INCIDENT_OTHER);
      if( valid_date($this->input->get('start'), 'Y-m-d') && valid_date($this->input->get('end'), 'Y-m-d') ) {
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsio_date_reported),"%Y-%m-%d") >=', $this->input->get('start'));
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsio_date_reported),"%Y-%m-%d") <=', $this->input->get('end'));      
      }      
      $this->db->where("tsio_active", 1 );
      $query = $this->db->get();
      return $query->row()->total_rows;  
   }
   
   public function get_incident_office_by_id( $iidx )
   {  
      $this->db->select("*");
      $this->db->from("{$this->_TBL_STOCK_INCIDENT} AS tsi_incident");
      $this->db->join("{$this->_TBL_STOCK_ITEM} AS tsi_stock","tsi_incident.tsin_tsit_siid = tsi_stock.tsit_siid", "LEFT");
      $this->db->join("{$this->_TBL_STOCK_SUB_CATEGORY} AS tssc","tssc.tssc_sscid = tsi_stock.tsit_tssc_sscid", "LEFT");
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te","tsi_stock.tsit_user_assigned = te.te_idx", "LEFT");
      $this->db->where("tsin_idx", $iidx);      
      $this->db->where("tsin_active", 1);      
      $query = $this->db->get();
      return $query->row();     
   }
   
   public function get_incident_others_by_id( $iidx )
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_INCIDENT_OTHER);
      $this->db->where("tsio_idx", $iidx );
      $this->db->where("tsio_active", 1 );
      $query = $this->db->get();
      return $query->row();  
   }
}