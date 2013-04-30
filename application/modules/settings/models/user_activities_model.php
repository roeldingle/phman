<?php

class User_activities_model extends CI_Model
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
   
   public function get_user_info()
   {
      // $ssql = "SELECT 
      // tug.tug_name AS username,
      // te.te_fname AS fname
      // FROM tbl_user AS tu
      // INNER JOIN 
      // tbl_user_grade AS tug 
      // ON tu.tu_tug_idx = tug.tug_idx
      // INNER JOIN 
      // tbl_employee AS te
      // ON tu.tu_te_idx = te.te_idx WHERE tu.tu_te_idx = {$this->_iemployeeidx}";
      // $query = $this->db->query($ssql);
      $this->db->select("tug.tug_name AS username,te.te_fname AS fname");
      $this->db->from("{$this->_TBL_USER} AS tu");
      $this->db->join("{$this->_TBL_USER_GRADE} AS tug", "tu.tu_tug_idx = tug.tug_idx", "INNER" );
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te", "tu.tu_te_idx = te.te_idx", "INNER" );
      $this->db->where("tu.tu_te_idx", $this->_iemployeeidx );
      $query = $this->db->get();
      
      return $query->result();
   }
   
   public function get_log( $smessage_log )
   {
      $sdate_created = date('Y-m-d',time());      
      // $ssql = "SELECT * FROM {$this->_TBL_LOGS} WHERE 
      // tl_tu_idx = '{$this->_iuseridx}' AND
      // tl_message_log = '{$smessage_log}' AND 
      // DATE_FORMAT( FROM_UNIXTIME( tl_date_created ), '%Y-%m-%d' ) = '{$sdate_created}'";
      // $query = $this->db->query($ssql);      
      $this->db->select("*");
      $this->db->from("{$this->_TBL_LOGS}");
      $this->db->where("tl_tu_idx", $smessage_log);
      $this->db->where("DATE_FORMAT( FROM_UNIXTIME( tl_date_created ), '%Y-%m-%d') = ", $sdate_created);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_logs_list($alimit)
   {
      // $ssql = "SELECT 
      // te.te_fname AS user,
      // tug.tug_name AS user_level,
      // tl.tl_message_log AS message_log,
      // tl.tl_message_from as message_from,
      // tl.tl_message_to as message_to,
      // tp.tp_position AS position,
      // DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y %H:%i:%s' ) AS date_created
      // FROM {$this->_TBL_LOGS} AS tl 
      // INNER JOIN {$this->_TBL_USER} AS tu
      // ON tl.tl_tu_idx = tu.tu_idx
      // INNER JOIN {$this->_TBL_USER_GRADE} as tug 
      // ON tu.tu_tug_idx = tug.tug_idx
      // INNER JOIN {$this->_TBL_EMPLOYEE} AS te
      // ON tu.tu_te_idx = te.te_idx 
      // INNER JOIN {$this->_TBL_EMP_COMP_REC} AS tecr
      // ON tecr.tecr_te_idx = te.te_idx
      // INNER JOIN {$this->_TBL_POSITION} AS tp
      // ON tp.tp_idx = tecr.tecr_tp_idx
      // ORDER BY tl.tl_date_created DESC 
      // LIMIT {$alimit['offset']}, {$alimit['limit']}";
      // $query = $this->db->query($ssql);
      
      $this->db->select("te.te_fname AS user,
                  tug.tug_name AS user_level,
                  tl.tl_message_log AS message_log,
                  tl.tl_message_from as message_from,
                  tl.tl_message_to as message_to,
                  tp.tp_position AS position,
                  DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y %H:%i:%s') AS date_created", FALSE);
      $this->db->from("{$this->_TBL_LOGS} AS tl");
      $this->db->join("{$this->_TBL_USER} AS tu", "tl.tl_tu_idx = tu.tu_idx", "INNER");
      $this->db->join("{$this->_TBL_USER_GRADE} AS tug", "tu.tu_tug_idx = tug.tug_idx", "INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te", "tu.tu_te_idx = te.te_idx", "INNER");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr", "tecr.tecr_te_idx = te.te_idx", "INNER");
      $this->db->join("{$this->_TBL_POSITION} AS tp","tp.tp_idx = tecr.tecr_tp_idx", "INNER");
      $this->db->order_by("tl.tl_date_created", "DESC");
      $this->db->limit($alimit['limit'], $alimit['offset']);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_list_count()
   {
      // $ssql = "SELECT COUNT(*) as total_rows
      // FROM {$this->_TBL_LOGS} AS tl 
      // INNER JOIN {$this->_TBL_USER} AS tu
      // ON tl.tl_tu_idx = tu.tu_idx
      // INNER JOIN {$this->_TBL_USER_GRADE} as tug 
      // ON tu.tu_tug_idx = tug.tug_idx
      // INNER JOIN {$this->_TBL_EMPLOYEE} AS te
      // ON tu.tu_te_idx = te.te_idx";
      // $query = $this->db->query($ssql);
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_LOGS} AS tl");
      $this->db->join("{$this->_TBL_USER} AS tu", "tl.tl_tu_idx = tu.tu_idx", "INNER");
      $this->db->join("{$this->_TBL_USER_GRADE} AS tug", "tu.tu_tug_idx = tug.tug_idx", "INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te", "tu.tu_te_idx = te.te_idx", "INNER");
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function insert_log( $smessage_log, $stransact_type, $sapp_type )
   {
      $adata = array(
         'tl_tu_idx' =>  $this->_iuseridx,
         'tl_message_log' => $smessage_log,
         'tl_transact_type' => $stransact_type,
         'tl_app_type' => $sapp_type,
         'tl_ip' => $_SERVER['REMOTE_ADDR'],
         'tl_date_created' => time()
      );
      return $this->db->insert($this->_TBL_LOGS, $adata);
   }
   
   public function get_logs_list_export()
   {
      // $ssql = "SELECT 
      // te.te_fname AS user,
      // tug.tug_name AS user_level,
      // tl.tl_message_log AS message_log,
      // tl.tl_message_from as message_from,
      // tl.tl_message_to as message_to,
      // tp.tp_position AS position,
      // DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y' ) AS date_created
      // FROM {$this->_TBL_LOGS} AS tl 
      // INNER JOIN {$this->_TBL_USER} AS tu
      // ON tl.tl_tu_idx = tu.tu_idx
      // INNER JOIN {$this->_TBL_USER_GRADE} as tug 
      // ON tu.tu_tug_idx = tug.tug_idx
      // INNER JOIN {$this->_TBL_EMPLOYEE} AS te
      // ON tu.tu_te_idx = te.te_idx 
      // INNER JOIN {$this->_TBL_EMP_COMP_REC} AS tecr
      // ON tecr.tecr_te_idx = te.te_idx
      // INNER JOIN {$this->_TBL_POSITION} AS tp
      // ON tp.tp_idx = tecr.tecr_tp_idx
      // ORDER BY tl.tl_date_created DESC";
      // $query = $this->db->query($ssql);
      $this->db->select("te.te_fname AS user,
                  tug.tug_name AS user_level,
                  tl.tl_message_log AS message_log,
                  tl.tl_message_from as message_from,
                  tl.tl_message_to as message_to,
                  tp.tp_position AS position,
                  DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y' ) AS date_created", FALSE);
      $this->db->from("{$this->_TBL_LOGS} AS tl");
      $this->db->join("{$this->_TBL_USER} AS tu", "tl.tl_tu_idx = tu.tu_idx", "INNER");
      $this->db->join("{$this->_TBL_USER_GRADE} AS tug", "tu.tu_tug_idx = tug.tug_idx", "INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE} AS te", "tu.tu_te_idx = te.te_idx", "INNER");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr", "tecr.tecr_te_idx = te.te_idx", "INNER");
      $this->db->join("{$this->_TBL_POSITION} AS tp", "tp.tp_idx = tecr.tecr_tp_idx", "INNER");
      $this->db->order_by("tl.tl_date_created", "DESC");
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_table($stable, $sidx)
   {
      $query = $this->db->query("SELECT * FROM {$stable} WHERE {$sidx}");
      return $query->row_array();
   }
   
   public function insert_expense_log( $smessage_log, $smessage_from, $smessage_to )
   {
      $adata = array(
         'tl_tu_idx' =>  $this->_iuseridx,
         'tl_message_log' => "{$smessage_log}",
         'tl_message_from' => $smessage_from,
         'tl_message_to' => $smessage_to,
         'tl_transact_type' => 'UPDATE',
         'tl_app_type' => 'expense',
         'tl_ip' => $_SERVER['REMOTE_ADDR'],
         'tl_date_created' => time()
      );
      return $this->db->insert($this->_TBL_LOGS, $adata);
   }
}