<?php
class Getmod_statistic extends Getclass{

    public function select_withnumrows_query($aOption,$aLimit=null,$aSearch=null){
    
        switch($aOption['type']){
            case "category":
                $aOption['type'] = 'tsit_tssc_sscid';
                $aOption['search_row_result'] = 'tbl_stock_sub_category.tssc_sscid';
            break;
            
            case "model":
                $aOption['type'] = 'tsit_model';
                $aOption['search_row_result'] = 'tsit.tsit_model';
            break;
         
        }
      
      if($aSearch != null){
        $sSearchhDate = 'AND tsit_registered_date >='.$aSearch['from_date'].' '.' AND tsit_registered_date <='.$aSearch['to_date'];
      }else{
        $sSearchhDate = '';
      }
       
       $squery = 'SELECT *,
                    (SELECT COUNT(*) FROM tbl_stock_item WHERE '.$aOption['type'].' LIKE '.$aOption['search_row_result'].' AND tsit_active = 1 ) AS search_row_count
                    FROM
                    tbl_stock_item AS tsit
                    INNER JOIN 
                    tbl_stock_sub_category
                    ON
                    tbl_stock_sub_category.tssc_sscid = tsit.tsit_tssc_sscid
                    INNER JOIN 
                    tbl_stock_main_category
                    ON
                    tbl_stock_main_category.tsmc_smcid = tbl_stock_sub_category.tssc_tsmc_smcid
                    WHERE tsit.tsit_active = 1
                    AND
                    tbl_stock_main_category.tsmc_smcid = '.$aOption['main_cat_id'].'
                    '.$sSearchhDate.'
                    GROUP BY '.$aOption['type'].'
                    ORDER BY search_row_count DESC';
                     
             if($aLimit != null){
                $squery .= ' LIMIT '.$aLimit['offset'].','.$aLimit['limit'];
             }
             
         $result = $this->db->query($squery);
         return $result->result();
        //return $squery;
    }
    
    function select_where($aOption){
            
        switch($aOption['type']){
            case "category":
                $aOption['type'] = 'tsit_tssc_sscid';
            break;
            
            case "model":
                $aOption['type'] = 'tsit_model';
            break;
         
        }
        
        $this->db->select('*');
        $this->db->group_by($aOption['type']);
        $this->db->from('tbl_stock_item AS tsit');
        $this->db->join('tbl_stock_sub_category AS tssc', 'tssc.tssc_sscid = tsit.tsit_tssc_sscid', 'INNER');
        
        //$this->db->join('tbl_stock_main_category AS tsmc', 'tsmc.tsmc_smcid = tssc.tssc_tsmc_smcid', 'INNER');
        
        $this->db->where('tsit_active',1); 
        $this->db->where('tssc_tsmc_smcid',$aOption['main_cat_id']); 
        $result = $this->db->get();
          
        return $result->result();
    
    }
    
    function get_db_data_count($aOption,$sDistinctOption){
        
        $this->db->select('*');
       $this->db->group_by($sDistinctOption);

        
        $this->db->from('tbl_stock_item AS tsit');
        $this->db->join('tbl_employee AS te', 'te.te_idx = tsit.tsit_user_assigned', 'INNER');
        $this->db->join('tbl_stock_sub_category AS tssc', 'tssc.tssc_sscid = tsit.tsit_tssc_sscid', 'INNER');
        $this->db->join('tbl_stock_main_category AS tsmc', 'tsmc.tsmc_smcid = tssc.tssc_tsmc_smcid', 'INNER');
        
        $this->db->where('tsit_active',1); 
        $this->db->where('tssc.tssc_tsmc_smcid',$aOption['main_cat_id']);  
        
        $result = $this->db->get();
        return $result->num_rows();
    }
    


}