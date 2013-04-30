<?php
class Modcustom extends Getclass{

	public $main_query;
    
    function __construct(){
        // $this->main_query = 'SELECT 
                    	// tu_idx,
                        // tu_te_idx,
                        // tu_tug_idx,
                        // tu_username,
                        // tu_date_created,
                        // tug_idx,
                        // tug_tm_idx,
                        // tug_name,
                        // te_fname,
                        // te_lname
                    // FROM
                        // tbl_user as tu
                        // JOIN
                        // tbl_employee as te
                        // ON
                        // tu.tu_te_idx = te.te_idx
                        // JOIN
                        // tbl_user_grade as tug
                        // ON
                        // tu.tu_tug_idx = tug.tug_idx WHERE tu_active = 1
                        // ORDER BY tu_date_created DESC';
                        
           $this->main_query = 'SELECT 
                    	tu_idx,
                        tu_te_idx,
                        tu_tug_idx,
                        tu_username,
                        tu_date_created,
                        tug_idx,
                        tug_tm_idx,
                        tug_name
                    FROM
                        tbl_user as tu
                        JOIN
                        tbl_user_grade as tug
                        ON
                        tu.tu_tug_idx = tug.tug_idx WHERE tu_active = 1
                        ORDER BY tu_date_created DESC';
    
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
        //return $sQuery;
                        
    }
	
    function get_db_tb_count(){
        
        $query = $this->db->query($this->main_query);
        return $query->num_rows();
    
    }
	


}