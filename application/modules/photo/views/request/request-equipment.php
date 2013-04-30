<h2 class="title nm np fl">
   <strong class="">Asset Accountability Form</strong><span class="subtext fn"><!-- nothing here --></span>
</h2>
<a href="<?php echo $module_path;?>req_usage_rec" class="btn btn_type_1 fl"><span>Back To List</span></a>
<!-- BEGIN inner content -->
<div class="content np">
   <?php echo $this->common->form_submit(array("module" => "photo", 
                                               "controller" => "exec_request_usage", 
                                               "method" => "save_request",
                                               "method_type" => "post",
                                               "name" => "request-form"));?>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th>Activity Date: <label for="activity-date" class="core-icons-calendar">&nbsp;</label></th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" id="activity-date" name="activity-date" readonly="true" class="input_type_1 nm"  style="width:75px"/>
               </div>
            </td>
         </tr>
         <tr>
            <th>Requested by: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" name="requested-by" class="input_type_2 nm" />
               </div>
            </td>
         </tr>
         <tr>
            <th>Location Shoot: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <textarea class="tarea" name="location" rows="5" cols="57"></textarea>
               </div>
            </td>
         </tr>
         <tr>
            <th>Purpose / Theme: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <textarea class="tarea" name="purpose" rows="5" cols="57"></textarea>
               </div>
            </td>
         </tr>         
         <tr>
            <th>Returned Date: <label for="returned-date" class="core-icons-calendar">&nbsp;</label></th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" id="returned-date" name="returned-date" readonly="true"  class="input_type_2 nm" />
               </div>
            </td>
         </tr>
      </table>
      <?php echo $scategory_html;?>
      <!--
      <div class="category-container mb10">
         <ul class="category-list">
            <?php for($i = 0; $i < 30; $i++){?>
            <li><input type="checkbox"/> Digital Camera1231</li>
            <?php }?>
         </ul>
      </div>
      --> 
   </form>
   <?php if($aassets){?>
   <div class="mt10" style="width:500px;display:inline-block;margin-bottom:20px;">
      <a href="#" class="btn_small btn_type_3s btn_space save-req-btn"><span>Save</span></a>
      <a href="<?php echo $module_path;?>req_usage_rec" class="link_1 mt5 btn_space">Return to List</a>
   </div>
   <?php }else {?>
   <b style="color:#ff0000;">Sorry you can't add request because there are no available assets list that you can select. <a href="<?php echo $module_path;?>photo_assets/add_photo_asset">Click</a> to add.</b>
      <br />
   <br />
   <br />
   <br />
   <?php }?>
</div>
<!-- END inner content -->