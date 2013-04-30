<?php

class Ip_management_exec_model extends CI_Model
{
   private $_TBL_STOCK_ASSIGN_IP = 'tbl_stock_assign_ip';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_TBL_EMPLOYEE_COMP_REC = 'tbl_employee_company_record';
   private $_TBL_DEPARTMENT = 'tbl_department';
   private $_TBL_SEATPLAN = 'tbl_seatplan';
   private $_iuseridx;
   
   public function __construct()
   {
      parent::__construct();
      $this->_iuseridx = $this->session->userdata('userid');
   }
   
   public function insert_ip($adata)
   {
      $adata['tsai_tu_idx'] = $this->_iuseridx;
      return $this->db->insert($this->_TBL_STOCK_ASSIGN_IP, $adata);
   }
   
   public function update_ip($adata, $iidx)
   {
      return $this->db->update($this->_TBL_STOCK_ASSIGN_IP, $adata, "tsai_idx = '{$iidx}'");
   }
   
   public function delete_ip($adata)
   {
      return $this->db->update_batch($this->_TBL_STOCK_ASSIGN_IP, $adata, 'tsai_idx');
   }
}