<?php
class Exec extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->module('core/app');
        $this->load->module('settings/logs');
    }

    public function getExpensesStatus()
    {
        $string = "";
        $aid = array('00000000003','00000000004'); 
        $saved_status = (isset($_POST['saved_status'])) ? $_POST['saved_status'] : '00000000003';

        $this->db->where_in('tes_idx', $aid);
        $query = $this->db->get('tbl_expense_status');
        
        foreach($query->result() as $row){
            $selected = ($saved_status == $row->tes_idx) ? "selected" : "";
            $string .= "<option value='{$row->tes_idx}' {$selected}>{$row->tes_status}";
        }
        echo json_encode($string);
    }

    public function getInOutStatus()
    {
        $page_action = $_POST['page_action'];
        $string = "";
        $saved_status = (isset($_POST['saved_status'])) ? $_POST['saved_status'] : '00000000001';

        if($page_action == "add_new_expense"){
            $aid = array('00000000001', '00000000005', '00000000006', '00000000007'); 
        }else{
            $aid = array('00000000001', '00000000002', '00000000004', '00000000005', '00000000006'); 
        }

        $this->db->where_in('tes_idx', $aid);
        $query = $this->db->get('tbl_expense_status');
        
        foreach($query->result() as $row){
            $selected = ($saved_status == $row->tes_idx) ? "selected" : "";
            $string .= "<option value='{$row->tes_idx}' {$selected}>{$row->tes_status}";
        }
        echo json_encode($string);
    }

    public function getReturn()
    {
        $string = "";
        $aid = array('00000000007');
        $saved_status = $_POST['saved_status'];

        $this->db->where_in('tes_idx', $aid);
        $query = $this->db->get('tbl_expense_status');
        
        foreach($query->result() as $row){
            $selected = ($saved_status == $row->tes_idx) ? "selected" : "";
            $string .= "<option value='{$row->tes_idx}' {$selected}>{$row->tes_status}";
        }
        echo json_encode($string);
    }

    public function addexpense()
    {
        $aitem = $this->input->post('ecitem');
        $aprice = $this->input->post('ecprice');

        //File upload
        $ainfo1 = $this->app->get_fileupload('eform',true);
        $ainfo2 = $this->app->get_fileupload('ereceipt',true);

        //items quantity
        if(($this->input->post('equantity')=="" || $this->input->post('equantity')== null) && $aprice[0] == ""){
            $qty = "";
            $qty_data = "";
        }elseif(($this->input->post('equantity')=="" || $this->input->post('equantity')== null) && $aprice[0] != ""){
            $qty = 1;
            $qty_data = 1;
        }else{
            $qty = $this->input->post('equantity');
            $qty_data = $this->input->post('equantity');
        }

        //Fix format of currency values
        $request_amt = str_replace(",","",$this->input->post('ereqamount'));
        $receive_amt = str_replace(",","",$this->input->post('erecamount'));
        $payment = str_replace(",","",$this->input->post('epayment'));
        $returned_amt = str_replace(",","",$this->input->post('ephkramount'));
        $deposit_amt = str_replace(",","",$this->input->post('erdepositamt'));
        $transfer_amt = str_replace(",","",$this->input->post('ertransferamt'));

        //Insert into tbl_expense_list
        if($this->input->post('estatus') == "00000000001"){
            $tel_edit_idx = null;
            for($ctr=0;$ctr<2;$ctr++){
                if($ctr==1){
                    $query = $this->db->query("SELECT MAX(tel_idx) as id FROM tbl_expense_list");
                    $maxid = $query->row();
                    $tel_edit_idx = $maxid->id;
                }else{
                    $tel_edit_idx = null;
                }
                
                $data = array(
                    'tel_td_idx' => $this->input->post('edepartment') ,
                    'tel_tec_idx' => $this->input->post('ecategory') ,
                    'tel_tes_idx' => $this->input->post('estatus') ,
                    'tel_edit_idx' => $tel_edit_idx,
                    'tel_date' => strtotime($this->input->post('edate')),
                    'tel_type' => $this->input->post('etype') ,
                    'tel_deposit_amt' => (float)$deposit_amt ,
                    'tel_transfer_amt' => (float)$transfer_amt ,
                    'tel_request_amt' => (float)$request_amt ,
                    'tel_receive_amt' => (float)$receive_amt ,
                    'tel_payment' => (float)$payment ,
                    'tel_quantity' => $qty_data,
                    'tel_supplier_name' => $this->input->post('esupplier') ,
                    'tel_returned_amt' => (float)$returned_amt ,
                    'tel_particulars' => $this->input->post('eparticulars') ,
                    'tel_date_updated' => strtotime('now'),
                    'tel_date_created' => strtotime('now')
                );

                $this->db->insert('tbl_expense_list', $data); 
            }
        }else{
            $data = array(
                'tel_td_idx' => $this->input->post('edepartment') ,
                'tel_tec_idx' => $this->input->post('ecategory') ,
                'tel_tes_idx' => $this->input->post('estatus') ,
                'tel_edit_idx' => null,
                'tel_date' => strtotime($this->input->post('edate')),
                'tel_type' => $this->input->post('etype') ,
                'tel_deposit_amt' => (float)$deposit_amt ,
                'tel_transfer_amt' => (float)$transfer_amt ,
                'tel_request_amt' => (float)$request_amt ,
                'tel_receive_amt' => (float)$receive_amt ,
                'tel_payment' => (float)$payment ,
                'tel_quantity' => $qty_data,
                'tel_supplier_name' => $this->input->post('esupplier') ,
                'tel_returned_amt' => (float)$returned_amt ,
                'tel_particulars' => $this->input->post('eparticulars') ,
                'tel_date_updated' => strtotime('now'),
                'tel_date_created' => strtotime('now')
            );

            $this->db->insert('tbl_expense_list', $data); 
        }
        
        //maximum id
        $query = $this->db->query("SELECT MAX(tel_idx)-1 as id FROM tbl_expense_list");
        $maxid = $query->row();
        $this->logs->set_expense_log_create("Real Expense #{$maxid->id}");

        //Insert into tbl_expense_items_list
        for($x=1;$x<=$qty;$x++){
            if($aitem[$x-1] != "" || $aprice[$x-1] != ""){
                $item = $aitem[$x-1];
                $price = str_replace(",","",$aprice[$x-1]);

                $this->db->query("INSERT INTO tbl_expense_items_list (teil_tel_idx, teil_name, teil_price) VALUES ({$maxid->id}, '{$item}', {$price})");
            }
        }

        //Insert into tbl_expense_attachment
        if($this->input->post('eform') != "" || $this->input->post('eform') != null){
            foreach($ainfo1['files'] as $form){
                $this->db->query("INSERT INTO tbl_expense_attachment (tea_tel_idx, 
                                tea_attachment_type, 
                                tea_filename, 
                                tea_newname, 
                                tea_filepath) 
                                VALUES ({$maxid->id}, 
                                            'request', 
                                            '".$form['filename']."', 
                                            '".$form['newfilename']."', 
                                            '".$ainfo2['upload-info']['directory']."/".$form['newfilename']."'
                                )
                ");
            }
        }
        if($this->input->post('ereceipt') != "" || $this->input->post('ereceipt') != null){
            foreach($ainfo2['files'] as $receipt){
                $this->db->query("INSERT INTO tbl_expense_attachment (tea_tel_idx, 
                                tea_attachment_type, 
                                tea_filename, 
                                tea_newname, 
                                tea_filepath) 
                                VALUES ({$maxid->id}, 
                                            'receipt', 
                                            '".$receipt['filename']."', 
                                            '".$receipt['newfilename']."', 
                                            '".$ainfo2['upload-info']['directory']."/".$receipt['newfilename']."'
                                )
                ");
            }
        }

        //Show success message and redirect after inserting to database
        $this->common->set_message("Saved Successfully!","my-save-message","success");
        redirect('expense/add_new_expense');
    }

    public function edit_expense()
    {
        $edit_id = $this->input->post('edit_id');
        $aitem = $this->input->post('ecitem');
        $aprice = $this->input->post('ecprice');
        $aid = $this->input->post('ecid');

        //File upload
        $ainfo1 = $this->app->get_fileupload('eform',true);
        $ainfo2 = $this->app->get_fileupload('ereceipt',true);

        //items quantity
        if(($this->input->post('equantity')=="" || $this->input->post('equantity')== null) && $aprice[0] == ""){
            $qty = "";
            $qty_data = "";
        }elseif(($this->input->post('equantity')=="" || $this->input->post('equantity')== null) && $aprice[0] != ""){
            $qty = 1;
            $qty_data = 1;
        }else{
            $qty = $this->input->post('equantity');
            $qty_data = $this->input->post('equantity'); 
        }

        //Fix format of currency values
        $request_amt = str_replace(",","",$this->input->post('ereqamount'));
        $receive_amt = str_replace(",","",$this->input->post('erecamount'));
        $payment = str_replace(",","",$this->input->post('epayment'));
        $returned_amt = str_replace(",","",$this->input->post('ephkramount'));
        $deposit_amt = str_replace(",","",$this->input->post('erdepositamt'));
        $transfer_amt = str_replace(",","",$this->input->post('ertransferamt'));
        
        //Check first whether it already exists before inserting
        $qsub_id = $this->db->query("SELECT tel_idx FROM tbl_expense_list 
                                    WHERE tel_edit_idx = {$edit_id}
                                    AND tel_tes_idx = {$this->input->post('estatus')}
                                    AND tel_edit_idx IS NOT NULL");
        $sub_id = $qsub_idx->tel_idx;

        // Update tbl_expense_list
        if($sub_id != ""){
            if($this->input->post('estatus') == "00000000004"){
                $data = array(
                    'tel_td_idx' => $this->input->post('edepartment') ,
                    'tel_tec_idx' => $this->input->post('selected_category') ,
                    'tel_tes_idx' => $this->input->post('estatus') ,
                    'tel_edit_idx' => $edit_id,
                    'tel_date' => strtotime($this->input->post('edate')),
                    'tel_type' => $this->input->post('etype') ,
                    'tel_deposit_amt' => "" ,
                    'tel_transfer_amt' => "" ,
                    'tel_request_amt' => "" ,
                    'tel_receive_amt' => (float)$receive_amt ,
                    'tel_payment' => "" ,
                    'tel_quantity' => 0,
                    'tel_supplier_name' => "" ,
                    'tel_returned_amt' => "" ,
                    'tel_particulars' => $this->input->post('eparticulars') ,
                    'tel_date_updated' => strtotime('now'),
                    'tel_date_created' => strtotime('now')
                );
                $this->db->insert('tbl_expense_list', $data);
            }if($this->input->post('estatus') == "00000000003"){
                $data = array(
                    'tel_td_idx' => $this->input->post('edepartment') ,
                    'tel_tec_idx' => $this->input->post('selected_category') ,
                    'tel_tes_idx' => $this->input->post('estatus') ,
                    'tel_edit_idx' => $edit_id,
                    'tel_date' => strtotime($this->input->post('edate')),
                    'tel_type' => $this->input->post('etype') ,
                    'tel_deposit_amt' => "" ,
                    'tel_transfer_amt' => "" ,
                    'tel_request_amt' => "" ,
                    'tel_receive_amt' => "" ,
                    'tel_payment' => (float)$payment ,
                    'tel_quantity' => "",
                    'tel_supplier_name' => "" ,
                    'tel_returned_amt' => "" ,
                    'tel_particulars' => $this->input->post('eparticulars') ,
                    'tel_date_updated' => strtotime('now'),
                    'tel_date_created' => strtotime('now')
                );
                $this->db->insert('tbl_expense_list', $data);
            }
        }else{
            if($this->input->post('estatus') == "00000000004"){
                $data = array(
                    'tel_td_idx' => $this->input->post('edepartment') ,
                    'tel_tec_idx' => $this->input->post('selected_category') ,
                    'tel_tes_idx' => $this->input->post('estatus') ,
                    'tel_edit_idx' => $edit_id,
                    'tel_date' => strtotime($this->input->post('edate')),
                    'tel_type' => $this->input->post('etype') ,
                    'tel_deposit_amt' => "" ,
                    'tel_transfer_amt' => "" ,
                    'tel_request_amt' => "" ,
                    'tel_receive_amt' => (float)$receive_amt ,
                    'tel_payment' => "" ,
                    'tel_quantity' => 0,
                    'tel_supplier_name' => "" ,
                    'tel_returned_amt' => "" ,
                    'tel_particulars' => $this->input->post('eparticulars') ,
                    'tel_date_updated' => strtotime('now'),
                    'tel_date_created' => strtotime('now')
                );
                $this->db->where('tel_edit_idx', $edit_id);
                $this->db->where('tel_tes_idx', $this->input->post('estatus'));
                $this->db->update('tbl_expense_list', $data);
                
            }if($this->input->post('estatus') == "00000000003"){
                $data = array(
                    'tel_td_idx' => $this->input->post('edepartment') ,
                    'tel_tec_idx' => $this->input->post('selected_category') ,
                    'tel_tes_idx' => $this->input->post('estatus') ,
                    'tel_edit_idx' => $edit_id,
                    'tel_date' => strtotime($this->input->post('edate')),
                    'tel_type' => $this->input->post('etype') ,
                    'tel_deposit_amt' => "" ,
                    'tel_transfer_amt' => "" ,
                    'tel_request_amt' => "" ,
                    'tel_receive_amt' => "" ,
                    'tel_payment' => (float)$payment ,
                    'tel_quantity' => "",
                    'tel_supplier_name' => "" ,
                    'tel_returned_amt' => "" ,
                    'tel_particulars' => $this->input->post('eparticulars') ,
                    'tel_date_updated' => strtotime('now'),
                    'tel_date_created' => strtotime('now')
                );
                $this->db->where('tel_edit_idx', $edit_id);
                $this->db->where('tel_tes_idx', $this->input->post('estatus'));
                $this->db->update('tbl_expense_list', $data);
            }
        }
        
        $data = array(
            'tel_td_idx' => $this->input->post('edepartment') ,
            'tel_tec_idx' => $this->input->post('selected_category') ,
            'tel_tes_idx' => $this->input->post('estatus') ,
            'tel_date' => strtotime($this->input->post('edate')),
            'tel_type' => $this->input->post('etype') ,
            'tel_deposit_amt' => (float)$deposit_amt ,
            'tel_transfer_amt' => (float)$transfer_amt ,
            'tel_request_amt' => (float)$request_amt ,
            'tel_receive_amt' => (float)$receive_amt ,
            'tel_payment' => (float)$payment ,
            'tel_quantity' => $qty_data,
            'tel_supplier_name' => $this->input->post('esupplier') ,
            'tel_returned_amt' => (float)$returned_amt ,
            'tel_particulars' => $this->input->post('eparticulars') ,
            'tel_date_updated' => strtotime('now'),
            'tel_date_created' => strtotime('now')
        );

        $exempted = array('tel_date_updated','tel_date_created');

        $this->logs->set_expense_log_update("Real Expense #{$edit_id}", "tbl_expense_list", $data, $exempted, "tel_idx = {$edit_id}");
        $this->db->where('tel_idx', $edit_id);
        $this->db->update('tbl_expense_list', $data); 

        // Update tbl_expense_items_list
        $existing_items = $this->input->post('existing_items');
        $aexisting_items = explode(",", $existing_items);
        
        for($x=1;$x<=$qty;$x++){
            if($aitem[$x-1] != "" || $aprice[$x-1] != ""){
                if((!array_filter($aexisting_items)) || (!in_array($aid[$x-1], $aexisting_items))){
                    // $item = $this->input->post('ecitem');
                    // $price = $this->input->post('ecprice');
                    $item = $aitem[$x-1];
                    $price = str_replace(",","",$aprice[$x-1]);
                    
                    $this->db->query("INSERT INTO tbl_expense_items_list (teil_tel_idx, teil_name, teil_price) VALUES ({$edit_id}, '{$item}', {$price})");
                    $query = $this->db->query("SELECT MAX(teil_idx) as id FROM tbl_expense_items_list WHERE teil_tel_idx ={$edit_id}");
                    $maxid = $query->row();
                    $this->logs->set_expense_log_create("Expense item #{$maxid->id}");
                 }
                 else{
                    // $item = $this->input->post('ecitem');
                    // $price = $this->input->post('ecprice');

                    $udata = array(
                        'teil_name' => $aitem[$x-1],
                        'teil_price' => str_replace(",","",$aprice[$x-1])
                    );
                    $exempted2 = array();
                    $this->logs->set_expense_log_update("Expense item #{$aid[$x-1]}", "tbl_expense_items_list", $udata,$exempted2, "teil_idx = {$aid[$x-1]}");
                    $this->db->where('teil_idx', $aid[$x-1]);
                    $this->db->update('tbl_expense_items_list', $udata);
                }
            }
        }

        // Insert into tbl_expense_attachment
        if($this->input->post('eform') != "" || $this->input->post('eform') != null){
            foreach($ainfo1['files'] as $form){
                $this->db->query("INSERT INTO tbl_expense_attachment (tea_tel_idx, 
                                tea_attachment_type, 
                                tea_filename, 
                                tea_newname, 
                                tea_filepath) 
                                VALUES ({$edit_id}, 
                                            'request', 
                                            '".$form['filename']."', 
                                            '".$form['newfilename']."', 
                                            '".$ainfo2['upload-info']['directory']."/".$form['newfilename']."'
                                )
                ");
                $query = $this->db->query("SELECT MAX(tea_idx) as id FROM tbl_expense_attachment WHERE tea_tel_idx = {$edit_id} AND tea_attachment_type='request'");
                $reqmaxid = $query->row();
                $this->logs->set_expense_log_create("Real Expense request form attachment #{$reqmaxid->id}");
            }
        }
        if($this->input->post('ereceipt') != "" || $this->input->post('ereceipt') != null){
            foreach($ainfo2['files'] as $receipt){
                $this->db->query("INSERT INTO tbl_expense_attachment (tea_tel_idx, 
                                tea_attachment_type, 
                                tea_filename, 
                                tea_newname, 
                                tea_filepath) 
                                VALUES ({$edit_id}, 
                                            'receipt', 
                                            '".$receipt['filename']."', 
                                            '".$receipt['newfilename']."', 
                                            '".$ainfo2['upload-info']['directory']."/".$receipt['newfilename']."'
                                )
                ");
                $query = $this->db->query("SELECT MAX(tea_idx) as id FROM tbl_expense_attachment WHERE tea_tel_idx = {$edit_id} AND tea_attachment_type='receipt'");
                $recmaxid = $query->row();
                $this->logs->set_expense_log_create("Real Expense receipt attachment #{$recmaxid->id}");
            }
        }

        // Show success message and redirect after inserting to database
        $this->common->set_message("Saved Successfully!","my-save-message","success");
        redirect('expense/edit_real_expense/'.$edit_id);
    }

    public function check_deposit_amt()
    {
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $query = $this->db->query('SELECT SUM(tel_receive_amt - tel_payment) - SUM(tel_deposit_amt) AS total_cashonhand
                                    FROM tbl_expense_list
                                    WHERE tel_edit_idx IS NULL');

        echo json_encode (sprintf("%.2f", $query->row()->total_cashonhand));
    }

    public function check_transfer_amt()
    {
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $query = $this->db->query('SELECT SUM(tel_deposit_amt) - SUM(tel_transfer_amt) AS union_bank_bal
                                    FROM tbl_expense_list
                                    WHERE FROM_UNIXTIME(tbl_expense_list.tel_date) 
                                    LIKE "'.$year.'-'.$month.'-%"
                                    AND tel_edit_idx IS NULL');

        echo json_encode ($query->row()->union_bank_bal);
    }

    public function delete_real_expense_item_list()
    {
        $delete_aid = $this->input->post('delete_aid');
        $sdelete_aid=implode($delete_aid);

        $this->logs->set_expense_log_delete("Expense #".$sdelete_aid);
        $this->db->where_in('tel_idx', $delete_aid);
        $this->db->or_where_in('tel_edit_idx', $delete_aid);
        $query = $this->db->delete('tbl_expense_list'); 
        // $query = $this->db->query("DELETE FROM tbl_expense_list 
                                    // WHERE tel_idx IN ({$delete_aid}) 
                                    // OR tel_edit_idx IN ({$delete_aid})");

        echo json_encode($query);
    }

    public function get_cost_per_items_edit()
    {
        $edit_id = $this->input->post('edit_id');

        $query = $this->db->query("SELECT teil_idx, teil_name, teil_price
                                    FROM tbl_expense_items_list
                                    WHERE teil_tel_idx = " . $edit_id);

        echo json_encode($query->result());
    }

    public function get_request_bill_attachment()
    {
        $edit_id = $this->input->post('edit_id');

        $query = $this->db->query("SELECT tea_idx, tea_filename 
                                    FROM tbl_expense_attachment
                                    WHERE tea_tel_idx = " . $edit_id . "
                                    AND tea_attachment_type = 'request'");
        echo json_encode($query->result());
    }

    public function get_receipt_attachment()
    {
        $edit_id = $this->input->post('edit_id');

        $query = $this->db->query("SELECT tea_idx, tea_filename 
                                    FROM tbl_expense_attachment
                                    WHERE tea_tel_idx = " . $edit_id . "
                                    AND tea_attachment_type = 'receipt'");
        echo json_encode($query->result());
    }

    public function delete_attach()
    {
        $del_id = $this->input->post('del_id');

        $query = $this->db->query("DELETE FROM tbl_expense_attachment WHERE tea_idx = " . $del_id);
        echo json_encode($query);
    }
}