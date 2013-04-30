<?php

class Statistics extends MX_Controller
{
   private $module_name ='hr';

   public function __construct()
   {
      parent::__construct();
      $this->load->model("statistics_model");
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->app->use_js(array("source"=>"site/libs/jquery.validate","cache"=>false));
      $this->app->use_js(array("source"=>$this->module_name . "/statistics/statistics_list","cache"=>false));
      $this->app->use_js(array("source"=>"site/libs/highcharts")); 
            
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->load->module('settings/logs');
   }
   
   public function index()
   {
       
        $this->logs->set_log("HR Management Statistics Page","READ");
        
        $adata = array();
        $adata['title'] = "Hr Management | Statistics";
        $adata['module_name'] = $this->module_name;           
        $adata['to'] = (!empty($_GET['to']) && !empty($_GET['from'])) ? $_GET['to'] : ""; 
        $adata['from'] = (!empty($_GET['from']) && !empty($_GET['to'])) ? $_GET['from'] : ""; 
        $adata['menu'] = $this->input->get('menu');          
        $adata['ilimit'] = (!empty($_GET['row'])) ? $_GET['row'] : 1; 
        
        /*Getting the months*/
        $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
        $adata['months'] = $cal_info['months'];
        
        if($adata['menu'] == "lwop" || $adata['menu'] == "awol" || $adata['menu'] == "tardiness" || $adata['menu'] == "sick_leave" || $adata['menu'] == "vacation_leave") { 
            /*ATTENDANCE*/
            $adata['attendance'] = $this->statistics_model->get_attendance();        
            $total_info = 0;
             
            if(!empty($adata['attendance'])){
                foreach($adata['attendance'] as $kattend => $vattend){
                    $total_info += $vattend->$adata['menu'];            
                }
            }        
            $adata['total_info'] = $total_info;
            
            if($adata['menu'] == "lwop"){
               $stype = "4";
            }else if($adata['menu'] == "awol"){
               $stype = "5";
            }else if($adata['menu'] == "tardiness"){
               $stype = "3";
            }else if($adata['menu'] == "sick_leave"){
               $stype = "2";
            }else if($adata['menu'] == "vacation_leave"){
               $stype = "1";
            }
            $total = 0;
            $itotal_row = $this->statistics_model->count_leave_years($stype);
            $adata['alimit_hired_reg'] = $this->common->sql_limit($itotal_row,$adata['ilimit']);
            $adata['pager_reg_per_month'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));      
            
            $adata['ayears_hired_reg'] = $this->statistics_model->get_leave_years($adata['alimit_hired_reg'], $stype);
            
            if(!empty($adata['ayears_hired_reg'])){
                foreach($adata['ayears_hired_reg'] as $kyear => $vyear){        
                    $adata['aRegEmp'] = $this->statistics_model->get_leaves($vyear->tlt_date, $stype);
                    
                    foreach($adata['aRegEmp'] as $khired=>$vhired)
                    {
                        $sdate = explode("-", $vhired->tlt_date);
                        $adata['years_reg'][] = $sdate[0];
                        $adata['aRegEmp'][$khired]->year = $sdate[0];
                        $adata['aRegEmp'][$khired]->month = $adata['months'][(int)$sdate[1]];
                        $total += $vhired->total_ids;
                    }            
                    $adata['total_ids_reg'] = $total;
                }
            }
        }
        
        if($adata['menu'] == "hired_emp" || empty($adata['menu'])) { 
            /*HIRED EMPLOYEES*/
            $stype = "";
            $total = 0;
            $itotal_row = $this->statistics_model->count_years($stype);
            $adata['alimit_hired'] = $this->common->sql_limit($itotal_row,$adata['ilimit']);        
            $adata['pager_hired'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));      
            
            $adata['ayears_hired'] = $this->statistics_model->get_years($adata['alimit_hired'], $stype);
            
