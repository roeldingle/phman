<?php
class Seatplan_Model extends CI_Model
{
   private $tbl_ssc = 'tbl_seatplan_coordinates';
   private $tbl_ss = 'tbl_seatplan_src';
   private $tbl_s = 'tbl_seatplan';
   private $tbl_ecr = 'tbl_employee_company_record';
   private $tbl_e = 'tbl_employee';
   private $tbl_d = 'tbl_department';
   
   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
   }
  //get seatplan attachment
   public function get_data_seatplan_src()
   {
      $this->db->select('*');
      $this->db->from('tbl_seatplan_src');
      $query = $this->db->get();
      return $query->result();

   }
   //get seatplan coordinates
   public function get_data_seatplan_coordinates()
   {
      $this->db->select('*');
      $this->db->from($this->tbl_ssc);
       $this->db->order_by('tsc_seat_no','ASC');
      $query = $this->db->get();
      return $query->result();
   }
   //save seatplan coordinates
   public function savecoords()
   {
   
      $update_idx = $this->input->post('update_idx');
      $flag = $this->input->post('sub_seat_flag');
      
      $this->db->select('tss_idx');
      $this->db->from($this->tbl_ss);
      $query = $this->db->get();
      $seatplan_src = $query->result();
      
      $seatplan_src_id = $seatplan_src[0]->tss_idx;
      $seat_no = $this->input->post('seatno');
      $left = $this->input->post('left');
      $top = $this->input->post('top');
      $x2 = $this->input->post('x2');
      $y2 = $this->input->post('y2');
      $width = $this->input->post('width');
      $height =$this->input->post('height');
      $seat_usage = $this->input->post('usage');
      
      $data = array(
         'tsc_tss_idx' => $seatplan_src_id,
         'tsc_seat_no' => $seat_no,
         'tsc_left' => $left,
         'tsc_top' => $top,
         'tsc_x2' => $x2,
         'tsc_y2' => $y2,
         'tsc_width' => $width,
         'tsc_height' => $height,
         'tsc_seat_usage' => $seat_usage
      );
      if($flag=='add'){
         $this->db->insert($this->tbl_ssc, $data);
         $this->logs->set_log('Seat No. '.$seat_no,"CREATE");         
      }else if($flag=='update'){
      
         $this->db->where('ts_tsc_seatno', $seat_no);
         $this->db->delete('tbl_seatplan');
         
         $this->db->where('tsc_idx', $update_idx);
         $this->db->update('tbl_seatplan_coordinates', $data);
         $this->logs->set_log('Seat No. '.$seat_no,"UPDATE");
      }else if($flag=='default_size'){
         $size = array(
            'tss_default_left' => $left,
            'tss_default_top' => $top,
            'tss_default_x2' => $x2,
            'tss_default_y2' => $y2
         );
         $this->db->where('tss_idx', '1');
         $this->db->update('tbl_seatplan_src', $size);
         $this->logs->set_log('Seat Plan Default Table Size',"CREATE");
      }
   }
   //select all department lists
   public function get_data_department()
   {
      $this->db->select('*');
      $this->db->from($this->tbl_d);
      $department = $this->db->get();
      return $department->result();
   }   
    //get seat number using seat id
   public function get_data_seatno()
   {
      $seat_idx = $this->input->get('seat_idx');
      $this->db->select('*');
      $this->db->from($this->tbl_ssc);
      $this->db->where('tsc_idx',$seat_idx);
      $seat_no = $this->db->get();
      return $seat_no->result();
   }
   //get employee info using department id
   public function get_data_emp()
   {
      $deptid = $this->input->get('dept_id');
      $this->db->select('*');
      $this->db->from($this->tbl_ecr);
      $this->db->join($this->tbl_e, 'tecr_te_idx = te_idx','left');
      $where = "tecr_td_idx = '".$deptid."'  AND te_active=1 AND tecr_date_ended='0000-00-00'";
      $this->db->where($where);
      $this->db->group_by('te_idx');
      // $this->db->where('tecr_td_idx',$deptid);
      $query = $this->db->get();
      
      return $query->result();
   }   
   
   //get employee info using employee id
   public function get_data_einfo()
   {
      $emp_id = $this->input->get('emp_id');
      $this->db->select('*');
      $this->db->from($this->tbl_e);
      // $this->db->where('te_idx',$emp_id);
      $where = "te_idx = '".$emp_id."'  AND te_active=1";
      $this->db->where($where);
      $query = $this->db->get();
      
      return $query->result();
   }      
   
   //get department info using employee id
   public function get_data_dept()
   {
      $empid = $this->input->get('emp_id');
      $this->db->select('*');
      $this->db->from($this->tbl_ecr);
      $this->db->join($this->tbl_d, 'tecr_td_idx = td_idx','left');
      $this->db->where('tecr_te_idx',$empid);
      $query = $this->db->get();
      
      return $query->result();
   }   
   //check seatplan info using seatno
   public function check_seat_res()
   {
      $seatnum = $this->input->get('seatnum');
      $this->db->select('*');
      $this->db->from($this->tbl_ssc);
      $this->db->join($this->tbl_s, 'ts_tsc_seatno = tsc_seat_no','left');
      $this->db->where('tsc_seat_no',$seatnum);
      $query = $this->db->get();
      
      return $query->result();
   }
   
   public function save_seat_map($adata)
   {
      $numrows = $this->db->count_all('tbl_seatplan_src');
   
      $map_name = $adata['map_name'];
      $file_name = $adata['upload_details']['files'][0]['newfilename'];
      
      $adata = array(
      'tss_idx'=>1,
      'tss_map_name'=>$map_name,
      'tss_map_src'=>$file_name
      );

      if($numrows==0){
         $this->db->insert('tbl_seatplan_src', $adata);
         $this->logs->set_log('New Seat Plan Map',"CREATE");
      }else{
      
         $this->db->select('*');
         $this->db->from($this->tbl_ss);
         $this->db->where('tss_idx', 1);
         $query = $this->db->get()->result();
         if(file_exists(APPPATH . 'modules/hr/uploads/map/'.$query[0]->tss_map_src)){
            unlink(APPPATH . 'modules/hr/uploads/map/'.$query[0]->tss_map_src);
         }
         $this->db->where('tss_idx', 1);
         $this->db->delete('tbl_seatplan_src');
         $this->db->insert('tbl_seatplan_src', $adata);
         $this->logs->set_log('Seat Plan Map',"UPDATE");  
         
      }
      
      redirect($_SERVER['HTTP_REFERER']);
      
   }
   
   public function submitDetail()
   {
      $emp_idx = $this->input->post('emp_idx');
      $flag = $this->input->post('flag');
      $seat_coords_id = $this->input->post('seat_coords_id');
      $seat_no = $this->input->post('seat_no');
      $seat_usage = $this->input->post('seat_usage');
      
      $data = array(
         'ts_tsc_seatno' => $seat_no,
         'ts_te_idx' => $emp_idx
      ); 
      
      if($flag=='add'){
         if($seat_usage!=3){
               $this->db->where('tsc_idx', $seat_coords_id);
               $this->db->update('tbl_seatplan_coordinates', array('tsc_seat_usage'=>$seat_usage));
               
               $this->db->where('ts_tsc_seatno', $seat_no);
               $this->db->delete('tbl_seatplan');
            }else{
               $this->db->insert('tbl_seatplan', $data);
               $this->db->where('tsc_idx', $seat_coords_id);
               $this->db->update('tbl_seatplan_coordinates', array('tsc_seat_usage'=>$seat_usage));
            }         
      }else if($flag=='update'){
         if($seat_usage!=3){
            $this->db->where('tsc_idx', $seat_coords_id);
            $this->db->update('tbl_seatplan_coordinates', array('tsc_seat_usage'=>$seat_usage));
            
            $this->db->where('ts_tsc_seatno', $seat_no);
            $this->db->delete('tbl_seatplan');
         }else{
            $this->db->where('ts_tsc_seatno', $seat_no);
            $this->db->update('tbl_seatplan', $data);
            
            $this->db->where('tsc_idx', $seat_coords_id);
            $this->db->update('tbl_seatplan_coordinates', array('tsc_seat_usage'=>'3'));      
         }
      }else if($flag=='update_usage'){
            $this->db->where('tsc_idx', $seat_coords_id);
            $this->db->update('tbl_seatplan_coordinates', array('tsc_seat_usage'=>$seat_usage)); 
      }
      return 'saved';
   }

   public function del_coords()
   {
      $this->db->where('tsc_idx', $this->input->get('seat_idx'));
      $this->db->delete('tbl_seatplan_coordinates');
      $this->logs->set_log('One of Seat No.',"DELETE");
   }
   
   
}