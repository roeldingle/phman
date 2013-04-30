<?php

class Budget_comparing_summary extends MX_Controller
{
   private $module_name ='expense';
   private $ilimit = 2;
   
   public function __construct()
   {
      parent::__construct();
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->model('bcomparing_summary_model');
      $this->app->use_js(array("source"=>"expense/budget_comparing/summary"));
      
      $this->app->use_js(array("source"=>"site/libs/highcharts"));        
      $this->app->use_js(array("source"=>"site/libs/exporting"));   
      $this->app->use_js(array("source"=>"expense/defaults"));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      $this->app->use_js(array("source"=>"expense/budget_planning/calendar_month_year"));      
      
      $this->load->module("expense/expense_common");
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->load->module('settings/logs');
   }
   
   public function index()
   {
      $this->logs->set_log("Budget Comparing Summary Page","READ");
      $this->template->header();
      $this->expense_common->sidebar();
      $this->template->breadcrumbs();
      
      $adata = array();      
      
      /*Parameters in the url*/  
      $adata['date_to'] = (!empty($_GET['to']) && !empty($_GET['from'])) ? $_GET['to'] : ""; 
      $adata['date_from'] = (!empty($_GET['from']) && !empty($_GET['to'])) ? $_GET['from'] : ""; 
      
      /*For pagination : number of years*/
      $adata['ilimit'] = (!empty($_GET['row'])) ? $_GET['row'] : 1; 
      $itotal_row = $this->bcomparing_summary_model->count_years($adata['date_from'], $adata['date_to']);
      $adata['alimit'] = $this->common->sql_limit($itotal_row,$adata['ilimit']);
      $adata['pager'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));
      
      /*Getting the months*/
      $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
      $adata['months'] = $cal_info['months'];
      
      /*Getting the years*/
      $adata['years'] = $this->bcomparing_summary_model->get_years($adata['alimit'], $adata['date_from'], $adata['date_to']);
      
      /*Background colors for every year*/
      $adata['bgcolor'] = array(3,7,2);    
      
      /*Get total planned -- Insert total planned to another table*/
      $aTotalPlanned = $this->bcomparing_summary_model->get_budget_planning();  
     
      foreach($adata['years'] as $kyear => $vyear){
        $adata['lists'][] = $this->bcomparing_summary_model->get_lists($vyear->teb_year, $adata['date_from'], $adata['date_to']);
        
        /*Total Amounts*/
        $adata['total_amounts'][$kyear] = $this->bcomparing_summary_model->get_total_amounts($vyear->teb_year, $adata['date_from'], $adata['date_to']);        
        $adata['total_amounts'][$kyear]['total_real'] = number_format($adata['total_amounts'][$kyear]['total_real'], 2, '.', ',');
        $adata['total_amounts'][$kyear]['total_difference'] = number_format($adata['total_amounts'][$kyear]['total_difference'], 2, '.', ',');
        $adata['total_amounts'][$kyear]['total_planned_budget'] = number_format($adata['total_amounts'][$kyear]['total_planned_budget'], 2, '.', ',');
      }
      
