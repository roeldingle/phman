<?php

class Hr_model extends CI_Model
{
   private $_TBL_USER = 'tbl_user';
   private $_TBL_USER_GRADE = 'tbl_user_grade';
   private $_TBL_EMPLOYEE = 'tbl_employee';
   private $_TBL_EMP_COMP_REC = 'tbl_employee_company_record';
   private $_TBL_DEPARTMENT = 'tbl_department';
   private $_TBL_LOGS = 'tbl_logs';
   private $_TBL_POSITION = 'tbl_position';
   private $_TBL_EMPLOYEE_WORK_STATUS = 'tbl_employee_work_status';
   private $_TBL_LEAVE_TARDINESS = 'tbl_leave_tardiness';
   private $_TBL_LEAVE_TARDINESS_TYPE = 'tbl_leave_tardiness_type';
   private $_iuseridx;
   private $_iemployeeidx;
   
   public function __construct()
   {
      parent::__construct();
   }
   
   public function get_department()
   {
      $this->db->select("*");
      $this->db->from($this->_TBL_DEPARTMENT);
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_current_employees( $iidx )
   {
      // $this->db->select("COUNT(*) as total_rows");
      // $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      // $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr","tecr.tecr_te_idx = te.te_idx","INNER");
      // $this->db->join("{$this->_TBL_EMPLOYEE_WORK_STATUS} AS tews","tews.tws_idx = tecr.tecr_tews_work_status","LEFT");
      // $this->db->where("tecr.tecr_td_idx", $iidx);
      // $this->db->where("te.te_active", 1);
      // $this->db->where("tews.tws_status_name !=", 'Resigned');
      // $query = $this->db->get();
      // return $query->row()->total_rows;
      
      $this->db->select("*");
      $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr","tecr.tecr_te_idx = te.te_idx","INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE_WORK_STATUS} AS tews","tews.tws_idx = tecr.tecr_tews_work_status","LEFT");
      $this->db->where("tecr.tecr_td_idx", $iidx);
      $this->db->where("te.te_active", 1);
      $this->db->where("tews.tws_status_name !=", 'Resigned');
      $this->db->group_by('tecr.tecr_te_idx');
      $query = $this->db->get();
      return $query->result();
   }
   
   public function get_probationary_employees( $iidx )
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr","tecr.tecr_te_idx = te.te_idx","INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE_WORK_STATUS} AS tews","tews.tws_idx = tecr.tecr_tews_work_status","LEFT");
      $this->db->where("tecr.tecr_td_idx", $iidx);
      $this->db->where("te.te_active", 1);
      $this->db->where("tews.tws_status_name", 'Probationary');
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_hired_employees( $sdate )
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_EMPLOYEE} as te");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} as tecr","te.te_idx = tecr.tecr_te_idx","INNER");
      $this->db->where("DATE_FORMAT(tecr.tecr_date_started,'%Y-%m')", $sdate);
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_retired_employees( $sdate )
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr","tecr.tecr_te_idx = te.te_idx","INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE_WORK_STATUS} AS tews","tews.tws_idx = tecr.tecr_tews_work_status","LEFT");
      $this->db->where("DATE_FORMAT(tecr.tecr_date_started,'%Y-%m')", $sdate);
      $this->db->where("te.te_active", 1);
      $this->db->where("tews.tws_status_name", 'Resigned');
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_new_employees( $iidx )
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr","tecr.tecr_te_idx = te.te_idx","INNER");
      $this->db->join("{$this->_TBL_EMPLOYEE_WORK_STATUS} AS tews","tews.tws_idx = tecr.tecr_tews_work_status","LEFT");
      $this->db->where("tecr.tecr_td_idx", $iidx);
      $this->db->where("te.te_active", 1);
      $this->db->where("tews.tws_status_name != ", 'Resigned');
      $this->db->where("DATE_FORMAT(tecr.tecr_date_started,'%Y-%m-%d') BETWEEN '" . date("Y-m-d", strtotime("-30 days") )  . "' AND DATE_FORMAT(NOW(),'%Y-%m-%d')" );
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_tardiness($sdate)
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      $this->db->join("{$this->_TBL_LEAVE_TARDINESS} AS tlt", "tlt.tlt_te_idx = te.te_idx","RIGHT");
      $this->db->join("{$this->_TBL_LEAVE_TARDINESS_TYPE} AS tltt", "tltt.tltt_idx = tlt.tlt_tltt_type","RIGHT");
      $this->db->where("te.te_active", 1);      
      $this->db->where("tltt.tltt_type",'Tardy');
      $this->db->where("DATE_FORMAT(tlt.tlt_date,'%Y-%m')", $sdate);
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
   
   public function get_absences( $iidx )
   {
      $this->db->select("COUNT(*) as total_rows");
      $this->db->from("{$this->_TBL_EMPLOYEE} AS te");
      $this->db->join("{$this->_TBL_EMP_COMP_REC} AS tecr","tecr.tecr_te_idx = te.te_idx","INNER");
      $this->db->join("{$this->_TBL_LEAVE_TARDINESS} AS tlt", "tlt.tlt_te_idx = te.te_idx","RIGHT");
      $this->db->join("{$this->_TBL_LEAVE_TARDINESS_TYPE} AS tltt", "tltt.tltt_idx = tlt.tlt_tltt_type","RIGHT");
      $this->db->where("tecr.tecr_td_idx", $iidx);
      $this->db->where("te.te_active", 1);
      $this->db->or_where_in("tltt.tltt_type",array('LWOP', 'AWOL'));
      $this->db->where("DATE_FORMAT(tecr.tecr_date_started,'%Y-%m-%d') BETWEEN '" . date("Y-m-d", strtotime("-6 months") )  . "' AND DATE_FORMAT(NOW(),'%Y-%m-%d')" );
      $query = $this->db->get();
      return $query->row()->total_rows;
   }
}