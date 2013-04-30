<?php

class Attendance extends MX_Controller
{
   private $module_name ='hr';

   public function __construct()
   {
      parent::__construct();
      $this->load->model("attendance_model");
      $this->load->module("core/app");
      $this->load->module("site/template");
      $this->load->module('settings/logs');
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>false));
      $this->app->use_js(array("source"=>"site/libs/jquery.validate","cache"=>false));
      $this->app->use_js(array("source"=>$this->module_name . "/attendance/attendance_list","cache"=>false));
   }
   
   public function index()
   {
         $date_from = isset($_GET['datefrom']) ? $_GET['datefrom'] : date("Y-01-01");
         $date_to = isset($_GET['dateto']) ? $_GET['dateto'] : date("Y-m-t");
         $date = array($date_from,$date_to);
         
         $adata = array();
         $adata['title'] = "Hr Management | Attendance";
         $adata['module_name'] = $this->module_name;
         $adata['date_from'] = $date_from;
         $adata['date_to'] = $date_to;

         $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
         $adata['keyword'] = $keyword;
         $RowPerPage = isset($_GET['row']) ? $_GET['row'] : '50';
         $Curpage = isset($_GET['page']) ? $_GET['page']:'1';
         
         $oresult = $this->attendance_model->getList($keyword,$RowPerPage,$Curpage,$date);
         $adata['lists'] = $oresult->result();

         $itotal_row =  $this->attendance_model->getTotal_rows($keyword,$date);
         $alimit = $this->common->sql_limit($itotal_row,$RowPerPage);
         $adata['pager'] = $this->common->pager($itotal_row,$RowPerPage,array('active_class'=>'current'));
         
         $this->template->header();
         $this->template->sidebar();
         $this->template->breadcrumbs();
         $this->app->content($this->module_name . '/attendance/index',$adata);
         $this->template->footer();
         $this->logs->set_log("Leave/Tardiness Page","READ");
   }
   
   public function history()
   {
      if(!isset($_POST['history_idx']) && !isset($_GET['history_idx'])){
            show_404();
      }else{
      
         $date_from = isset($_GET['datefrom']) ? $_GET['datefrom'] : date("Y-01-01");
         $date_to = isset($_GET['dateto']) ? $_GET['dateto'] : date("Y-m-t");
         $date = array($date_from,$date_to);
         
         $adata = array();
         $adata['title'] = "Hr Management | Attendance History";
         $adata['module_name'] = $this->module_name;
         $adata['date_from'] = $date_from;
         $adata['date_to'] = $date_to;

         $history_idx = isset($_POST['history_idx']) ? $_POST['history_idx'] : $_GET['history_idx'] ;
         $adata['history_idx'] = $history_idx;
         $RowPerPage = isset($_GET['row']) ? $_GET['row'] : '10';
         $Curpage = isset($_GET['page']) ? $_GET['page']:'1';
         
         $oresult = $this->attendance_model->getHistoryList($history_idx,$RowPerPage,$Curpage,$date);
         $adata['lists'] = $oresult->result();
         
         $this->db->select('te_fname,te_mname,te_lname');
         $this->db->from('tbl_employee');
         $this->db->where('te_idx',$history_idx);
         $query = $this->db->get()->result();

         $adata['employee_info'] = $query;
         
         $itotal_row =  $this->attendance_model->getHistoryTotal_rows($history_idx,$date);
         $alimit = $this->common->sql_limit($itotal_row,$RowPerPage);
         $adata['pager'] = $this->common->pager($itotal_row,$RowPerPage,array('active_class'=>'current'));

         $this->template->header();
         $this->template->sidebar();
         $this->template->breadcrumbs();
         $this->app->content($this->module_name . '/attendance/history',$adata);
         $this->template->footer(); 
         $this->logs->set_log($query[0]->te_fname.' '.$query[0]->te_lname.' Attendance History',"READ");
      }
   }
   
   public function export_to_excel()
   {
   
         // get data
         $date_from = isset($_GET['datefrom']) ? $_GET['datefrom'] : date("Y-m-01");
         $date_to = isset($_GET['dateto']) ? $_GET['dateto'] : date("Y-m-t");
         $date = array($date_from,$date_to);
         
         $adata = array();
         $adata['title'] = "Hr Management | Attendance";
         $adata['module_name'] = $this->module_name;
         $adata['date_from'] = $date_from;
         $adata['date_to'] = $date_to;
        

         $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
         $adata['keyword'] = $keyword;
         $RowPerPage = isset($_GET['row']) ? $_GET['row'] : '10';
         $Curpage = isset($_GET['page']) ? $_GET['page']:'1';
         
         $oresult = $this->attendance_model->getList($keyword,$RowPerPage,$Curpage,$date);
         $adata['lists'] = $oresult->result();
   
         $this->logs->set_log("Excel File for attendance","READ");
         $cell_num = 2;
         $stitle = 'Simplex Internet Philippines - Attendance Record';

         $this->phpexcel->setActiveSheetIndex(0);        
         //name the worksheet
         $this->phpexcel->getActiveSheet()->setTitle($stitle);        
         $this->phpexcel->getActiveSheet()->setCellValue('A1', $stitle);                          
         $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
         //merge cell A1 until F1
         $this->phpexcel->getActiveSheet()->mergeCells('A1:F1');  
         
          //set period of time
         $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, '( '.$date_from.' - '.$date_to.' )');
         $this->phpexcel->getActiveSheet()->mergeCells('A'.$cell_num.':F'.$cell_num); 
         $cell_num+=1;
         
         $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, '');
         $this->phpexcel->getActiveSheet()->mergeCells('A'.$cell_num.':F'.$cell_num); 
         $cell_num+=1;
         
         $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, "Employee Name");     
         $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, "Tardiness");
         $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, "Vacation Leave");
         $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, "Sick Leave");
         $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, "LWOP Leave");
         $this->phpexcel->getActiveSheet()->setCellValue('F'.$cell_num, "AWOL");
         $this->phpexcel->getActiveSheet()->getStyle('A'. $cell_num.':F'.$cell_num)->getFont()->setBold(true);            
         $cell_num+=1;
                  
         foreach($adata['lists'] as $list){
         
            $mi = $list->te_mname=='' ? '' : substr($list->te_mname,0,1).'.';
            $tardy = $list->tardy != '' ? $list->tardy : '0';
            $vl =    $list->vl != '' ? $list->vl : '0';
            $sl =  $list->sl != '' ? $list->sl : '0';
            $lwop =  $list->lwop != '' ? $list->lwop : '0';
            $awol =  $list->awol != '' ? $list->awol : '0';
         
             $this->phpexcel->getActiveSheet()->setCellValue('A'.$cell_num, $list->te_fname .' '.$mi.' '.$list->te_lname);
             $this->phpexcel->getActiveSheet()->setCellValue('B'.$cell_num, $tardy);
             $this->phpexcel->getActiveSheet()->setCellValue('C'.$cell_num, $vl);          
             $this->phpexcel->getActiveSheet()->setCellValue('D'.$cell_num, $sl);          
             $this->phpexcel->getActiveSheet()->setCellValue('E'.$cell_num, $lwop);          
             $this->phpexcel->getActiveSheet()->setCellValue('F'.$cell_num, $awol);
            $cell_num++;             
         }


         $this->phpexcel->getActiveSheet()->getStyle('A4:F'.$cell_num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
         $this->phpexcel->getActiveSheet()->getStyle('A2:F'.$cell_num)->getFont()->setSize(12);
         $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
         $this->phpexcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);

         //set aligment to center for cells
         $this->phpexcel->getActiveSheet()->getStyle('A1:F'.$cell_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         $cell_num+=1;
         
         //adjust column width
         $this->phpexcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
         $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
         $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
         $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
         $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
         $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
         $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20); 

         $filename= $stitle.'( '.$date_from.' - '.$date_to.' )'.'.xls'; //save our workbook as this file name
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