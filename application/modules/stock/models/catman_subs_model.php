<?php

class Catman_subs_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

	public function record_list_model($main_catId=null, $page=null, $limit=null, $search=null)
    {
        $query  = 'SELECT 
                        tbl_stock_item.tsit_tssc_sscid,
                        tbl_stock_item.tsit_siid,
                        tbl_stock_sub_category.tssc_tsmc_smcid,
                        tbl_stock_sub_category.tssc_sscid,
                        tbl_stock_sub_category.tssc_name,

                        tbl_stock_main_category.tsmc_smcid,

                        tbl_stock_item.tsit_user_assigned,
                        tbl_stock_item.tsit_model,
                        tbl_stock_item.tsit_brand,
                        tbl_stock_item.tsit_serial_number,
                        tbl_stock_item.tsit_description,
                        tbl_stock_item.tsit_user_assigned,
                        tbl_stock_item.tsit_version,
                        
                        tbl_employee.te_idx,
                        tbl_employee.te_fname,
                        tbl_employee.te_lname,
                        
                        
                        DATE_FORMAT(from_unixtime(tbl_stock_item.tsit_registered_date),"%b %d %Y") AS reg_date,
                        DATE_FORMAT(from_unixtime(tbl_stock_item.tsit_last_update),"%b %d %Y") AS last_update,
                        tbl_stock_item.tsit_active
                        
                        FROM 
                        tbl_stock_item LEFT JOIN tbl_stock_sub_category
                            ON tbl_stock_item.tsit_tssc_sscid = tbl_stock_sub_category.tssc_sscid
                        LEFT JOIN tbl_stock_main_category
                            ON tbl_stock_sub_category.tssc_tsmc_smcid = tbl_stock_main_category.tsmc_smcid
                        LEFT JOIN tbl_employee
                            ON tbl_employee.te_idx = tbl_stock_item.tsit_user_assigned 
                            
                            WHERE tbl_stock_main_category.tsmc_smcid = '.$main_catId.' AND tbl_stock_item.tsit_active = 1 ';

        /* --If search is set */
        if ($search != null) {
            // if ($main_catId == 11) {
                // $query          .= ' AND tsit_version LIKE "%'.$search.'%" ';
                if ($search['search_type'] != 'tsit_user_assigned') {
                    $query          .= ' AND '.$search['search_type'].' LIKE "%'.$search['search_key'].'%" ';
                } else {
                    $query          .= ' AND '.$search['search_type'].' = '.$search['search_key'].' ';
                }
                
            // } else {
                
                // $query          .= ' AND tsit_model LIKE "%'.$search.'%" ';
            // }
        }
        $query          .=    ' ORDER BY tbl_stock_item.tsit_registered_date ASC';
                                    
        /* --Get the total rows of stocks under stocks for pagination */
        $total      = $this->db->query($query);
        $totalrows  = $total->result();
        
        /* --Get the paginated results of stock */
        if ($page != null && $limit != null) {
            $offset     = ($page - 1) * $limit;
            $query      .= ' LIMIT '.$offset.', '.$limit;
            
            $strQuery   = $query;
            
            $query      = $this->db->query($query);
            $rows       = $query->result();
        } else {
            $rows       = $totalrows;
            $strQuery   = $query;
        }
        
        
        /* --Get the list of main categories
            Purpose is to get the ids of main categories and put it
            in the sidebar upon loading the stock page, so that there
            will be no more ajax request whenever main category info
            are needed */
        $main_categories    = $this->main_category();
        
        /* --Session */
        $_session           = $this->session->all_userdata();
        
        return array('rows'=>$rows, 'total_rows'=>count($totalrows), 'main_categories'=>$main_categories, 'sessions'=>$_session, 'query'=>$strQuery);
    }
    
    public function main_category()
	{
        $query  = $this->db->query('SELECT * FROM tbl_stock_main_category WHERE tsmc_active = 1');
        $rows   = $query->result();
        return array('rows'=>$rows);
	}
    
   
}