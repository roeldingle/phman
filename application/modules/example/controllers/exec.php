<?php

class Exec extends MX_Controller
{
   private $TB_EXAMPLE = 'tb_example';
   public function __construct()
   {
      parent::__construct();
      $this->load->module('core/app');
   }
   
   public function save()
   {
      $data = array(
         'fname' => $this->input->post('firstname') ,
         'mname' => $this->input->post('middlename') ,
         'lname' => $this->input->post('lastname') ,
         'date_created' => date("Y-m-d H:i:s",time())
      );

      $this->db->insert($this->TB_EXAMPLE, $data); 
      $this->common->set_message("Saved Successfully!","my-save-message","success");
      redirect('example/content/');
   }
   
   public function upload()
   {
      
      $ainfo1 = $this->app->get_fileupload('first',true);
      $ainfo2 = $this->app->get_fileupload('second',true);
      echo"<Pre>";
      var_dump($ainfo1);
      // $ainfo = $this->app->get_fileupload('second');
      // $this->common->set_message("Successfully Uploaded!","upload-succcess","success");
      // redirect($_SERVER['HTTP_REFERER']);
     
   }
}