<h2 class="title nm np fl">
   <strong class="">Asset Accountability Form </strong> <span class="subtext fn"><!-- nothing here --></span>
</h2>
<a href="<?php echo $module_path;?>req_usage_rec" class="btn btn_type_1 fl"><span>Back To List</span></a>
<!-- BEGIN inner content -->
<div class="content np">
   <?php echo $this->common->get_message("request-message");?>
   <?php echo $this->common->form_submit(array("module" => "photo", 
                                               "controller" => "exec_request_usage", 
                                               "method" => "update_request",
                                               "method_type" => "post",
                                               "name" => "request-form"));?>
      <input type="hidden" value="<?php echo $iidx;?>" name="id" />
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th>Activity Date: <label for="activity-date" class="core-icons-calendar">&nbsp;</label></th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" id="activity-date" value="<?php echo date('Y-m-d', $arequest->tprl_activity_date);?>" name="activity-date" readonly="true" class="input_type_1 nm" style="width:75px"/>
               </div>
            </td>
         </tr>
         <tr>
            <th>Requested by: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" value="<?php echo $arequest->tprl_requested_by;?>" name="requested-by" class="input_type_2 nm" />
               </div>
            </td>
         </tr>
         <tr>
            <th>Location Shoot: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <textarea class="tarea" name="location" rows="5" cols="57"><?php echo $arequest->tprl_location_shoot;?></textarea>
               </div>
            </td>
         </tr>
         <tr>
            <th>Purpose / Theme: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <textarea class="tarea" name="purpose" rows="5" cols="57"><?php echo $arequest->tprl_purpose_theme;?></textarea>
               </div>
            </td>
         </tr>         
         <tr>
            <th>Returned Date: <label for="returned-date" class="core-icons-calendar">&nbsp;</label></th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" id="returned-date" name="returned-date" value="<?php echo date('Y-m-d', $arequest->tprl_returned_date);?>" readonly="true"  class="input_type_2 nm" />
               </div>
            </td>
         </tr>
      </table>
      <?php echo $scategory_html;?>
      <div style="display:block;">
         <ul class="export-ul">
            <li>
               <a href="<?php echo $exec_path?>?mod=photo|exec_request_usage|export_request&id=<?php echo $iidx;?>"><img src="<?php echo $assets_path;?>site/images/btn_export.png" /><b>Export</b></a>
            </li>
            <?php if( $arequest->tprl_attachment_filename != "" && $arequest->tprl_attachment_rawname) { ?>
            <li>&nbsp;</li>
            <li class="li-attachment">
               <a href="<?php echo $getfile_path;?>photo/uploads/request-attachment/<?php echo $arequest->tprl_attachment_rawname;?>"><img src="<?php echo $assets_path;?>site/images/btn_export.png" />
               </a>
               <b>Signed Document: <?php echo $arequest->tprl_attachment_filename;?> 
                  <a href="#" style="color:#ff0000;" id="remove-attachment-link"> (x) Remove</a>
                  <input type="hidden" id="current-attachment" name="current-attachment" value="<?php echo $arequest->tprl_attachment_rawname; ?>"/>
               </b>
            </li>
            <?php }?>
         </ul>
         <br />
            <?php 
               $this->app->set_fileupload(
                  array(
                     "modulename" => "photo",
                     "uploadname"=>"request-form",
                     "button_text"=>"Upload Document",
                     "directory" => "request-attachment",
                     "extensions"=>array("odt","ods","xlsx","xls"),
                     "total_upload" => 1,
                     "file_size" => array("pdf" =>"5000000")
                  )
               );
            ?>
      </div>
   </form>
   <br />
   <?php if($aassets){?>
   <div class="mt10" style="width:500px;display:inline-block;margin-bottom:20px;">
      <a href="#" class="btn_small btn_type_3s btn_space save-req-btn"><span>Save</span></a>
      <a href="<?php echo $module_path;?>req_usage_rec" class="link_1 mt5 btn_space">Return to List</a>
   </div>
   <?php }else {?>
   <b style="color:#ff0000;">Sorry you can't edit your file because there are no available assets list that you can select. <a href="<?php echo $module_path;?>photo_assets/add_photo_asset">Click</a> to add.</b>   <br />
   <br />
   <br />
   <br />
   <?php }?>
</div>
<!-- END inner content -->
<!-- hidden dialogs -->
<div class="confirm-remove-attachment-dialog no-display">
   <div style="align-center" id="delete-confirm-buttons">   
      <p><b>Are you sure you want to remove the attached file?<b></p>   
      <a href="#" id="remove-confirm-btn" class="btn btn_type_2"><span>&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;</span></a>
      <a href="#" id="cancel-remove-btn" class="btn btn_type_2"><span>Cancel</span></a>
   </div>
</div>