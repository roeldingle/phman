<?php
  $aoptions = array(
   "id" =>"addexpenseform",
   "name" =>"addexpenseform",
   "class" =>"addexpenseform",
   "method_type" =>"post",
   "module" =>"expense",
   "controller" => "exec",
   "method" => "addexpense"   
);

$this->common->form_submit($aoptions);
?>

<div style="width:900px;display:inline-block;">
<?php
   $this->common->get_message('my-save-message');
?>
</div><br />

<h2 class="title nm np fl"><strong class="">New Expense </strong><span class="subtext fn">Add New Expense</span></h2><a href="<?php echo base_url() . "expense"; ?>" class="btn btn_type_1 fl"><span>Back To List</span></a>
<!-- BEGIN inner content -->
<div class="content np">
        <input type="hidden" name="pageaction" value="<?php echo $this->uri->segment(2); ?>" />
        <table class="table_form al" border="0">
            <colgroup>
                <col width="140px" />
                <col />
            </colgroup>
            <tr>
                <th><label for="select1">Department</label></th>
                <td>
                    <select class="select_type_3 nm np" id="select1" name="edepartment">
                        <option value="0" selected>--Select a Department--</option>
                        <?php foreach($adepartment as $dept){ ?>
                            <option value="<?php echo $dept->td_idx; ?>" deptname="<?php echo $dept->td_dept_name; ?>"><?php echo $dept->td_dept_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Date <span class="required">*</span></th>
                <td>
                    <div class="holder">
                        <div class="fl nm np" style="display:inline-block">
                            <input type="text" class="input_type_3 fl" name="edate" id = "edate" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="select2">Type <span class="required">*</span></label></th>
                <td>
                    <select class="select_type_3 nm np" id="select2" name="etype">
                        
                    </select>
                </td>
            </tr>
            <tr>
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
                        <input type="text" class="input_type_3 fl" name="erdepositamt" />
                        <span class="message_type2 np fl" id="msg_erdepositamt" ></span>
                    </div>
                </td>
            </tr>
            <tr id="tr_ertransferamt" style="display:none">
                <th>Transfer Amount <span class="required">*</span></th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="ertransferamt" />
                        <span class="message_type2 np fl" id="msg_ertransferamt"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="filebrowse">Request From / Billing Statement/s</label></th>
                <td><div id="requestbill">Attach: 
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
                </td>
            </tr>
            <tr>
                <th>Requested Amount</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="ereqamount" />
                    </div>
                </td>
            </tr>
            <tr>
                <th>Received Amount</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="erecamount"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Payment</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="epayment"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="filebrowse">Receipt/s</label></th>
                <td><div id="receipt">Attach:
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
                </td>
            </tr>
            <tr>
                <th><label for="select4">Quantity</label></th>
                <td>
                     <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="equantity"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Cost Per Item</th>
                <td>
                    <div class="fl nm np" style="display:inline-block" id="costperitem">
                        <input type="text" class="input_type_3 fl mb5 costperitem" name="ecprice[]" placeholder="Price" />
                        <input type="text" class="input_type_1 fl mb5" name="ecitem[]" placeholder="Item Name" />
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="input1">Supplier Name</label></th>
                <td><input type="text" class="input_type_2 nm" id="input1" name="esupplier" /></td>
            </tr>
            <tr>
                <th>PH Returned Amount / KR Received Amount</th>
                <td>
                    <div class="fl nm np" style="display:inline-block">
                        <input type="text" class="input_type_3 fl" name="ephkramount" />
                    </div>
                </td>
            </tr>
            <tr>
                <th><label for="select5">Category</label></th>
                <td>
                    <select class="select_type_3 nm np" id="select5" name="ecategory">
                        <?php foreach($acategory as $dept){ ?>
                            <option value="<?php echo $dept->tec_idx; ?>"><?php echo $dept->tec_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="input2">Particulars</label></th>
                <td><input type="text" class="input_type_2 nm" id="input2" name="eparticulars" /></td>
            </tr>
            <tr>
                <td><input type="submit" class="btn" value="Save" /></td>
                <td>
                        <a href="javascript:void(0)" id="resettodefault" class="link_1 mt5 btn_space">Reset to Default</a>
                        <a href="<?php echo base_url() . "expense"; ?>" class="link_1 mt5 btn_space">Return to Journal</a>
                </td>
            </tr>
        </table>
        <div id="hide_error" style="display:none">
        
        </div>
    </form>
    
</div>