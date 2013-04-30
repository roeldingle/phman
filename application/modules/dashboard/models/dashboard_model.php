<?php

class Dashboard_model extends CI_Model
{
   private $_TBL_USER = 'tbl_user';
   private $_TBL_USER_GRADE = 'tbl_user_grade';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_TBL_EMP_COMP_REC = 'tbl_employee_company_record';
   private $_TBL_DEPARTMENT = 'tbl_department';
   private $_TBL_LOGS = 'tbl_logs';
   private $_TBL_POSITION = 'tbl_position';
   private $_TBL_DASHBOARD_SEQUENCE = 'tbl_dashboard_sequence';
   private $_TBL_DASHBOARD_USER_SETTINGS = 'tbl_dashboard_user_settings';
   private $_iuseridx;
   private $_iemployeeidx;
   private $_usergrade;
   
   public function __construct()
   {
      parent::__construct();
      $this->_iuseridx = $this->session->userdata('userid');
      $this->_usergrade = $this->session->userdata('usergradeid');
   }
   
   public function get_dashboard_settings()
   {
      $this->db->select("*");
      $this->db->from( $this->_TBL_DASHBOARD_SEQUENCE );
      $this->db->where("tds_tug_idx", $this->_usergrade);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function get_user_settings()
   {
      $this->db->select("*");
      $this->db->from( $this->_TBL_DASHBOARD_USER_SETTINGS );
      $this->db->where("tdus_tu_idx", $this->_iuseridx);
      $query = $this->db->get();
      return $query->row();
   }
   
   public function update_dashboard_user_settings( $ssequence )
   {
      $adata = array(
         'tdus_sequence' => $ssequence
      );
      $this->db->where('tdus_tu_idx', $this->_iuseridx);
      $this->db->update($this->_TBL_DASHBOARD_USER_SETTINGS, $adata,"");
   }
   
   public function insert_dashboard_user_settings( $ssequence )
   {
      $adata = array(
         'tdus_tu_idx' => $this->_iuseridx,
         'tdus_sequence' => $ssequence
      );
      $this->db->insert($this->_TBL_DASHBOARD_USER_SETTINGS, $adata);
   }
   
   public function get_user_logs_list( $swhere, $ilogs_row)
   {
      $ssql = "
         SELECT  
            DATE_FORMAT(FROM_UNIXTIME(tl.tl_date_created),'%m/%d/%Y %H:%i:%s') AS date_created,
            tu.tu_username AS user_id,
            CONCAT( te.te_fname,' ', te.te_mname,' ', te.te_lname) AS full_name,
             tp.tp_position AS position,
             tug.tug_name AS user_level
            FROM 
            {$this->_TBL_LOGS} AS tl 
            INNER JOIN {$this->_TBL_USER} AS tu 
               ON tu.tu_idx = tl.tl_tu_idx
            INNER JOIN {$this->_TBL_EMP_COMP_REC} AS tecr
               ON tecr.tecr_te_idx = tu.tu_te_idx
            INNER JOIN {$this->_TBL_POSITION} AS tp
               ON tp.tp_idx = tecr.tecr_tp_idx
            INNER JOIN {$this->_TBL_EMPLOYEE} AS te
               ON te.te_idx = tecr.tecr_te_idx
            INNER JOIN {$this->_TBL_USER_GRADE} AS tug
               ON tug.tug_idx = tu.tu_tug_idx
            {$swhere}
            GROUP BY tu.tu_idx            
            ORDER BY tl.tl_date_created DESC
            LIMIT 0, {$ilogs_row}
      ";
      $query =  $this->db->query($ssql);
      return $query->result();
   }

   public function get_logs_list( $swhere, $alimit )
   {
     $ssql = "SELECT 
                  te.te_fname AS user,
                  tug.tug_name AS user_level,
                  tl.tl_message_log AS message_log,
                  tl.tl_message_from as message_from,
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
                  {$swhere}
                  ORDER BY tl.tl_date_created DESC
                  LIMIT {$alimit['offset']}, {$alimit['limit']}";
      $query = $this->db->query($ssql);
      return $query->result();
   }   
}