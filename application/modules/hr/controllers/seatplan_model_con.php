<?php
class Seatplan_model_con extends MX_Controller
{  
   public function __construct()
   {
      parent::__construct();
      $this->load->model("seatplan_model");
   }

   public function _remap()
   {
      show_404();
   }
   
   public function get_seatplan_coordinates()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_seatplan_coordinates();
      echo json_encode($adata['aresult']);
   }
   
   public function save_coords()
   {
      $this->seatplan_model->savecoords();
   }
   
   public function get_seatplan_src()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_seatplan_src();
      echo json_encode($adata['aresult']);
   }
   
   public function get_department()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_department();
      echo json_encode($adata['aresult']);
   }   
   
   public function get_seatno()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_seatno();
      echo json_encode($adata['aresult']);
   }   
   
   public function get_emp()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_emp();
      echo json_encode($adata['aresult']);
   }        
   
   public function get_einfo()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_einfo();
      echo json_encode($adata['aresult']);
   }      
   
   public function get_dept()
   {
      $adata['aresult'] = $this->seatplan_model->get_data_dept();
      echo json_encode($adata['aresult']);
   }   
   
   public function check_seat()
   {
      $adata['aresult'] = $this->seatplan_model->check_seat_res();
      echo json_encode($adata['aresult']);
   }

   public function submitDetail()
   {
      $adata['aresult'] = $this->seatplan_model->submitDetail();
      echo json_encode($adata['aresult']);
   }
   public function del_coords()
   {
      $adata['aresult'] = $this->seatplan_model->del_coords();
      echo json_encode($adata['aresult']);
   }
   


  
   
}