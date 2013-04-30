<?php

class Bcomparing_view_model extends CI_Model
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
    
    public function get_sort()
    {
        $sort_by = $this->input->get('sort');    
        if ($sort_by =='positive_diff'){
            $sort_by = 'difference DESC';
        } else if($sort_by == 'negative_diff'){
            $sort_by = 'difference ASC';
        } else if($sort_by == 'recent'){
            $sort_by = 'tel_date DESC';
        } else if($sort_by == 'oldest'){
            $sort_by = 'tel_date ASC';
        } else {
            $sort_by = 'tel_date DESC';
        }
        return $sort_by;
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
    
    public function get_keyword()
    {
        $skeyword = $this->input->get('keyword');  
        
        if($skeyword == ""){
            $skeyword = "";
        } else {
            $skeyword  = " AND c.tec_name LIKE '%".$skeyword."%' ";
        }
        
        return $skeyword;
    }
    
    /*GET COMMENTS*/
    public function get_comment_total($smonth)
    {
        $this->db->select('teb_idx, teb_comment, teb_total')->from($this->tbl_bcomments)->where('teb_month', $smonth);
        return $this->db->get()->row();               
    }
    
    /*DISPLAYING THE RECENT EXPENSES LIST*/
    public function get_lists($smonth, $syear)
    {       
        $ddate_sort = $this->get_date_sort();
        $ssort_by = $this->get_sort();
        $skeyword = $this->get_keyword();
        
        $query = $this->db->query(
            'SELECT tel_idx, c.tec_name, tel_type, tel_payment, l.tel_tec_idx,            
            DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") AS tel_month, tel_date
            FROM '.$this->tb_explist.' l
            INNER JOIN '.$this->tb_categories.' c ON c.tec_idx = l.tel_tec_idx
            WHERE tel_type = "expenses"
            AND DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") = "'.(string)$smonth.'" 
            AND DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") = "'.(string)$syear.'" 
            '.$ddate_sort.$skeyword);
        return $query->result();         
    }
    

    public function get_months($alimit = null)
    {
        $ddate_sort = $this->get_date_sort();
        $ssort_by = $this->get_sort();
        if(isset($_GET['limit'])) {
            $ilimit = $this->input->get('limit');
            $ioffset = $this->input->get('offset');            
        } else {
            $ilimit = $alimit['limit'];
            $ioffset = $alimit['offset'];
        }
        
        $slimits = ' LIMIT '.$ilimit . ' OFFSET ' . $ioffset;
        
        $query = $this->db->query(
                'SELECT DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") AS tel_month, 
                DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") AS tel_year, SUM(tel_payment) AS total,
                b.teb_idx, b.teb_comment AS comment, b.teb_total AS planned_budget, 
                (b.teb_total - SUM(tel_payment)) AS difference
                FROM '.$this->tb_explist.' l
                LEFT OUTER JOIN '.$this->tbl_bcomments.' b ON b.teb_month = DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M")
                AND b.teb_year = DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y")
                WHERE tel_type = "expenses"
                '.$ddate_sort.'                
                GROUP BY tel_month, tel_year
                ORDER BY '. $ssort_by.$slimits);
        return $query->result(); 
    }
    
    /*GET TOTAL COUNT*/
    public function get_count()
    {
        $ddate_sort = $this->get_date_sort();
        $query = $this->db->query(
                'SELECT DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") AS tel_month, 
                DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") AS tel_year
                FROM '.$this->tb_explist.'
                WHERE tel_type = "expenses"
                '.$ddate_sort.
                ' GROUP BY tel_month, tel_year');
                
        return $query->num_rows();        
    }
}

?>