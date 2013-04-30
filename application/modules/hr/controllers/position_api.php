<?php
class Position_api extends MX_Controller
{  
   public function __construct()
   {
      parent::__construct();
      $this->load->model("position_model");
   }

   public function _remap()
   {
      show_404();
   }
   
   public function submitForm()
   {
        $oresult =  $this->position_model->submit();
        echo json_encode($oresult);
   }   
   
   public function getInfo()
   {
        $oresult =  $this->position_model->getInfo();
        echo json_encode($oresult->result());
   }
   
   public function delPosition()
   {
        $oresult =  $this->position_model->delPosition();
        echo json_encode($oresult);
   }
   
   
}