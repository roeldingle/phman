<?php

class Hr_exec extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module('core/app');
      $this->load->model("seatplan_model");
   }
   
   public function exec_save()
   {
      $adata = array();
      $adata['upload_details']= $this->app->get_fileupload('seatplan_upload',true);
      $adata['map_name'] = $this->input->post('map-name');
      $aresult = $this->seatplan_model->save_seat_map($adata);
      
      vd($aresult);
   }
}