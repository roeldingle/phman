<?php

class In_report_exec_model extends CI_Model
{
   private $_TBL_STOCK_ITEM = 'tbl_stock_item';
   private $_TBL_STOCK_MAIN_CATEGORY = 'tbl_stock_main_category';
   private $_TBL_STOCK_SUB_CATEGORY = 'tbl_stock_sub_category';
   private $_TBL_STOCK_INCIDENT = 'tbl_stock_incident';
   private $_TBL_STOCK_INCIDENT_OTHER = 'tbl_stock_incident_other';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_iuseridx;
   
   public function __construct()
   {
      parent::__construct();
      $this->_iuseridx = $this->session->userdata('userid');
      $this->load->module("site/template");
   }
   
   public function insert_incident_report($adata)
   {
      $adata['tsin_tu_idx'] = $this->_iuseridx;
      return $this->db->insert($this->_TBL_STOCK_INCIDENT, $adata);
   }
   
   public function insert_incident_others($adata)
   {
      $adata['tsio_tu_idx'] = $this->_iuseridx;
      return $this->db->insert($this->_TBL_STOCK_INCIDENT_OTHER, $adata);
   }
   
   
   public function delete_office_equipments( $adata )
   {
      return $this->db->update_batch($this->_TBL_STOCK_INCIDENT, $adata, 'tsin_idx');
   }
   
   public function delete_others( $adata )
   {
      return $this->db->update_batch($this->_TBL_STOCK_INCIDENT_OTHER, $adata, 'tsio_idx');
   }
   
   public function update_office($adata, $iidx)
   {
      return $this->db->update($this->_TBL_STOCK_INCIDENT, $adata, "tsin_idx = '{$iidx}'");
   }
   public function update_others($adata, $iidx)
   {
      return $this->db->update($this->_TBL_STOCK_INCIDENT_OTHER, $adata, "tsio_idx = '{$iidx}'");
   }   
}