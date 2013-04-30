<?php

class Core_Model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }

   public function get_token($stoken)
   {
      $this->db->select('token');
      $this->db->from('token_counter');
      $this->db->where('token',$stoken);
      $query = $this->db->get();
      return $query->row();
   }

   public function insert_token($stoken)
   {
      $adata = array(
         'token' => $stoken
      );
      $this->db->insert('token_counter',$adata);
   }
}