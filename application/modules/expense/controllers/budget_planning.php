<?php

class Budget_planning extends MX_Controller
{
   private $module_name ='expense';
    
   public function __construct()
   {
      parent::__construct();
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->model("budget_planning_model");
      $this->app->use_js(array("source"=>"expense/budget_planning/expected_expenses"));
      
      $this->app->use_js(array("source"=>"expense/defaults"));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      $this->app->use_js(array("source"=>"expense/budget_planning/calendar_month_year"));
      
      $this->load->module("expense/expense_common");  
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->load->module('settings/logs');
      
      /*Currency*/
      $this->app->use_js(array("source"=>"site/jquery-formatcurrency/jquery.formatCurrency-1.4.0"));
      $this->app->use_js(array("source"=>"site/jquery-formatcurrency/i18n/jquery.formatCurrency.all"));
   }
   
   public function index()
   {
      $this->logs->set_log("Budget Expense Planning Page","READ");
      $this->template->header();
      $this->expense_common->sidebar();
      $this->template->breadcrumbs();
      
      /*Get total planned -- Insert total planned to another table*/
      $aTotalPlanned = $this->budget_planning_model->get_budget_planning();       
      
      $adata = array();
      /*Getting the months*/
      $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
      $adata['months'] = $cal_info['months'];

      $itotal_row = $this->budget_planning_model->get_count();
      $adata['ilimit'] = (!empty($_GET['row_rec'])) ? $_GET['row_rec'] : 3; 
      $adata['to'] = (!empty($_GET['to']) && !empty($_GET['from'])) ? $_GET['to'] : ""; 
      $adata['from'] = (!empty($_GET['from']) && !empty($_GET['to'])) ? $_GET['from'] : ""; 
      $alimit = $this->common->sql_limit($itotal_row,$adata['ilimit'], 'page_rec');
      
      $adata['limit'] = $alimit['limit'];
      $adata['offset'] = $alimit['offset'];
      
      $adata['cutoff_to'] = (!empty($_GET['cutoff_to'])) ? $_GET['cutoff_to'] : "";
      $adata['cutoff_from'] = (!empty($_GET['cutoff_from'])) ? $_GET['cutoff_from'] : "";
      
      $adata['pager'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'), 'page_rec');      
      $adata['acategory'] = $this->budget_planning_model->get_category();  
      $adata['alists'] = $this->budget_planning_model->get_months($alimit);  
      
      $itotal_row_expected = count($this->budget_planning_model->get_expected_expenses());
      $adata['ilimit_expected'] = (!empty($_GET['row_exp'])) ? $_GET['row_exp'] : 10; 
      $alimit_expected = $this->common->sql_limit($itotal_row_expected,$adata['ilimit_expected'], 'page_exp');
      $adata['limit_expected'] = $alimit_expected['limit'];
      $adata['offset_expected'] = $alimit_expected['offset'];      
      
      $adata['aexpected'] = $this->budget_planning_model->get_expected_expenses($alimit_expected);   
      $adata['pager_expected'] = $this->common->pager($itotal_row_expected,$adata['ilimit_expected'],array('active_class'=>'current'), 'page_exp');    
      
      $adata['today'] = date('F Y');
      $adata['usergrade'] = $this->session->userdata('usergradeid');
      
      foreach($adata['alists'] as $kmonth=>$vmonth){        
        $adata['alists'][$kmonth]->lists = $this->budget_planning_model->get_lists($vmonth->tel_month, $vmonth->tel_year);
        foreach($adata['alists'][$kmonth]->lists as $klist=>$vlist){  
            $adata['alists'][$kmonth]->paid_total += (float)$vlist->tep_planned_amount;
            $adata['alists'][$kmonth]->payment_total += (float)$vlist->tep_payment_amount;
        }
      }      
      
    $adata['lastmonthspayment'] = $this->budget_planning_model->get_lastmonth_payment();
      
      /*Planned Year*/
      $this->budget_planning_model->plannedyear();
      
      $this->app->content($this->module_name . '/budget_planning/index', $adata);
      $this->template->footer();
   } 
   
   public function export_to_excel()
   {
        $this->logs->set_log("Excel File for the Budget Planning","READ");
        
        /*Getting the months*/
        $cal_info = cal_info(0); //Gregorian calendar , months, abbrevmonths
        $months = $cal_info['months'];
      
        $itotal_row = $this->budget_planning_model->get_count();
        $ilimit = (!empty($_GET['row'])) ? $_GET['row'] : 3; 
        $alimit = $this->common->sql_limit($itotal_row,$ilimit);
        
        $alists = $this->budget_planning_model->get_months($alimit);
        // $aexpected = $this->budget_planning_model->get_expected_expenses();  
        $aexpected = $this->budget_planning_model->get_lists(date("m",strtotime("+1 month")), date("Y",strtotime("+1 month")));  //date("Y/m/01",strtotime("+1 month"))
        $stoday = date('M Y',strtotime("+1 month"));
        $cell_num = 3;                
        $this->phpexcel->setActiveSheetIndex(0);
        
        //Phil Branch and Date *HEADER*
        $this->phpexcel->getActiveSheet()->setTitle("Budget Planning (".$stoday.")");        
        $this->phpexcel->getActiveSheet()->setCellValue('A1', "Philippines Branch");
        $this->phpexcel->getActiveSheet()->setCellValue('B1', date('F Y',strtotime("+1 month")). " Expected Expenses");
        
        /*Merge cells for title*/
        $this->phpexcel->getActiveSheet()->mergeCells('B1:E1');        
        $this->phpexcel->getActiveSheet()->getStyle('B1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Details');
        $this->phpexcel->getActiveSheet()->setCellValue('C2', 'Expected Date');
        $this->phpexcel->getActiveSheet()->setCellValue('D2', 'Budget Before Money Transfer');  
        $this->phpexcel->getActiveSheet()->setCellValue('E2', 'Budget After Money Transfer');  
        $this->phpexcel->getActiveSheet()->setCellValue('E4', 0);  
        $this->phpexcel->getActiveSheet()->getComment('E4')->getText()->createTextRun('Remaining amount after spending (Cash on hand)');
        $this->phpexcel->getActiveSheet()->setCellValue('B3', 'Current Balance');  

        $this->phpexcel->getActiveSheet()->setCellValue('B4', 'Expected Balance');
        
        /*Current Balance*/
        $icurrentbal = $this->budget_planning_model->get_current_balance(); 
        $icash_onhand = $this->budget_planning_model->get_total_cash_onhand(); 
        $icurrentbal = ($icurrentbal!=null || $icash_onhand) ? (float)$icash_onhand+$icurrentbal : '0.00';
        $this->phpexcel->getActiveSheet()->setCellValue('D3', $icurrentbal);
        $this->phpexcel->getActiveSheet()->getComment('D3')->getText()->createTextRun('Cash on Hand (+) Remaining Bank Balance');
                  
        $cell_num = 4;
        $iexpectedtotal = 0;
        $ipaymenttotal = 0;
        foreach($aexpected as $key=>$expected)
        {
            $cell_num++;
            //saved expected expenses information
            $expected->tep_desc = ($expected->tep_desc!="") ? $expected->tep_desc : "No description available.";
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $expected->tec_name);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $expected->tep_expected_date);  
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, $expected->tep_planned_amount);  
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, $expected->tep_payment_amount);  
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$cell_num, $expected->tep_desc);  
            //$this->phpexcel->getActiveSheet()->getStyle('F'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
            //border for the data
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            $iexpectedtotal += (float)$expected->tep_planned_amount;                
            $ipaymenttotal += (float)$expected->tep_payment_amount;                
        }
        $cell_num+=1;
        
        //set cells footer content values
        $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, 'Net Budget');        
        $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getFont()->setBold(true); 
        $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, '=SUM(D5:D'.($cell_num-1).')'); //$iexpectedtotal/*TOTAL BUDGET -> sum of amounts*/ 
        $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, '=SUM(E5:E'.($cell_num-1).')'); //$ipaymenttotal /*TOTAL payment -> sum of amounts*/ 
        
        $cell_num+=1;
        /*$icurrentbal-$iexpectedtotal*/
        $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, 'Expected Balance'); 
        $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getFont()->setBold(true); 
        $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, '=D3-D'.($cell_num-1)); /*Amount before money transfer - total net budget*/
        
        $cell_num+=1;        
        /*Merge cells for title --Phil Branch */
        $this->phpexcel->getActiveSheet()->mergeCells('A1:A'.$cell_num);
        $this->phpexcel->getActiveSheet()->getStyle('A1:A'.$cell_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A1:A'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /*Yellow BG*/
        $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num.':E'.$cell_num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
            
        /*$ipaymenttotal - ($icurrentbal-$iexpectedtotal)*/ 
        $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, 'Necessary Money');            
        $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getFont()->setBold(true); 
        $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, '=E'.($cell_num-2).'-D'.($cell_num-1)); /*budget after money trasfer total - total expected bal(before money transfer)*/
        $this->phpexcel->getActiveSheet()->getComment('E'.$cell_num)->getText()->createTextRun("Required Amount to be requested from KR's Finance");
        
        /*Previous month budget expense without plan*/
        $cell_num+=1;          
        
        $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, 'Previous month budget Actual expense without plan');
        /*Merge cells for title --Previous month budget expense without plan */        
        $this->phpexcel->getActiveSheet()->mergeCells('A'.$cell_num.':A'.($cell_num+5));
        $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':A'.($cell_num+5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':A'.($cell_num+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        for($kmonth=0;$kmonth<=4;$kmonth++){
        
            if(!empty($alists[$kmonth]->tel_month) || !empty($alists[$kmonth]->tel_year)){
                $alists[$kmonth]->lists = $this->budget_planning_model->get_lists($alists[$kmonth]->tel_month, $alists[$kmonth]->tel_year);            
                
                if(!empty($alists[$kmonth]->lists)){
                    foreach($alists[$kmonth]->lists as $klist=>$vlist){  
                        $alists[$kmonth]->payment_total += (float)$vlist->tep_payment_amount; 
                    }
                    
                    $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, substr($months[$alists[$kmonth]->tel_month], 0,3)." ". $alists[$kmonth]->tel_year . " Categories");
                    $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $alists[$kmonth]->tel_year . "-". $alists[$kmonth]->tel_month);
                    $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, $alists[$kmonth]->payment_total);
                }
            } else {
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "");
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, "");
                $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, 0);
            }
            $cell_num+=1;
        }
        
        $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, 'Total');        
        $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num)->getFont()->setBold(true); 
        $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, '=SUM(E'.($cell_num-5).':E'.($cell_num-1).')');
        /*Yellow BG*/
        $this->phpexcel->getActiveSheet()->getStyle('B'.$cell_num.':E'.$cell_num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
        
        /*Currency Format*/
        $this->phpexcel->getActiveSheet()->getStyle('D1:E'.$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);        
        
        //border for footer        
        $this->phpexcel->getActiveSheet()->getStyle('A1:F'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
            
        //change the font size
        $this->phpexcel->getActiveSheet()->getStyle('A1:F'.$cell_num)->getFont()->setSize(11);    
        //wrap text
        $this->phpexcel->getActiveSheet()->getStyle('A1:F'.$cell_num)->getAlignment()->setWrapText(true); 
        
        //adjust column width
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
        $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
        $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(80);
        
        /*Text bold*/
        $this->phpexcel->getActiveSheet()->getStyle('A1:F2')->getFont()->setBold(true); 
        $this->phpexcel->getActiveSheet()->getStyle('B3:B4')->getFont()->setBold(true); 
        $this->phpexcel->getActiveSheet()->getStyle('A1:A'.$cell_num)->getFont()->setBold(true); 
        
        $filename= 'Budget Planning '.$stoday.'.xlsx'; //save our workbook as this file name
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
   
   
   
   
   
   
   
