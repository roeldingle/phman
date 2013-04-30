<?php

class Api extends MX_Controller
{
     public function __construct(){
        parent::__construct();
        /*load model class*/
        $this->load->model("getclass");
        $this->load->model("getmod_category_management");
        $this->load->model("execmod_category_management");
        
        $this->load->library('common_mod_lib');
        
        $this->load->module("settings/logs");
        
    }
    
    public function get_maincategory_data(){
        
        $aReturn = $this->getclass->select('tbl_stock_main_category','',$bRow = "rows");
        
        echo json_encode($aReturn);
    
    }
    
    public function modify_subcat_data(){
        
        $sub_cat_id = $this->input->post('sub_cat_id', TRUE);
        
        $aReturn['maincat_data'] = $this->getclass->select('tbl_stock_main_category','',$bRow = "rows");
        $aReturn['subcat_data'] = $this->getclass->select('tbl_stock_sub_category','tssc_sscid = "'.$sub_cat_id.'"');
        
        echo json_encode($aReturn);
    
    }
    
    public function subcategory_save(){
        
        $adata = $this->common_mod_lib->urldecode_to_array($this->input->post('data', TRUE));
        $sTableName = 'tbl_stock_sub_category';
        $aInsertTbItemData['tssc_tsmc_smcid'] = $adata['main_category_selectlist'];
        $aInsertTbItemData['tssc_tu_idx'] = $this->session->userdata('userid');
        $aInsertTbItemData['tssc_name'] = $adata['sub_category_name'];
        $aInsertTbItemData['tssc_description'] = $adata['sub_category_desc'];
        $aInsertTbItemData['tssc_active'] = 1;
        
        if(isset($adata['subcat_id'])){
            $aWhere = array(
                'field' => 'tssc_sscid',
                'value' => $adata['subcat_id']
            );
            $aReturn = $this->execmod_category_management->update_data($sTableName,$aInsertTbItemData,$aWhere);
        }else{
            $aReturn = $this->execmod_category_management->save_data($sTableName,$aInsertTbItemData);
        }
        
        echo json_encode($aReturn);
    }
    
    public function delete_subcategory_data(){
        
        $sub_cat_id = $this->input->post('sub_cat_id', TRUE);
        
        $sTbName = 'tbl_stock_sub_category';
        $sWhere = 'tssc_sscid ';
        $aData = $sub_cat_id;
        $sSet = 'tssc_active = 0';
        
        $aReturn = $this->execmod_category_management->delete_data($sTbName,$sWhere,$aData,$sSet);
        
        if($aReturn == true){
            $this->logs->set_log("Sub-category #{$sub_cat_id}",'DELETE');
            $sTableName = 'tbl_stock_item';
            $aInsertTbItemData['tsit_active'] = 0;
            $aWhere = array(
                'field' => 'tsit_tssc_sscid',
                'value' => $sub_cat_id
            );
            $this->execmod_category_management->update_data($sTableName,$aInsertTbItemData,$aWhere);
        }
        
        echo json_encode($aReturn);
    
    }
    
    
    public function get_category_data(){
    
        $category_id = $this->input->post('category_id', TRUE);
        
        $aReturn['main_cat_data'] = $this->getmod_category_management->get_maincategory_data($category_id);
        
        $aReturn['sub_cat_data'] = $this->getmod_category_management->get_subcategory_data($category_id);
        
        $aReturn['emp_data'] = $this->getmod_category_management->get_employee_list();
        
        echo json_encode($aReturn);
        
    }
    
