<?php

class Exec_ip_management extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
      $this->load->model("stock/ip_management_exec_model");
      $this->load->model("stock/ip_management_get_model");
      $this->load->model("stock/in_report_get_model");
   }
   
   public function save_ip()
   {
      $iemployee_id = $this->input->post('employee-id');
      $sassign_ip = $this->input->post('assign-ip');
      $sgateway = $this->input->post('gateway');
      $sexternal_ip = $this->input->post('external-ip');
      
      if(!$iemployee_id || !is_numeric($iemployee_id) || !validateIpAddress($sassign_ip) ||!validateIpAddress($sgateway) || !validateIpAddress($sexternal_ip) ) {
         show_404();
      }
      
      $aemployee = $this->in_report_get_model->get_employee_by_id($iemployee_id);
      if( !$aemployee ) {
         show_404();
      }
      
      $adata = array(
         'tsai_te_idx' => $iemployee_id,
         'tsai_assign_ip' => $sassign_ip,
         'tsai_gateway' => $sgateway,
         'tsai_external_ip' => $sexternal_ip,
         'tsai_date_created' => time(),
         'tsai_active' => 1         
      );
      
      $bresult = $this->ip_management_exec_model->insert_ip($adata);
      if( $bresult ) {
         $this->logs->set_log("IP Management Record ","CREATE");
         $this->common->set_message("Record has been saved successfully!","php-message", "success");
      } else {
         $this->common->set_message("Sorry, saved failed!","php-message", "warning");
      }
      
      redirect(base_url() . 'stock/ip_management');
   }
   
   public function update_ip()
   {
      $iidx = $this->input->post('modify-id');
      $sassign_ip = $this->input->post('modify-assign-ip');
      $sgateway = $this->input->post('modify-gateway');
      $sexternal_ip = $this->input->post('modify-external-ip');
      
      if(!$iidx || !is_numeric($iidx) || !validateIpAddress($sassign_ip) ||!validateIpAddress($sgateway) || !validateIpAddress($sexternal_ip) ) {
         show_404();
      }
      
      $aassign = $this->ip_management_get_model->get_ip_by_id($iidx);
      if( !$aassign ) {
         show_404();
      }
      
      $adata = array(
         'tsai_assign_ip' => $sassign_ip,
         'tsai_gateway' => $sgateway,
         'tsai_external_ip' => $sexternal_ip,
         'tsai_active' => 1
      );
      
      $bresult = $this->ip_management_exec_model->update_ip($adata, $iidx);
      if( $bresult ) {
         $this->logs->set_log("IP Management Record #{$iidx} ","UPDATE");  
         $this->common->set_message("Record has been updated successfully!","php-message", "success");
      } else {
         $this->common->set_message("Sorry, update failed!","php-message", "warning");
      }
      redirect($this->input->post('redirect_url'));
   }
   
   public function export_ip()
   {
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->logs->set_log("Export IP Management Report","UPDATE");  
      $itotal_rows = $this->ip_management_get_model->get_ip_list_count();
      $alimit = $this->common->sql_limit( $itotal_rows, $itotal_rows);
      $aip = $this->ip_management_get_model->get_ip_list( $alimit );
      
      $istart = 2;
      $this->phpexcel->setActiveSheetIndex(0); 
      //sets title of your spreadsheet
      $this->phpexcel->getActiveSheet()->setTitle("Assigned IP-" . date('Y-m-d',time())); 

      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Seat No.');
      $this->phpexcel->getActiveSheet()->setCellValue('B1', 'First Name');
      $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Last Name');
      $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Department');
      $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Assign IP');
      $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Gateway');
      $this->phpexcel->getActiveSheet()->setCellValue('G1', 'External IP');
      
      if( $aip ) {
         foreach( $aip as $row ) {
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$istart, ($row->ts_tsc_seatno) ? $row->ts_tsc_seatno : '-');
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, $row->te_fname);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$istart, $row->te_lname);
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$istart, $row->td_dept_name);
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$istart, $row->tsai_assign_ip);
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$istart, $row->tsai_gateway);
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$istart, $row->tsai_external_ip);
            $istart++;
         }
      }
      
      //changes the text style on the specified cell
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(10);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

      //adjusts column width
      $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

      //this is for downloading and saving the excel file

      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'."Assigned IP-" . date('Y-m-d',time()).'.xls'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache

      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  

      $objWriter->save('php://output');
   }
}