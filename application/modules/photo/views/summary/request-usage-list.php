   <!-- BEGIN Recent Activities -->
   <div id="recent_activities" class="dragbox dashboard-key">
      <a href="#" name="activities"></a>
      <div class="dashboard_wrap mt10 dragbox-content">
         <div class="title">
            <span class="header-span" title="Recent User Activities - Click to drag">Request & Usage Record <b></i></b></span>
            <div class="fr" style="visibility:hidden">
               <label>Show Entries by </label>
               <select class="select_type_2 nm np" id="recent-activities-row">
               </select>						
            </div>
         </div>            
         <div class="table-container">
            <table class="tstyle_1 ac" id="request-list-table">
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <thead class="tborder_1 tfonts_4">
                  <tr>
                     <th>Activity Date</th>
                     <th>Requested By</th>
                     <th>Location Shoot</th>
                     <th>Purpose</th>
                     <th>Items</th>								
                  </tr>
               </thead>
               <tbody>
                  <?php if( $arequest_list) {?>
                  <?php foreach( $arequest_list as $rows ) {?>
                  <tr>
                     <td><a href="<?php echo $module_path;?>req_usage_rec/edit_request_equipment/?id=<?php echo $rows['idx'];?>" class="tfonts_7"><?php echo $rows['activity_date'];?></a></td>
                     <td><?php echo $rows['requested_by'];?></td>
                     <td><?php echo $rows['location'];?></td>
                     <td><?php echo $rows['purpose'];?></td>
                     <td>
                        <?php if( $rows['aitems'] ) {?>
                        <?php $i = 1;?>
                        <?php foreach($rows['aitems'] as $items_rows ) {?>
                        <b style="color:gray"><?php echo $items_rows->tpal_item_name . "#{$i}";?></b> <br />
                        <?php $i++;?>
                        <?php }?>
                        <?php } else {?>
                        -no items-
                        <?php }?>
                     </td>
                  </tr>
                  <?php }?>
                  <?php }else{?>
                  <tr><td align="center" colspan="5"><b>No Record</b></td></tr>
                  <?php }?>
               </tbody>
            </table>          
         </div>
         <div class="table_fl_50">
            <a href="<?php echo $module_path;?>req_usage_rec" class="btn btn_type_1 fl ml5"><span>Go to Request Usage & Record List Page</span></a>	
         </div>           
      </div>
   </div>
   
   <!-- END Recent Activities -->