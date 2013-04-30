<?php
class Myaccount_model extends Getclass{

	public $main_query;
    
    function __construct(){
        $this->main_query = 'SELECT 
                    	*
                    FROM
                        tbl_user as tu
                        JOIN
                        tbl_employee as te
                        ON
                        tu.tu_te_idx = te.te_idx
                        JOIN
                        tbl_user_grade as tug
                        ON
                        tu.tu_tug_idx = tug.tug_idx
                        JOIN
                        tbl_employee_company_record as tecr
                        ON
                        tecr.tecr_te_idx = te.te_idx
                        JOIN
                        tbl_position as tp
                        ON
                        tecr.tecr_tp_idx = tp.tp_idx

                        WHERE tu_active = 1';
    
    }
                   
	function get_tb_user_data($sWhere){
                      
        /*primary row count*/                
        $iTbTotCount = count($this->getclass->query_db($this->main_query));
        
        $sQuery = $this->main_query;
        
        /*set query conditions*/
        if($sWhere){
            $sQuery .= ' '.$sWhere;
        }
        
        return $aData = $this->getclass->query_db($sQuery);
                        
    }
	
    function get_db_tb_count(){
        
        $query = $this->db->query($this->main_query);
        return $query->num_rows();
    
    }
	


}