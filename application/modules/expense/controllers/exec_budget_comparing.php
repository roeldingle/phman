<?php

class Exec_budget_comparing extends MX_Controller
{
    private $tbl_bcomments = 'tbl_expense_bcomment';
    
    public function __construct()
    {
       parent::__construct();
       $this->load->model("bcomparing_view_model");
       $this->load->model("bcomparing_summary_model");
       $this->load->module('core/app');
    } 
   
    public function _remap()
    {
       show_404();
    } 
    
    public function add_comments()
    {
        $scomments = $this->input->post('comments');  
        $smonthyear = explode(',', $this->input->post('monthyear'));
        $smonth = $smonthyear[0];
        $syear = $smonthyear[1];
        
        $data = array(
            'teb_comment' => $scomments,
            'teb_month' => $smonth,
            'teb_year' => $syear
        );
        
        $icount = $this->bcomparing_view_model->get_comment_total($smonth);
        if(!empty($icount)){
            $sResult = $this->db->update($this->tbl_bcomments, $data, array('teb_month' => $smonth)); 
        } else {
            $sResult = $this->db->insert($this->tbl_bcomments, $data); 
        }
        echo json_encode($sResult);        
    }

    public function get_summary_graph()
    {
        $ayearsummary = array();
        $sdatefrom = $this->input->post('calendar_from');  
        $sdateto = $this->input->post('calendar_to');
        $alimit = array("offset"=> $this->input->post('offset'), "limit"=> $this->input->post('limit'));          
        $ayears = $this->bcomparing_summary_model->get_years($alimit, $sdatefrom, $sdateto);        
        foreach($ayears as $kyear => $vyear){
            array_push($ayearsummary, $vyear->teb_year);
        }
        $syear = implode(',', $ayearsummary);
        
        $sdate = $this->bcomparing_summary_model->get_date_sort($sdatefrom, $sdateto);
        $query = $this->db->query(
                'SELECT DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y") AS tel_year, DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M") AS tel_month, 
                SUM(tel_payment) AS real_exp, b.teb_total AS planned_budget
                FROM tbl_expense_list l
                LEFT OUTER JOIN tbl_expense_bcomment b ON b.teb_month = DATE_FORMAT(FROM_UNIXTIME(tel_date), "%M")
                AND DATE_FORMAT(FROM_UNIXTIME(tel_date), "%Y")  = b.teb_year
                WHERE l.tel_type = "expenses" AND b.teb_year IN ('.$syear.') 
                '.$sdate.'
                GROUP BY tel_month, tel_year                 
                ORDER BY tel_date ASC');
        echo json_encode($query->result()); 
    }
        

}