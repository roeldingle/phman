<h2 class="title nm np fl"><strong class="">Incident Report</strong><span class="subtext"></span></h2>
<a href="<?php echo $module_path;?>in_report?type=<?php echo @$stype;?><?php echo ($icategory_id) ? "&category={$icategory_id}" : "";?>" title="Back to Incident Report" class="btn_small btn_type_1s fl ml10"><span>Back To Incident Report</span></a>
<div class="category_container">   
   <!-- BEGIN inner content -->
   <div class="content np">
      <div class="js-message"></div>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>               
            <td colspan="2">
               <ul class="opt_radio nl nm np">
                  <li><label for="option1" title="Office Equipments">Office Equipments</label><input title="Office Equipments" type="radio" class="radio_type_1 np" name="incident-type" id="incident-type" value="office" <?php echo ($stype =='office') ? 'checked="checked"' : '';?> /></li>
                  <li><label for="option2" title="Others">Others</label><input type="radio" title="Others" class="radio_type_1 np" name="incident-type" id="incident-type" value="others" <?php echo ($stype=='others') ? 'checked="checked"' : '';?>/></li>
               </ul>
            </td>
         </tr>
      </table>
      <div class="office <?php echo ($stype =='office') ? '' : 'no-display';?>">
      <?php echo $this->common->form_submit(array("module" => "stock", 
                                               "controller" => "exec_in_report", 
                                               "method" => "save_incident_report",
                                               "method_type" => "post",
                                               "name" => "incident-report-form",
                                               "id" => "incident-report-form"
                                               ));?>
            <input type="hidden" value="<?php echo $icategory_id;?>" name="cat-id" />
            <table class="table_form al" border="0">
               <colgroup>
                  <col width="140px" />
                  <col />
               </colgroup>    
               <tr>
                  <th><label for="main-category" title="Category">Category: </label></th>
                  <td>
                     <select title="Category" class="select_type_1 nm np" id="main-category" name="main-category">
                        <option value="">Select Category</option>
                        <?php if( $acategory ) {?>
                        <?php foreach($acategory as $rows) {?>
                        <option value="<?php echo $rows->tsmc_smcid;?>"><?php echo $rows->tsmc_name?></option>
                        <?php }?>
                        <?php }?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th><label title="Sub Category" for="sub-category">Sub Category: </label></th>
                  <td>
                     <select title="Sub Category" class="select_type_1 nm np" id="sub-category" name="sub-category">
                        <option value="">Select Sub Category</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th><label title="Serial" for="serial">Serial: </label></th>
                  <td>
                     <select title="Serial" class="select_type_1 nm np" id="serial" name="serial">
                        <option value="">Select Serial</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th><label for="assign-to"  title="Assign to" >Assign To:</label></th>
                  <td><input type="text" title="Assign to" readonly="true" class="input_type_1 nm" id="assign-to" name="assign-to" /></td>
               </tr>
               <!--
               <tr>
                  <th><label for="purchased-date"  title="Purchased Date" >Purchased Date: </label></th>
                  <td><input type="text" readonly="true" title="Purchased Date" class="input_type_1 nm" id="purchased-date" /></td>
               </tr>
               -->
               <tr>
                  <th><label for="date-reported" title="Date Reported" >Date Reported: </label></th>
                  <td>
                     <input type="text" title="Date Reported" readonly="true" style="width:80px;"class="input_type_1 nm date-reported" id="date-reported" name="date-reported"/>
                     <label class="calendar_icon" title="Date Reported" for="date-reported"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
                  </td>
               </tr>
               <tr>
                  <th><label for="remarks" title="Remarks" >Remarks: </label></th>
                  <td>
                     <textarea class="tarea" title="Remarks" cols="50" rows="9" id="remarks" name="remarks"></textarea>
                  </td>
               </tr>
               
            </table>
         </form>
         <ul class="control_buttons np nl">
            <li><a href="#" class="btn_small btn_type_3s" id="save-incident-btn" title="Save" ><span>Save</span></a></li>
            <li><a href="#" class="link_1 mt5 fl reset-office-btn" title="Reset to Default" >Reset to Default</a></li>
         </ul> 
      </div>
      <div class="others <?php echo ($stype =='others') ? '' : 'no-display';?>">      
      <?php echo $this->common->form_submit(array("module" => "stock", 
                                               "controller" => "exec_in_report", 
                                               "method" => "save_others_report",
                                               "method_type" => "post",
                                               "name" => "others-form",
                                               "id" => "others-form"
                                               ));?>    
            <table class="table_form al" border="0">
               <colgroup>
                  <col width="140px" />
                  <col />
               </colgroup>
               <tr>
                  <th><label for="model" title="Model" >Model:</label></th>
                  <td><input type="text" title="Model" class="input_type_1 nm" id="model" name="model" /></td>
               </tr>
               <tr>
                  <th><label for="date-reported" title="Date Reported" >Date Reported: </label></th>
                  <td>
                     <input type="text" title="Date Reported" readonly="true" style="width:80px;"class="input_type_1 nm date-reported" id="date-reported-others" name="date-reported"/>
                     <label class="calendar_icon" title="Date Reported" for="date-reported-others"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
                  </td>
               </tr>
               <tr>
                  <th><label for="remarks" title="Remarks">Remarks</label></th>
                  <td>
                     <textarea class=" tarea" title="Remarks" cols="50" rows="9" id="remarks" name="remarks"></textarea>
                  </td>
               </tr>
            </table>
         </form>
         <ul class="control_buttons np nl">
            <li><a href="#" class="btn_small btn_type_3s" id="save-others-btn" title="Save"><span>Save</span></a></li>
            <li><a href="#" class="link_1 mt5 fl reset-others-btn" title="Reset to Default">Reset to Default</a></li>
         </ul>   
      </div>            
   </div>
<!-- END inner content -->
</div> <!-- END category container -->