   <!-- BEGIN Recent Activities -->
   <div id="recent_activities" class="dragbox dashboard-key">
      <a href="#" name="activities"></a>
      <div class="dashboard_wrap mt10 dragbox-content">
         <div class="title">
            <span class="header-span" title="Recent User Activities - Click to drag">Photo Assets List <b></i></b></span>
            <div class="fr" style="visibility:hidden">
               <label>Show Entries by </label>
               <select class="select_type_2 nm np" id="recent-activities-row">
               </select>						
            </div>
         </div>            
         <div class="table-container">
            <table class="tstyle_1 ac" id="photo-assets-list-table">
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <thead class="tborder_1 tfonts_4">
                  <tr>
                     <th>Category</th>
                     <th>Item Name</th>
                     <th>Description</th>
                     <th>Status</th>								
                  </tr>
               </thead>
               <tbody>
                  <?php if( $aphoto_assets_list ) {?>
                  <?php foreach( $aphoto_assets_list as $rows ) {?>
                  <tr>
                     <td class="ac"><?php echo $rows['category'];?></td>
                     <td class="ac"><a href="<?php echo $module_path;?>photo_assets/edit_photo_asset/?id=<?php echo $rows['idx'];?>" class="tfonts_7"><?php echo $rows['item_name'];?></a></td>
                     <td class="ac"><?php echo $rows['description'];?></td>
                     <td class="ac"><?php echo $rows['status'];?></td>
                  </tr>
                  <?php }?>
                  <?php } else {?>
                  <tr>
                     <td colspan="4" align="center">No Record</td>
                  </tr>
                  <?php }?>
               </tbody>
            </table>	
         </div>
         <div class="table_fl_50">
            <a href="<?php echo $module_path;?>photo_assets/" class="btn btn_type_1 fl ml5"><span>Go to Photo Assets List Page</span></a>	
         </div>           
      </div>
   </div>
   <!-- END Recent Activities -->