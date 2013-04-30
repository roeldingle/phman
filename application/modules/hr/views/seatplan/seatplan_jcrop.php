<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<br/>
<!-- message container -->
<div class="message-container"></div>
<h1 class="title nm np fl">
<strong class=""><?php echo $aresult!=null ? $aresult[0]->tss_map_name : 'Seat Plan'?></strong>
<span class="subtext" style="font-size:15px">Coordinates</span>
</h1>
<!-- Settings -->
<div class="fr setting_toggle">
	<input class="settings_icon" type="image" src="<?php echo $assets_path; ?>site/images/settings.png"> &nbsp <strong class="al np t_settings">Settings</strong>
	<ul class="ac seatplan_list fr nl nm" style="">
      <li><a href="#" id="upload_new_map">Upload New Map</a></li>
      <li><a href="#" id="set_dt">Set Default Table Size</a></li>
      <li id="li_append_list"><a href="#" id="msp" class="viewas" >Manage Seat Plan</a></li>
	</ul>
</div>
<br><br><br>
<!-- Set table button -->
<?php if($aresult!=null){?>
<div id="coords_btn" style="width:100%;display:inline-block">
   <br>
   <button id="animateTo">Set Table</button><button id="release">Release</button><button id="edit_seat_coords">Edit Seat Plan Position</button>
</div>
<?php } ?>
 <br><br> <br><br> <br><br>
 <div class="fr" style="margin-right:20px">
Color Scheme<br><br>
   <img src="<?php echo $assets_path; ?>hr/images/3.png">&nbsp;used<br>
   <img src="<?php echo $assets_path; ?>hr/images/1.png">&nbsp;vacant pc<br>
   <img src="<?php echo $assets_path; ?>hr/images/2.png">&nbsp;vacant table<br>
   <img src="<?php echo $assets_path; ?>hr/images/0.png">&nbsp;not available<br>
</div>
<!-- display jcrop image -->
<?php 

if($aresult!=null){
   if(file_exists(APPPATH . 'modules/hr/uploads/map/'.$aresult[0]->tss_map_src)){ ?>

   <div id="frame_map">
   <iframe src="<?php echo base_url()?>hr/seat_plan/Map" class="map_size" scrolling="no" id="target" alt="Jcrop Image" ></iframe>
   </div>
   <div style="margin: .8em 0 .5em;">
      <span class="requiresjcrop">

      </span>
         <form id="coords" class="coords">
           <div>
           <label>X1 <input type="text" size="4" id="x1" name="x1" /></label>
           <label>Y1 <input type="text" size="4" id="y1" name="y1" /></label>
           <label>X2 <input type="text" size="4" id="x2" name="x2" /></label>
           <label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>
           <label>W <input type="text" size="4" id="w" name="w" /></label>
           <label>H <input type="text" size="4" id="h" name="h" /></label>
           </div>
         </form>
   </div>
   
<?php
   }else{ 
      echo '<br><br><br><font color="#ff0000">* '.$aresult[0]->tss_map_name.' map was not found on this server. Please try to upload again.</font>';
   }

}else{
   echo '<br><br><br><font color="#ff0000">* Image not set yet</font>';
} ?>

<!-- Dialog div's -->

<div class="upload_dialog" style="display:none">
<?php
  $aoptions = array(
   "id" =>"test-form",
   "name" =>"test-form",
   "class" =>"test-form",
   "method_type" =>"post",
   "module" =>"hr",
   "controller" => "hr_exec",
   "method" => "exec_save"   
);

$this->common->form_submit($aoptions);
?>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th><label for="map-name">Map Name</label></th>
            <td><input type="text" class="input_type_1 nm" id="map-name" /></td>
         </tr>
         <tr>
            <th><label for="load-map">Upload Map</label></th>
            <td><?php 
                  $this->app->set_fileupload(
                     array(
                        "modulename" => "hr",
                        "uploadname"=>"seatplan_upload",
                        "button_text"=>"Browse File",
                        "directory" => "map",
                        "extensions"=>array("gif","jpg","png"),
                        "total_upload" => 1,
                        "file_size" => array("pdf" =>"5000000")
                     )
                  );
               ?>
            </td>
         </tr>
      </table>
   </form>
   <ul class="control_buttons np nl">
      <li><a href="#" id="submit_upload" class="btn_small btn_type_3s"><span>Submit</span></a></li>
      <li><a href="#" class="link_1 mt5 fl">Cancel</a></li>
   </ul>
</div>
<div class="set_seatno" style="display:none">
   <form>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tbody id="set_seat">
            <tr>
               <th><label for="map-name">Seat No.</label></th>
               <td><input type="text" class="input_type_1 nm" id="seat_no" /></td>
            </tr>
            <tr>
               <th><label for="map-name">Availability</label></th>
               <td>
                  <select id="seat_usage">
                     <option value="1" selected>Vacant PC</option>
                     <option value="2">Vacant Table</option>
                     <option value="0">Not Available</option>
                  </select>
               </td>
            </tr>
         </tbody>
         <tbody id="set_size" style="display:none">
            <tr>
               <th><label for="map-name">X1</label></th>
               <td><input type="text" class="input_type_3 nm" id="set_x1" readonly /></td>
            </tr>
            <tr>
               <th><label for="map-name">Y1</label></th>
               <td><input type="text" class="input_type_3 nm" id="set_y1" readonly /></td>
            </tr>
            <tr>
               <th><label for="map-name">X2</label></th>
               <td><input type="text" class="input_type_3 nm" id="set_x2" readonly /></td>
            </tr>
            <tr>
               <th><label for="map-name">Y2</label></th>
               <td><input type="text" class="input_type_3 nm" id="set_y2" readonly /></td>
            </tr>
         </tbody>
      </table>
   </form>
   <ul class="control_buttons np nl">
      <li><a href="#" id="submit_seatno" class="btn_small btn_type_3s"><span>Submit</span></a></li>
      <li><a href="#" class="link_1 mt5 fl cancel">Edit Coordinates</a></li>
   </ul>
</div>
<div class="alert_msg" style="display:none">
   <form>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <label id="alert_message">Setting new image will delete all seat plan position and back to default configuration, Are you sure?</label>
         </tr>
      </table>
   </form>
   <ul class="control_buttons np nl">
      <li><a href="#" class="btn_small btn_type_3s" id="alert_submit"><span>Ok</span></a></li>
      <li><a href="#" class="link_1 mt5 fl reset">cancel</a></li>
   </ul>
</div>

<div class="edit_seatno" style="display:none">
   <form>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th><label for="map-name">Select Seat No.</label></th>
            <td><select id="select_seatno"></select></td>
         </tr>
      </table>
   </form>
   <ul class="control_buttons np nl">
      <li><a href="#" id="submit_edit_seat" class="btn_small btn_type_3s"><span>Update</span></a></li>
      <li><a href="#" id="submit_del_seat" class="btn_small btn_type_3s"><span>Delete</span></a></li>
   </ul>
</div>
