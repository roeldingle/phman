<?php

class Ajax_in_report extends MX_Controller
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
   
   public function get_sub_category_by_id()
   {
      $astatus = array();
      $iidx = $this->input->post('id');
      if ( !is_numeric( $iidx ) ) {
         show_404();
      };
      $asub_category = $this->in_report_get_model->get_sub_category_by_id( $iidx );
      if( !$asub_category ) {
         $astatus['status'] = '';
      } else {
         $astatus['status'] = 'ok';
         $asub_category_list = array();
         foreach($asub_category as $rows) {
            $asub_category_list[] = array(
               'id' => $rows->tssc_sscid,
               'name' => $rows->tssc_name,
               'description' => $rows->tssc_description               
            );
         }
         $astatus['sub_categories'] = $asub_category_list;
      };
      
      echo json_encode($astatus);
   }
   
   
   public function get_stock_item_by_sub_id()
   {
      $astatus = array();
      $iidx = $this->input->post('id');
      if ( !is_numeric( $iidx ) ) {
         show_404();
      };
      $astock_item = $this->in_report_get_model->get_stock_item_by_sub_id( $iidx );
      if( !$astock_item ) {
         $astatus['status'] = '';
      } else {
         $astatus['status'] = 'ok';
         $astock_items_list = array();
         foreach($astock_item as $rows) {
            $astock_items_list[] = array(
               'id' => $rows->tsit_siid,
               'user_id' => $rows->tsit_user_assigned,
               'serial' => $rows->tsit_serial_number,               
               'purchased_date' => @date("l, F d, Y", $rows->tsit_purchased_date),               
            );
         }
         $astatus['stock_items'] = $astock_items_list;
      };
      
      echo json_encode($astatus);
   }
   
   public function get_assign_on_item()
   {
      $astatus = array();
      $iidx = $this->input->post('id');
      if ( !is_numeric( $iidx ) ) {
         show_404();
      };
      $aemployee = $this->in_report_get_model->get_employee_by_id($iidx);
      if(!$aemployee) {
         $astatus['status'] = '';
      } else {
         $adata = array(
            'fullname' => "{$aemployee->te_fname} {$aemployee->te_mname} {$aemployee->te_lname}"
         );
         $astatus['status'] = 'ok';
         $astatus['employee'] = $adata;         
      }
      echo json_encode($astatus);
   }
   
   public function remove_office_equipments()
   {
      $aidx = $this->input->post('ids');
      $afilter_ids = array();
      foreach( $aidx as $rows ) {
         if(is_numeric($rows)) {
            $afilter_ids[] = array(
               'tsin_idx' => $rows,
               'tsin_active' => 0
            );
            $this->logs->set_log("Office Equipment #{$rows}","DELETE");
         }
      }
      
      $bresult = $this->in_report_exec_model->delete_office_equipments($afilter_ids);
      $this->common->set_message("Record has been deleted succesfully!", "php-message", "success");    
   }
   
   public function remove_others()
   {
      $aidx = $this->input->post('ids');
      $afilter_ids = array();
      foreach( $aidx as $rows ) {
         if(is_numeric($rows)) {
            $afilter_ids[] = array(
               'tsio_idx' => $rows,
               'tsio_active' => 0
            );
            $this->logs->set_log("Other Equipment #{$rows}","DELETE");
            // $this->logs->set_log("Request #{$rows}","DELETE");
         }
      }
      
      $bresult = $this->in_report_exec_model->delete_others($afilter_ids);
      $this->common->set_message("Record has been deleted succesfully!", "php-message", "success");    
   }
}