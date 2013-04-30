<?php
class Attendance_api extends MX_Controller
{  
   public function __construct()
   {
      parent::__construct();
      $this->load->model("attendance_model");
   }

   public function _remap()
   {
      show_404();
   }
   
   public function submitForm()
   {
        $oresult =  $this->attendance_model->submitForm();
        echo json_encode($oresult);
   }   
   
   public function getHistoryInfo()
   {
        $oresult =  $this->attendance_model->getHistoryInfo();
        echo json_encode($oresult->result());
   }
   
   public function delHistory()
   {
        $oresult =  $this->attendance_model->delHistory();
        echo json_encode($oresult);
   }   
   
   public function getEmployee()
   {
        $oresult =  $this->attendance_model->getEmployee();
        echo json_encode($oresult->result());
   }
   
   public function getType()
   {
        $oresult =  $this->attendance_model->getType();
        echo json_encode($oresult->result());
   }   

}