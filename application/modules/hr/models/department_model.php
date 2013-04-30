<?php
class Department_model extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
   }

   public function getTotal_rows($keyword)
   {
      $this->db->like('td_dept_name',$keyword);
      $this->db->from('tbl_department');
      return $this->db->count_all_results();
   }
   
   public function getList($keyword,$RowPerPage,$Curpage)
   {

      $ilimit =  $RowPerPage;
      $iOffset = ($Curpage - 1) * $RowPerPage;
      $this->db->select('*');
      $this->db->from('tbl_department');
      $this->db->like('td_dept_name',$keyword);
      $this->db->order_by("td_dept_name", "ASC");
      $this->db->limit($ilimit,$iOffset);
      $query = $this->db->get();
      
      return $query;
   }
   
   public function getInfo()
   {
      $this->db->select('*');
      $this->db->from('tbl_department');
      $this->db->where('td_idx',$this->input->post('modify_id'));
      $query = $this->db->get();
      return $query;
   }
   
   
   public function submit()
   {
      $flag       = $this->input->post('flag');
      $modify_id       = $this->input->post('modify_id');
      $date = date("m/d/y H:i:s");
      $dept_name     = $this->input->post('dept_name');
      $list = array(
         'td_dept_name' => $dept_name ,
         'td_date_created' => $date
      );
      
      if($flag=='add'){
         $this->db->insert('tbl_department', $list);
         $this->logs->set_log(ucwords($dept_name).' To Department List',"CREATE");
         return 'saved';
      }else if($flag=='update'){
      
         $this->db->where('td_idx', $modify_id);
         $this->db->update('tbl_department', $list);
         $this->logs->set_log(ucwords($dept_name).' Department Name',"UPDATE");         
         return 'saved';
      }
   }
   
   public function delDepartment()
   {
      $idxs = $this->input->post('idx');
      $arr = array();
      
      foreach ($idxs as $item){
         $this->db->select('tecr_td_idx');
         $this->db->from('tbl_employee_company_record');
         $this->db->where('tecr_td_idx', $item);
         $this->db->group_by('tecr_td_idx');
         $query = $this->db->get()->result();
         $query_arr = $query==null ? 'delete' : 'deny';
        array_push($arr,$query_arr);
       
	   }
      
      if(in_array('deny',$arr)){
         return 'denied';
      }else{
      	 foreach ($idxs as $item){
            $this->db->where('td_idx', $item);
            $this->db->delete('tbl_department'); 
          }
         $this->logs->set_log('One of Department List',"DELETE");   
         return 'deleted';
      }
            
   }
}