            if(!empty($adata['ayears_hired'])){
                foreach($adata['ayears_hired'] as $kyear => $vyear){        
                    $adata['ahiredEmp'] = $this->statistics_model->get_hired_resigned_employees($vyear->date_started, $stype);
                    
                    foreach($adata['ahiredEmp'] as $khired=>$vhired)
                    {
                        $sdate = explode("-", $vhired->date_started);
                        $adata['years_hired'][] = $sdate[0];
                        $adata['ahiredEmp'][$khired]->year = $sdate[0];
                        $adata['ahiredEmp'][$khired]->month = $adata['months'][(int)$sdate[1]];
                        $total += $vhired->total_ids;
                    }            
                    $adata['total_ids_hired'] = $total;
                }
            }
        }
        
        if($adata['menu'] == "prob_emp") {
            /*PROBATIONARY EMPLOYEES*/
            $total = 0;
            $stype = "Probationary";
            $adata['adepts_prob'] = $this->statistics_model->get_departments();
            $adata['aprob'] = $this->statistics_model->get_prob_employees($stype);
            foreach($adata['adepts_prob'] as $kdept => $vdept){               
                $adata['probationary'][$kdept]->dept_name = $vdept->dept_name;            
                
                foreach($adata['aprob'] as $kprob => $vprob){                 
                    if($vprob->dept_name == $vdept->dept_name) {
                        $adata['probationary'][$kdept]->total_ids = $vprob->total_ids;
                        $total += $adata['probationary'][$kdept]->total_ids;
                    }                 
                }
            }     
            $adata['total_ids_prob'] = $total;   
        }

         if($adata['menu'] == "reg_emp") {
            /*REGULAR EMPLOYEES -monthly*/
            $stype = "Regular";
            $total = 0;
            $itotal_row = $this->statistics_model->count_years($stype);
            $adata['alimit_hired_reg'] = $this->common->sql_limit($itotal_row,$adata['ilimit']);
            $adata['pager_reg_per_month'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));      
            
            $adata['ayears_hired_reg'] = $this->statistics_model->get_years($adata['alimit_hired_reg'], $stype);
            
            if(!empty($adata['ayears_hired_reg'])){
                foreach($adata['ayears_hired_reg'] as $kyear => $vyear){        
                    $adata['aRegEmp'] = $this->statistics_model->get_hired_resigned_employees($vyear->date_started, $stype);
                    
                    foreach($adata['aRegEmp'] as $khired=>$vhired)
                    {
                        $sdate = explode("-", $vhired->date_started);
                        $adata['years_reg'][] = $sdate[0];
                        $adata['aRegEmp'][$khired]->year = $sdate[0];
                        $adata['aRegEmp'][$khired]->month = $adata['months'][(int)$sdate[1]];
                        $total += $vhired->total_ids;
                    }            
                    $adata['total_ids_reg'] = $total;
                }
            }
            /*REGULAR EMPLOYEES -by dept*/
            $total = 0;
            $stype = "Regular";
            $adata['adepts_reg'] = $this->statistics_model->get_departments();
            $adata['areg_dept'] = $this->statistics_model->get_prob_employees($stype);
            foreach($adata['adepts_reg'] as $kdept => $vdept){ 
                $adata['regular_by_dept'][$kdept]->dept_name = $vdept->dept_name;
                foreach($adata['areg_dept'] as $kprob => $vprob){                 
                    if($vprob->dept_name == $vdept->dept_name) {
                        $adata['regular_by_dept'][$kdept]->total_ids = $vprob->total_ids;
                        $total += $adata['regular_by_dept'][$kdept]->total_ids;
                    }                 
                }
            }     
            $adata['total_ids_reg_dept'] = $total;  
        }        

        if($adata['menu'] == "contract_emp") { 
            /*CONTRACTUAL EMPLOYEES*/
            $total = 0;
            $stype = "Contractual";
            $adata['adepts_contract'] = $this->statistics_model->get_departments();
            $adata['acontr'] = $this->statistics_model->get_prob_employees($stype);
            foreach($adata['adepts_contract'] as $kdept => $vdept){      
                $adata['contractual'][$kdept]->dept_name = $vdept->dept_name;
                foreach($adata['acontr'] as $kcontract => $vcontract){            
                    if($vcontract->dept_name == $vdept->dept_name) {
                        $adata['contractual'][$kdept]->total_ids = $vcontract->total_ids;
                        $total += $adata['contractual'][$kdept]->total_ids;
                    }                 
                }
            }     
            $adata['total_ids_contract'] = $total;       
        }
        
        if($adata['menu'] == "total_emp") {
            /*TOTAL EMPLOYEES*/
            $total = 0;
            $adata['adepts'] = $this->statistics_model->get_departments();
            $adata['aemps'] = $this->statistics_model->get_prob_employees();
            foreach($adata['adepts'] as $kdept => $vdept){ 
                $adata['all_emp'][$kdept]->dept_name = $vdept->dept_name;
                foreach($adata['aemps'] as $kprob => $vprob){                 
                    if($vprob->dept_name == $vdept->dept_name) {
                        $adata['all_emp'][$kdept]->total_ids = $vprob->total_ids;
                        $total += $adata['all_emp'][$kdept]->total_ids;
                    }                 
                }
            }     
            $adata['total_ids_all'] = $total;    
        }
        
        if($adata['menu'] == "retired_emp") {
            /*RETIRED EMPLOYEES*/
            $stype = "Resigned";
            $total = 0;
            $itotal_row = $this->statistics_model->count_years($stype);
            $adata['alimit_resigned'] = $this->common->sql_limit($itotal_row,$adata['ilimit']);
            $adata['pager_resigned'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));      
            
            $adata['ayears_resigned'] = $this->statistics_model->get_years($adata['alimit_resigned'], $stype);
            
            if(!empty($adata['ayears_resigned'])){
                foreach($adata['ayears_resigned'] as $kyear => $vyear){        
                    $adata['aresignedEmp'] = $this->statistics_model->get_hired_resigned_employees($vyear->date_started, $stype);
                    
                    foreach($adata['aresignedEmp'] as $kresg=>$vresg)
                    {
                        $sdate = explode("-", $vresg->date_started);
                        $adata['years_resigned'][] = $sdate[0];
                        $adata['aresignedEmp'][$kresg]->year = $sdate[0];
                        $adata['aresignedEmp'][$kresg]->month = $adata['months'][(int)$sdate[1]];
                        $total += $vresg->total_ids;
                    }
                    $adata['total_ids_resigned'] = $total;
                }
            }
        }
        
        $this->template->header();
        $this->template->sidebar();
        $this->template->breadcrumbs();
        $this->app->content($this->module_name . '/statistics/index',$adata);
        $this->template->footer(); 
   }
   
   public function export_to_excel()
   {

        $stype_get = $this->input->get('type');
        $stype = "";
        if($stype_get == 'excel_hired'){
            $stitle = "Hired Employees";            
        } else if($stype_get == 'excel_reg_per_month' || $stype_get == 'excel_reg_by_dept'){
            $stitle = "Regular Employees";
            $stype = "Regular";
        } else if($stype_get == 'excel_probationary' || $stype_get == 'excel_contractual' || $stype_get == 'excel_retired'){            
            $stype = ucwords(str_replace("excel_", "", $stype_get));
            $stitle = $stype." Employees";
        } else if($stype_get == 'excel_employees'){
            $stitle = "All Employees";     
        } else if($stype_get == "excel_by_dept_vacation_leave" || $stype_get == "excel_by_dept_sick_leave" || 
            $stype_get == "excel_by_dept_awol" || $stype_get == "excel_by_dept_lwop" || $stype_get == "excel_by_dept_tardiness"){
            if($stype_get == "excel_by_dept_awol" || $stype_get == "excel_by_dept_lwop"){
                $stitle = strtoupper(str_replace("excel_by_dept_", "", $stype_get));
            } else {
                $stitle = str_replace("excel_by_dept_", "", $stype_get.'');
                $stitle = ucwords(str_replace("_", " ", $stitle));
            }
        } else if($stype_get == "excel_per_month_vacation_leave" || $stype_get == "excel_per_month_sick_leave" || 
            $stype_get == "excel_per_month_awol" || $stype_get == "excel_per_month_lwop" || $stype_get == "excel_per_month_tardiness"){
            
            if($stype_get == "excel_per_month_lwop"){
               $stype = "4";
            }else if($stype_get == "excel_per_month_awol"){
               $stype = "5";
            }else if($stype_get == "excel_per_month_tardiness"){
               $stype = "3";
            }else if($stype_get == "excel_per_month_sick_leave"){
               $stype = "2";
            }else if($stype_get == "excel_per_month_vacation_leave"){
               $stype = "1";
            }
            if($stype_get == "excel_by_dept_awol" || $stype_get == "excel_by_dept_lwop"){
                $stitle = strtoupper(str_replace("excel_per_month_", "", $stype_get));
            } else {
                $stitle = str_replace("excel_per_month_", "", $stype_get.'');
                $stitle = ucwords(str_replace("_", " ", $stitle));
            }
        }
        
        $this->logs->set_log("Excel File for the ".$stitle." Statistics","READ");
        $cell_num = 2;            

        $this->phpexcel->setActiveSheetIndex(0);        
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle($stitle);        
        $this->phpexcel->getActiveSheet()->setCellValue('A1', $stitle);                          
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            
        /*Getting the months*/
        $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
        $amonths = $cal_info['months'];        
        
        if($stype_get == 'excel_hired' || $stype_get == 'excel_retired' || $stype_get == 'excel_reg_per_month'){
            /*HIRED EMPLOYEES and Resigned*/
            $adata['ilimit'] = (!empty($_GET['row'])) ? $_GET['row'] : 1; 
            $total = 0;
            $itotal_row = $this->statistics_model->count_years($stype);
            $alimit['offset'] = (int)($_GET['page_num'] - 1);
            $alimit['limit'] = 1;
            $ayears_hired = $this->statistics_model->get_years($alimit, $stype);            
            
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, "Month");     
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "Year");
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, "TOTAL");
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':C'.$cell_num)->getFont()->setBold(true);            
            $cell_num+=1;            
                
            foreach($ayears_hired as $kyear => $vyear){        
                $ahiredEmp = $this->statistics_model->get_hired_resigned_employees($vyear->date_started, $stype);
                
                foreach($ahiredEmp as $khired=>$vhired)
                {
                    $sdate = explode("-", $vhired->date_started);
                    $years_hired[] = $sdate[0];
                    $ahiredEmp[$khired]->year = $sdate[0];
                    $ahiredEmp[$khired]->month = $amonths[(int)$sdate[1]];
                    $total += $vhired->total_ids;
                }            
                $total_ids_hired = $total; 
                
                for($yr=(int)min($years_hired);$yr<=(int)max($years_hired);$yr++) {
                    for($mon=1;$mon<=12;$mon++) {           
                            //Total Amount
                            $total_ids = "0.00";
                            foreach($ahiredEmp as $khired => $vhired) {                     
                                if($vhired->month == $amonths[$mon] && $vhired->year == $yr) {              
                                    $total_ids = $vhired->total_ids;
                                } 
                            }                                       
                            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $amonths[$mon]);
                            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $yr);
                            $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $total_ids);                            
                        $cell_num++;
                    }
                }                   
                $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getFont()->setBold(true);        
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "Total");
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $total_ids_hired); // total amount     
            }
                        
            $this->phpexcel->getActiveSheet()->getStyle('A2:C'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A2:C'.$cell_num)->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $this->phpexcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
            
            //merge cell A1 until D1
            $this->phpexcel->getActiveSheet()->mergeCells('A1:C1');        
            //set aligment to center for cells
            $this->phpexcel->getActiveSheet()->getStyle('A1:C'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
            //adjust column width
            $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
            
        }
        else if($stype_get == 'excel_per_month_vacation_leave' || $stype_get == 'excel_per_month_sick_leave' || $stype_get == 'excel_per_month_tardiness' || $stype_get == 'excel_per_month_lwop' || $stype_get == 'excel_per_month_awol'){
            /*LEAVES*/
            $adata['ilimit'] = (!empty($_GET['row'])) ? $_GET['row'] : 1; 
            $total = 0;
            $itotal_row = $this->statistics_model->count_leave_years($stype);
            $alimit['offset'] = (int)($_GET['page_num'] - 1);
            $alimit['limit'] = 1;
            $ayears_hired = $this->statistics_model->get_leave_years($alimit, $stype);            
            
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, "Month");     
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "Year");
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, "TOTAL");
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':C'.$cell_num)->getFont()->setBold(true);            
            $cell_num+=1;            
                
            foreach($ayears_hired as $kyear => $vyear){        
                $ahiredEmp = $this->statistics_model->get_leaves($vyear->tlt_date, $stype);
                
                foreach($ahiredEmp as $khired=>$vhired)
                {
                    $sdate = explode("-", $vhired->tlt_date);
                    $years_hired[] = $sdate[0];
                    $ahiredEmp[$khired]->year = $sdate[0];
                    $ahiredEmp[$khired]->month = $amonths[(int)$sdate[1]];
                    $total += $vhired->total_ids;
                }            
                $total_ids_hired = $total; 
                
                for($yr=(int)min($years_hired);$yr<=(int)max($years_hired);$yr++) {
                    for($mon=1;$mon<=12;$mon++) {           
                            //Total Amount
                            $total_ids = "0.00";
                            foreach($ahiredEmp as $khired => $vhired) {                     
                                if($vhired->month == $amonths[$mon] && $vhired->year == $yr) {              
                                    $total_ids = $vhired->total_ids;
                                } 
                            }                                       
                            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $amonths[$mon]);
                            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $yr);
                            $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $total_ids);                            
                        $cell_num++;
                    }
                }                   
                $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getFont()->setBold(true);        
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "Total");
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $total_ids_hired); // total amount     
            }
                        
            $this->phpexcel->getActiveSheet()->getStyle('A2:C'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A2:C'.$cell_num)->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $this->phpexcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
            
            //merge cell A1 until D1
            $this->phpexcel->getActiveSheet()->mergeCells('A1:C1');        
            //set aligment to center for cells
            $this->phpexcel->getActiveSheet()->getStyle('A1:C'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
            //adjust column width
            $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
            
        } 
        else if($stype_get == 'excel_probationary' || $stype_get == 'excel_employees' || $stype_get == 'excel_contractual' || $stype_get == 'excel_reg_by_dept'
            || $stype_get == "excel_by_dept_vacation_leave" || $stype_get == "excel_by_dept_sick_leave" || 
            $stype_get == "excel_by_dept_awol" || $stype_get == "excel_by_dept_lwop" || $stype_get == "excel_by_dept_tardiness"){
                
            if($stype_get == "excel_by_dept_vacation_leave" || $stype_get == "excel_by_dept_sick_leave" || 
                $stype_get == "excel_by_dept_awol" || $stype_get == "excel_by_dept_lwop" || $stype_get == "excel_by_dept_tardiness"){
                $sname = str_replace("excel_by_dept_", "", $stype_get);
                /*ATTENDANCE*/
                $all_emp = $this->statistics_model->get_attendance();        
                $total_ids_all = 0;
                 
                if(!empty($all_emp)){
                    foreach($all_emp as $kattend => $vattend){
                         $total_ids_all += $vattend->$sname;                    
                    }
                }
                
            } else {
                $sname = 'total_ids';
                /*Probationary and all employees*/            
                $total_ids_all = 0;
                $adepts = $this->statistics_model->get_departments();
                $aemps = $this->statistics_model->get_prob_employees($stype);
                
                foreach($adepts as $kdept => $vdept){ 
                    foreach($aemps as $kprob => $vprob){                 
                        $all_emp[$kdept]->dept_name = $vdept->dept_name;
                        if($vprob->dept_name == $vdept->dept_name) {
                            $all_emp[$kdept]->total_ids = $vprob->total_ids;
                            $total_ids_all += $all_emp[$kdept]->total_ids;
                        }                 
                    }
                } 
                
            }
        
            
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, "Department");   
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "TOTAL");
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':B'.$cell_num)->getFont()->setBold(true);   
            $cell_num+=1;  
            foreach($all_emp as $kprob=>$vprob) {                  
                $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $vprob->dept_name);
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, (empty($vprob->$sname)) ? "0" : $vprob->$sname);
                     
                $cell_num++;   
                
            }
            
            /*Footer*/
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num)->getFont()->setBold(true);        
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, "Total");
            
            // if($stype_get == "excel_by_dept_vacation_leave" || $stype_get == "excel_by_dept_sick_leave" || 
                // $stype_get == "excel_by_dept_awol" || $stype_get == "excel_by_dept_lwop" || $stype_get == "excel_by_dept_tardiness"){ 
                // $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $total_);
            // } else {            
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $total_ids_all);
            // }
                
            $this->phpexcel->getActiveSheet()->getStyle('A2:B'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A2:B'.$cell_num)->getFont()->setSize(12);
            $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $this->phpexcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
            
            //merge cell A1 until D1
            $this->phpexcel->getActiveSheet()->mergeCells('A1:B1');      
            //set aligment to center for cells
            $this->phpexcel->getActiveSheet()->getStyle('A1:B'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
             
        }   
        
        //adjust column width
        $this->phpexcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            
        $filename= $stitle.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
   }

}