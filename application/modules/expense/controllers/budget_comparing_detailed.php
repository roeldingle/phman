<?php

class Budget_comparing_detailed extends MX_Controller
{
   private $module_name ='expense';
   
   public function __construct()
   {
      parent::__construct();
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->model("bcomparing_view_model");
      $this->app->use_js(array("source"=>"expense/budget_comparing/detailed_view"));
      $this->app->use_js(array("source"=>"expense/budget_planning/calendar_month_year"));
      
      $this->app->use_js(array("source"=>"expense/defaults"));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate.min","cache"=>true));
      
      $this->load->module("expense/expense_common");
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->load->module('settings/logs');
   }
   
   public function index()
   {
      $this->logs->set_log("Budget Comparing Detailed View Page","READ");
      $this->template->header();
      $this->expense_common->sidebar();
      $this->template->breadcrumbs();
      
      /*Get total planned -- Insert total planned to another table*/
      $aTotalPlanned = $this->bcomparing_view_model->get_budget_planning();   
      
      $adata = array();
      $itotal_row = $this->bcomparing_view_model->get_count();
      $adata['ilimit'] = (!empty($_GET['row'])) ? $_GET['row'] : 5; 
      $alimit = $this->common->sql_limit($itotal_row,$adata['ilimit']);
      $adata['limit'] = $alimit['limit'];
      $adata['offset'] = $alimit['offset'];
      $adata['pager'] = $this->common->pager($itotal_row,$adata['ilimit'],array('active_class'=>'current'));       
      $adata['alists'] = $this->bcomparing_view_model->get_months($alimit); 
      $adata['keyword'] = $this->input->get('keyword');  
      $adata['date_to'] = (!empty($_GET['to']) && !empty($_GET['from'])) ? $_GET['to'] : ""; 
      $adata['date_from'] = (!empty($_GET['from']) && !empty($_GET['to'])) ? $_GET['from'] : "";  
      $adata['sort'] = $this->input->get('sort');  
      $adata['today'] = date('F Y');
      $adata['usergrade'] = $this->session->userdata('usergradeid');
      
      foreach($adata['alists'] as $kmonth=>$vmonth){        
        $adata['alists'][$kmonth]->lists = $this->bcomparing_view_model->get_lists($vmonth->tel_month, $vmonth->tel_year);
        
        /*Number format*/
        $adata['alists'][$kmonth]->difference = number_format($adata['alists'][$kmonth]->difference, 2, '.', ',');
        $adata['alists'][$kmonth]->planned_budget = number_format($adata['alists'][$kmonth]->planned_budget, 2, '.', ',');
        $adata['alists'][$kmonth]->total = number_format($adata['alists'][$kmonth]->total, 2, '.', ',');
      } 
      
      $this->app->content($this->module_name . '/budget_comparing/detailed_view', $adata);
      $this->template->footer();
   } 
   
   public function export_to_excel()
   {
        $this->logs->set_log("Excel File for the Budget Comparing","READ");
        $alists = $this->bcomparing_view_model->get_months();
        $stoday = date('M Y');
        $cell_num = 1;               
        $this->phpexcel->setActiveSheetIndex(0);
        
        //name the worksheet
        $this->phpexcel->getActiveSheet()->setTitle("Budget Comparing (".$stoday.")");        
        $this->phpexcel->getActiveSheet()->setCellValue('A1', "Budget Comparing Expense (".$stoday.")");
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $cell_num +=1; 
        foreach($alists as $kmonths=>$vmonths) {      
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('C'.$cell_num)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('D'.$cell_num.':F'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            /*Merge cells for title*/
            $this->phpexcel->getActiveSheet()->mergeCells('A'.$cell_num.':C'.$cell_num);        
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
            //set cells A1:F2 content as headers
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $vmonths->tel_month." ". $vmonths->tel_year. ' Real Expense (Total Cost)');
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, 'Planned Budget');
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, 'Difference');
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$cell_num, 'Comment');    
            //make the font become bold
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getFont()->setBold(true);
        
            $ainfo = $this->bcomparing_view_model->get_lists($vmonths->tel_month, $vmonths->tel_year);
            
            //merging cells
            $this->phpexcel->getActiveSheet()->mergeCells('F'.($cell_num+1).':F'.(int)($cell_num+1+count($ainfo)));
            $this->phpexcel->getActiveSheet()->getStyle('F'.($cell_num+1).':F'.(int)($cell_num+1+count($ainfo)))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
            
            //set cells content values
            foreach($ainfo as $klists=>$vlists){
                $cell_num++;
                    
                $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, date('m/d/Y', $vlists->tel_date));
                $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $vlists->tel_payment);
                $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $vlists->tec_name);
                
                if($klists == 0){ 
                    $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, $vmonths->planned_budget);
                    $vmonths->comment = ($vmonths->comment!="") ? $vmonths->comment : "No existing comment.";
                    $this->phpexcel->getActiveSheet()->setCellValue('F'.$cell_num, $vmonths->comment);                    
                }
                
                $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }  
            $cell_num+=1;
            
            //set cells footer content values
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, 'Total');
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $vmonths->total);
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, $vmonths->planned_budget);
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, $vmonths->difference);  
            if($vmonths->difference <0){
                $this->phpexcel->getActiveSheet()->getStyle('E'.$cell_num)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            }
            
            /*Yellow BG*/
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':E'.$cell_num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');    
            
            //make the font become bold
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->getStyle('A'.$cell_num.':F'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            
            /*Currency Format*/
            $this->phpexcel->getActiveSheet()->getStyle('B1:E'.$cell_num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1); 
            $cell_num+=3;   
        }
        
        //change the font size
        $this->phpexcel->getActiveSheet()->getStyle('A2:F'.$cell_num)->getFont()->setSize(10);
        
        //merging cells
        $this->phpexcel->getActiveSheet()->mergeCells('A1:F1');
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);        
        
        //adjust column width
        $this->phpexcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        
        $filename= 'Budget Comparing '.$stoday.'.xls'; //save our workbook as this file name
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