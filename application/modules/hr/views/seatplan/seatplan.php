<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<br/>
<h1 class="title nm np fl">
<strong class=""><?php echo $aresult!=null ? $aresult[0]->tss_map_name : 'Seat Plan'?></strong>
<span class="subtext" style="font-size:15px">Manage View</span>
</h1>
<!-- Settings -->
<div class="fr setting_toggle">
	<input class="settings_icon" type="image" src="<?php echo $assets_path; ?>site/images/settings.png"> &nbsp <strong class="al np t_settings">Settings</strong>
	<ul class="ac seatplan_list fr nl nm" style="">
      <li><a href="#" id="upload_new_map">Upload New Map</a></li>
      <li id="li_append_list"><a href="#" id="vcoords" class="viewas" >Manage Coordinates</a></li>
	</ul>
</div>
<br><br><br><br><br><br><br><br><br>

<div class="fr" style="margin-right:20px">
Color Scheme<br><br>
   <img src="<?php echo $assets_path; ?>hr/images/3.png">&nbsp;used<br>
   <img src="<?php echo $assets_path; ?>hr/images/1.png">&nbsp;vacant pc<br>
   <img src="<?php echo $assets_path; ?>hr/images/2.png">&nbsp;vacant table<br>
   <img src="<?php echo $assets_path; ?>hr/images/0.png">&nbsp;not available<br>
</div>
<!-- message container -->
<div class="message-container"></div>

<!-- display jcrop image -->
<?php 

if($aresult!=null){
   if(file_exists(APPPATH . 'modules/hr/uploads/map/'.$aresult[0]->tss_map_src)){ ?>

   <div id="frame_map">
   <iframe src="<?php echo base_url()?>hr/seat_plan/Map" class="map_size" scrolling="no" id="" alt="Jcrop Image" ></iframe>
   </div>
   <div style="margin: .8em 0 .5em;">
      <span class="requiresjcrop">

      </span>

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
         <tbody class="upload_form">
         <tr>
            <th><label for="map-name">Map Name</label></th>
            <td><input type="text" class="input_type_1 nm" id="map-name" name="map-name" /></td>
         </tr>
         <tr>
            <th><label for="load-map">Upload Map</label></th>
            <td>
            <?php 
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
         </tbody>
      </table>
      </form>
   <ul class="control_buttons np nl">
      <li><a href="#" id="submit_upload" class="btn_small btn_type_3s"><span>Submit</span></a></li>
      <li><a href="#" class="link_1 mt5 fl">Cancel</a></li>
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
            <label>Setting new image will delete all seat plan position and back to default configuration, Are you sure?</label>
         </tr>
      </table>
   </form>
   <ul class="control_buttons np nl">
      <li><a href="#" class="btn_small btn_type_3s" id="alert_submit"><span>Ok</span></a></li>
      <li><a href="#" class="link_1 mt5 fl reset">Reset</a></li>
   </ul>
</div>
