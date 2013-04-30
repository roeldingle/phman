<?php
class Department_api extends MX_Controller
{  
   public function __construct()
   {
      parent::__construct();
      $this->load->model("department_model");
   }

   public function _remap()
   {
      show_404();
   }
   
   public function submitForm()
   {
        $oresult =  $this->department_model->submit();
        echo json_encode($oresult);
   }   
   
   public function getInfo()
   {
        $oresult =  $this->department_model->getInfo();
        echo json_encode($oresult->result());
   }
   
   public function delDepartment()
   {
        $oresult =  $this->department_model->delDepartment();
        echo json_encode($oresult);
   }
   
   
}