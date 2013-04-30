<?php
class Total_cost_report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_year($view,$sp_from,$sp_to)
    {
        if($view=="all" && $sp_from=="" && $sp_to==""){
            $year = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) as pyear
                                    FROM tbl_expense_list ");
        }else{
            $limit_order = ($view=="all") ? "" : "ORDER BY pyear LIMIT ".$view." OFFSET 0";
            $where = ($sp_from=="" && $sp_to=="") ? "" : "WHERE EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) BETWEEN {$sp_from} AND {$sp_to}";
            
            $year = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) as pyear
                                    FROM tbl_expense_list 
                                    {$where} 
                                    {$limit_order}");
            
        }
        
        return $year->result();
    }
    
    public function get_graph_year()
    {
        $year = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) as pyear
                                    FROM tbl_expense_list ");
        return $year->result();
    }
    
    public function get_total_payment($year,$month)
    {
        $total_payment = $this->db->query("SELECT SUM(tel_payment) payment
                                            FROM tbl_expense_list
                                            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-{$month}-%'");
        return sprintf("%.2f",$total_payment->row()->payment);
    }
    
    public function get_total_cost($year)
    {
        $total_cost = $this->db->query("SELECT SUM(tel_payment) as total_cost
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-%'");
        return $total_cost->row()->total_cost;                         
    }
    
    public function get_annual_savings($year)
    {
        $tc2_year = $year - 1;
        
        $total_cost1 = $this->db->query("SELECT SUM(tel_payment) as total_cost1
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-%'");
        $total_cost2 = $this->db->query("SELECT SUM(tel_payment) as total_cost2
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$tc2_year}-%'");
        
        $annual_savings = sprintf("%.2f", $total_cost1->row()->total_cost1 - $total_cost2->row()->total_cost2);
        return $annual_savings;
        
    }
    
    public function get_monthly_average($year)
    {
        $monthly_average = $this->db->query("SELECT SUM(tel_payment)/12 as monthly_average
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-%'");
        return sprintf("%.2f",$monthly_average->row()->monthly_average);  
        
    }
    
    public function get_yearly_average($year,$ya_ctr)
    {
        $total_yearly_average = 0;
        for($i=0;$i<=$ya_ctr ;$i++){
            $fyear = $year - $i;
            $yearly_average_query = $this->db->query("SELECT SUM(tel_payment) as yearly_average
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$fyear}-%'");
            $total_yearly_average+=$yearly_average_query->row()->yearly_average;
        }
        
        $yearly_average = sprintf("%.2f", $total_yearly_average/$ya_ctr);
        return $yearly_average;
    }
    
    public function get_quarterly_average1($year,$aq1_months)
    {
        $total_quarterly_average_1 = 0;
        foreach($aq1_months as $aq1m){
            $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_1
                                                            FROM tbl_expense_list
                                                            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-{$aq1m}-%'");
            $total_quarterly_average_1 = sprintf("%.2f",$total_quarterly_average_1 + $quarterly_average_query->row()->quarterly_average_1);
        }
        $quarterly_average_1 = sprintf("%.2f", $total_quarterly_average_1 / 3);
        return $quarterly_average_1;
        
    }
    
    public function get_quarterly_average2($year,$aq2_months)
    {
        $total_quarterly_average_2 = 0;
        foreach($aq2_months as $aq2m){
            $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_2
                                                            FROM tbl_expense_list
                                                            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-{$aq2m}-%'");
            $total_quarterly_average_2 = sprintf("%.2f",$total_quarterly_average_2 + $quarterly_average_query->row()->quarterly_average_2);
        }
        $quarterly_average_2 = sprintf("%.2f", $total_quarterly_average_2 / 3);
        return $quarterly_average_2;
        
    }
    
    public function get_quarterly_average3($year,$aq3_months)
    {
        $total_quarterly_average_3 = 0;
        foreach($aq3_months as $aq3m){
            $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_3
                                                            FROM tbl_expense_list
                                                            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-{$aq3m}-%'");
            $total_quarterly_average_3 = sprintf("%.2f",$total_quarterly_average_3 + $quarterly_average_query->row()->quarterly_average_3);
        }
        $quarterly_average_3 = sprintf("%.2f", $total_quarterly_average_3 / 3);
        return $quarterly_average_3;
        
    }
    
    public function get_quarterly_average4($year,$aq4_months)
    {
        $total_quarterly_average_4 = 0;
        foreach($aq4_months as $aq4m){
            $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_4
                                                            FROM tbl_expense_list
                                                            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$year}-{$aq4m}-%'");
            $total_quarterly_average_4 = sprintf("%.2f",$total_quarterly_average_4 + $quarterly_average_query->row()->quarterly_average_4);
        }
        $quarterly_average_4 = sprintf("%.2f", $total_quarterly_average_4 / 3);
        return $quarterly_average_4;
        
    }
    
    public function get_current_monthly_average()
    {
        $previous_month = date('m',strtotime('-1 Months'));
        $current_year = date('Y');
        $imonth = (int)$previous_month;
        
        $current_monthly_average = $this->db->query("SELECT SUM(tel_payment) AS current_monthly_average
                                                        FROM tbl_expense_list
                                                        WHERE CONCAT(EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),'-',LPAD(EXTRACT(MONTH FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),2,'0')) BETWEEN '{$current_year}-01' AND '{$current_year}-{$previous_month}'");
        return sprintf("%.2f", $current_monthly_average->row()->current_monthly_average / $imonth);
    }
    
    public function get_previous_current_monthly_average()
    {
        $previous_month = date('m',strtotime('-1 Months'));
        $current_year = date('Y',strtotime('-1 Years'));
        $imonth = (int)$previous_month;
        
        $previous_current_monthly_average = $this->db->query("SELECT SUM(tel_payment) AS previous_current_monthly_average
                                                        FROM tbl_expense_list
                                                        WHERE CONCAT(EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),'-',LPAD(EXTRACT(MONTH FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),2,'0')) BETWEEN '{$current_year}-01' AND '{$current_year}-{$previous_month}'");
        return sprintf("%.2f", $previous_current_monthly_average->row()->previous_current_monthly_average / $imonth);
    }
    
    public function get_previous_current_quarterly_average()
    {
        $a1q = array('01','02','03');
        $a2q = array('04','05','06');
        $a3q = array('07','08','09');
        $a4q = array('10','11','12');
        
        $previous_month = date('m',strtotime('-1 Months'));
        $current_year = date('Y',strtotime('-1 Years'));
        
        if(in_array($previous_month,$a1q)){
            $where = "CONCAT(EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),'-',LPAD(EXTRACT(MONTH FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),2,'0')) BETWEEN '{$current_year}-01' AND '{$current_year}-03'";
        }elseif(in_array($previous_month,$a2q)){
            $where = "CONCAT(EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),'-',LPAD(EXTRACT(MONTH FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),2,'0')) BETWEEN '{$current_year}-04' AND '{$current_year}-06'";
        }elseif(in_array($previous_month,$a3q)){
            $where = "CONCAT(EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),'-',LPAD(EXTRACT(MONTH FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),2,'0')) BETWEEN '{$current_year}-07' AND '{$current_year}-09'";
        }else{
            $where = "CONCAT(EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),'-',LPAD(EXTRACT(MONTH FROM FROM_UNIXTIME(tbl_expense_list.tel_date)),2,'0')) BETWEEN '{$current_year}-10' AND '{$current_year}-12'";
        }
        
        $previous_current_quarterly_average = $this->db->query("SELECT SUM(tel_payment) AS previous_current_quarterly_average
                                                        FROM tbl_expense_list
                                                        WHERE ".$where);
        return sprintf("%.2f", $previous_current_quarterly_average->row()->previous_current_quarterly_average / 3);
    }
}