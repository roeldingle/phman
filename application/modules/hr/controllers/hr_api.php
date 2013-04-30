<?php
class Hr_api extends MX_Controller
{  
   public function __construct()
   {
      parent::__construct();
      $this->load->model("hr_model");
      $this->load->module('settings/logs');
   }

   public function _remap()
   {
      show_404();
   }
   
   public function getFormData() 
   {
   	  $oresult = $this->hr_model->db_select('tbl_employee_work_status','tws_idx');
   	  $adata['work_status'] = $oresult->result();
   	  $oresult = $this->hr_model->db_select('tbl_employee_employment_type','tet_idx');
   	  $adata['employment_type'] = $oresult->result();
   	  $oresult = $this->hr_model->db_select('tbl_position','tp_position');
   	  $adata['position'] = $oresult->result();
   	  $oresult = $this->hr_model->db_select('tbl_department','td_dept_name');
   	  $adata['department'] = $oresult->result();
   	  $adata['usergradeid'] = $this->session->userdata('usergradeid');
   	  echo json_encode($adata);
   }

   public function getEmployee()
   {
   	  $oresult = $this->hr_model->getEmployee();
   	  $adata['employee_info'] = $oresult->result();
         $this->logs->set_log($adata['employee_info'][0]->te_fname .' '.$adata['employee_info'][0]->te_lname.' Information',"READ");

   	  echo json_encode($adata);
   }
   
   public function submitForm()
   {
        $oresult =  $this->hr_model->employee_info();
        echo json_encode($oresult);
   }  
   
   public function delEmployee()
   {
        $oresult =  $this->hr_model->delEmployee();
        echo json_encode($oresult);
   }
   
}