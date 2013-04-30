<!-- //TABS-->
<div class="search_02">
   <form>
      <div class="holder">
         <label>Period :</label>
         <input type="text" title="Start Date" value="<?php echo $start_date;?>" class="input_type_3" id="calendar-from" />
         <label class="calendar_icon" title="Start Date" for="calendar-from"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
      </div>
      <div class="holder">
         <span class="mr10">&ndash;</span>
         <input type="text" title="End Date" value="<?php echo $end_date;?>" class="input_type_3"  id="calendar-to" />
         <label class="calendar_icon" title="End Date" for="calendar-to"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
      </div>
      <a href="#" title="Search" class="btn_small btn_type_2s search-btn" alt="<?php echo $this->common->qry_str_array_builder(array('start','end','blank','page'))?>"><span>Search</span></a>
   </form>
</div>
<a href="#" class="btn_small btn_type_1s delete-btn" title="Delete"><span>Delete</span></a>
<div class="show_rows fr">
   <form>
   <label title="Rows Per Page">Show Rows</label>
   <?php echo $this->app->show_rows($ilimit,array(10, 20, 30, 50, 100));?>
   </form>
</div>
<div class="js-message" style="width:99%"></div>
<div class="php-message" style="width:99%"><?php $this->common->get_message("php-message");?></div>
<table border="0" cellspacing="0" cellpadding="0" class="table_02 table-fixed" id="others-list-table">
   <colgroup>
      <col width="25" />
      <col width="50" />
      <col width="170" />
      <col width="270" />
      <col width="370" />
   </colgroup>
   <thead>
      <tr>
         <th><input type="checkbox" class="check-all" value="" title="Check All"/></th>
         <th title="Row No.">No.</th>
         <th title="Date Reported">Date Reported</th>
         <th title="Model">Model</th>
         <th title="Remarks" class="last">Remarks</th>
      </tr>
   </thead>
   <tbody>
      <?php if( $aothers_list ) {?>
      <?php foreach($aothers_list as $rows ) {?>
      <tr>
         <td><input type="checkbox" value="<?php echo $rows['idx'];?>" class="row-list" /></td>
         <td><?php echo $rows['row'];?></td>
         <td><a href="<?php echo $module_path;?>in_report/edit_incident_report?type=others&id=<?php echo $rows['idx'];?>" class="tfonts_7"><?php echo $rows['date_reported'];?></a></td>
         <td><?php echo $rows['model'];?></td>
         <td class="last"><?php echo $rows['remarks'];?></td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr>
         <td colspan="5">No Record.</td>
      </tr>
      <?php }?>
   </tbody>
</table>
<a href="#" class="btn_small btn_type_1s delete-btn" title="Delete"><span>Delete</span></a>
<a class="btn_export fr" title="Export to Excel" href="<?php echo $exec_path;?>?mod=stock|exec_in_report|export_others<?php echo $qry_param;?>"><span>Export</span></a>
<div class="center">
   <?php echo $pager;?>
</div>

<!-- hidden dialogs -->
<div class="confirm-delete-dialog no-display">
   <div class="no-display align-center mt10" id="delete-loader-message">
      Deleting . . .
   </div>
   <div style="align-center" id="delete-confirm-buttons">   
      <p><b>Are you sure you want to delete selected record?<b></p>   
      <a href="#" id="delete-confirm-btn" class="btn btn_type_2"><span>&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;</span></a>
      <a href="#" id="cancel-delete-btn" class="btn btn_type_2"><span>Cancel</span></a>
   </div>
</div>