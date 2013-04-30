<?php

class Req_usage_exec_model extends CI_MODEL
{
   private $_TBL_PHOTO_REQUEST_LIST = 'tbl_photo_request_list';
   private $_TBL_PHOTO_REQUEST_ITEMS = 'tbl_photo_request_items';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function insert_request($adata)
   {
      return $this->db->insert($this->_TBL_PHOTO_REQUEST_LIST, $adata);
   }

   public function insert_items($adata)
   {
      return $this->db->insert_batch($this->_TBL_PHOTO_REQUEST_ITEMS, $adata);
   }

   public function delete_request_row($iidx)
   {
      $this->db->where('tprl_idx', $iidx);
      return $this->db->delete($this->_TBL_PHOTO_REQUEST_LIST);
   }

   public function delete_request( $adata )
   {
      return $this->db->update_batch($this->_TBL_PHOTO_REQUEST_LIST, $adata, 'tprl_idx'); 
   }
   
   public function update_request($adata, $iidx)
   {
      $this->db->where('tprl_idx', $iidx);
      return $this->db->update($this->_TBL_PHOTO_REQUEST_LIST, $adata);
   }
   
   public function delete_items( $iidx )
   {
      return $this->db->query("DELETE FROM {$this->_TBL_PHOTO_REQUEST_ITEMS} WHERE tpri_tprl_idx = '{$iidx}'");
   } 
}