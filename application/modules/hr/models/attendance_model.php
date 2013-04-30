<?php
class Attendance_model extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
   }
   
   public function array_push_assoc($array, $key, $value)
   {
      $array[$key] = $value;
      return $array;
   }

   public function getTotal_rows($keyword,$date)
   {
      $datefrom = $date[0];
      $dateto = $date[1];
      
      $this->db->select("te_idx,te_fname,te_mname,te_lname,
      (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 1 AND tlt_te_idx = te_idx)) AS vl,
      (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 2 AND tlt_te_idx = te_idx)) AS sl,
      (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 3 AND tlt_te_idx = te_idx)) AS tardy,
      (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 4 AND tlt_te_idx = te_idx)) AS lwop,
      (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 5 AND tlt_te_idx = te_idx)) AS awol"
                        );
      $this->db->from('tbl_employee');
      $where = "(te_fname LIKE '%".$keyword."%' OR te_lname LIKE '%".$keyword."%' OR te_mname LIKE '%".$keyword."%') AND te_active=1";
      $this->db->where($where);
      return $this->db->count_all_results();
   }
   
   public function getList($keyword,$RowPerPage,$Curpage,$date)
   {
      $datefrom = $date[0];
      $dateto = $date[1];
      
      $ilimit =  $RowPerPage;
      $iOffset = ($Curpage - 1) * $RowPerPage;
      $this->db->select("te_idx,te_fname,te_mname,te_lname,
         (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 1 AND tlt_te_idx = te_idx)) AS vl,
         (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 2 AND tlt_te_idx = te_idx)) AS sl,
         (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 3 AND tlt_te_idx = te_idx)) AS tardy,
         (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 4 AND tlt_te_idx = te_idx)) AS lwop,
         (SELECT SUM(tlt_type_count) FROM tbl_leave_tardiness WHERE (tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND (tlt_tltt_type = 5 AND tlt_te_idx = te_idx)) AS awol"
                        );
      $this->db->from('tbl_employee');
      $this->db->join('tbl_employee_company_record', 'tecr_te_idx = te_idx','left');
      $this->db->join('tbl_department', 'td_idx = tecr_td_idx','left');
      $where = "(te_fname LIKE '%".$keyword."%' OR te_lname LIKE '%".$keyword."%' OR te_mname LIKE '%".$keyword."%') AND te_active=1";
      $this->db->where($where);
      $this->db->group_by("te_idx");
      $this->db->order_by("td_dept_name", "ASC");
      $this->db->limit($ilimit,$iOffset);
      $query = $this->db->get();
      
      return $query;
   }
   
   public function getEmployee()
   {
      $this->db->select('te_idx,te_fname,te_mname,te_lname');
      $this->db->from('tbl_employee');
      $this->db->where('te_active',1);
      $this->db->order_by("te_fname", "ASC");
      $query = $this->db->get();
      return $query;
   }
   
   public function getType()
   {
      $this->db->select('*');
      $this->db->from('tbl_leave_tardiness_type');
      $query = $this->db->get();
      return $query;
   }
   
   public function getHistoryInfo()
   {
      $this->db->select('*');
      $this->db->from('tbl_leave_tardiness');
      $this->db->where('tlt_idx',$this->input->post('modify_id'));
      $query = $this->db->get();
      return $query;
   }
   
   
   public function submitForm()
   {
      $empid       = $this->input->post('empid');
      $type       = $this->input->post('type');
      $flag       = $this->input->post('flag');
      $modify_id       = $this->input->post('modify_id');
      $date = $this->input->post('date');
      $reason     = $this->input->post('reason');
      $tardy     = $this->input->post('tardy');
      $type_count = $this->input->post('type_count');
      $date_created = date("Y-m-d");
      
      $date_exploded = explode(',',$date);
      
      $list = array(
         'tlt_te_idx' => $empid ,
         'tlt_tltt_type' => $type,
         'tlt_type_count' => $type_count,
         'tlt_reason' => $reason,
         'tlt_time_tardy' => $tardy,
         'tlt_date_created' => $date_created
      );
      
         $this->db->select('te_fname,te_mname,te_lname');
         $this->db->from('tbl_employee');
         $this->db->where('te_idx',$empid);
         $query_name = $this->db->get()->result();
         
         if($type=='1'){
            $stype='Vacation Leave';
         }else if($type=='2'){
            $stype='Sick Leave';
         }else if($type=='3'){
            $stype='Tardiness';
         }else if($type=='4'){
            $stype='Leave Without Pay';
         }else if($type=='5'){
            $stype='Absence Without Leave';
         }
      
      if($flag=='add'){
         if(count($date_exploded)==1){
            $lists = $this->array_push_assoc($list, 'tlt_date', $date);
            $this->db->insert('tbl_leave_tardiness', $lists);
         }else{
            foreach($date_exploded as $li)
            {
               $lists = $this->array_push_assoc($list, 'tlt_date', $li);
               $this->db->insert('tbl_leave_tardiness', $lists);
            }
         }
         $this->logs->set_log($query_name[0]->te_fname.' '.$query_name[0]->te_lname .' '.$stype,"CREATE");
         return 'saved add';
      }else if($flag=='update'){
            $lists = $this->array_push_assoc($list, 'tlt_date', $date);
            $this->db->where('tlt_idx', $modify_id);
            $this->db->update('tbl_leave_tardiness', $lists);
         $this->logs->set_log($query_name[0]->te_fname.' '.$query_name[0]->te_lname .' '.$stype,"UPDATE");
         return 'savedupdate';
         
      }
   }
   
   public function delHistory()
   {
      $idxs = $this->input->post('idx');
	    foreach ($idxs as $item){
         $this->db->where('tlt_idx', $item);
         $this->db->delete('tbl_leave_tardiness'); 
	    }
      $this->logs->set_log('One of Employee History',"DELETE");
      return 'deleted';
   }
   
   public function getHistoryList($history_idx,$RowPerPage,$Curpage,$date)
   {
      $datefrom = $date[0];
      $dateto = $date[1];
      
      $ilimit =  $RowPerPage;
      $iOffset = ($Curpage - 1) * $RowPerPage;
      $this->db->select('*,
      (SELECT tltt_type FROM tbl_leave_tardiness_type WHERE tltt_idx=tlt_tltt_type) AS tltt_type,
      (SELECT te_fname FROM tbl_employee WHERE te_idx=tlt_te_idx) AS te_fname,
      (SELECT te_mname FROM tbl_employee WHERE te_idx=tlt_te_idx) AS te_mname,
      (SELECT te_lname FROM tbl_employee WHERE te_idx=tlt_te_idx) AS te_lname
      ');
      $this->db->from('tbl_leave_tardiness');     
      $where = "(tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND tlt_te_idx = '".$history_idx."' ";
      $this->db->where($where);
      $this->db->order_by('tlt_idx','DESC');
      $this->db->group_by("tlt_idx");
      $this->db->limit($ilimit,$iOffset);
      $query = $this->db->get();
      
      return $query;
   }
   
   public function getHistoryTotal_rows($history_idx,$date)
   {
      $datefrom = $date[0];
      $dateto = $date[1];

      $this->db->select('*,(SELECT tltt_type FROM tbl_leave_tardiness_type WHERE tltt_idx=tlt_tltt_type) AS tltt_type');
      $this->db->from('tbl_leave_tardiness');     
      $where = "(tlt_date BETWEEN '".$datefrom."' AND '".$dateto."') AND tlt_te_idx = '".$history_idx."' ";
      $this->db->where($where);
      return $this->db->count_all_results();
   }
   
   
}