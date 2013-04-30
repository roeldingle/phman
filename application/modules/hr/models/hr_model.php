<?php
class Hr_model extends CI_Model
{

   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
   }

   public function db_select($table_name,$orderby)
   {
      $this->db->select('*');
      $this->db->from($table_name);
      $this->db->order_by($orderby, "asc");
      $query = $this->db->get();
      return $query;
   }
   
   public function getTotal_rows($keyword)
   {
      $this->db->select('te_idx,te_fname,te_lname,te_mname,td_dept_name,tp_position,tws_status_name,tecr_date_started,tecr_date_ended,tecr_probationary_date_ended,td_dept_name');
      $this->db->from('tbl_employee');
      $this->db->join('tbl_employee_company_record', 'tecr_te_idx = te_idx','left');
      $this->db->join('tbl_department', 'td_idx = tecr_td_idx','left');
      $this->db->join('tbl_position', 'tp_idx = tecr_tp_idx','left');
      $this->db->join('tbl_employee_work_status', 'tws_idx = tecr_tews_work_status','left');
      $where = "(te_fname LIKE '".$keyword."' OR te_lname LIKE '".$keyword."' OR te_mname LIKE '".$keyword."' OR td_dept_name LIKE '%".$keyword."%' OR tp_position LIKE '%".$keyword."%' OR tws_status_name LIKE '%".$keyword."%') AND te_active=1";
      $this->db->where($where);
      $this->db->order_by("tws_status_name asc, td_dept_name asc");
      return $this->db->count_all_results();
   }
   
   public function getEmployee_list($keyword,$RowPerPage,$Curpage)
   {
   
      $ilimit =  $RowPerPage;
      $iOffset = ($Curpage - 1) * $RowPerPage;
   
      $this->db->select('te_idx,te_fname,te_lname,te_mname,td_dept_name,tp_position,tws_status_name,tecr_date_started,tecr_date_ended,tecr_probationary_date_ended,td_dept_name');
      $this->db->from('tbl_employee');
      $this->db->join('tbl_employee_company_record', 'tecr_te_idx = te_idx','left');
      $this->db->join('tbl_department', 'td_idx = tecr_td_idx','left');
      $this->db->join('tbl_position', 'tp_idx = tecr_tp_idx','left');
      $this->db->join('tbl_employee_work_status', 'tws_idx = tecr_tews_work_status','left');
      $where = "(te_fname LIKE '".$keyword."' OR te_lname LIKE '".$keyword."' OR te_mname LIKE '".$keyword."' OR td_dept_name LIKE '%".$keyword."%' OR tp_position LIKE '%".$keyword."%' OR tws_status_name LIKE '%".$keyword."%') AND te_active=1";
      $this->db->where($where);
      $this->db->group_by("te_idx");
      $this->db->order_by("tws_status_name asc, td_dept_name asc");
      $this->db->limit($ilimit,$iOffset);
      $query = $this->db->get();
      
      return $query;
   }
   
   public function getEmployee()
   {
      $this->db->select('*');
      $this->db->from('tbl_employee');
      $this->db->where('te_idx',$this->input->get('emp_id'));
      $this->db->join('tbl_employee_company_record', 'tecr_te_idx = te_idx','left');
      $this->db->join('tbl_employee_employment_history', 'teeh_te_idx = te_idx','left');
      $query = $this->db->get();
      return $query;
   }
   
   public function array_push_assoc($array, $key, $value){
      $array[$key] = $value;
      return $array;
   }
   
   public function employee_info()
   {
      $flag       = $this->input->post('flag');
      $modify_id       = $this->input->post('modify_id');
      $date = date("m/d/y H:i:s");
      
   /* employee info */
      $emp_image = $this->input->post('emp_image');
      $emp_id     = $this->input->post('emp_id');
      $lname    = $this->input->post('lname');
      $fname    = $this->input->post('fname');
      $mname    = $this->input->post('mname');
      $nickname    = $this->input->post('nickname');
      $address    = $this->input->post('address');
      $prov_address    = $this->input->post('prov_address');
      $home_no    = $this->input->post('home_no');
      $mobile_no    = $this->input->post('mobile_no');
      $email    = $this->input->post('email');
      $gender    = $this->input->post('gender');
      $status    = $this->input->post('status');
      $bday    = $this->input->post('bday');
      $school    = $this->input->post('school');
      $school_address    = $this->input->post('school_address');
      $course    = $this->input->post('course');
      $inc_dates    = $this->input->post('inc_dates');
      $year_grad    = $this->input->post('year_grad');
      $certi_deg    = $this->input->post('certi_deg');
      $certi_deg_completed    = $this->input->post('certi_deg_completed');
      $notify_name    = $this->input->post('notify_name');
      $notify_relation    = $this->input->post('notify_relation');
      $notify_no    = $this->input->post('notify_no');
      $notify_no2    = $this->input->post('notify_no2');
      $notify_add    = $this->input->post('notify_add');
    
   /* company record */  
      $position    = $this->input->post('position');
      $department    = $this->input->post('department');
      $current_salary    = $this->input->post('current_salary');
      $status_work    = $this->input->post('status_work');
      $date_start    = $this->input->post('date_start');
      $date_end    = $this->input->post('date_end');
      $date_prob    = $this->input->post('date_prob');
      $emp_type    = $this->input->post('emp_type');
      $sss    = $this->input->post('sss');
      $tin    = $this->input->post('tin');
      $philhealth    = $this->input->post('philhealth');
      $pag_track    = $this->input->post('pag_track');
      $pag_mid    = $this->input->post('pag_mid');
      $bank_name    = $this->input->post('bank_name');
      $bank_account    = $this->input->post('bank_account');
      $depen_name    = $this->input->post('depen_name');
      $depen_bday    = $this->input->post('depen_bday');
      $depen_rel    = $this->input->post('depen_rel');

   /* Employment history */  
      $tot_year    = $this->input->post('tot_year');
      $tot_month    = $this->input->post('tot_month');
      $employ_from    = $this->input->post('employ_from');
      $employ_to    = $this->input->post('employ_to');
      $prev_company_name    = $this->input->post('prev_company_name');
      $prev_company_pos    = $this->input->post('prev_company_pos');
      $prev_company_res    = $this->input->post('prev_company_res');
      $prev_company_contact    = $this->input->post('prev_company_contact');
      $prev_company_add    = $this->input->post('prev_company_add');
      $prev_start_salary    = $this->input->post('prev_start_salary');
      $prev_last_salary    = $this->input->post('prev_last_salary');
      $reason_leave    = $this->input->post('reason_leave');

      
      $employee_info = array(
         'te_image_path' => $emp_image,
         'te_employee_id' => $emp_id ,
         'te_fname' => ucwords($fname),
         'te_lname' => ucwords($lname),
         'te_mname' => ucwords($mname),
         'te_nickname' => $nickname,
         'te_address' => $address,
         'te_prov_address' => $prov_address,
         'te_home_no' => $home_no,
         'te_contact_number' => $mobile_no,
         'te_email_address' => $email,
         'te_gender' => $gender,
         'te_status' => $status,
         'te_bdate' => $bday,
         'te_school' => $school,
         'te_school_add' => $school_address,
         'te_course' => $course,
         'te_inc_dates' => $inc_dates,
         'te_year_grad' => $year_grad,
         'te_certi_deg' => $certi_deg,
         'te_certi_deg_completed' => $certi_deg_completed,
         'te_notify_name' => $notify_name,
         'te_notify_rel' => $notify_relation,
         'te_notify_no' => $notify_no,
         'te_notify_no2' => $notify_no2,
         'te_notify_add' => $notify_add,
         'te_date_created' => $date,
         'te_active' => 1
      );
      
      $company_record = array(
         'tecr_tp_idx' => $position ,
         'tecr_td_idx' => $department ,
         'tecr_tews_work_status' => $status_work ,
         'tecr_basic_salary' => $current_salary ,
         'tecr_date_started' => $date_start ,
         'tecr_date_ended' => $date_end ,
         'tecr_probationary_date_ended' => $date_prob ,
         'tecr_tet_idx' => $emp_type ,
         'tecr_sss' => $sss ,
         'tecr_tin' => $tin ,
         'tecr_philhealth' => $philhealth ,
         'tecr_pag_track' => $pag_track ,
         'tecr_pag_midno' => $pag_mid ,
         'tecr_bank_name' => $bank_name ,
         'tecr_bank_account_number' => $bank_account ,
         'tecr_depen_name' => $depen_name ,
         'tecr_depen_bday' => $depen_bday ,
         'tecr_depen_relation' => $depen_rel
      );
      
       $employment_history = array(
         'teeh_tot_year' => $tot_year ,
         'teeh_tot_month' => $tot_month ,
         'teeh_employ_from' => $employ_from ,
         'teeh_employ_to' => $employ_to ,
         'teeh_company_name' => $prev_company_name ,
         'teeh_position' => $prev_company_pos ,
         'teeh_responsibility' => $prev_company_res ,
         'teeh_contact' => $prev_company_contact ,
         'teeh_address' => $prev_company_add ,
         'teeh_salary_start' => $prev_start_salary ,
         'teeh_salary_last' => $prev_last_salary ,
         'teeh_reason_leave' => $reason_leave
       );
            
      if($flag=='add'){
          
         if($emp_image!=null){
            if(file_exists(APPPATH . 'modules/hr/uploads/temp/'.$emp_image)){
               copy(APPPATH . 'modules/hr/uploads/temp/'.$emp_image,APPPATH . 'modules/hr/uploads/emp_image/'.$emp_image);
            }
         }
         
         $this->db->insert('tbl_employee', $employee_info);
         
         $this->db->select('te_idx');
         $this->db->from('tbl_employee');
         $this->db->where('te_employee_id',$emp_id);
         $query_idx = $this->db->get()->result();
         
         $em_idx = $query_idx[0]->te_idx;
         
         $company_records = $this->array_push_assoc($company_record, 'tecr_te_idx', $em_idx);
         $this->db->insert('tbl_employee_company_record', $company_records);
         
         $employment_historys = $this->array_push_assoc($employment_history, 'teeh_te_idx', $em_idx);
         $this->db->insert('tbl_employee_employment_history', $employment_historys);
         $this->logs->set_log(ucwords($fname).' '.ucwords($lname).' To Employee List',"CREATE");         
         return 'add';
         
      }else if($flag=='update'){
      
         $this->db->select('te_idx,te_image_path');
         $this->db->from('tbl_employee');
         $this->db->where('te_idx',$modify_id);
         $query_update = $this->db->get()->result();
             
         if($emp_image!=$query_update[0]->te_image_path){
            
            if(file_exists(APPPATH . 'modules/hr/uploads/temp/'.$emp_image)){
               copy(APPPATH . 'modules/hr/uploads/temp/'.$emp_image,APPPATH . 'modules/hr/uploads/emp_image/'.$emp_image);
            }
            if(file_exists(APPPATH . 'modules/hr/uploads/emp_image/'.$query_update[0]->te_image_path)){
               unlink(APPPATH . 'modules/hr/uploads/emp_image/'.$query_update[0]->te_image_path);
            }
         }
            
         $this->db->where('te_idx', $modify_id);
         $this->db->update('tbl_employee', $employee_info); 
         
         $this->db->where('tecr_te_idx', $modify_id);
         $this->db->update('tbl_employee_company_record', $company_record); 
         
         $this->db->where('teeh_te_idx', $modify_id);
         $this->db->update('tbl_employee_employment_history', $employment_history); 
         $this->logs->set_log(ucwords($fname).' '.ucwords($lname).' Information',"UPDATE");
         return 'update';
      }
   }
   
   public function delEmployee()
   {
      $idxs = $this->input->post('idx');
      $data = array('te_active' => 0);
	    foreach ($idxs as $item){
         $this->db->where('te_idx', $item);
         $this->db->update('tbl_employee',$data); 
	    }
      $this->logs->set_log('One of Employee List',"DELETE");
      return 'deleted';
   }
}