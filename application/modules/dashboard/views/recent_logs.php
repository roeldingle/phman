   <!-- BEGIN Recent Logs -->
   <div id="recent_logs" class="dragbox dashboard-key">
      <a href="#" name="logs"></a>
      <div class="dashboard_wrap dragbox-content" >
         <div class="title">
            <span class="header-span" title="Recent Logs - Click to drag">Recent Logs <b><i class="<?php echo ($sshow_hide=='off') ? 'down': 'up';?>-icon"></i></b></span>
            <div class="fr">
               <label>Show Entries by </label>
               <select class="select_type_2 nm np" id="recent-logs-row">
                  <option <?php echo ($stype=='logs' && $irow == 5) ? 'selected="selected"' : ""; ?>>5</option>
                  <option <?php echo ($stype=='logs' && $irow == 10) ? 'selected="selected"' : ""; ?>>10</option>
                  <option <?php echo ($stype=='logs' && $irow == 20) ? 'selected="selected"' : ""; ?>>20</option>
                  <option <?php echo ($stype=='logs' && $irow == 30) ? 'selected="selected"' : ""; ?>>30</option>
               </select>
            </div>
         </div>      
         <div class="table-container <?php echo ($sshow_hide=='off') ? 'no-display': '';?>" data-key="recent_logs" data-status="<?php echo ($sshow_hide=='off') ? 'off': 'on';?>">			
            <table class="tstyle_1 ac" id="recent-logs-table">
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <thead class="tborder_1 tfonts_4">
                  <tr>
                     <th>Date</th>
                     <th>User ID</th>
                     <th>Full Name</th>
                     <th>Position</th>
                     <th>User Level</th>										
                  </tr>
               </thead>
               <tbody> 
                  <?php if ( $auser_list ) {?>
                  <?php foreach( $auser_list as $rows ) {?>
                  <tr>
                     <td><?php echo $rows['date_created'];?></td>
                     <td><?php echo $rows['user_id'];?></td>
                     <td><?php echo $rows['full_name'];?></td>
                     <td><?php echo $rows['position'];?></td>
                     <td><?php echo $rows['user_level'];?></td>
                  </tr>
                  <?php }?>
                  <?php } else {?>
                  <tr><td align="center" colspan="5"><b>No Record</b></td></tr>
                  <?php }?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- END Recent Logs -->