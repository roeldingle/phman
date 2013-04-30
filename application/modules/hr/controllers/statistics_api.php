<?php
class Statistics_api extends MX_Controller
{  
   public function __construct()
   {
      parent::__construct();
      $this->load->model("statistics_model");
   }

   public function _remap()
   {
      show_404();
   }
   

    public function get_monthly_graph()
    {
        $ahired = array();     
        $stype = $this->input->get('type');  
        
        /*Getting the months*/
        $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
        $amonths = $cal_info['months'];
        
        $alimit['offset'] = (int)$this->input->get('page_num') -1;
        $alimit['limit'] = 1;
        
        $ayears_hired = $this->statistics_model->get_years($alimit, $stype);        
        foreach($ayears_hired as $kyear => $vyear){
            $ahiredEmp = $this->statistics_model->get_hired_resigned_employees($vyear->date_started, $stype);
            
            $ihired = (count($ahiredEmp)>12) ? (int)count($ahiredEmp) : (int)12;
            for($mon=1;$mon<=$ihired;$mon++){
                foreach($ahiredEmp as $khired=>$vhired)
                {                      
                    $sdate = explode("-", $vhired->date_started);
                    $ahired[$kyear][$mon]->year = $sdate[0];
                    $ahired[$kyear][$mon]->month = $amonths[$mon];
                    if($amonths[(int)$sdate[1]] == $amonths[$mon]){
                        $ahired[$kyear][$mon]->total_ids = $vhired->total_ids;
                    } 
                }                
            }
        }
        echo json_encode($ahired); 
    }
    
    public function get_by_department_graph()
    {
        $aprobationary = array();  
        $stype = $this->input->get('type');  
        
        $adepts = $this->statistics_model->get_departments();
        $aprob = $this->statistics_model->get_prob_employees($stype);
        foreach($adepts as $kdept => $vdept){ 
            foreach($aprob as $kprob => $vprob){                 
                $aprobationary[$kdept]->dept_name = $vdept->dept_name;
                if($vprob->dept_name == $vdept->dept_name) {
                    $aprobationary[$kdept]->total_ids = $vprob->total_ids;
                }                 
            }
        }
        
        echo json_encode($aprobationary); 
    }
    
    public function get_by_department_graph_attendance()
    {
        $aatendance = array();
        $aatendance = $this->statistics_model->get_attendance();
        echo json_encode($aatendance); 
    }
    
    public function get_monthly_leave_graph()
    {
        $ahired = array();     
        $stype = $this->input->get('type');  
        
        /*Getting the months*/
        $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
        $amonths = $cal_info['months'];
        
        $alimit['offset'] = (int)$this->input->get('page_num') -1;
        $alimit['limit'] = 1;
        
        $ayears_hired = $this->statistics_model->get_leave_years($alimit, $stype);        
        foreach($ayears_hired as $kyear => $vyear){
            $ahiredEmp = $this->statistics_model->get_leaves($vyear->tlt_date, $stype);
            
            $ihired = (count($ahiredEmp)>12) ? (int)count($ahiredEmp) : (int)12;
            for($mon=1;$mon<=$ihired;$mon++){
                foreach($ahiredEmp as $khired=>$vhired)
                {                      
                    $sdate = explode("-", $vhired->tlt_date);
                    $ahired[$kyear][$mon]->year = $sdate[0];
                    $ahired[$kyear][$mon]->month = $amonths[$mon];
                    if($amonths[(int)$sdate[1]] == $amonths[$mon]){
                        $ahired[$kyear][$mon]->total_ids = $vhired->total_ids;
                    } 
                }                
            }
        }
        echo json_encode($ahired); 
    }
}