    public function save_stock_data(){
        /*get the ajax data*/
        $aData['data'] = $this->common_mod_lib->urldecode_to_array($this->input->post('data', TRUE));
        $aData['category_id'] = $this->input->post('category_id', TRUE);
        $aData['process'] = $this->input->post('process', TRUE);
        $aData['mod_stock_id'] = $this->input->post('mod_stock_id', TRUE);
        $aData['mod_history_id'] = $this->input->post('mod_history_id', TRUE);
        
        /*loop for the data to insert*/
        foreach($aData['data'] as $key=>$val){
            if($val != ""){
                $aInsertData[$key] = $val;
            }
        }
        
        /*data for tbl_stock_item*/
        $sTableName = 'tbl_stock_item';
        $aInsertTbItemData = $aInsertData;
        $aInsertTbItemData['tsit_tu_idx'] = $this->session->userdata('userid');
        $aInsertTbItemData['tsit_purchased_date'] = time();
        $aInsertTbItemData['tsit_registered_date'] = time();
        $aInsertTbItemData['tsit_active'] = 1;
        $aInsertTbItemData['tsit_model'] = trim($aInsertTbItemData['tsit_model']);
        /*if cat=software unset brand and set to version*/
        if($aData['category_id'] === "000011"){
            $aInsertTbItemData['tsit_version'] = $aInsertTbItemData['tsit_model'];
            unset($aInsertTbItemData['tsit_model']);
            
        }
        
        /*remove history data=will not be inserted in the tbl_stock_item*/
        unset($aInsertTbItemData['tshis_history']);
        
        /*validate if add or update by the modify stock id return null=add , !null=modify*/
        if($aData['mod_stock_id'] != 'null'){
            $aWhere = array(
                'field' => 'tsit_siid',
                'value' => $aData['mod_stock_id']
            );
            
            /*if modify remove tsit_registered_date and set tsit_last_update*/
            unset($aInsertTbItemData['tsit_registered_date']);
            $aInsertTbItemData['tsit_last_update'] = time();
            
            $bStockReturn = $this->execmod_category_management->update_data($sTableName,$aInsertTbItemData,$aWhere);
            $this->logs->set_log('Stock #'.$aData['mod_stock_id'],'UPDATE');
        }else{
            $bStockReturn = $this->execmod_category_management->save_data($sTableName,$aInsertTbItemData);
            $this->logs->set_log('New stock','CREATE');
        }
        
        /*if tbl_stock_item is inserted ****wrong tshis_tsit_siid inserted to tbl_stock_history */
        if($aData['mod_stock_id'] == 'null' && $bStockReturn == true){
            
            /*add on data for tbl_stock_history*/
            $aLastStockItem = $this->getclass->select($sTableName,'tsit_active = 1 ORDER BY tsit_siid DESC','row');
            $sTableName = 'tbl_stock_history';
            $aInsertTbHistoryData['tshis_tsit_siid'] = $aLastStockItem['tsit_siid'];//$this->db->insert_id();
            $aInsertTbHistoryData['tshis_history'] = $aInsertData['tshis_history'];
            $aInsertTbHistoryData['tshis_date_created'] = time();
            $aInsertTbHistoryData['tshis_active'] = 1;
            
            $bHisReturn = $this->execmod_category_management->save_data($sTableName,$aInsertTbHistoryData);
            
            
           
        }else{
            /*add on data for tbl_stock_history*/
            $sTableName = 'tbl_stock_history';
            //$aInsertTbHistoryData['tshis_tsit_siid'] = $this->db->insert_id();
            $aInsertTbHistoryData['tshis_history'] = $aInsertData['tshis_history'];
            $aInsertTbHistoryData['tshis_date_created'] = time();
            //$aInsertTbHistoryData['tshis_active'] = 1;
            
                $aWhere = array(
                    'field' => 'tshis_idx',
                    'value' => $aData['mod_history_id']
                );
                 
                 /*remove tshis_tsit_siid & tshis_date_created*/
                 unset($aInsertTbHistoryData['tshis_tsit_siid']);
                 unset($aInsertTbHistoryData['tshis_date_created']);
                 
                 $bHisReturn = $this->execmod_category_management->update_data($sTableName,$aInsertTbHistoryData,$aWhere);
                 
            }
        
        
        if($bStockReturn== false && $bHisReturn== false){
            $bReturn = false;
        }else{
            $bReturn = true;
        }
       
        echo json_encode($bReturn);
    }
    
    public function search_option(){
    
        $aOption = $this->input->post('data', TRUE);
        $aReturn = $this->getmod_category_management->get_search_option($aOption);
        echo json_encode($aReturn);
    
    }
    
    public function get_stock_data(){
    
        $stockitem_id = $this->input->post('stockitem_id', TRUE);
        
        $aReturn = $this->getmod_category_management->get_stock_data($stockitem_id);
        
        echo json_encode($aReturn);
    
    
    }
    
    public function get_modstock_data(){
    
        $category_id = $this->input->post('category_id', TRUE);
        
        $stockitem_id = $this->input->post('stockitem_id', TRUE);
        
        $aReturn['main_cat_data'] = $this->getmod_category_management->get_maincategory_data($category_id);
        
        $aReturn['sub_cat_data'] = $this->getmod_category_management->get_subcategory_data($category_id);
        
        $aReturn['stock_data'] = $this->getmod_category_management->get_stock_data($stockitem_id);
        
        $aReturn['emp_data'] = $this->getmod_category_management->get_employee_list();

        echo json_encode($aReturn);
    
    
    
    }
    
    /*just remove from list (Update to active=0)*/
    public function delete_stock_data(){
        
        $row_stock_ids = $this->input->post('row_stock_ids', TRUE);
        
        $sTbName = 'tbl_stock_item';
        $sWhere = 'tsit_siid';
        $aData = is_array($row_stock_ids) ? implode(',',$row_stock_ids) : $row_stock_ids;
        $sSet = 'tsit_active = 0';
        
        $aReturn = $this->execmod_category_management->delete_data($sTbName,$sWhere,$aData,$sSet);
        
        if($aReturn == true){
            $this->logs->set_log("Stock #{$row_stock_ids}",'DELETE');
        }
        
        echo json_encode($aReturn);
    }
    
}