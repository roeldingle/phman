<h2 class="title nm np fl"><strong class="">Incident Report</strong><span class="subtext"></span></h2>
<a href="<?php echo $module_path;?>in_report?type=<?php echo @$stype;?><?php echo ($icategory_id) ? "&category={$icategory_id}" : "";?>" class="btn_small btn_type_1s fl ml10"><span>Back To Incident Report</span></a>
<div class="category_container">   
   <!-- BEGIN inner content -->
   <div class="content np">
      <div class="js-message"></div>
      <div class="php-message"><?php echo $this->common->get_message("php-message");?></div>      
      <div class="office <?php echo ($stype =='office') ? '' : 'no-display';?>">
   <?php echo $this->common->form_submit(array("module" => "stock", 
                                               "controller" => "exec_in_report", 
                                               "method" => "update_office",
                                               "method_type" => "post",
                                               "name" => "incident-report-form",
                                               "id" => "incident-report-form"
                                               ));?>
            <input type="hidden" value="<?php echo $icategory_id;?>" name="cat-id" />
            <input type="hidden" name="id" value="<?php echo $iidx;?>"/>
            <table class="table_form al" border="0">
               <colgroup>
                  <col width="140px" />
                  <col />
               </colgroup>    
               <tr>
                  <th><label for="main-category">Category: </label></th>
                  <td>
                     <select class="select_type_1 nm np" disabled="true">
                        <option value="">Select Category</option>
                        <?php if( $acategory ) {?>
                        <?php foreach($acategory as $rows) {?>
                        <option value="<?php echo $rows->tsmc_smcid;?>" <?php echo ($aoffice->tssc_tsmc_smcid==$rows->tsmc_smcid) ? 'selected="selected"' : "";?>><?php echo $rows->tsmc_name?></option>
                        <?php }?>
                        <?php }?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th><label for="sub-category">Sub Category: </label></th>
                  <td>
                     <select class="select_type_1 nm np" id="sub-category" name="sub-category" disabled="true">
                        <option value=""><?php echo $aoffice->tssc_name;?></option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <th><label for="serial">Serial: </label></th>
                  <td>
                     <select class="select_type_1 nm np" id="serial" name="serial" disabled="true">
                        <option value=""><?php echo $aoffice->tsit_serial_number;?></option>
                     </select>
                  </td>
               </tr>
               
               <tr>
                  <th><label for="assign-to">Assign To:</label></th>
                  <td><input type="text" readonly="true" class="input_type_1 nm" value="<?php echo "{$aoffice->te_fname} {$aoffice->te_mname} {$aoffice->te_lname}";?>"/></td>
               </tr>
               <tr>
                  <th><label for="date-reported" title="Date Reported" >Date Reported: </label></th>
                  <td>
                     <input type="text" readonly="true" title="Date Reported" style="width:80px;" value="<?php echo date("Y-m-d", $aoffice->tsin_date_reported);?>" class="input_type_1 nm date-reported" id="date-reported" name="date-reported"/>
                     <label class="calendar_icon" title="Date Reported" for="date-reported"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
                  </td>
               </tr>               
               <!--
               <tr>
                  <th><label for="purchased-date">Purchased Date: </label></th>
                  <td><input type="text" readonly="true" class="input_type_1 nm" value="<?php echo date("l, F d, Y",$aoffice->tsit_purchased_date);?>"/></td>
               </tr>
               -->
               <tr>
                  <th><label for="remarks">Remarks: </label></th>
                  <td>
                     <textarea class="tarea" cols="50" rows="9" id="remarks" name="remarks"><?php echo $aoffice->tsin_remarks;?></textarea>
                  </td>
               </tr>
               
            </table>
         </form>
         <ul class="control_buttons np nl">
            <li><a href="#" class="btn_small btn_type_3s" id="save-incident-btn"><span>Save</span></a></li>
         </ul> 
      </div>        
   </div>
<!-- END inner content -->
</div> <!-- END category container -->