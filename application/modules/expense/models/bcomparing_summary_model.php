<?php

class Bcomparing_summary_model extends CI_Model
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
    
    public function _remap()
    {
        show_404();
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
    
    /*Count years*/
    public function count_years($sfrom, $sto)
    {
        $sdate = $this->get_date_sort($sfrom, $sto);
        $query = $this->db->query(
        'SELECT COUNT(DISTINCT DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y")) AS total_years
        FROM '.$this->tb_explist.' 
        WHERE tel_type = "expenses" '.$sdate);
        return $query->row()->total_years;  
    }
    
    /*Display years*/
    public function get_years($alimit, $sfrom, $sto)
    {
        $sdate = $this->get_date_sort($sfrom, $sto);
        $query = $this->db->query(
        'SELECT DISTINCT DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") AS teb_year 
        FROM '.$this->tb_explist.' 
        WHERE tel_type = "expenses" '.$sdate.' 
        ORDER BY teb_year ASC
        LIMIT '.$alimit['limit'] . ' OFFSET ' . $alimit['offset']);
        return $query->result();  
    }

    /*Planned budget*/
    public function total_planned_amount($syear, $sfrom, $sto)
    {
        $sdate = $this->get_date_sort($sfrom, $sto);
        $query = $this->db->query(
        'SELECT tep_idx, SUM(tep_planned_amount) AS total_planned_amount,  MONTH(tep_expected_date), YEAR(tep_expected_date)
        FROM tbl_expenses_planning
        WHERE YEAR(tep_expected_date) = "'.$syear.'" 
        '.$sdate.'
        GROUP BY MONTH(tep_expected_date), YEAR(tep_expected_date)');
        return $query->row()->total_planned_amount;  
    } 
    
    /*List*/
    public function get_lists($syear, $sfrom, $sto)
    {
        $sdate = $this->get_date_sort($sfrom, $sto);
        $query = $this->db->query(
        'SELECT b.teb_year, DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") AS tel_month, DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") AS tel_year,
        SUM(tel_payment) AS real_exp, b.teb_comment AS COMMENT, 
        b.teb_total AS planned_budget, (b.teb_total - SUM(tel_payment)) AS difference 
        FROM tbl_expense_list l 
        LEFT OUTER JOIN tbl_expense_bcomment b ON b.teb_month = DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") 
        AND DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y")  = b.teb_year
        WHERE l.tel_type = "expenses" AND b.teb_year = "'.$syear.'" 
        '.$sdate.'
        GROUP BY tel_month, tel_year
        ORDER BY tel_date');
        return $query->result();  
    }
    
    /*Date Sorting*/
    public function get_date_sort($sfrom, $sto)
    {
        if($sfrom == "" || $to == "") {
            $ddate_sort = "";
        } else {
            $cal_from = explode("/", $sfrom);
            $cal_to = explode("/", $sto);
            
            $ddate_sort = "";
            if(!empty($cal_from[1]) && !empty($cal_from[0]) && !empty($cal_to[1]) && !empty($cal_to[0])){
                $ilastday = cal_days_in_month(CAL_GREGORIAN, $cal_to[0], $cal_to[1]);         
                $d_from = strtotime($cal_from[0]."/01/".$cal_from[1]);
                $d_to = strtotime($cal_to[0]."/".$ilastday."/".$cal_to[1]);
                
                if($d_from > $d_to) {
                    $ilastday = cal_days_in_month(CAL_GREGORIAN, $cal_from[0], $cal_from[1]); 
                    $d_from = strtotime($cal_to[0]."/01/".$cal_to[1]);
                    $d_to = strtotime($cal_from[0]."/".$ilastday."/".$cal_from[1]);
                }     
                $ddate_sort = "AND tel_date BETWEEN ".$d_from." AND ". $d_to;                     
            }                
        }        
        return $ddate_sort;
    }
    
    /*Get Total Amounts*/
    public function get_total_amounts($syear, $sfrom, $sto)
    {
        $aresult = array();
        $adiff = 0;
        $arealexp = 0;
        $aplannedbudget = 0;
        
        $alists = $this->get_lists($syear, $sfrom, $sto);
        for($smonth=0;$smonth<12;$smonth++){
            if(!empty($alists[$smonth]->planned_budget)){
                $adiff += $alists[$smonth]->planned_budget - $alists[$smonth]->real_exp;
                if($alists[$smonth]->real_exp != null){
                    $arealexp += $alists[$smonth]->real_exp;
                }
                if($alists[$smonth]->planned_budget != null){
                    $aplannedbudget += $alists[$smonth]->planned_budget;
                }
            }
        }
        $aresult['total_difference'] = $adiff;
        $aresult['total_planned_budget'] = $aplannedbudget;
        $aresult['total_real'] = $arealexp;
        return $aresult;
    }
    
}

?>