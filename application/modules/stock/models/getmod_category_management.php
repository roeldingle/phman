<?php
class Getmod_category_management extends Getclass{

    private function mainQuery(){
        $this->db->select('*');
        $this->db->from('tbl_stock_item AS tsit');
        $this->db->join('tbl_employee AS te', 'te.te_idx = tsit.tsit_user_assigned', 'INNER');
        $this->db->join('tbl_stock_sub_category AS tssc', 'tssc.tssc_sscid = tsit.tsit_tssc_sscid', 'INNER');
        $this->db->join('tbl_stock_main_category AS tsmc', 'tsmc.tsmc_smcid = tssc.tssc_tsmc_smcid', 'INNER');
        
        
        return $this->db;
    }
	
    function get_db_tb_data($sMainCatId,$aLimit = null,$aSearch = null){
        self::mainQuery();
        $this->db->join('tbl_stock_history AS tshis', 'tshis.tshis_tsit_siid = tsit.tsit_siid', 'INNER');
        $this->db->where('tsit_active',1); 
        $this->db->where('tssc.tssc_tsmc_smcid',$sMainCatId); 
        
        if($aSearch != null){
            $this->db->where($aSearch['field'],$aSearch['item']); 
        }
        
        $this->db->order_by("tsit_registered_date", "desc"); 
        if($aLimit != null){
            $this->db->limit($aLimit['limit'],$aLimit['offset']);
        }
        
        
        $result = $this->db->get();
        return $result->result();
    }
    
    function get_db_data_count($sMainCatId,$aSearch = null){
        self::mainQuery();
        $this->db->where('tsit_active',1); 
        $this->db->where('tssc.tssc_tsmc_smcid',$sMainCatId);  

        if($aSearch != null){
            $this->db->where($aSearch['field'],$aSearch['item']); 
        }
        
        $result = $this->db->get();
        return $result->num_rows();
    }
    
    function get_maincategory_data($sMainCatId = null,$sRows = null){
        $this->db->select('*');
        $this->db->from('tbl_stock_main_category');
        
        if($sMainCatId != null){
            $this->db->where('tsmc_smcid',$sMainCatId); 
        }
        
        $this->db->where('tsmc_active',1); 
        $result = $this->db->get();
        return ($sRows == 'rows') ? $result->result_array() :$result->row_array();
    }
    
    function get_subcategory_data($sSubCatId){
        $this->db->select('*');
        $this->db->from('tbl_stock_sub_category');
        $this->db->where('tssc_tsmc_smcid',$sSubCatId); 
        $this->db->where('tssc_active',1); 
        $result = $this->db->get();
        return $result->result_array();
    }
    
    function get_employee_list(){
        $this->db->select('*');
        $this->db->from('tbl_employee');
        $this->db->where('te_active',1); 
        $result = $this->db->get();
        return $result->result_array();
    }
    
    function get_search_option($aOption){
    
        switch($aOption['search_by']){
            
            case "tsit_tssc_sscid":
                $aDbData = $this->get_subcategory_data($aOption['main_cat_id']);
                $aResult = $this->_customize_search_return($aOption['search_by'],$aDbData);
            break;
            
            case "tsit_user_assigned":
                $aDbData = $this->get_employee_list();
                $aResult = $this->_customize_search_return($aOption['search_by'],$aDbData);
            break;
            
            default:
                $sql = "
                SELECT 
                DISTINCT 
                ".$aOption['search_by']." 
                FROM 
                tbl_stock_item 
                WHERE 
                tsit_tssc_sscid 
                IN (SELECT tssc_sscid FROM tbl_stock_sub_category WHERE tssc_tsmc_smcid=?)
                AND tsit_active = 1";
                
                $result = $this->db->query($sql,$aOption['main_cat_id']); 
                $aResult = $aResult = $this->_customize_search_return($aOption['search_by'],$result->result_array());
            break;
        
        
        }
        
        return $aResult;
    }
    
    private function _customize_search_return($sSearch_by,$aDbData){
        $aResult = array();
        switch($sSearch_by){
            case "tsit_tssc_sscid":
                foreach ($aDbData as $key=>$row){
                    $aResult[$key]['id'] = $row['tssc_sscid'];
                    $aResult[$key]['label'] = $row['tssc_name'];
                }
            break;
            
            case "tsit_user_assigned":
                foreach ($aDbData as $key=>$row){
                    $aResult[$key]['id'] = $row['te_idx'];
                    $aResult[$key]['label'] = $row['te_fname'].' '.$row['te_lname'];
                }
            break;
            
            default:
                foreach ($aDbData as $key=>$row){
                    $aResult[$key]['id'] = "";
                    $aResult[$key]['label'] = $row[$sSearch_by];
                }
            break;
            
        }
        return $aResult;
    }
    
	function get_stock_data($stockitem_id){
        self::mainQuery();
        $this->db->join('tbl_stock_history AS tshis', 'tshis.tshis_tsit_siid = tsit.tsit_siid', 'INNER');
        $this->db->where('tsit_active',1); 
        $this->db->where('tsit.tsit_siid',$stockitem_id); 
        $result = $this->db->get();
        return $result->row_array();
    }
    
    function get_stock_by_subcategory($subcat_id,$sRows){
        $this->db->select('*');
        $this->db->from('tbl_stock_item');
        $this->db->where('tsit_active',1); 
        $this->db->where('tsit_tssc_sscid',$subcat_id); 
        $result = $this->db->get();
        return ($sRows == 'rows') ? $result->result_array() :$result->row_array();
    }
    
    
    

}