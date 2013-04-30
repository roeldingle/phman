<?php

class Total_cost_report_exec extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->module('core/app');
        $this->load->library('site/PHPExcel/PHPExcel');
    }
    
    public function getYear()
    {
        $ya_ctr = 1;
        //POST variables
        $gsp_from = isset($_POST['gsp_from']) ? $_POST['gsp_from'] : "";
        $gsp_to = isset($_POST['gsp_to']) ? $_POST['gsp_to'] : "";
        $gcop_from = isset($_POST['gcop_from']) ? $_POST['gcop_from'] : "";
        $gcop_to = isset($_POST['gcop_to']) ? $_POST['gcop_to'] : "";
        $view_year = isset($_POST['view_year']) ? $_POST['view_year'] : "";
        
        //array variables
        $months = array('01','02','03','04','05','06','07','08','09','10','11','12');
        $aq1_months = array('01','02','03');
        $aq2_months = array('04','05','06');
        $aq3_months = array('07','08','09');
        $aq4_months = array('10','11','12');
        
        $limit = ($view_year=="0" || $view_year=="all") ? "" : "ORDER BY pyear LIMIT ".$view_year." OFFSET 0";
        $where = ($gsp_from=="0" && $gsp_to=="0") ? "" : "WHERE EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) BETWEEN '{$gsp_from}' AND '{$gsp_to}'";
        
        $year = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM FROM_UNIXTIME(tbl_expense_list.tel_date)) as pyear
                                    FROM tbl_expense_list
                                    {$where}
                                    {$limit}");
        //adata array variables
        $adata = array();
        $ayear = array();
        $adata['year'] = $year->result();
        $adata['total_cost'] = array();
        $adata['annual_savings'] = array();
        $adata['monthly_average'] = array();
        $adata['yearly_average'] = array();
        $adata['quarterly_average_1'] = array();
        $adata['quarterly_average_2'] = array();
        $adata['quarterly_average_3'] = array();
        $adata['quarterly_average_4'] = array();
        
        //get total costs
        foreach($year->result() as $y){
            array_push($ayear, $y->pyear);
            //get total cost
            $total_cost = $this->db->query("SELECT SUM(tel_payment) as total_cost
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-%'");
            array_push($adata['total_cost'], $total_cost->row()->total_cost);
            
            //get annual savings
            $as_year = (int)$y->pyear - 1;
            $syear = (string)$as_year;
           
            if(!in_array($syear, $ayear)){
               array_push($adata['annual_savings'], sprintf("%.2f",0.00));
            }else{
                $tc2_year = (int)$y->pyear - 1;
        
                $total_cost1 = $this->db->query("SELECT SUM(tel_payment) as total_cost1
                                                FROM tbl_expense_list
                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-%'");
                $total_cost2 = $this->db->query("SELECT SUM(tel_payment) as total_cost2
                                                FROM tbl_expense_list
                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$tc2_year}-%'");
                
                $annual_savings = sprintf("%.2f", $total_cost1->row()->total_cost1 - $total_cost2->row()->total_cost2);
                array_push($adata['annual_savings'], $annual_savings);
            }
            
            //get monthly average
            $monthly_average = $this->db->query("SELECT SUM(tel_payment)/12 as monthly_average
                                        FROM tbl_expense_list
                                        WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-%'");
            $monthly_average = sprintf("%.2f", $monthly_average->row()->monthly_average);
            array_push($adata['monthly_average'], $monthly_average);
            
            //get yearly_average
            if(!in_array($syear, $ayear)){
               array_push($adata['yearly_average'], sprintf("%.2f",0.00));
            }else{
                $total_yearly_average = 0;
                for($i=0;$i<=$ya_ctr ;$i++){
                    $fyear = $y->pyear - $i;
                    $yearly_average_query = $this->db->query("SELECT SUM(tel_payment) as yearly_average
                                                FROM tbl_expense_list
                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$fyear}-%'");
                    $total_yearly_average+=$yearly_average_query->row()->yearly_average;
                }
            
                $yearly_average = sprintf("%.2f", $total_yearly_average/$ya_ctr);
                array_push($adata['yearly_average'], $yearly_average);
            }
            
            //get quarterly average
            $total_quarterly_average_1 = 0;
            $total_quarterly_average_2 = 0;
            $total_quarterly_average_3 = 0;
            $total_quarterly_average_4 = 0;
            
            //1st quarter
            foreach($aq1_months as $aq1m){
                $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_1
                                                                FROM tbl_expense_list
                                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-{$aq1m}-%'");
                $total_quarterly_average_1 = sprintf("%.2f",$total_quarterly_average_1 + $quarterly_average_query->row()->quarterly_average_1);
            }
            $quarterly_average_1 = sprintf("%.2f", $total_quarterly_average_1 / 3);
            array_push($adata['quarterly_average_1'], $quarterly_average_1);
            
            //2nd quarter
            foreach($aq2_months as $aq2m){
                $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_2
                                                                FROM tbl_expense_list
                                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-{$aq2m}-%'");
                $total_quarterly_average_2 = sprintf("%.2f",$total_quarterly_average_2 + $quarterly_average_query->row()->quarterly_average_2);
            }
            $quarterly_average_2 = sprintf("%.2f", $total_quarterly_average_2 / 3);
            array_push($adata['quarterly_average_2'], $quarterly_average_2);
            
            //3rd quarter
            foreach($aq3_months as $aq3m){
                $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_3
                                                                FROM tbl_expense_list
                                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-{$aq3m}-%'");
                $total_quarterly_average_3 = sprintf("%.2f",$total_quarterly_average_3 + $quarterly_average_query->row()->quarterly_average_3);
            }
            $quarterly_average_3 = sprintf("%.2f", $total_quarterly_average_3 / 3);
            array_push($adata['quarterly_average_3'], $quarterly_average_3);
            
            //4th quarter
            foreach($aq4_months as $aq4m){
                $quarterly_average_query = $this->db->query("SELECT SUM(tel_payment) AS quarterly_average_4
                                                                FROM tbl_expense_list
                                                                WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '{$y->pyear}-{$aq4m}-%'");
                $total_quarterly_average_4 = sprintf("%.2f",$total_quarterly_average_4 + $quarterly_average_query->row()->quarterly_average_4);
            }
            $quarterly_average_4 = sprintf("%.2f", $total_quarterly_average_4 / 3);
            array_push($adata['quarterly_average_4'], $quarterly_average_4);
            
            $ya_ctr++;
        }
        echo json_encode($adata);
    }
    
    
}
