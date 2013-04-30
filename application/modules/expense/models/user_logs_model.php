<?php

class User_logs_model extends CI_Model
{
   private $_TBL_USER = 'tbl_user';
   private $_TBL_USER_GRADE = 'tbl_user_grade';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_TBL_EMP_COMP_REC = 'tbl_employee_company_record';
   private $_TBL_DEPARTMENT = 'tbl_department';
   private $_TBL_LOGS = 'tbl_logs';
   private $_TBL_POSITION = 'tbl_position';
   private $_iuseridx;
   private $_iemployeeidx;
   
   public function __construct()
   {
      parent::__construct();
      // Assign user idx
      $this->_iuseridx = $this->session->userdata('userid');
      // Assign employee idx
      $this->_iemployeeidx = $this->session->userdata('employeeid');
   }
   
   public function get_expense_logs_list( $sdaterange_where, $ssearch_where, $alimit )
   {
      $ssql = "SELECT 
                  te.te_fname AS user,
                  tug.tug_name AS user_level,
                  tl.tl_message_log AS message_log,
                  tl.tl_message_from as message_from,
                  tl.tl_transact_type as transact_type,
                  tl.tl_message_to as message_to,
                  tp.tp_position AS position,
                  DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y %H:%i:%s' ) AS date_created
                  FROM {$this->_TBL_LOGS} AS tl 
                  INNER JOIN {$this->_TBL_USER} AS tu
                     ON tl.tl_tu_idx = tu.tu_idx
                  INNER JOIN {$this->_TBL_USER_GRADE} as tug 
                     ON tu.tu_tug_idx = tug.tug_idx
                  INNER JOIN {$this->_TBL_EMPLOYEE} AS te
                     ON tu.tu_te_idx = te.te_idx 
                  INNER JOIN {$this->_TBL_EMP_COMP_REC} AS tecr
                     ON tecr.tecr_te_idx = te.te_idx
                  INNER JOIN {$this->_TBL_POSITION} AS tp
                     ON tp.tp_idx = tecr.tecr_tp_idx
                  WHERE tl_app_type = 'expense' {$sdaterange_where} {$ssearch_where}
                  ORDER BY tl.tl_date_created DESC
                  LIMIT {$alimit['offset']}, {$alimit['limit']}";
      $query = $this->db->query($ssql);
      return $query->result();
   }
   
   public function get_list_count( $sdaterange_where, $ssearch_where )
   {
      $ssql = "SELECT COUNT(*) as total_rows
                  FROM {$this->_TBL_LOGS} AS tl 
                  INNER JOIN {$this->_TBL_USER} AS tu
                     ON tl.tl_tu_idx = tu.tu_idx
                  INNER JOIN {$this->_TBL_USER_GRADE} as tug 
                     ON tu.tu_tug_idx = tug.tug_idx
                  INNER JOIN {$this->_TBL_EMPLOYEE} AS te
                     ON tu.tu_te_idx = te.te_idx 
                  INNER JOIN {$this->_TBL_EMP_COMP_REC} AS tecr
                     ON tecr.tecr_te_idx = te.te_idx
                  INNER JOIN {$this->_TBL_POSITION} AS tp
                     ON tp.tp_idx = tecr.tecr_tp_idx
                  WHERE tl_app_type = 'expense' {$sdaterange_where} {$ssearch_where}
                  ";
      $query = $this->db->query($ssql);
      return $query->row()->total_rows;
   }   
}  