<?php

class Budget_planning_model extends CI_Model
{
    private $tb_categories = 'tbl_expense_category';
    private $tb_explist = 'tbl_expense_list';
    private $tb_expected = 'tbl_expected_expenses';
    private $tbl_bcomments = 'tbl_expense_bcomment';
    private $tbl_planning = 'tbl_expenses_planning';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /*Get all saved total planned*/
    public function get_budget_planning()
    {        
        $this->db->select('tep_idx, SUM(tep_planned_amount) AS total_planned_amount, DATE_FORMAT(tep_expected_date, "%M") AS tep_month, YEAR(tep_expected_date) AS tep_year', FALSE);
        $this->db->from($this->tbl_planning);     
        $this->db->group_by('tep_month, tep_year');
        $query = $this->db->get();            
        $aTotalPlanned = $query->result();
        
        foreach($aTotalPlanned as $kplanned=>$vplanned){
            $this->db->update($this->tbl_bcomments, array('teb_total' => $vplanned->total_planned_amount), array('teb_month' => (string)$vplanned->tep_month, 'teb_year' => (string)$vplanned->tep_year)); 
        }
        return $this->db->affected_rows();
    }
    
    /*CHECK IF THE EXPECTED EXPENSE EXISTS*/
    public function check_expected_expense($iIdx = null)
    {        
        $this->db->where('MONTH(CURRENT_DATE + INTERVAL 1 MONTH) = MONTH(tep_expected_date)');
        $this->db->where('YEAR(CURRENT_DATE + INTERVAL 1 MONTH) = YEAR(tep_expected_date)');
        if(!empty($iIdx)){        
            $this->db->where('tep_idx = '.$iIdx);
        }
        $this->db->from($this->tbl_planning);
        return $this->db->count_all_results();
    }
    
    /*Get Last month's total payment*/
    public function get_lastmonth_payment(){
        $this->db->select('SUM(tep_payment_amount) AS total_payment');
        $this->db->from($this->tbl_planning);
        $this->db->where('MONTH(tep_expected_date) = MONTH(CURRENT_DATE)');
        $this->db->where('YEAR(tep_expected_date) = YEAR(CURRENT_DATE)'); 
        $this->db->group_by('MONTH(tep_expected_date), YEAR(tep_expected_date)');
        $query = $this->db->get();            
        return $query->row()->total_payment;
    }
    
    /*LISTING THE CATEGORIES*/
    public function get_category()
    {
        $this->db->select('tec_idx, tec_name');
        $this->db->from($this->tb_categories);
        $query = $this->db->get();
        return $query->result();        
    }
    
