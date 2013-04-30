<?php

class Expense_model extends CI_Model
{
   private $_TBL_EXPENSE_BCOMMENT = 'tbl_expense_bcomment';
   private $_TBL_EXPENSE_LIST = 'tbl_expense_list';
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_planned_budget($syear, $smonth)
   {
      $this->db->select("SUM(teb_total) as total");
      $this->db->from($this->_TBL_EXPENSE_BCOMMENT);
      $this->db->where("teb_year", $syear);
      $this->db->where("teb_month", $smonth);
      $query = $this->db->get();
      return $query->row()->total;
   }
   
   public function get_expenses($sdate)
   {
      $this->db->select("SUM(tel_payment) as total");
      $this->db->from($this->_TBL_EXPENSE_LIST);
      $this->db->where("tel_type", 'expenses');
      $this->db->where("DATE_FORMAT(FROM_UNIXTIME(tel_date),'%Y-%m')", $sdate);
      $query = $this->db->get();
      return $query->row()->total;
   }      
}