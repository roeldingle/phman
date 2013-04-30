<!-- //TABS-->
<div class="search_02">
   <form name="search-form" id="search-form">
      <div class="holder_2">
         <label title="Serial/Model" >Serial / Model</label>
         <input title="Serial/Model" type="text" value="<?php echo $search;?>" class="input_type_2" id="search-txtbox"/>
      </div>
      <div class="holder">
         <label>Period :</label>
         <input type="text" value="<?php echo $end_date; ?>" title="Start Date" readonly="true" class="input_type_3" id="calendar-from" name="calendar-from"/>
         <label class="calendar_icon" for="calendar-from" title="Start Date" ><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
      </div>
      <div class="holder">
         <span class="mr10">&ndash;</span>
         <input type="text" title="End Date" value="<?php echo $end_date;?>" readonly="true" class="input_type_3" id="calendar-to" name="calendar-to" />
         <label class="calendar_icon" title="End Date" for="calendar-to"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"></label>
      </div>
      <a href="#" title="Search" class="btn_small btn_type_2s search-btn" alt="<?php echo $this->common->qry_str_array_builder(array('start','end', 'search','page'))?>"><span>Search</span></a>
      <a href="#" title="Reset" class="btn_small btn_type_2s reset-search-btn"><span>Reset</span></a>
      <span class="range-error-message"></span>
   </form>
</div>
<a href="#" class="btn_small btn_type_1s delete-btn" title="Delete" ><span>Delete</span></a>
<div class="show_rows fr">
   <form>
   <label title="Rows Per Page" >Show Rows</label>
   <?php echo $this->app->show_rows($ilimit,array(10, 20, 30, 50, 100));?>
   </form>
</div>
<div class="js-message" style="width:99%"></div>
<div class="php-message" style="width:99%"><?php $this->common->get_message("php-message");?></div>
<ul class="sort_view nl np" style="width:auto;">
   <li class="<?php echo ($icategory_id=='') ? 'active': '';?> all">
   <a href="?category=<?php echo $this->common->qry_str_array_builder(array('category','page'));?>" title="View All" >All (<?php echo $itotal_office;?>)</a>
   </li>
   <?php if($acategory ){?>
   <?php foreach($acategory as $rows ){?>
   <li class="<?php echo ( $rows['id'] == $icategory_id ) ? 'active' : '' ;?>">
   <span class="statTab" style="margin-right:2px;background:<?php echo $acategory_color[$rows['name']];?>;" title="<?php echo $rows['name'];?>"></span>
   <a href="?category=<?php echo $rows['id'] . $this->common->qry_str_array_builder(array('category','page'));?>" title="<?php echo $rows['name'];?>"><?php echo $rows['name'];?>(<?php echo $rows['total_items'];?>)</a>
   </li>
   <?php }?>
   <?php }?>
</ul>
<table border="0" cellspacing="0" cellpadding="0" class="tstyle_6 ac tfonts_4 hover_1 mb10 table-fixed" id="office-equipments-list-table">
   <colgroup>
      <col width="45" />
      <col width="75" />
      <col />
      <!--<col />-->
      <col />
      <col />
      <col />
      <col />
      <col />
   </colgroup>
   <thead>
      <tr>
         <th><input type="checkbox" class="check-all" title="Check All"/></th>
         <th title="Row No.">No.</th>
         <th title="Date Reported">Date Reported</th>
         <!--<th title="Purchased Date">Purchased Date</th>-->
         <th title="Category">Category</th>
         <th title="Model">Model</th>
         <th title="Serial">Serial</th>
         <th title="Assign To">Assign to:</th>
         <th title="Remarks" class="last">Remarks</th>
      </tr>
   </thead>
   <tbody>
      <?php if( $aoffice_equipments_list ) {?>
      <?php foreach( $aoffice_equipments_list as $rows ) {?>
      <tr class="tbground_10">
         <td><input type="checkbox" class="row-list" value="<?php echo $rows['idx'];?>" /></td>
         <td><?php echo $rows['row'];?></td>
         <td><a href="<?php echo $module_path;?>in_report/edit_incident_report?type=office&id=<?php echo $rows['idx'];?><?php echo ($icategory_id) ? "&category={$icategory_id}" : "";?>" class="tfonts_7"><?php echo $rows['date_reported'];?></a></td>
         <!--<td><?php echo $rows['date_purchased'];?></td>-->
         <td><?php echo $rows['category_name'];?></td>
         <td><?php echo $rows['model'];?></td>
         <td><?php echo $rows['serial'];?></td>
         <td><?php echo $rows['assign_to']?></td>
         <td class="last"><?php echo $rows['remarks'];?></td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="tbground_10">
         <td colspan="8">No Record</td>
      </tr>
      <?php }?>
   </tbody>
</table>
<a href="#" class="btn_small btn_type_1s delete-btn" title="Delete"><span>Delete</span></a>
<a class="btn_export fr" title="Export to Excel" href="<?php echo $exec_path;?>?mod=stock|exec_in_report|export_office_equipments<?php echo $qry_param;?>"><span>Export</span></a>
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