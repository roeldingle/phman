<?php

class Exec_in_report extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->module('settings/logs');
      $this->load->model('stock/in_report_get_model');
      $this->load->model('stock/in_report_exec_model');
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function save_incident_report()
   {
      $adata = array();
      $icat_id = $this->input->post('cat-id');
      $isub_cat_id = $this->input->post('sub-category');
      $istock_id = $this->input->post('serial');
      $sremarks = $this->input->post('remarks');
      $sdate_reported = $this->input->post('date-reported');
      
      if(!is_numeric($isub_cat_id) || !is_numeric($istock_id) || !valid_date($sdate_reported, 'Y-m-d')) {
         show_404();
      }

      if( strlen($sremarks) > 250 ) {
         show_404();
      }
      
      $asub_category = $this->in_report_get_model->get_sub_category_by_main_id( $isub_cat_id );
      $astock = $this->in_report_get_model->get_stock_by_id( $istock_id );   
      if(!$asub_category || !$astock) {
         show_404();
      }
            
      $adata = array(
         'tsin_sscid' => $isub_cat_id,
         'tsin_tsit_siid' => $istock_id,
         'tsin_remarks' => $sremarks,
         'tsin_date_reported' => strtotime($sdate_reported),
         'tsin_date_created' => time(),
         'tsin_active' => 1,
      );

      $bresult = $this->in_report_exec_model->insert_incident_report($adata);
      if( $bresult ) {
         $this->logs->set_log("Incident Report - Office ","CREATE");
         $this->common->set_message("Record has been saved successfully!","php-message", "success");
      } else {
         $this->common->set_message("Sorry, saved failed!","php-message", "warning");
      }
      $sqry_cat = ($icat_id) ? '&category=' . $icat_id : "";
      redirect(base_url() . 'stock/in_report?type=office' . $sqry_cat);
   }
   
   public function save_others_report()
   {
      $smodel = $this->input->post("model");
      $sremarks = $this->input->post("remarks");
      $sdate_reported = $this->input->post("date-reported");
      
      if($smodel == "" || $sremarks == "" || strlen($sremarks) > 250 || !valid_date($sdate_reported, 'Y-m-d')) {
         show_404();
      }
      
      $adata = array(
         'tsio_model' => $smodel,
         'tsio_remarks' => $sremarks,
         'tsio_date_reported' => strtotime($sdate_reported),
         'tsio_date_created' => time(),
         'tsio_active' => 1
      );
      
      $bresult = $this->in_report_exec_model->insert_incident_others($adata);
      if( $bresult ) {
         $this->logs->set_log("Incident Report - Others ","CREATE");
         $this->common->set_message("Record has been saved successfully!","php-message", "success");
      } else {
         $this->common->set_message("Sorry, saved failed!","php-message", "warning");
      }
      redirect(base_url() . 'stock/in_report?type=others');      
   }
   
   public function export_office_equipments()
   {
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->logs->set_log("Export Office Equipment Report","CREATE");

      $itotal_rows = $this->in_report_get_model->get_office_equipments_list_count();  
      $alimit = $this->common->sql_limit( $itotal_rows, $itotal_rows);
      $aoffice_equipments = $this->in_report_get_model->get_office_equipments_list($alimit);
      
      $istart = 2;
      $this->phpexcel->setActiveSheetIndex(0); 
      //sets title of your spreadsheet
      $this->phpexcel->getActiveSheet()->setTitle("Incident Report Office-" . date('Y-m-d',time())); 

      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Date Reported');
      $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Purchased Date');
      $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Category');
      $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Model');
      $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Serial');
      $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Assign To');
      $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Remarks');
      
      if( $aoffice_equipments ) {
         foreach( $aoffice_equipments as $row ) {
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$istart, date("Y-m-d", $row->tsin_date_reported));
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, date("Y-m-d", $row->tsit_purchased_date));
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$istart, $row->tssc_name);
            $this->phpexcel->getActiveSheet()->setCellValue('D'.$istart, $row->tsit_model);
            $this->phpexcel->getActiveSheet()->setCellValue('E'.$istart, $row->tsit_serial_number);
            $this->phpexcel->getActiveSheet()->setCellValue('F'.$istart, "{$row->te_fname} {$row->te_mname} {$row->te_lname}");
            $this->phpexcel->getActiveSheet()->setCellValue('G'.$istart, $row->tsin_remarks);
            $istart++;
         }
      }
      
      //changes the text style on the specified cell
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(10);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

      //adjusts column width
      $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

      //this is for downloading and saving the excel file
      $filename='Incident Report' . '.xls'; //save our workbook as this file name

      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'."Incident Report Office-" . date('Y-m-d',time()).'.xls'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache

      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  

      $objWriter->save('php://output');
   }
   
   public function export_others()
   {
      $this->load->library('site/PHPExcel/PHPExcel');
      $this->logs->set_log("Export Other Equipment Report","CREATE");
      $itotal_rows = $this->in_report_get_model->get_others_list_count();
      $alimit = $this->common->sql_limit($itotal_rows,$itotal_rows);
      $aothers = $this->in_report_get_model->get_others_list($alimit);
      
      $istart = 2;
      $this->phpexcel->setActiveSheetIndex(0); 
      //sets title of your spreadsheet
      $this->phpexcel->getActiveSheet()->setTitle("Incident Report Other-" . date('Y-m-d',time())); 

      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Date Reported');
      $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Model');
      $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Remarks');
      
      if( $aothers ) {
         foreach( $aothers as $row ) {
            $this->phpexcel->getActiveSheet()->setCellValue('A'.$istart, date("Y-m-d", $row->tsio_date_reported));
            $this->phpexcel->getActiveSheet()->setCellValue('B'.$istart, $row->tsio_model);
            $this->phpexcel->getActiveSheet()->setCellValue('C'.$istart, $row->tsio_remarks);
            $istart++;
         }
      }
      
      //changes the text style on the specified cell
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(10);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

      //adjusts column width
      $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);

      //this is for downloading and saving the excel file
      $filename='Incident Report' . '.xls'; //save our workbook as this file name

      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'."Incident Report Other-" . date('Y-m-d',time()).'.xls'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache

      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  

      $objWriter->save('php://output');
   }
   
   public function update_office()
   {
      $adata = array();
      $icat_id = $this->input->post('cat-id');
      $iidx = $this->input->post('id');
      $sremarks = $this->input->post('remarks');
      $sdate_reported = $this->input->post('date-reported');
      if( !is_numeric( $iidx ) || $sremarks == "" || strlen($sremarks) > 250 || !valid_date($sdate_reported, 'Y-m-d')) {
         show_404();
      }
      
      $aoffice = $this->in_report_get_model->get_incident_office_by_id($iidx);
      if( !$aoffice ) {
         show_404();
      }
      $adata = array(
         'tsin_remarks' => $sremarks,
         'tsin_date_reported' => strtotime($sdate_reported),
      );
      
      $bresult = $this->in_report_exec_model->update_office($adata, $iidx);
      if( $bresult ) {
         $this->logs->set_log("Office Equipment #{$iidx}","UPDATE");
         $this->common->set_message("Record has been updated successfully!","php-message", "success");
      } else {
         $this->common->set_message("Sorry, update failed!","php-message", "warning");
      }
      
      $sqry_cat = ($icat_id) ? '&category=' . $icat_id : "";
      redirect(base_url() . "stock/in_report/edit_incident_report?type=office&id={$iidx}" . $sqry_cat);      
   }
   
   public function update_others()
   {
      $adata = array();
      $iidx = $this->input->post('id');
      $smodel = $this->input->post("model");
      $sremarks = $this->input->post("remarks");
      $sdate_reported = $this->input->post("date-reported");
      
      if( !is_numeric( $iidx ) || $smodel == "" || $sremarks == "" || strlen($sremarks) > 250  || !valid_date($sdate_reported, 'Y-m-d')) {
         show_404();
      }
      
      $aothers = $this->in_report_get_model->get_incident_others_by_id($iidx);
      if( !$aothers ) {
         show_404();
      }
      $adata = array(
         'tsio_model' => $smodel,
         'tsio_remarks' => $sremarks,
         'tsio_date_reported' => strtotime($sdate_reported),
      );
      
      $bresult = $this->in_report_exec_model->update_others($adata, $iidx);
      if( $bresult ) {
         $this->logs->set_log("Other Equipment #{$iidx}","UPDATE");
         $this->common->set_message("Record has been updated successfully!","php-message", "success");
      } else {
         $this->common->set_message("Sorry, update failed!","php-message", "warning");
      }
      redirect(base_url() . "stock/in_report/edit_incident_report?type=others&id={$iidx}");      
   }
}