<?php
class Statistics_model extends CI_Model
{
    private $emp_company_record = 'tbl_employee_company_record';
    private $emp_work_status = 'tbl_employee_work_status';
    private $employees = 'tbl_employee';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->module('settings/logs');
    }

    /*Date Sorting*/
    public function get_date_sort($check)
    {
        $ddate_from = $this->input->get('from');  
        $ddate_to = $this->input->get('to');  
        
        if($ddate_from == "" || $ddate_to == "") {
            $ddate_sort = "";
        } else {
            $cal_from = explode("-", $this->input->get('from'));
            $cal_to = explode("-", $this->input->get('to'));            
            
            $ddate_sort = "";
            if(!empty($cal_from[1]) && !empty($cal_from[0]) && !empty($cal_to[1]) && !empty($cal_to[0])){
                $ilastday = (int)date('t',strtotime($cal_to[0].'/1/'.$cal_to[1]));  
                $d_from = $cal_from[0]."-".$cal_from[1]."-01";
                $d_to = $cal_to[0]."-".$cal_to[1]."-".$ilastday;
                
                if(strtotime($d_from) > strtotime($d_to)) {
                    $ilastday = (int)date('t',strtotime($cal_from[0].'/1/'.$cal_from[1]));  
                    $d_from = $cal_to[0]."-".$cal_to[1]."-01";
                    $d_to = $cal_from[0]."-".$cal_from[1]."-".$ilastday;
                }    
                if($check == 0) { 
                    $ddate_sort = $this->db->where('tecr_date_started BETWEEN "'.$d_from.'" AND "'.$d_to.'"');    
                } else {
                    $ddate_sort = "(tlt_date BETWEEN '".$d_from."' AND '".$d_to."') AND ";
                }
            }
        }        
        return $ddate_sort;
    }
    
    /*VL, Sick Leave, AWOL, LWOP, tardiness*/
    public function get_attendance()
    {
        $squery = "SELECT td_dept_name AS dept_name,
                (SELECT COUNT(tlt_tltt_type) FROM tbl_leave_tardiness LEFT JOIN tbl_employee_company_record AS tecr_te_idx ON tlt_te_idx WHERE ".$this->get_date_sort(1)." 
                (tlt_tltt_type=1 AND tlt_te_idx=tecr_te_idx AND tecr_td_idx=td_idx) ) AS vacation_leave,
                (SELECT COUNT(tlt_tltt_type) FROM tbl_leave_tardiness LEFT JOIN tbl_employee_company_record AS tecr_te_idx ON tlt_te_idx WHERE ".$this->get_date_sort(1)." 
                (tlt_tltt_type=2 AND tlt_te_idx=tecr_te_idx AND tecr_td_idx=td_idx) ) AS sick_leave,
                (SELECT COUNT(tlt_tltt_type) FROM tbl_leave_tardiness LEFT JOIN tbl_employee_company_record AS tecr_te_idx ON tlt_te_idx WHERE ".$this->get_date_sort(1)." 
                (tlt_tltt_type=3 AND tlt_te_idx=tecr_te_idx AND tecr_td_idx=td_idx) ) AS tardiness,
                (SELECT COUNT(tlt_tltt_type) FROM tbl_leave_tardiness LEFT JOIN tbl_employee_company_record AS tecr_te_idx ON tlt_te_idx WHERE ".$this->get_date_sort(1)." 
                (tlt_tltt_type=4 AND tlt_te_idx=tecr_te_idx AND tecr_td_idx=td_idx) ) AS lwop,
                (SELECT COUNT(tlt_tltt_type) FROM tbl_leave_tardiness LEFT JOIN tbl_employee_company_record AS tecr_te_idx ON tlt_te_idx WHERE ".$this->get_date_sort(1)." 
                (tlt_tltt_type=5 AND tlt_te_idx=tecr_te_idx AND tecr_td_idx=td_idx) ) AS awol
                FROM tbl_department";
        $query = $this->db->query($squery); 
        return $query->result();   
    }
    
    /*HIRED EMP*/
    public function get_hired_resigned_employees($syear, $stype = null)
    {
        // SELECT COUNT(tecr_idx) AS total_ids, tecr_date_started AS date_started FROM tbl_employee_company_record r
        // INNER JOIN tbl_employee_work_status s ON s.tws_idx = r.tecr_tews_work_status
        // WHERE s.tws_status_name = "Regular" AND 
        // SUBSTRING(tecr_date_started, 1, 4) = "2011"
        // GROUP BY SUBSTRING(date_started, 1, 7)
        
        $this->db->select('COUNT(tecr_idx) AS total_ids, tecr_date_started AS date_started, SUBSTRING(tecr_date_started, 1, 7) AS group_by', FALSE);
        $this->db->from('tbl_employee_company_record');
        $this->db->join('tbl_employee', 'te_idx = tecr_te_idx', 'inner');
        $this->db->join('tbl_employee_work_status', 'tws_idx = tecr_tews_work_status', 'inner');
        $this->db->where('te_active = 1');
        if($stype!=null || $stype!=""){
            $this->db->where('tws_status_name = "'.$stype.'"');
        } 
        if($this->input->get('from') == ""){
            $this->db->where('SUBSTRING(tecr_date_started, 1, 4) IN ("'.$syear.'")');
        }
        $this->get_date_sort(0);
        $this->db->group_by('group_by');
        $query = $this->db->get();

        return $query->result();
    }
    
    /*PROBATIONARY EMP*/
    public function get_prob_employees($stype = null)
    {
        // SELECT COUNT(tecr_idx) AS total_ids, d.td_dept_name AS dept_name, tecr_date_started AS date_started FROM tbl_department d
        // LEFT OUTER JOIN tbl_employee_company_record r ON d.td_idx = r.tecr_td_idx
        // LEFT OUTER JOIN tbl_employee_work_status s ON s.tws_idx = r.tecr_tews_work_status
        // WHERE s.tws_status_name = "Probationary"
        // GROUP BY d.td_dept_name
        
        $this->db->select('COUNT(tecr_idx) AS total_ids, td_dept_name AS dept_name, tecr_date_started AS date_started', FALSE);
        $this->db->from('tbl_department');
        $this->db->join('tbl_employee_company_record', 'td_idx = tecr_td_idx', 'inner');
        $this->db->join('tbl_employee', 'te_idx = tecr_te_idx', 'inner');
        $this->db->join('tbl_employee_work_status', 'tws_idx = tecr_tews_work_status', 'inner');

        $this->db->where('te_active = 1');
        if($stype!=null || $stype!=""){
            $this->db->where('tws_status_name = "'.$stype.'"');
        }
        $this->get_date_sort(0);
        $this->db->group_by('td_dept_name');
        $query = $this->db->get();

        return $query->result();
    }
    
    /*DEPARTMENTS*/
    public function get_departments()
    {
        $this->db->select('td_dept_name AS dept_name ')->from('tbl_department');
        $query = $this->db->get();

        return $query->result();
    }

    
    /*Display years*/
    public function get_years($alimit = null, $stype)
    {           
        $this->db->select('DISTINCT(SUBSTRING(tecr_date_started, 1, 4)) AS date_started', FALSE);
        $this->db->from('tbl_employee_company_record');
        $this->db->join('tbl_employee_work_status', 'tws_idx = tecr_tews_work_status', 'inner');
        if($stype!=null || $stype!=""){
            $this->db->where('tws_status_name = "'.$stype.'"');
        }
        $this->get_date_sort(0);
        $this->db->order_by('tecr_date_started', 'DESC');
        $this->db->limit($alimit['limit'], $alimit['offset']);
        $query = $this->db->get();
        return $query->result();
    }
    
    /*Count years*/
    public function count_years($stype = null)
    {
        $this->db->select('COUNT(DISTINCT SUBSTRING(tecr_date_started, 1, 4)) AS total_years', FALSE);
        $this->db->from('tbl_employee_company_record');
        $this->db->join('tbl_employee_work_status', 'tws_idx = tecr_tews_work_status', 'inner');
        if($stype!=null || $stype!=""){
            $this->db->where('tws_status_name = "'.$stype.'"');
        }
        $this->get_date_sort(0);
        $query = $this->db->get();
        return $query->row()->total_years;  
    }
    
    /*Display years leaves*/
    public function get_leave_years($alimit = null, $stype)
    {           
        $this->db->select('DISTINCT SUBSTRING(tlt_date, 1, 4) AS tlt_date', FALSE);
        $this->db->from('tbl_leave_tardiness');
        if($stype!=null || $stype!=""){
            $this->db->where($this->get_date_sort(1).'tlt_tltt_type = "'.$stype.'"');
        }
        $this->db->order_by('tlt_date', 'DESC');
        $this->db->limit($alimit['limit'], $alimit['offset']);
        $query = $this->db->get();
        return $query->result();
    }
    
    /*Count years leaves*/
    public function count_leave_years($stype = null)
    {
        $this->db->select('COUNT(DISTINCT SUBSTRING(tlt_date, 1, 4)) AS total_years', FALSE);
        $this->db->from('tbl_leave_tardiness');
        if($stype!=null || $stype!=""){
            $this->db->where($this->get_date_sort(1).'tlt_tltt_type = "'.$stype.'"');
        }
        $query = $this->db->get();
        return $query->row()->total_years;
    }

    /*leaves*/
    public function get_leaves($syear, $stype = null)
    {
        
        $this->db->select('COUNT(tlt_idx) AS total_ids, tlt_date, SUBSTRING(tlt_date, 1, 7) AS group_by', FALSE);
        $this->db->from('tbl_leave_tardiness');
        if($stype!=null || $stype!=""){
            $this->db->where($this->get_date_sort(1).'tlt_tltt_type = "'.$stype.'"');
        } 
        if($this->input->get('from') == ""){
            $this->db->where('SUBSTRING(tlt_date, 1, 4) IN ("'.$syear.'")');
        }
        $this->db->group_by('group_by');
        $query = $this->db->get();

        return $query->result();
    }
    
    
}