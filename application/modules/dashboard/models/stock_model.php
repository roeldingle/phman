<?php

class Stock_model extends MX_Controller
{
   private $_TBL_USER = 'tbl_user';
   private $_TBL_USER_GRADE = 'tbl_user_grade';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_TBL_EMP_COMP_REC = 'tbl_employee_company_record';
   private $_TBL_DEPARTMENT = 'tbl_department';
   private $_TBL_LOGS = 'tbl_logs';
   private $_TBL_POSITION = 'tbl_position';
   private $_TBL_STOCK_MAIN_CATEGORY = 'tbl_stock_main_category';
   private $_TBL_STOCK_SUB_CATEGORY = 'tbl_stock_sub_category';
   private $_TBL_STOCK_ITEM = 'tbl_stock_item';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_main_category()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_STOCK_MAIN_CATEGORY);
      $query = $this->db->get();
      return $query->result();
   }

   public function get_sub_category( $iidx, $alimit )
   {
      $this->db->select("tssc.tssc_name AS tssc_name,COUNT(tsi.tsit_siid) AS total_item");
      $this->db->from("{$this->_TBL_STOCK_SUB_CATEGORY} AS tssc");
      $this->db->join("{$this->_TBL_STOCK_ITEM} AS tsi","tssc.tssc_sscid = tsi.tsit_tssc_sscid","LEFT");
      $this->db->where('tssc.tssc_tsmc_smcid', $iidx );
      $this->db->where('tsi.tsit_active', 1 );
      $this->db->group_by('tssc.tssc_sscid');
      $this->db->order_by('tssc.tssc_name ASC');
      $this->db->limit($alimit['limit'],$alimit['offset'] );
      $query = $this->db->get();
      return $query->result();
   }   
}