<?php
class Expense_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_sidebar_year()
    {
        $query = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM(FROM_UNIXTIME(tel_date))) AS menu_year
                                    FROM tbl_expense_list 
                                    ORDER BY menu_year DESC");
        return $query->result();
    }

    public function get_sidebar_month()
    {
        $query = $this->db->query("SELECT DISTINCT EXTRACT(YEAR FROM(FROM_UNIXTIME(tel_date))) AS menu_year,
                                    MONTHNAME(FROM_UNIXTIME(tel_date)) AS menu_month,
                                    LPAD(EXTRACT(MONTH FROM (FROM_UNIXTIME(tel_date))), 2, '0') AS i_menu_month
                                    FROM tbl_expense_list
                                    ORDER BY i_menu_month DESC");
        return $query->result();
    }

    public function get_sidebar_main_menu()
    {
        $query = $this->db->query("SELECT *
                                    FROM tbl_submenu
                                    WHERE tsu_tm_idx = '000002'
                                    ORDER BY tsu_sequence ASC");
        return $query->result();
    }

    public function get_department()
    {
        $query = $this->db->query("SELECT td_idx, td_dept_name
                                    FROM tbl_department
                                    WHERE td_dept_name LIKE '%HR%' 
                                    OR td_dept_name LIKE '%Admin%'
                                    OR td_dept_name LIKE '%IT%'
                                    OR td_dept_name LIKE '%Head Office%'
                                    OR td_dept_name LIKE '%Photo%'");
        return $query->result();
    }

    public function get_status()
    {
        $this->db->select('tes_idx, tes_status');
        $this->db->from('tbl_expense_status');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_category()
    {
        $this->db->select('tec_idx, tec_name');
        $this->db->from('tbl_expense_category');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_dept_links($year,$month)
    {
        $adept['dept'] = array();
        $adept['dept_id'] = array();
        $adept['count'] = array();

        $query1 = $this->db->query('SELECT * 
                                    FROM tbl_department 
                                    WHERE td_dept_name IN ("HR","IT","Admin","Head Office")
                                    LIMIT 5 OFFSET 0');

        foreach($query1->result() as $dept_name){
            array_push($adept['dept'], $dept_name->td_dept_name);
            array_push($adept['dept_id'], $dept_name->td_idx);
        }

        foreach($query1->result() as $dept){
            $query2 = $this->db->query('SELECT COUNT(*) as dcount
                                        FROM tbl_expense_list
                                        WHERE tel_td_idx = ' . $dept->td_idx . '
                                        AND FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                        AND tbl_expense_list.tel_edit_idx IS NULL');
            array_push($adept['count'], $query2->row()->dcount);
        }

        return $adept;
    }

    public function get_count($year,$month,$sort,$pf,$pt,$dept)
    {
        if($sort == '0'){
            $order_by = "tbl_expense_list.tel_idx";
        }elseif($sort == '1'){
            $order_by = "tbl_department.td_dept_name";
        }elseif($sort == '2'){
            $order_by = "tbl_expense_status.tes_status";
        }elseif($sort == '3'){
            $order_by = "tbl_expense_list.tel_type";
        }else{
            $order_by = "tbl_expense_list.tel_date";
        }

        if($pf!='' && $pt!=''){
            $additional_where = "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'";
        }else{
            $additional_where = "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."%'";
        }

        if($dept != ''){
            $additional_where2 = "AND tbl_department.td_idx = ".$dept;
        }else{
            $additional_where2 = "";
        }

        $query = $this->db->query('SELECT  COUNT(*) as total_rows
                                    FROM tbl_expense_list 
                                    INNER JOIN tbl_department ON tbl_expense_list.tel_td_idx = tbl_department.td_idx
                                    WHERE '.$additional_where.' '.$additional_where2 . '
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->total_rows;
    }

    public function get_count_search($year,$month,$search_string)
    {
        $query = $this->db->query('SELECT  COUNT(*) as total_rows
                                    FROM tbl_expense_list 
                                    INNER JOIN tbl_department ON tbl_expense_list.tel_td_idx = tbl_department.td_idx
                                    INNER JOIN tbl_expense_status ON tbl_expense_list.tel_tes_idx = tbl_expense_status.tes_idx
                                    INNER JOIN tbl_expense_category ON tbl_expense_list.tel_tec_idx = tbl_expense_category.tec_idx
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                    AND tbl_department.td_dept_name = "'.$search_string.'"
                                    OR tbl_expense_category.tec_name = "'.$search_string.'"
                                    OR tbl_expense_status.tes_status = "'.$search_string.'"
                                    OR tbl_expense_list.tel_type = "'.$search_string.'"
                                    OR tbl_expense_list.tel_supplier_name = "'.$search_string.'"
                                    OR tbl_expense_list.tel_particulars = "'.$search_string.'"
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->total_rows;
    }

    public function get_list($year,$month,$alimit,$sort,$pf,$pt,$dept)
    {
        if($sort == '0'){
            $order_by = "tbl_expense_list.tel_idx";
        }elseif($sort == '1'){
            $order_by = "tbl_department.td_dept_name";
        }elseif($sort == '2'){
            $order_by = "tbl_expense_status.tes_status";
        }elseif($sort == '3'){
            $order_by = "tbl_expense_list.tel_type";
        }else{
            $order_by = "tbl_expense_list.tel_date";
        }

        if($pf!='' && $pt!=''){
            $additional_where = "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'";
        }else{
            $additional_where = "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."%'";
        }

        if($dept != ''){
            $additional_where2 = "AND tbl_department.td_idx = ".$dept;
        }else{
            $additional_where2 = "";
        }

        $query = $this->db->query('SELECT  tbl_expense_list.*,
                                        tbl_expense_list.tel_receive_amt - tbl_expense_list.tel_payment AS cashonhand,
                                        tbl_department.td_dept_name,
                                        DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) AS new_tel_date,
                                        tbl_expense_status.tes_status,
                                        tbl_expense_category.tec_name
                                    FROM tbl_expense_list 
                                    INNER JOIN tbl_department ON tbl_expense_list.tel_td_idx = tbl_department.td_idx
                                    INNER JOIN tbl_expense_status ON tbl_expense_list.tel_tes_idx = tbl_expense_status.tes_idx
                                    INNER JOIN tbl_expense_category ON tbl_expense_list.tel_tec_idx = tbl_expense_category.tec_idx
                                    WHERE '.$additional_where.' '.$additional_where2 .' 
                                    AND tbl_expense_list.tel_edit_idx IS NULL
                                    ORDER BY '.$order_by.'
                                    LIMIT '.$alimit['limit'] . ' OFFSET ' . $alimit['offset']);
        return $query->result();
    }
    
    public function get_list_spreadsheet($year,$month,$alimit,$sort,$pf,$pt,$dept)
    {
        if($sort == '0'){
            $order_by = "tbl_expense_list.tel_idx";
        }elseif($sort == '1'){
            $order_by = "tbl_department.td_dept_name";
        }elseif($sort == '2'){
            $order_by = "tbl_expense_status.tes_status";
        }elseif($sort == '3'){
            $order_by = "tbl_expense_list.tel_type";
        }else{
            $order_by = "tbl_expense_list.tel_date";
        }

        if($pf!='' && $pt!=''){
            $additional_where = "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'";
        }else{
            $additional_where = "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."%'";
        }

        if($dept != ''){
            $additional_where2 = "AND tbl_department.td_idx = ".$dept;
        }else{
            $additional_where2 = "";
        }

        $query = $this->db->query('SELECT  tbl_expense_list.*,
                                        tbl_expense_list.tel_receive_amt - tbl_expense_list.tel_payment AS cashonhand,
                                        tbl_department.td_dept_name,
                                        DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) AS new_tel_date,
                                        tbl_expense_status.tes_status,
                                        tbl_expense_category.tec_name
                                    FROM tbl_expense_list 
                                    INNER JOIN tbl_department ON tbl_expense_list.tel_td_idx = tbl_department.td_idx
                                    INNER JOIN tbl_expense_status ON tbl_expense_list.tel_tes_idx = tbl_expense_status.tes_idx
                                    INNER JOIN tbl_expense_category ON tbl_expense_list.tel_tec_idx = tbl_expense_category.tec_idx
                                    WHERE '.$additional_where.' '.$additional_where2 .' 
                                    AND (
                                    (tbl_expense_list.tel_tes_idx = "00000000001" AND tbl_expense_list.tel_edit_idx IS NOT NULL)
                                    OR
                                    (tbl_expense_list.tel_tes_idx = "00000000003" AND tbl_expense_list.tel_edit_idx IS NOT NULL)
                                    OR
                                    (tbl_expense_list.tel_tes_idx = "00000000008" AND tbl_expense_list.tel_edit_idx IS NOT NULL)
                                    OR
                                    (tbl_expense_list.tel_tes_idx IN ("00000000002","00000000004","00000000005","00000000006","00000000007"))
                                    )
                                    ORDER BY '.$order_by.'
                                    LIMIT '.$alimit['limit'] . ' OFFSET ' . $alimit['offset']);
        return $query->result();
    }

    public function search($year,$month,$alimit,$search_string)
    {
        $query = $this->db->query('SELECT  tbl_expense_list.*,
                                        tbl_expense_list.tel_receive_amt - tbl_expense_list.tel_payment AS cashonhand,
                                        tbl_department.td_dept_name,
                                        DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) AS new_tel_date,
                                        tbl_expense_status.tes_status,
                                        tbl_expense_category.tec_name
                                    FROM tbl_expense_list 
                                    INNER JOIN tbl_department ON tbl_expense_list.tel_td_idx = tbl_department.td_idx
                                    INNER JOIN tbl_expense_status ON tbl_expense_list.tel_tes_idx = tbl_expense_status.tes_idx
                                    INNER JOIN tbl_expense_category ON tbl_expense_list.tel_tec_idx = tbl_expense_category.tec_idx
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                    AND tbl_department.td_dept_name = "'.$search_string.'"
                                    OR tbl_expense_category.tec_name = "'.$search_string.'"
                                    OR tbl_expense_status.tes_status = "'.$search_string.'"
                                    OR tbl_expense_list.tel_type = "'.$search_string.'"
                                    OR tbl_expense_list.tel_supplier_name = "'.$search_string.'"
                                    OR tbl_expense_list.tel_particulars = "'.$search_string.'"
                                    AND tbl_expense_list.tel_edit_idx IS NULL
                                    LIMIT '.$alimit['limit'] . ' OFFSET ' . $alimit['offset']);
        return $query->result();
    }

    public function get_all($year,$month)
    {
        $query = $this->db->query('SELECT COUNT(*) as all_items
            FROM tbl_expense_list
            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
            AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->all_items;
    }

    public function get_head_office($year,$month)
    {
        $query = $this->db->query('SELECT COUNT(*) as head_office
                                    FROM tbl_expense_list
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                    AND tel_td_idx = 000010
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->head_office;
    }

    public function get_admin($year,$month)
    {
        $query = $this->db->query('SELECT COUNT(*) as head_office
                                    FROM tbl_expense_list
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                    AND tel_td_idx = 000004
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->head_office;
    }

    public function get_hr($year,$month)
    {
        $query = $this->db->query('SELECT COUNT(*) as hr
                                    FROM tbl_expense_list
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                    AND tel_td_idx = 000006
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->hr;
    }

    public function get_it($year,$month)
    {
        $query = $this->db->query('SELECT COUNT(*) as it
                                    FROM tbl_expense_list
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
                                    AND tel_td_idx = 000005
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->it;
    }

    public function get_design($year,$month)
    {
        $query = $this->db->query('SELECT COUNT(*) as design
            FROM tbl_expense_list
            WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE "'.$year.'-'.$month.'%"
            AND tel_td_idx = 000002
            AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->design;
    }

    public function get_items()
    {
        $query = $this->db->query('SELECT * 
                                    FROM tbl_expense_items_list');
        return $query->result();
    }

    public function get_attachments()
    {
        $query = $this->db->query('SELECT * 
                                    FROM tbl_expense_attachment');
        return $query->result();
    }

    public function get_requested_amt($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query('SELECT SUM(tel_request_amt) AS requested_amt 
                                    FROM tbl_expense_list 
                                    WHERE '.$where .'
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        return $query->row()->requested_amt;
    }

    public function get_receive_amt($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query('SELECT SUM(tel_receive_amt) AS receive_amt 
                                    FROM tbl_expense_list 
                                    WHERE '.$where . ' 
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        return $query->row()->receive_amt;
    }

    public function get_payment($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query('SELECT SUM(tel_payment + tel_returned_amt) AS payment 
                                    FROM tbl_expense_list 
                                    WHERE '.$where.'
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        $query2 = $this->db->query('SELECT SUM(tel_transfer_amt) as transfer_amt
                                    FROM tbl_expense_list
                                    WHERE '.$where.' 
                                    AND tel_tes_idx = "00000000006"
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        return sprintf("%.2f", $query->row()->payment + $query2->row()->transfer_amt);
    }

    public function get_quantity($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query('SELECT SUM(tel_quantity) AS quantity
                                    FROM tbl_expense_list 
                                    WHERE '.$where.'
                                    AND tbl_expense_list.tel_type = "expenses"
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        return $query->row()->quantity;
    }

    public function get_items_price($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "DATE(FROM_UNIXTIME(tbl_expense_list.tel_date)) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query('SELECT SUM(tbl_expense_items_list.teil_price) AS item_price 
                                    FROM tbl_expense_items_list 
                                    INNER JOIN tbl_expense_list
                                    ON tbl_expense_list.tel_idx = tbl_expense_items_list.teil_tel_idx
                                    WHERE '.$where.'
                                    AND tbl_expense_list.tel_type = "expenses"');

        return $query->row()->item_price;
    }

    public function get_total_cashonhand($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "WHERE DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "";
        
        $additional_where = ($where != "") ? " AND tbl_expense_list.tel_edit_idx IS NULL" : "WHERE tbl_expense_list.tel_edit_idx IS NULL";
        $additional_where2 = ($where != "") ? " AND tel_tes_idx = '00000000006' AND tbl_expense_list.tel_edit_idx IS NULL" : "WHERE tel_tes_idx = '00000000006' AND tbl_expense_list.tel_edit_idx IS NULL";

        $query = $this->db->query('SELECT SUM(tel_receive_amt) - (SUM(tbl_expense_list.tel_payment)  + SUM(tbl_expense_list.tel_returned_amt)) AS total_cashonhand
                                    FROM tbl_expense_list ' .
                                    $where . $additional_where);
        $query2 = $this->db->query('SELECT SUM(tel_transfer_amt) as transfer_amt
                                    FROM tbl_expense_list ' .
                                    $where . $additional_where2);

        return sprintf("%.2f", $query->row()->total_cashonhand - $query2->row()->transfer_amt);
    }

    public function get_year()
    {
        $query = $this->db->query('SELECT  DISTINCT(YEAR(FROM_UNIXTIME(tel_date))) AS tel_date FROM tbl_expense_list');
        return $query->result();
    }

    public function get_union_bank_balance($year, $month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "WHERE DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."' AND tbl_expense_list.tel_edit_idx IS NULL" : "WHERE tbl_expense_list.tel_edit_idx IS NULL";

        $query = $this->db->query('SELECT SUM(tel_deposit_amt - tel_transfer_amt) - SUM(tel_returned_amt) AS union_bank_bal
                                    FROM tbl_expense_list
                                    '.$where);

        return $query->row()->union_bank_bal;
    }

    public function get_saved_data($edit_id)
    {
        $query = $this->db->query('SELECT tbl_expense_list.*,
                                        DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), "%m/%d/%Y") AS new_date
                                    FROM tbl_expense_list  
                                    WHERE tel_idx = ' . $edit_id);
        return $query->row();
    }

    public function get_total_cash_receive($year,$month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query("SELECT SUM(tel_receive_amt) as cash_receive
                                    FROM tbl_expense_list
                                    WHERE ".$where.'
                                    AND tbl_expense_list.tel_type = "expenses"
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->cash_receive;  
    }

    public function get_total_cash_payment($year,$month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query("SELECT SUM(tel_payment) as cash_payment
                                    FROM tbl_expense_list
                                    WHERE ".$where.'
                                    AND tbl_expense_list.tel_type = "expenses"
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        $query2 = $this->db->query('SELECT SUM(tel_transfer_amt) as transfer_amt
                                    FROM tbl_expense_list
                                    WHERE '.$where.' 
                                    AND tel_tes_idx = "00000000006"
                                    AND tbl_expense_list.tel_edit_idx IS NULL');

        return sprintf("%.2f", $query->row()->cash_payment + $query2->row()->transfer_amt);
    }

    public function get_total_ub_deposit($year,$month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query("SELECT SUM(tel_deposit_amt) as deposit_amt
                                    FROM tbl_expense_list
                                    WHERE ".$where.'
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->deposit_amt;  
    }

    public function get_total_ub_transfer($year,$month,$pf,$pt)
    {
        $where = ($pf != "" && $pt != "") ? "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'" : "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."-%'";

        $query = $this->db->query("SELECT SUM(tel_transfer_amt) as transfer_amt
                                    FROM tbl_expense_list
                                    WHERE ".$where.'
                                    AND tbl_expense_list.tel_edit_idx IS NULL');
        return $query->row()->transfer_amt;  
    }

    public function export_list($year,$month,$sort,$pf,$pt,$dept)
    {
        if($sort == '0'){
            $order_by = "tbl_expense_list.tel_idx";
        }elseif($sort == '1'){
            $order_by = "tbl_department.td_dept_name";
        }elseif($sort == '2'){
            $order_by = "tbl_expense_status.tes_status";
        }elseif($sort == '3'){
            $order_by = "tbl_expense_list.tel_type";
        }else{
            $order_by = "tbl_expense_list.tel_date";
        }

        if($pf!='' && $pt!=''){
            $additional_where = "DATE_FORMAT(FROM_UNIXTIME(tbl_expense_list.tel_date), '%m/%d/%Y') BETWEEN '".$pf."' AND '".$pt."'";
        }else{
            $additional_where = "FROM_UNIXTIME(tbl_expense_list.tel_date) LIKE '".$year."-".$month."%'";
        }

        if($dept != ''){
            $additional_where2 = "AND tbl_department.td_idx = ".$dept;
        }else{
            $additional_where2 = "";
        }

        $query = $this->db->query('SELECT  tbl_expense_list.*,
                                        tbl_expense_list.tel_receive_amt - tbl_expense_list.tel_payment AS cashonhand,
                                        tbl_department.td_dept_name,
                                        FROM_UNIXTIME(tbl_expense_list.tel_date) AS new_tel_date,
                                        tbl_expense_status.tes_status,
                                        tbl_expense_category.tec_name
                                    FROM tbl_expense_list 
                                    INNER JOIN tbl_department ON tbl_expense_list.tel_td_idx = tbl_department.td_idx
                                    INNER JOIN tbl_expense_status ON tbl_expense_list.tel_tes_idx = tbl_expense_status.tes_idx
                                    INNER JOIN tbl_expense_category ON tbl_expense_list.tel_tec_idx = tbl_expense_category.tec_idx
                                    WHERE '.$additional_where.' '.$additional_where2 .'
                                    AND (
                                    (tbl_expense_list.tel_tes_idx = "00000000001" AND tbl_expense_list.tel_edit_idx IS NOT NULL)
                                    OR
                                    (tbl_expense_list.tel_tes_idx = "00000000003" AND tbl_expense_list.tel_edit_idx IS NOT NULL)
                                    OR
                                    (tbl_expense_list.tel_tes_idx = "00000000008" AND tbl_expense_list.tel_edit_idx IS NOT NULL)
                                    OR
                                    (tbl_expense_list.tel_tes_idx IN ("00000000002","00000000004","00000000005","00000000006","00000000007"))
                                    )
                                    ORDER BY '.$order_by);
        return $query->result();
        }
}