<?php

class Activities_exec extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->load->model('settings/user_activities_model');
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function export()
   {
      $istart = 2;
      $aresult = $this->user_activities_model->get_logs_list_export();
      $this->phpexcel->setActiveSheetIndex(0); 
      //sets title of your spreadsheet
      $this->phpexcel->getActiveSheet()->setTitle("User Logs Report - " . date('Y-m-d',time())); 

      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Date');
      $this->phpexcel->getActiveSheet()->setCellValue('B1', 'User');
      $this->phpexcel->getActiveSheet()->setCellValue('C1', 'User Level');
      $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Logs');
      $this->phpexcel->getActiveSheet()->setCellValue('E1', 'From');
      $this->phpexcel->getActiveSheet()->setCellValue('F1', 'To');
      
      if( $aresult ) {
         foreach( $aresult as $row ) {
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$istart, $row->date_created);
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, $row->user);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$istart, $row->user_level);
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$istart, $row->message_log);
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$istart, $row->message_from);
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$istart, $row->message_to);
            $istart++;
         }
      }
      //changes the text style on the specified cell
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(10);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

      //adjusts column width
      $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

      //this is for downloading and saving the excel file
      $filename='Real Expense' . '.xls'; //save our workbook as this file name

      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'."User Logs Report - " . date('Y-m-d',time()).'.xls'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache

      //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
      //if you want to save it as .XLSX Excel 2007 format
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
      //force user to download the Excel file without writing it to server's HD
      $objWriter->save('php://output');      
   }
}