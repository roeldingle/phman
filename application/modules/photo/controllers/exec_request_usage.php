<?php

class Exec_request_usage extends MX_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('photo/req_usage_exec_model');
      $this->load->model('photo/req_usage_get_model');
      $this->load->module('core/app');
      $this->load->module('settings/logs');
   }
   
   public function _remap()
   {
      show_404();
   }
   
   public function save_request()
   {
      $afilter_ids = array();
      $sactivity_date = $this->input->post('activity-date');
      $srequested_by = $this->input->post('requested-by');
      $slocation = $this->input->post('location');
      $spurpose = $this->input->post('purpose');
      $sreturned_date = $this->input->post('returned-date');
      $aassets_id = $this->input->post("assets-id");
      
      if(valid_date($sactivity_date, 'Y-m-d') === false) {
         show_404();
      }
      
      if($srequested_by === "") {
         show_404();      
      }

      if($slocation === "" || strlen($slocation) > 250 ) {
         show_404();      
      }

      if($spurpose === "" || strlen($spurpose) > 250 ) {
         show_404();      
      }
      
      if(valid_date($sreturned_date, 'Y-m-d') === false) {
         show_404();
      }
      
      if(!$aassets_id) {
         show_404();
      }
      
      $adata = array(
         'tprl_activity_date' => strtotime($sactivity_date),
         'tprl_requested_by' => $srequested_by,
         'tprl_location_shoot' => $slocation,
         'tprl_purpose_theme' => $spurpose,
         'tprl_returned_date' => strtotime($sreturned_date),
         'tprl_date_updated' => time(),
         'tprl_date_created' => time(),
         'tprl_active' => 1,
      );

      $bresult = $this->req_usage_exec_model->insert_request($adata);
      if( $bresult === true ) {      
         if( $aassets_id ) {
            // Filter IDS to be sure that there is no alphabet included
            foreach($aassets_id as $rows) {
               if(is_numeric($rows)) {
                  $afilter_ids[] = array(
                     'tpri_tprl_idx' => $this->db->insert_id(),
                     'tpri_tpal_idx' => $rows
                  );
               }
            }            
            if( $afilter_ids ) {
               $bitem_result = $this->req_usage_exec_model->insert_items($afilter_ids);
               if( !$bitem_result ) {
                  $this->req_usage_exec_model->delete_request_row($this->db->insert_id());
               }
               if( $bitem_result ) {
                  $this->logs->set_log("New Request #{$this->db->insert_id()}","CREATE");
                  $this->common->set_message("Request has been saved successfully!","request-message", "success");
               } else {
                  $this->common->set_message("Sorry, there is an error saving the data.","request-message", "warning");               
               }
               redirect(base_url() . 'photo/req_usage_rec');
            }
         }
      }
   }
   
   public function update_request()
   {
      $afilter_ids = array();
      $sactivity_date = $this->input->post('activity-date');
      $srequested_by = $this->input->post('requested-by');
      $slocation = $this->input->post('location');
      $spurpose = $this->input->post('purpose');
      $sreturned_date = $this->input->post('returned-date');
      $scurrent_attachment = $this->input->post('current-attachment');
      $aassets_id = $this->input->post("assets-id");
      
      $iidx = $this->input->post('id');
      
      // Check if the id is not numeric then show 404 page
      if( !is_numeric( $iidx ) ) {
         show_404();
      }
      
      $arequest = $this->req_usage_get_model->get_request_row($iidx);
      
      // Check if there is no record in the database then show 404 page
      if( !$arequest ) {
         show_404();
      }      
      
      if(valid_date($sactivity_date, 'Y-m-d') === false) {
         show_404();
      }
      
      if($srequested_by === "") {
         show_404();      
      }

      if($slocation === "" || strlen($slocation) > 250 ) {
         show_404();      
      }

      if($spurpose === "" || strlen($spurpose) > 250 ) {
         show_404();      
      }
      
      if(valid_date($sreturned_date, 'Y-m-d') === false) {
         show_404();
      }
      
      if(!$aassets_id) {
         show_404();
      }
      
      $adata = array(
         'tprl_activity_date' => strtotime($sactivity_date),
         'tprl_requested_by' => $srequested_by,
         'tprl_location_shoot' => $slocation,
         'tprl_purpose_theme' => $spurpose,
         'tprl_returned_date' => strtotime($sreturned_date),
         'tprl_date_updated' => time(),
         'tprl_active' => 1,
      );
      
      $auploads = $this->app->get_fileupload('request-form', true);      
      if( $auploads ) {
         $supload_path = APPPATH . 'modules/photo/uploads/request-attachment/';
         if(file_exists($supload_path . $arequest->tprl_attachment_rawname ) ) {
            if( is_readable($supload_path . $arequest->tprl_attachment_rawname ) ) {
               unlink($supload_path . $arequest->tprl_attachment_rawname);
            }
         }
         if($auploads['files']) {
            $adata['tprl_attachment_filename'] = $auploads['files'][0]['filename'];
            $adata['tprl_attachment_rawname'] = $auploads['files'][0]['newfilename'];
         }
      } else {
         if( !$scurrent_attachment ) {
            $supload_path = APPPATH . 'modules/photo/uploads/request-attachment/';
            if(file_exists($supload_path . $arequest->tprl_attachment_rawname ) ) {
               if( is_readable($supload_path . $arequest->tprl_attachment_rawname ) ) {
                  unlink($supload_path . $arequest->tprl_attachment_rawname);
               }
            }
            $adata['tprl_attachment_filename'] = '';
            $adata['tprl_attachment_rawname'] = '';
         }
      }

      $bresult = $this->req_usage_exec_model->update_request($adata, $iidx);
      if( $bresult === true ) {
         if( $aassets_id ) {
            foreach($aassets_id as $rows) {
               if(is_numeric($rows)) {
                  $afilter_ids[] = array(
                     'tpri_tprl_idx' => $iidx,
                     'tpri_tpal_idx' => $rows
                  );
               }
            }            
            if( $afilter_ids ) {
               $this->req_usage_exec_model->delete_items( $iidx );
               $bitem_result = $this->req_usage_exec_model->insert_items( $afilter_ids );

               if( $bitem_result ) {
                  $this->logs->set_log("Request #{$iidx}","UPDATE");
                  $this->common->set_message("Request has been updated successfully!","request-message", "success");
               } else {
                  $this->common->set_message("Sorry, there is an error saving the data.","request-message", "warning");               
               }
               redirect($_SERVER['HTTP_REFERER']);
            }
         }
      }
   }
   
   public function export_request()
   {
      $arequest_items_list = array();
      // Load PHPExvel Library
      $this->load->library('site/PHPExcel/PHPExcel');
      // Load PHPExvel Worksheet Drawing
      $objDrawing = new PHPExcel_Worksheet_Drawing();
      
      $iidx = $this->input->get('id');      
      // Check if the id is not numeric then show 404 page
      if( !is_numeric( $iidx ) ) {
         show_404();
      }      
      $arequest = $this->req_usage_get_model->get_request_row($iidx);
      // Check if there is no record in the database then show 404 page
      if( !$arequest ) {
         show_404();
      }
      $srequested_by = $arequest->tprl_requested_by;
      $slocation = $arequest->tprl_location_shoot;
      $stheme = $arequest->tprl_purpose_theme;
      $sactivity_date = $arequest->tprl_activity_date;
      $acategory = $this->req_usage_get_model->get_category();
      
      if( $acategory ) {
         foreach( $acategory as $rows ) {
            $arequest_items = $this->req_usage_get_model->get_request_items_by_person_category($iidx, $rows->tpac_idx);
            $arequest_items_list[] = array(
               'category' => $rows->tpac_category,
               'arequest_items' => $arequest_items
            );
         }
      }
      $this->phpexcel->setActiveSheetIndex(0);
      $this->phpexcel->getActiveSheet()->setTitle("Asset Accountability Form" . date('Y-m-d',time()));
      $this->phpexcel->getDefaultStyle()->getFont()->setName('Arial');
      $this->phpexcel->getDefaultStyle()->getFont()->setSize(10); 
      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'ASSET ACCOUNTABILITY FORM');
      $this->phpexcel->getActiveSheet()->mergeCells('A1:D1');
      
      $objDrawing->setName('SIMPLEX INTERNET Philippines Inc.');
      $objDrawing->setDescription('SIMPLEX INTERNET Philippines Inc. logo');
      $objDrawing->setPath(APPPATH . 'modules/photo/assets/images/company_logo.png');
      
      $objDrawing->setOffsetX(36);
      $objDrawing->setCoordinates('D1');
      $objDrawing->setWorksheet($this->phpexcel->getActiveSheet());
      
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
      $this->phpexcel->getActiveSheet()->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFFFFF');
      $this->phpexcel->getActiveSheet()->getRowDimension(1)->setRowHeight(33);
      
      $this->phpexcel->getActiveSheet()->mergeCells('A2:D2');
      $this->phpexcel->getActiveSheet()->getStyle('A2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('A2')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('A2')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('E2')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('A2')->getFill()->applyFromArray(
         array(
            'type'       => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => 'BEBEBE'),
            'endcolor'   => array('rgb' => 'BEBEBE')
         )
      );

      // Set value for user information
      $this->phpexcel->getActiveSheet()->setCellValue('A3', 'Requested by:');
      $this->phpexcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
      
      $this->phpexcel->getActiveSheet()->setCellValue('A4', 'Location:');
      $this->phpexcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);
      
      $this->phpexcel->getActiveSheet()->setCellValue('A5', 'Theme:');
      $this->phpexcel->getActiveSheet()->getStyle("A5")->getFont()->setBold(true);      
      
      $this->phpexcel->getActiveSheet()->setCellValue('C3', 'Activity date:');
      $this->phpexcel->getActiveSheet()->getStyle("C3")->getFont()->setBold(true);
      
      $this->phpexcel->getActiveSheet()->setCellValue('C4', 'Department:');
      $this->phpexcel->getActiveSheet()->getStyle("C4")->getFont()->setBold(true);
      
      $this->phpexcel->getActiveSheet()->setCellValue('B3', $srequested_by);
      $this->phpexcel->getActiveSheet()->setCellValue('B4', $slocation);
      $this->phpexcel->getActiveSheet()->setCellValue('B5', $stheme);
      $this->phpexcel->getActiveSheet()->setCellValue('D3', date('Y-m-d', $sactivity_date) );
      $this->phpexcel->getActiveSheet()->setCellValue('D4', '');
      
      
      // Set width for user information textfields
      $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(28);
      $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
      $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
      $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
      
      $this->phpexcel->getActiveSheet()->getStyle('B3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('D3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('B4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('D4')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('B5')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

      $irows_start = 7;
      $iitem_counter = 1;
      if( $arequest_items_list ) {
         foreach( $arequest_items_list as $rows ) {
            $this->phpexcel->getActiveSheet()->setCellValue("A{$irows_start}", $rows['category']);
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getFont()->setBold(true);
            $this->phpexcel->getActiveSheet()->mergeCells("A{$irows_start}:D{$irows_start}");
            
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
            $this->phpexcel->getActiveSheet()->getStyle("E{$irows_start}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getFill()->applyFromArray(
               array(
                  'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                  'startcolor' => array('rgb' => 'BEBEBE'),
                  'endcolor'   => array('rgb' => 'BEBEBE')
               )
            );
            
            $this->phpexcel->getActiveSheet()->getRowDimension($irows_start)->setRowHeight(15);
            if( $rows['arequest_items'] ) {
               foreach( $rows['arequest_items'] as $item_rows ) {
                  $irows_start += 1;
                  $this->phpexcel->getActiveSheet()->setCellValue("A{$irows_start}", "{$iitem_counter}. {$item_rows->tpal_item_name}");
                  $this->phpexcel->getActiveSheet()->setCellValue("B{$irows_start}", $item_rows->tpal_description);
                  $this->phpexcel->getActiveSheet()->setCellValue("D{$irows_start}", $item_rows->tpal_remarks);
                  
                  $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                  $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                  $this->phpexcel->getActiveSheet()->getStyle("B{$irows_start}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->phpexcel->getActiveSheet()->getStyle("B{$irows_start}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                  $this->phpexcel->getActiveSheet()->getStyle("B{$irows_start}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                  $this->phpexcel->getActiveSheet()->getStyle("C{$irows_start}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->phpexcel->getActiveSheet()->getStyle("C{$irows_start}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                  $this->phpexcel->getActiveSheet()->getStyle("C{$irows_start}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                  
                  $this->phpexcel->getActiveSheet()->getStyle("D{$irows_start}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->phpexcel->getActiveSheet()->getStyle("D{$irows_start}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                  $this->phpexcel->getActiveSheet()->getStyle("D{$irows_start}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                  $this->phpexcel->getActiveSheet()->getStyle("E{$irows_start}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                  $iitem_counter += 1;
               }
            }else {
               $irows_start += 1;
               $this->phpexcel->getActiveSheet()->setCellValue("A{$irows_start}", "----");
               $this->phpexcel->getActiveSheet()->mergeCells("A{$irows_start}:D{$irows_start}");
               $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getFont()->setBold(true);
               $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
               $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
               $this->phpexcel->getActiveSheet()->getStyle("A{$irows_start}")->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
               $this->phpexcel->getActiveSheet()->getStyle("E{$irows_start}")->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);               
            }
            $irows_start += 1;
         }
      }
      
      $ifooter_start_row = $irows_start + 2;
      
      // Approved By
      $this->phpexcel->getActiveSheet()->setCellValue("A".($ifooter_start_row), "Approved by:");
      $this->phpexcel->getActiveSheet()->getStyle("A".($ifooter_start_row))->getFont()->setBold(true);
      // Returned by/date
      $this->phpexcel->getActiveSheet()->setCellValue("C".($ifooter_start_row), "Returned by/date:");
      $this->phpexcel->getActiveSheet()->getStyle("C".($ifooter_start_row))->getFont()->setBold(true);
      // Alex cho
      $this->phpexcel->getActiveSheet()->setCellValue("B".($ifooter_start_row + 1), "Alex Cho");
      $this->phpexcel->getActiveSheet()->getStyle("B".($ifooter_start_row + 1))->getFont()->setBold(true);
      $this->phpexcel->getActiveSheet()->getStyle('B'.($ifooter_start_row + 1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('B'.($ifooter_start_row + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      // Photographer
      $this->phpexcel->getActiveSheet()->setCellValue("D".($ifooter_start_row + 1), "Photographer:");
      $this->phpexcel->getActiveSheet()->getStyle("D".($ifooter_start_row + 1))->getFont()->setBold(true);
      $this->phpexcel->getActiveSheet()->getStyle('D'.($ifooter_start_row + 1))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('D'.($ifooter_start_row + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      
      // Released by
      $this->phpexcel->getActiveSheet()->setCellValue("A".($ifooter_start_row + 3), "Released by:");
      $this->phpexcel->getActiveSheet()->getStyle("A".($ifooter_start_row + 3))->getFont()->setBold(true);
      // Received by/date
      $this->phpexcel->getActiveSheet()->setCellValue("C".($ifooter_start_row + 3), "Received by/date:");
      $this->phpexcel->getActiveSheet()->getStyle("C".($ifooter_start_row + 3))->getFont()->setBold(true);      
      
      // IT Department
      $this->phpexcel->getActiveSheet()->setCellValue("B".($ifooter_start_row + 4), "IT Dept.");
      $this->phpexcel->getActiveSheet()->getStyle("B".($ifooter_start_row + 4))->getFont()->setBold(true);
      $this->phpexcel->getActiveSheet()->getStyle('B'.($ifooter_start_row + 4))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $this->phpexcel->getActiveSheet()->getStyle('B'.($ifooter_start_row + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);      
      // IT Department
      $this->phpexcel->getActiveSheet()->setCellValue("D".($ifooter_start_row + 4), "IT Dept.");
      $this->phpexcel->getActiveSheet()->getStyle("D".($ifooter_start_row + 4))->getFont()->setBold(true);
      $this->phpexcel->getActiveSheet()->getStyle('D'.($ifooter_start_row + 4))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);      
      $this->phpexcel->getActiveSheet()->getStyle('D'.($ifooter_start_row + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     

      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="'."Asset Accountability Form-{$srequested_by}-" . date('Y-m-d',time()).'.xls'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache

      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');  
      $objWriter->save('php://output');
   }
}