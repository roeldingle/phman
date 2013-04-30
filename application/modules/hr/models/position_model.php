<?php
class Position_model extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
   }

   public function getTotal_rows($keyword)
   {
      $this->db->like('tp_idx',$keyword);
      $this->db->from('tbl_position');
      return $this->db->count_all_results();
   }
   
   public function getList($keyword,$RowPerPage,$Curpage)
   {

      $ilimit =  $RowPerPage;
      $iOffset = ($Curpage - 1) * $RowPerPage;
      $this->db->select('*');
      $this->db->from('tbl_position');
      $this->db->like('tp_position',$keyword);
      $this->db->order_by("tp_position", "asc");
      $this->db->limit($ilimit,$iOffset);
      $query = $this->db->get();
      
      return $query;
   }
   
   public function getInfo()
   {
      $this->db->select('*');
      $this->db->from('tbl_position');
      $this->db->where('tp_idx',$this->input->post('modify_id'));
      $query = $this->db->get();
      return $query;
   }
   
   
   public function submit()
   {
      $flag       = $this->input->post('flag');
      $modify_id       = $this->input->post('modify_id');
      $date = date("m/d/y H:i:s");
      $pos_name     = $this->input->post('pos_name');
      $list = array(
         'tp_position' => $pos_name ,
         'tp_date_created' => $date
      );
      
      if($flag=='add'){
         $this->db->insert('tbl_position', $list);
         $this->logs->set_log(ucwords($pos_name).' To Position List',"CREATE");
         return 'saved';
      }else if($flag=='update'){
         $this->db->where('tp_idx', $modify_id);
         $this->db->update('tbl_position', $list); 
         $this->logs->set_log(ucwords($pos_name).' Position Name',"UPDATE");
         return 'saved';
      }
   }
   
   public function delPosition()
   {
      $idxs = $this->input->post('idx');
      $arr = array();
      
      foreach ($idxs as $item){
         $this->db->select('tecr_tp_idx');
         $this->db->from('tbl_employee_company_record');
         $this->db->where('tecr_tp_idx', $item);
         $this->db->group_by('tecr_tp_idx');
         $query = $this->db->get()->result();
         $query_arr = $query==null ? 'delete' : 'deny';
        array_push($arr,$query_arr);
       
	   }
      
      if(in_array('deny',$arr)){
         return 'denied';
      }else{
      	 foreach ($idxs as $item){
            $this->db->where('tp_idx', $item);
            $this->db->delete('tbl_position'); 
          }
         $this->logs->set_log('One of Position List',"DELETE");  
         return 'deleted';
      }
   }
}