      $this->app->content($this->module_name . '/budget_comparing/summary', $adata);
      $this->template->footer();
   }
   
    private function num2alpha($n)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return (string)$r;
    }

   public function export_to_excel()
   {        
        /*User log*/
        $this->logs->set_log("Excel File for the Budget Comparing Summary","READ");
        
        /*Letter Index*/
        $sAlpha = 0;
        
        /*Parameters in the url*/
        $sdate_from = $this->input->get('from');  
        $sdate_to = $this->input->get('to');         
        $ilimit = (!empty($_GET['row'])) ? $_GET['row'] : 1; 
        
        $itotal_row = $this->bcomparing_summary_model->count_years($sdate_from, $sdate_to);
        $alimit = $this->common->sql_limit($itotal_row,$ilimit);
        /*Getting the months*/
        $cal_info = cal_info(0);
        $amonths = $cal_info['months'];
        
        /*Getting the years*/
        $ayears = $this->bcomparing_summary_model->get_years($alimit, $sdate_from, $sdate_to);
        $stoday = date('M Y');              
        $this->phpexcel->setActiveSheetIndex(0);
        
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle("Budget Summary (".$stoday.")");        
        $this->phpexcel->getActiveSheet()->setCellValue('A1', "Budget Comparing Summary (".$stoday.")");                          
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        
        foreach($ayears as $kyear=>$vyears) {    
            $cell_num = 2;   
            $first_num = $cell_num;
            $alists[] = $this->bcomparing_summary_model->get_lists($vyears->teb_year, $sdate_from, $sdate_to);
            $atotal_amounts[$kyear] = $this->bcomparing_summary_model->get_total_amounts($vyears->teb_year, $sdate_from, $sdate_to);  
            if($kyear == 0){
                $this->phpexcel->getActiveSheet()->setCellValue( $this->num2alpha($sAlpha) .$cell_num, "Period");     
            }
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+1)) .$cell_num, $vyears->teb_year);
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+3)) .$cell_num, "Difference");
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$cell_num.':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getFont()->setBold(true);
            
            //merging cells
            $this->phpexcel->getActiveSheet()->mergeCells( ($this->num2alpha($sAlpha+1)) .$first_num.':'. ($this->num2alpha($sAlpha+2)) .$first_num);
            $this->phpexcel->getActiveSheet()->mergeCells( ($this->num2alpha($sAlpha)) .$first_num.':'. ($this->num2alpha($sAlpha)) .($first_num+1));
            $this->phpexcel->getActiveSheet()->mergeCells( ($this->num2alpha($sAlpha+3)) .$first_num.':'.  ($this->num2alpha($sAlpha+3)) .($first_num+1));
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .'1:'. ($this->num2alpha($sAlpha+3)) .($first_num+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
        
            
            $cell_num+=1;
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+1)) .$cell_num, "Planned Budget");
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+2)) .$cell_num, "Real Expense");
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$cell_num.':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getFont()->setBold(true);
            
            $cell_num+=1;
            for($i=0;$i<12;$i++){
                if($kyear == 0){
                    $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha)) .$cell_num, $amonths[($i+1)]); 
                    $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$cell_num)->getFont()->setBold(true);
                }                
                         
                    //Planned Budget
                    $planned_budget = "0.00";
                    for($ii=0;$ii<12;$ii++){                        
                        if(!empty($alists[$kyear][$ii]->tel_month) && $alists[$kyear][$ii]->tel_month == $amonths[($i+1)]) {                        
                            $planned_budget = $alists[$kyear][$ii]->planned_budget;
                        } 
                    }
                    
                    //Real Expense
                    $real_expense = "0.00";
                    for($ii=0;$ii<12;$ii++){                        
                        if(!empty($alists[$kyear][$ii]->tel_month) && $alists[$kyear][$ii]->tel_month == $amonths[($i+1)]) {                        
                            $real_expense = $alists[$kyear][$ii]->real_exp;
                        } 
                    } 
                    
                    //Difference
                    $difference = "0.00"; 
                    for($ii=0;$ii<12;$ii++){ 
                        if(!empty($alists[$kyear][$ii]->tel_month) && $alists[$kyear][$ii]->tel_month == $amonths[($i+1)]) {                        
                            $difference = $alists[$kyear][$ii]->difference;                           
                        } 
                    }
                    
                    $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+1)) .$cell_num, $planned_budget);
                    $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+2)) .$cell_num, $real_expense);
                    $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+3)) .$cell_num, $difference);  
                    if($difference<0){
                        $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha+3)) .$cell_num)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
                    }     
                    
                    $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha+1)) .$cell_num.':'. ($this->num2alpha($sAlpha+2)) .$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
                    
                $cell_num++;
            }                
                    
            if($kyear == 0){
                $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha)) .$cell_num, "Total");
            }
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+1)) .$cell_num, '=SUM('. ($this->num2alpha($sAlpha+1)) .($first_num+2).':'. ($this->num2alpha($sAlpha+1)) .($cell_num-1).')');
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+2)) .$cell_num, '=SUM('. ($this->num2alpha($sAlpha+2)) .($first_num+2).':'. ($this->num2alpha($sAlpha+2)) .($cell_num-1).')'); // $atotal_amounts[$kyear]['total_real']
            $this->phpexcel->getActiveSheet()->setCellValue( ($this->num2alpha($sAlpha+3)) .$cell_num, '='. ($this->num2alpha($sAlpha+1)).$cell_num .'-'. ($this->num2alpha($sAlpha+2)).$cell_num.'');
            if($atotal_amounts[$kyear]['total_difference']<0){
                $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha+3)) .$cell_num)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            } 
                           
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$cell_num.':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getFont()->setBold(true);
            
            /*Yellow BG*/
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$cell_num.':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');  
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$cell_num.':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);         
            
            //border for the data
            $this->phpexcel->getActiveSheet()->getStyle( ($this->num2alpha($sAlpha)) .$first_num.':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            /*Currency Format*/
            $this->phpexcel->getActiveSheet()->getStyle(($this->num2alpha($sAlpha+1)).($first_num+2).':'. ($this->num2alpha($sAlpha+3)) .$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
            // $cell_num+=2;            
            
            //adjust column width
            $this->phpexcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
            $this->phpexcel->getActiveSheet()->getColumnDimension( ($this->num2alpha($sAlpha)) )->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension( ($this->num2alpha($sAlpha+1)) )->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension( ($this->num2alpha($sAlpha+2)) )->setWidth(20);
            $this->phpexcel->getActiveSheet()->getColumnDimension( ($this->num2alpha($sAlpha+3)) )->setWidth(20);
            
            $sAlpha+=3;
        }
        /*end main*/
        
        /*Merge the title*/          
        $this->phpexcel->getActiveSheet()->mergeCells('A1:'.($this->num2alpha($sAlpha)).'1');
        
        $filename= 'Budget Summary '.$stoday.'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
   }
}