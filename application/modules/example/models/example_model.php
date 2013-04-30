<?php
class Example_Model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_data($asort, $alimit)
   {
      $this->db->select('*');
      $this->db->from('tb_example');
      $this->db->order_by($asort['field'],$asort["order"]);
      $this->db->limit($alimit['limit'],$alimit['offset']);
      $query = $this->db->get();
      return $query->result();

   }
   
   public function get_count()
   {
      $this->db->select('COUNT(*) as total_rows');
      $this->db->from('tb_example');
      $query = $this->db->get();
      return $query->row()->total_rows;

   }   
}