<?php

class Login_model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }
   
   public function save()
   {
      $adata = array(
         'firstname' => $this->input->post('firstname'),
         'lastname' => $this->input->post('lastname')
      );
      
      $this->db->insert('test',$adata);

   }
}