    /*GET the UB Current Balance for excel file*/
    public function get_current_balance()
    {
        $this->db->select('SUM(tel_deposit_amt - tel_transfer_amt) - SUM(tel_returned_amt) AS current_bal');
        $this->db->from($this->tb_explist);
        $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tel_date), "%m") = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)'); //MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
        $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)');
        $query = $this->db->get();
        return $query->row()->current_bal;        
    } 
    
    /*GET the UB Current Balance for excel file*/
    public function get_total_cash_onhand()
    {
        $this->db->select('SUM(tel_receive_amt - tel_payment) - SUM(tel_deposit_amt) AS total_cash_onhand'); 
        $this->db->from($this->tb_explist);
        $this->db->where('tel_type = "expenses"'); 
        $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tel_date), "%m") = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)');
        $this->db->where('DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)');
        $query = $this->db->get();
        return $query->row()->total_cash_onhand;        
    }
    
    public function _remap()
    {
        show_404();
    }
    
    /*GET COMMENTS*/
    public function get_comment_total()
    {
        $this->db->select('teb_idx')->from($this->tbl_bcomments)->where('teb_year', date('Y'));
        return $this->db->get()->row();               
    }    
    
    /*Get all saved expected expenses*/
    public function get_expected_expenses($alimit_expected = NULL)
    {        
        $this->db->select('tep_idx, tec_name, tep_tec_idx, tep_planned_amount, tep_payment_amount, tep_desc, tep_expected_date');
        $this->db->from('tbl_expenses_planning');
        $this->db->join('tbl_expense_category', 'tec_idx = tep_tec_idx', 'inner');
        $this->db->where('MONTH(CURRENT_DATE + INTERVAL 1 MONTH) = MONTH(tep_expected_date)');
        $this->db->where('YEAR(CURRENT_DATE + INTERVAL 1 MONTH) = YEAR(tep_expected_date)');
        if($alimit_expected != NULL){
            $this->db->limit($alimit_expected['limit'], $alimit_expected['offset']);  
        }        
        $query = $this->db->get();            
        return $query->result();
    }
    
    /*Delete All Expenses*/
    public function delete_expected()
    {
        $query = $this->db->empty_table($this->tb_expected);
        return $query;
    }
    
    /*Delete Expected Expenses*/
    public function delete_expected_expense($iexp_idx)
    {
        $query = $this->db->query('DELETE FROM tbl_expenses_planning WHERE tep_idx IN ('.$iexp_idx.')');
        return $this->db->affected_rows();
    }
    
    public function get_sort()
    {
        $sort_by = $this->input->get('sort');    
        if($sort_by == 'amnt_l_h'){
            $sort_by = $this->db->order_by('tep_planned_amount', 'ASC');
        } else if($sort_by == 'amnt_h_l'){
            $sort_by = $this->db->order_by('tep_planned_amount', 'DESC');
        } else if($sort_by == 'recent'){
            $sort_by = $this->db->order_by('tep_expected_date', 'DESC');
        } else if($sort_by == 'oldest'){
            $sort_by = $this->db->order_by('tep_expected_date', 'ASC');
        } else {
            $sort_by = $this->db->order_by('tep_expected_date', 'DESC');
        }
        return $sort_by;
    }
    
    public function get_date_sort()
    {
        $ddate_from = $this->input->get('from');  
        $ddate_to = $this->input->get('to');  
        
        if($ddate_from == "" || $ddate_to == "") {
            $ddate_sort = "";
        } else {
            $cal_from = explode("/", $this->input->get('from'));
            $cal_to = explode("/", $this->input->get('to'));
            $ddate_sort = "";
            if(!empty($cal_from[1]) && !empty($cal_from[0]) && !empty($cal_to[1]) && !empty($cal_to[0])){
                $ilastday = (int)date('t',strtotime($cal_to[0].'-01-'.$cal_to[1]));  
                $d_from = $cal_from[1]."-".$cal_from[0]."-01";
                $d_to = $cal_to[1]."-".$cal_to[0]."-".$ilastday;
                
                if(strtotime($d_from) > strtotime($d_to)) {
                    $ilastday = (int)date('t',strtotime($cal_from[0].'-1-'.$cal_from[1]));  
                    $d_from = $cal_to[1]."-".$cal_to[0]."-01";
                    $d_to = $cal_from[1]."-".$cal_from[0]."-".$ilastday;
                }          
                $ddate_sort = $this->db->where('tep_expected_date BETWEEN "'.(string)$d_from.'" AND "'.(string)$d_to.'"');    
            }
        }        
        return $ddate_sort;
    }
    
    /*Planned Year*/
    public function plannedyear()
    {
        $cal_info = cal_info(0);
        $amonths = $cal_info['months'];
        
        $icount = $this->get_comment_total();
        if(empty($icount)){
            for($i=1;$i<=count($amonths);$i++){
                $data = array(
                    'teb_total' => 0,
                    'teb_month' => $amonths[$i],
                    'teb_comment' => "",
                    'teb_year'  => date('Y')
                );
                $this->db->insert($this->tbl_bcomments, $data); 
            }
        }
    }
    
    /*DISPLAYING THE RECENT EXPENSES LIST*/
    public function get_lists($smonth, $syear)
    {       
        $ddate_sort = $this->get_date_sort();
        $ssort_by = $this->get_sort();
        
        // $query = $this->db->query(
            // 'SELECT tel_idx, c.tec_name, tel_type, tel_payment, l.tel_tec_idx,
            // DATE_FORMAT(FROM_UNIXTIME(tel_date), "%b") AS tel_month,
            // DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") AS tel_yr
            // FROM '.$this->tb_explist.' l
            // INNER JOIN '.$this->tb_categories.' c ON c.tec_idx = l.tel_tec_idx
            // WHERE tel_type = "expenses"
            // AND DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") = "'.(string)$smonth.'" 
            // AND DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") = "'.(string)$syear.'" 
            // '.$ddate_sort);
            
        $this->db->select('tep_idx, tec_name, tep_tec_idx, tep_planned_amount, tep_payment_amount, tep_desc, tep_expected_date, 
                MONTH(tep_expected_date) AS tel_month, 
                YEAR(tep_expected_date) AS tel_year');
        $this->db->from('tbl_expenses_planning');
        $ddate_sort;
        $this->db->join('tbl_expense_category', 'tec_idx = tep_tec_idx', 'inner');
        
        
        if($this->input->get('cutoff_from')!=""){
            $this->get_cutoff($smonth, $syear);
        } else {
            $this->db->where('MONTH(tep_expected_date) = '.$smonth);
            $this->db->where('YEAR(tep_expected_date) = '.$syear);
        }
        
        $ssort_by;
        $query = $this->db->get(); 
 
        return $query->result();
    }
    
    public function get_cutoff($smonth, $syear)
    {
        $scutoff = $this->input->get('cutoff_from');
        $scutto = $this->input->get('cutoff_to');
        $scutOffFrom = $syear."-".$smonth."-".$scutoff; 
        $scutOffTo   = $syear."-".$smonth."-".$scutto;
        
        if ($scutoff == $scutto || $scutoff > $scutto){ 
            $scutOffTo = date('Y-m-d', strtotime($scutOffTo . "+1 month")); 
        }            
        $sdateRange = "tep_expected_date BETWEEN '".$scutOffFrom."' AND '".$scutOffTo."'";    
        $this->db->where($sdateRange, NULL, FALSE);  
    }
    
    public function get_months($alimit)
    {
        if(isset($_GET['limit'])) {
            $ilimit = $this->input->get('limit');
            $ioffset = $this->input->get('offset');            
        } else {
            $ilimit = $alimit['limit'];
            $ioffset = $alimit['offset'];
        }        
        // SELECT MONTH(tep_expected_date) AS tel_month, 
        // YEAR(tep_expected_date) AS tel_year, SUM(tep_planned_amount) AS total
        // FROM tbl_expenses_planning
        // GROUP BY tel_month, tel_year
        
        $this->db->select('MONTH(tep_expected_date) AS tel_month, YEAR(tep_expected_date) AS tel_year, SUM(tep_planned_amount) AS total, SUM(tep_payment_amount) AS total_payment', FALSE);
        $this->db->from($this->tbl_planning);        
        $this->db->where('tep_expected_date < "'.date("Y/m/01",strtotime("+1 month")).'"');
        $this->get_date_sort();
        $this->db->group_by('tel_month, tel_year');
                 
        $this->get_sort();
        $this->db->limit($ilimit, $ioffset);        
        $query = $this->db->get();
        
        return $query->result();        
    }
    
    /*GET TOTAL COUNT*/
    public function get_count()
    {
        $ddate_sort = $this->get_date_sort();
        $this->db->select('MONTH(tep_expected_date) AS tel_month, YEAR(tep_expected_date) AS tel_year, SUM(tep_planned_amount) AS total', FALSE);
        $this->db->from($this->tbl_planning);
        $ddate_sort;
        $this->db->group_by('tel_month, tel_year');                
        $query = $this->db->get();
        
        return $query->num_rows();   
    }
}

?>