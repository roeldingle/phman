<?php

class User_logs_report_exec extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->load->model('expense/user_logs_model');
   }
   
   public function export_user_logs()
   {
      $istart = 2;
      $alist = array();
      $sdaterange_where = "";
      $ssearch_where = "";
      $sfrom = $this->input->get('from');
      $sto = $this->input->get('to');
      $ssearch = $this->input->get('search');
      // Date range where clause
      if( $sfrom && $sto ) {
         $sdaterange_where = " AND DATE_FORMAT( FROM_UNIXTIME( tl.tl_date_created ), '%m/%d/%Y' ) BETWEEN '{$sfrom}' AND '{$sto}'";
      }
      // Search where clause
      if( $ssearch) {
         $ssearch_where = " AND CONCAT_WS(' ',te.te_fname, tug.tug_name, tl.tl_message_log, tl.tl_message_from, tl.tl_message_to, tp.tp_position) LIKE '%{$ssearch}%'";
      }
      
      
      $this->phpexcel->setActiveSheetIndex(0); 
      //sets title of your spreadsheet
      $this->phpexcel->getActiveSheet()->setTitle("User Logs Report - " . date('Y-m-d',time())); 

      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Date');
      $this->phpexcel->getActiveSheet()->setCellValue('B1', 'User Name');
      $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Position');
      $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Logs');
      $this->phpexcel->getActiveSheet()->setCellValue('E1', 'From');
      $this->phpexcel->getActiveSheet()->setCellValue('F1', 'To');      
      
      // Get total rows of logs
      $itotal_rows = $this->user_logs_model->get_list_count($sdaterange_where, $ssearch_where );
      // Set limit rows
      $alimit = array("offset" => 0, "limit" => $itotal_rows);
      // Get list result
      $aresult = $this->user_logs_model->get_expense_logs_list( $sdaterange_where, $ssearch_where, $alimit );
      if( $aresult ) {      
         foreach( $aresult as $row ) {
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$istart, $row->date_created);
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, $row->user);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$istart, $row->position);
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