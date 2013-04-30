   <!-- BEGIN Recent Activities -->
   <div id="recent_activities" class="dragbox dashboard-key">
      <a href="#" name="activities"></a>
      <div class="dashboard_wrap mt10 dragbox-content">
         <div class="title">
            <span class="header-span" title="Recent User Activities - Click to drag">Recent User Activities <b><i class="<?php echo ($sshow_hide=='off') ? 'down': 'up';?>-icon"></i></b></span>
            <div class="fr">
               <label>Show Entries by </label>
               <select class="select_type_2 nm np" id="recent-activities-row">
                  <option <?php echo ($stype=='activities' && $irow == 5) ? 'selected="selected"' : ""; ?>>5</option>
                  <option <?php echo ($stype=='activities' && $irow == 10) ? 'selected="selected"' : ""; ?>>10</option>
                  <option <?php echo ($stype=='activities' && $irow == 20) ? 'selected="selected"' : ""; ?>>20</option>
                  <option <?php echo ($stype=='activities' && $irow == 30) ? 'selected="selected"' : ""; ?>>30</option>
               </select>								
            </div>
         </div>            
         <div class="table-container <?php echo ($sshow_hide=='off') ? 'no-display': '';?>" data-key="recent_activities" data-status="<?php echo ($sshow_hide=='off') ? 'off': 'on';?>">
            <table class="tstyle_1 ac" id="recent-activities-table">
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <thead class="tborder_1 tfonts_4">
                  <tr>
                     <th>Date</th>
                     <th>User ID</th>
                     <th>Position</th>
                     <th>Logs</th>
                     <th>From</th>
                     <th>to</th>										
                  </tr>
               </thead>
               <tbody>
                  <?php if( $aactivities_list ) {?>
                  <?php foreach( $aactivities_list as $rows ) {?>
                  <tr>
                     <td><?php echo $rows['date_created'];?></td>
                     <td><?php echo $rows['user'];?></td>
                     <td><?php echo $rows['position'];?></td>
                     <td><?php echo $rows['message_log'];?></td>
                     <td><?php echo $rows['message_from'];?></td>
                     <td><?php echo $rows['message_to'];?></td>
                  </tr>
                  <?php }?>
                  <?php } else {?>
                  <tr><td align="center" colspan="6"><b>No Record</b></td></tr>
                  <?php }?>
               </tbody>
            </table>	
         </div>         
      </div>
   </div>
   <!-- END Recent Activities -->