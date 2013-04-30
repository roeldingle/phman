<?php
$aoptions = array(
   "id" =>"edit_real_expenses",
   "name" =>"edit_real_expenses",
   "class" =>"edit_real_expenses",
   "method_type" =>"post",
   "module" =>"expense",
   "controller" => "exec",
   "method" => "edit_expense" 
);
$this->common->form_submit($aoptions);
?>

<div style="width:900px;display:inline-block;">
<?php
   $this->common->get_message('my-save-message');
?>
</div><br />

<h2 class="title nm np fl">
    <strong class="">Edit Real Expense </strong>
    <span class="subtext fn">Edit Expense</span>
</h2>
<a href="<?php echo base_url() . "expense"; ?>" class="btn btn_type_1 fl"><span>Back To List</span></a>

<div class="content np">
    <input type="hidden" name="pageaction" value="<?php echo $this->uri->segment(2); ?>" />
    <form id="edit_real_expenses" name="edit_real_expenses">
        <input type="hidden" name="page_action" value="<?php echo $this->uri->segment(2); ?>" />
        <input type="hidden" name="edit_id" value="<?php echo $saved_data->tel_idx; ?>" />
        <table class="table_form al" border="0">
            <colgroup>
                <col width="140px" />
                <col />
            </colgroup>
            <tr>
                <th><label for="select1">Department</label></th>
                <td>
                    <select class="select_type_3 nm np" id="select1" name="edepartment">
                        <?php foreach($adepartment as $dept){ ?>
                            <option value="<?php echo $dept->td_idx; ?>" deptname="<?php echo $dept->td_dept_name; ?>" <?php if($dept->td_idx == $saved_data->tel_td_idx){echo "selected";} ?>><?php echo $dept->td_dept_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Date <span class="required">*</span></th>
                <td>
                    <div class="holder">
                        <div class="fl nm np" style="display:inline-block">
                            <input type="text" class="input_type_3 fl" name="edate" id = "edate" value="<?php echo $saved_data->new_date; ?>" />
                            <span class="message_type2 np fl" id="msg_edate" ></span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <input type="hidden" name="saved_type" value="<?php if(isset($saved_data->tel_type)){echo $saved_data->tel_type;} ?>" />
                <th><label for="select2">Type <span class="required">*</span></label></th>
                <td>
                    <select class="select_type_3 nm np" id="select2" name="etype">
                    
                    </select>
                </td>
            </tr>
            <tr>
                <input type="hidden" name="saved_status" value="<?php if(isset($saved_data->tel_tes_idx)){echo $saved_data->tel_tes_idx;} ?>" />
                <th><label for="select3">Status <span class="required">*</span></label></th>
                <td>
                    <select class="select_type_3 nm np" id="select3" name="estatus">
                        
                    </select>
                </td>
            </tr>
            <tr id="tr_erdepositamt" style="display:none">
                <th>Deposit Amount <span class="required">*</span></th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="erdepositamt" value="<?php echo $saved_data->tel_deposit_amt; ?>" />
                        <span class="message_type2 np fl" id="msg_erdepositamt" ></span>
                    </div>
                </td>
            </tr>
            <tr id="tr_ertransferamt" style="display:none">
                <th>Transfer Amount <span class="required">*</span></th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="ertransferamt" value="<?php echo $saved_data->tel_transfer_amt; ?>" />
                        <span class="message_type2 np fl" id="msg_ertransferamt"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="filebrowse">Request From / Billing Statement/s</label></th>
                <td>
                    <div id="requestbill">Attach: 
                        <?php
                        $this->app->set_fileupload(
                           array(
                              "modulename" => "expense",
                              "uploadname"=>"eform",
                              "button_text"=>"Browse...",
                              "directory" => "request_billing_forms",
                              "extensions"=>array("odt",'txt',"gif","jpg","png","bz2","pdf","doc","xls"),
                              "total_upload" => 10,
                              "file_size" => array("pdf" =>"5000000")
                           )
                        );
                        ?>
                    </div>
                    <div id="requestbill_message" style="color:#FF0000;font-weight:bold"></div>
                    <?php 
                        if($this->uri->segment(2) == "edit_real_expense"){
                            echo "<div id='requestbill_uploaded'>";
                            echo "</div>";
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Requested Amount</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="ereqamount" value="<?php if($saved_data->tel_request_amt != 0.00){echo $saved_data->tel_request_amt;} ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th>Received Amount</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="erecamount" value="<?php if($saved_data->tel_receive_amt != 0.00){echo $saved_data->tel_receive_amt;} ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th>Payment</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="epayment" value="<?php if($saved_data->tel_payment != 0.00){echo $saved_data->tel_payment;} ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="filebrowse">Receipt/s</label></th>
                <td>
                    <div id="receipt">Attach:
                        <?php
                        $this->app->set_fileupload(
                           array(
                              "modulename" => "expense",
                              "uploadname"=>"ereceipt",
                              "button_text"=>"Browse...",
                              "directory" => "receipts",
                              "extensions"=>array("odt",'txt',"gif","jpg","png","bz2","pdf","doc","xls"),
                              "total_upload" => 10,
                              "file_size" => array("pdf" =>"5000000")
                           )
                        );
                        ?>
                    </div>
                    <div id="receipt_message" style="color:#FF0000;font-weight:bold"></div>
                    <?php 
                        if($this->uri->segment(2) == "edit_real_expense"){
                            echo "<div id='receipt_uploaded'>";
                            echo "</div>";
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <th><label for="select4">Quantity</label></th>
                <td>
                     <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="equantity" value="<?php if($saved_data->tel_quantity != 0){echo $saved_data->tel_quantity;} ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th>Cost Per Item</th>
                <td>
                    <input type="hidden" name="existing_items" />
                    <div class="fl nm np" style="display:inline-block" id="costperitem">
                        <input type="text" class="input_type_3 fl mb5" name="ecid[]"/>
                        <input type="text" class="input_type_3 fl mb5 costperitem" name="ecprice[]" id="price1"/>
                        <input type="text" class="input_type_1 fl mb5" name="ecitem[]" id="item1"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="input1">Supplier Name</label></th>
                <td><input type="text" class="input_type_2 nm" id="input1" name="esupplier" value="<?php echo $saved_data->tel_supplier_name; ?>" /></td>
            </tr>
            <tr>
                <th>PH Returned Amount / KR Received Amount</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="ephkramount" value="<?php if($saved_data->tel_returned_amt != 0.00){echo $saved_data->tel_returned_amt;} ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="select5">Category</label></th>
                <td>
                    <select class="select_type_3 nm np" id="select5" name="ecategory">
                        <?php foreach($acategory as $dept){ ?>
                            <option value="<?php echo $dept->tec_idx; ?>" <?php if($dept->tec_idx == $saved_data->tel_tec_idx){echo "selected";} ?>><?php echo $dept->tec_name; ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="selected_category" value="<?php echo $saved_data->tel_tec_idx ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="input2">Particulars</label></th>
                <td><input type="text" class="input_type_2 nm" id="input2" name="eparticulars" value="<?php echo $saved_data->tel_particulars;?>" /></td>
            </tr>
        </table>
    </form>
    <div class="btn_div" style="width:500px;">
        <input type="submit" class="btn" id="edit_real_expense_save" value="Save" />
        <a href="javascript:void(0)" id="resettodefault" class="link_1 mt5 btn_space">Reset to Default</a>
                        <a href="<?php echo base_url() . "expense"; ?>" class="link_1 mt5 btn_space">Return to Journal</a>
    </div>
</div>