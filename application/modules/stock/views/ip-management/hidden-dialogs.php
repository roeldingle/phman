<!-- Hidden dialogs -->
<!-- MODIFY_HARDWARE_POPUP-->
<div class="popup_wrap no-display ip-form-dialog">
   <div class="popup"> 
   <?php echo $this->common->form_submit(array("module" => "stock", 
                                               "controller" => "exec_ip_management", 
                                               "method" => "save_ip",
                                               "method_type" => "post",
                                               "name" => "ip-form",
                                               "id" => "ip-form"
                                               ));?>   
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th><label for="employee-id" title="Employee Name" >Employee Name:</label></th>
            <td>
               <select id="employee-id" name="employee-id" title="Employee Name" >
                  <option value=""> Select Employee </option>
                  <?php if($aemployees) {?>
                  <?php foreach( $aemployees as $rows ) {?>
                  <option value="<?php echo $rows->te_idx;?>" data-seat-no="<?php echo $rows->ts_tsc_seatno;?>" data-department="<?php echo $rows->td_dept_name;?>"><?php echo "{$rows->te_fname} {$rows->te_lname}";?></option>
                  <?php }?>
                  <?php }?>
               </select>
            </td>
         </tr>
         <tr>
            <th><label for="seat-no" title="Seat No." >Seat No. :</label></th>
            <td><input type="text" title="Seat No."  class="input_type_1 nm readonly1 span1" id="seat-no" name="seat-no" readonly="true" /></td>
         </tr>
         <tr>
            <th><label for="department-name" title="Department" >Department :</label></th>
            <td><input type="text" title="Department"  class="input_type_1 nm readonly1 span1" id="department-name" name="department-name" readonly="true" /></td>
         </tr>
         <tr>
            <th><label for="assign-ip" title="Assign IP" >Assign IP :</label></th>
            <td><input type="text" title="Assign IP"  class="input_type_1 nm" id="assign-ip" name="assign-ip" /></td>
         </tr>
         <tr>
            <th><label for="gateway" title="Gateway" >Gateway :</label></th>
            <td><input type="text" title="Gateway" class="input_type_1 nm" id="gateway" name="gateway" /></td>
         </tr>
         <tr>
            <th><label for="external-ip" title="External IP" >External IP :</label></th>
            <td><input type="text" title="External IP" class="input_type_1 nm" id="external-ip" name="external-ip" /></td>
         </tr>
      </table>
      <div class="btn_div">
         <a href="#" class="btn btn_type_3 btn_space save-btn"  title="Save" ><span>Save</span></a>
         <a href="#" class="btn btn_type_3 cancel-btn"  title="Cancel" ><span>Cancel</span></a>
      </div>
   </form>
      
   </div>
</div>
<!-- //MODIFY_HARDWARE_POPUP-->

<!-- MODIFY_HARDWARE_POPUP-->
<div class="popup_wrap no-display modify-ip-form-dialog">
   <div class="popup"> 
   <?php echo $this->common->form_submit(array("module" => "stock", 
                                               "controller" => "exec_ip_management", 
                                               "method" => "update_ip",
                                               "method_type" => "post",
                                               "name" => "modify-ip-form",
                                               "id" => "modify-ip-form"
                                               ));?>
      <input type="text" name="redirect_url" value="<?php echo full_url();?>"/>
      <input type="hidden" id="modify-id" name="modify-id"/>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th><label for="modify-employee-id">Employee Name:</label></th>
            <td>
               <select id="modify-employee-id" name="modify-employee-id" disabled>
                  <option value=""> Select Employee </option>
                  <?php if($aemployees) {?>
                  <?php foreach( $aemployees as $rows ) {?>
                  <option value="<?php echo $rows->te_idx;?>" data-seat-no="<?php echo $rows->ts_tsc_seatno;?>" data-department="<?php echo $rows->td_dept_name;?>"><?php echo "{$rows->te_fname} {$rows->te_lname}";?></option>
                  <?php }?>
                  <?php }?>
               </select>
            </td>
         </tr>
         <tr>
            <th><label for="modify-seat-no">Seat No. :</label></th>
            <td><input type="text" class="input_type_1 nm readonly1 span1" id="modify-seat-no" name="modify-seat-no" readonly="true" /></td>
         </tr>
         <tr>
            <th><label for="modify-department-name">Department :</label></th>
            <td><input type="text" class="input_type_1 nm readonly1 span1" id="modify-department-name" name="modify-department-name" readonly="true" /></td>
         </tr>
         <tr>
            <th><label for="modify-assign-ip">Assign IP :</label></th>
            <td><input type="text" class="input_type_1 nm" id="modify-assign-ip" name="modify-assign-ip" /></td>
         </tr>
         <tr>
            <th><label for="modify-gateway">Gateway :</label></th>
            <td><input type="text" class="input_type_1 nm" id="modify-gateway" name="modify-gateway" /></td>
         </tr>
         <tr>
            <th><label for="modify-external-ip">External IP :</label></th>
            <td><input type="text" class="input_type_1 nm" id="modify-external-ip" name="modify-external-ip" /></td>
         </tr>
      </table>
      <div class="btn_div modify-option">
         <a href="#" class="btn btn_type_3 btn_space update-btn"><span>Update</span></a>
         <a href="#" class="btn btn_type_3 cancel-btn"><span>Cancel</span></a>
      </div>
   </form>
      
   </div>
</div>
<!-- //MODIFY_HARDWARE_POPUP-->


<!-- hidden dialogs -->
<div class="confirm-delete-dialog no-display">
   <div class="no-display align-center mt10" id="delete-loader-message">
      Deleting . . .
   </div>
   <div style="align-center" id="delete-confirm-buttons">   
      <p><b>Are you sure you want to delete selected record?<b></p>   
      <a href="#" id="delete-confirm-btn" class="btn btn_type_2"><span>&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;</span></a>
      <a href="#" id="cancel-delete-btn" class="btn btn_type_2 cancel-delete-btn"><span>Cancel</span></a>
   </div>
</div>

<!-- hidden dialogs -->
<div class="single-confirm-delete-dialog no-display">
   <div class="no-display align-center mt10" id="delete-loader-message">
      Deleting . . .
   </div>
   <div style="align-center" id="delete-confirm-buttons">   
      <p><b>Are you sure you want to delete selected record?<b></p>   
      <a href="#" id="single-delete-confirm-btn" class="btn btn_type_2"><span>&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;</span></a>
      <a href="#" class="btn btn_type_2 cancel-delete-btn"><span>Cancel</span></a>
   </div>
</div>