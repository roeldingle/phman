<h2 class="title nm np fl"><strong class="">Incident Report</strong><span class="subtext"></span></h2>
<a href="<?php echo $module_path;?>in_report?type=<?php echo @$stype;?>" class="btn_small btn_type_1s fl ml10"><span>Back To Incident Report</span></a>
<div class="category_container">   
   <!-- BEGIN inner content -->
   <div class="content np">
      <div class="js-message"></div>
      <div class="php-message"><?php echo $this->common->get_message('php-message');?></div>
      <div class="others <?php echo ($stype =='others') ? '' : 'no-display';?>">      
      <?php echo $this->common->form_submit(array("module" => "stock", 
                                               "controller" => "exec_in_report", 
                                               "method" => "update_others",
                                               "method_type" => "post",
                                               "name" => "others-form",
                                               "id" => "others-form"
                                               ));?>
            <input type="hidden" value="<?php echo $aothers->tsio_idx;?>" name="id" />                                               
            <table class="table_form al" border="0">
               <colgroup>
                  <col width="140px" />
                  <col />
               </colgroup>
               <tr>
                  <th><label for="model">Model:</label></th>
                  <td><input type="text" class="input_type_1 nm" id="model" name="model" value="<?php echo $aothers->tsio_model;?>"/></td>
               </tr> 
               <tr>
                  <th><label for="date-reported" title="Date Reported" >Date Reported: </label></th>
                  <td>
                     <input type="text" title="Date Reported" readonly="true" value="<?php echo date("Y-m-d", $aothers->tsio_date_reported);?>" style="width:80px;"class="input_type_1 nm date-reported" id="date-reported-others" name="date-reported"/>
                     <label class="calendar_icon" title="Date Reported" for="date-reported-others"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
                  </td>
               </tr>               
               <tr>
                  <th><label for="remarks">Remarks</label></th>
                  <td>
                     <textarea class=" tarea" cols="50" rows="9" id="remarks" name="remarks"><?php echo $aothers->tsio_remarks;?></textarea>
                  </td>
               </tr>
            </table>
         </form>
         <ul class="control_buttons np nl">
            <li><a href="#" class="btn_small btn_type_3s" id="save-others-btn"><span>Save</span></a></li>
         </ul>   
      </div>            
   </div>
<!-- END inner content -->
</div> <!-- END category container -->