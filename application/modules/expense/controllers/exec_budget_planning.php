<?php

class Exec_budget_planning extends MX_Controller
{
    private $tbl_expected = 'tbl_expected_expenses';
    private $tbl_bcomments = 'tbl_expense_bcomment';
    private $tbl_planning = 'tbl_expenses_planning';
    
    public function __construct()
    {
       parent::__construct();
       $this->load->model("budget_planning_model");
       $this->load->module('core/app');
       $this->load->module('settings/logs');
    } 
   
    public function _remap()
    {
       show_404();
    } 
    
    public function add_expected()
    {        
        /*Getting the months*/
        //Gregorian calendar , months, abbrevmonths
        $cal_info = cal_info(0); 
        $amonths = $cal_info['months'];
        $iexistingids = 0;
        $aexp_amount = $this->input->post('exp_amount');         
        $aexp_desc = $this->input->post('exp_desc');
        $aexp_categ = $this->input->post('exp_categ');
        $aexp_date = $this->input->post('exp_date');
        $itotal = $this->input->post('exp_total'); 
        $aidx = $this->input->post('exp_idx'); 
        
        if($this->input->post('exp_type') == "recent"){
            $iexistingids = 1;
            $aexp_payment = $this->input->post('exp_payment');
            $apayment = $aexp_payment[0];
        } else {
            $iexistingids = $this->budget_planning_model->check_expected_expense(); 
            $apayment = 0;
        }      
        
        /*Add total to the new inserted month*/
        if($iexistingids == 0){
            $dataa = array(
                'teb_month' => date("F",strtotime("+1 month")),
                'teb_total' => $itotal,
                'teb_comment' => "",
                'teb_year' => date("Y",strtotime("+1 month"))
            );
            $this->db->insert($this->tbl_bcomments, $dataa); 
        }

        /*Add expected expense*/
        for($iCount=0;$iCount<count($aexp_amount);$iCount++){
            $aexp_date[$iCount] = ($aexp_date[$iCount]=="") ? date("Y-m-01", strtotime("+1 month")) : $aexp_date[$iCount];
            
            $data = array(
                'tep_tec_idx' => $aexp_categ[$iCount],
                'tep_expected_date' => $aexp_date[$iCount],
                'tep_planned_amount' => $aexp_amount[$iCount],
                'tep_payment_amount' => $apayment,
                'tep_desc' => $aexp_desc[$iCount]
            );
            
            /*For Expected_list type - Save function*/
            $iUpdateData = $this->budget_planning_model->check_expected_expense($aidx[$iCount]); 
            if($this->input->post('exp_type') == "expected_list" && $iUpdateData > 0){
                $this->db->update($this->tbl_planning, $data, array('tep_idx' => $aidx[$iCount])); 
            } else {
                $this->db->insert($this->tbl_planning, $data); 
            }
        }        
        
        /*Logs*/
        if($this->input->post('exp_type') == "expected_list"){
            $this->logs->set_expense_log_create("New Expected Expense List");              
        } else {
            $this->logs->set_expense_log_create("New ".ucwords($this->input->post('exp_type'))." Expense", $this->db->insert_id());          
        }
        echo json_encode($this->db->insert_id()); 
    }

    public function edit_expenses()
    {   
        $sexp_type = $this->input->post('exp_type');
        $sdate = $this->input->post('exp_date');
        $ipayment = $this->input->post('exp_payment');
        $iIdx = $this->input->post('exp_idx'); 
        
        $aexp_amount = $this->input->post('exp_amount');         
        $aexp_desc = $this->input->post('exp_desc');
        $aexp_categ = $this->input->post('exp_categ');
        
        if($this->input->post('exp_type') == "recent"){
            $data = array(
                'tep_expected_date' => $sdate[0],
                'tep_payment_amount' => $ipayment[0]
            );
            $exempted = array('tep_tec_idx','tep_planned_amount', 'tep_desc');
        } else if($this->input->post('exp_type') == "expected"){
            $data = array(
                'tep_expected_date' => $sdate[0],
                'tep_tec_idx' => $aexp_categ[0],
                'tep_planned_amount' => $aexp_amount[0],
                'tep_desc' => $aexp_desc[0]
            );            
            $exempted = array('tep_payment_amount');
        }
        
        $this->logs->set_expense_log_update(ucfirst($sexp_type)." Expense #".$iIdx, "tbl_expenses_planning", $data, $exempted, "tep_idx = " . $iIdx);
        
        $sResult = $this->db->update($this->tbl_planning, $data, array('tep_idx' => $iIdx)); 
        echo json_encode($sResult); 
    }
    
    public function delete_expense()
    {      
        $iexp_idx = implode($this->input->post('exp_idx'), ',');
        $iRows = $this->budget_planning_model->delete_expected_expense($iexp_idx); 
        
        $this->logs->set_expense_log_delete("Expected Expense #".$iexp_idx);        
        
        echo json_encode($iRows); 
    }
    
}