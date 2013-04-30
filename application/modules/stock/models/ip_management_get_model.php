<?php

class Ip_management_get_model extends CI_Model
{
   private $_TBL_STOCK_ASSIGN_IP = 'tbl_stock_assign_ip';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_TBL_EMPLOYEE_COMP_REC = 'tbl_employee_company_record';
   private $_TBL_DEPARTMENT = 'tbl_department';
   private $_TBL_SEATPLAN = 'tbl_seatplan';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_ip_list( $alimit )
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_ASSIGN_IP);
      $this->db->join($this->_TBL_EMPLOYEE, "tsai_te_idx = te_idx", "INNER");
      $this->db->join($this->_TBL_EMPLOYEE_COMP_REC, "te_idx = tecr_te_idx", "INNER");
      $this->db->join($this->_TBL_DEPARTMENT, "td_idx = tecr_td_idx", "LEFT");
      $this->db->join($this->_TBL_SEATPLAN, "te_idx = ts_te_idx", "LEFT");
      
      if($this->input->get("search")){
         $this->db->like("CONCAT(te_fname,' ', te_lname)", $this->input->get("search"));
      }
   
      if( $this->input->get('department') ) {
         $this->db->where("td_idx", $this->input->get('department'));
      }  
      
      if( valid_date($this->input->get('start'), 'Y-m-d') && valid_date($this->input->get('end'), 'Y-m-d') ) {
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsai_date_created),"%Y-%m-%d") >=', $this->input->get('start'));
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsai_date_created),"%Y-%m-%d") <=', $this->input->get('end'));      
      }
      
      $this->db->where('te_active', 1);
      $this->db->where('tsai_active', 1);
      $this->db->limit($alimit['limit'], $alimit['offset']);
      $this->db->order_by("tsai_date_created", "DESC");
      $query = $this->db->get();
      return $query->result();   
   }
   
   public function get_ip_list_count()
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from($this->_TBL_STOCK_ASSIGN_IP);
      $this->db->join($this->_TBL_EMPLOYEE, "tsai_te_idx = te_idx", "INNER");
      $this->db->join($this->_TBL_EMPLOYEE_COMP_REC, "te_idx = tecr_te_idx", "INNER");
      $this->db->join($this->_TBL_DEPARTMENT, "td_idx = tecr_td_idx", "LEFT");
      $this->db->join($this->_TBL_SEATPLAN, "te_idx = ts_te_idx", "LEFT");
      
      if($this->input->get("search")){
         $this->db->like("CONCAT(te_fname,' ', te_lname)", $this->input->get("search"));
      }
   
      if( $this->input->get('department') ) {
         $this->db->where("td_idx", $this->input->get('department'));
      }  
      
      if( valid_date($this->input->get('start'), 'Y-m-d') && valid_date($this->input->get('end'), 'Y-m-d') ) {
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsai_date_created),"%Y-%m-%d") >=', $this->input->get('start'));
         $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tsai_date_created),"%Y-%m-%d") <=', $this->input->get('end'));      
      }
      
      $this->db->where('te_active', 1);
      $this->db->where('tsai_active', 1);
      $query = $this->db->get();
      return $query->row()->total_rows;   
   }
   
   public function get_ip_by_id($iidx)
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_ASSIGN_IP);
      $this->db->where("tsai_idx", $iidx);
      $this->db->where("tsai_active", 1);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_employees()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_EMPLOYEE);
      $this->db->join($this->_TBL_EMPLOYEE_COMP_REC, "te_idx = tecr_te_idx", "INNER");
      $this->db->join($this->_TBL_DEPARTMENT, "td_idx = tecr_td_idx", "LEFT");
      $this->db->join($this->_TBL_SEATPLAN, "te_idx = ts_te_idx", "LEFT");
      $this->db->where('te_active', 1);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_department()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_DEPARTMENT);
      $query = $this->db->get();
      return $query->result();   
   }
}