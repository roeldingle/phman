<h2 class="title nm np fl">
   <strong class="">Photo Assets List </strong>
   <span class="subtext"><!-- nothing here --> </span>
</h2>
<a href="<?php echo $module_path;?>photo_assets/add_photo_asset" class="btn btn_type_1 fl ml5"><span>Add New Photo Asset</span></a>	
<!-- BEGIN innder content -->
<div class="content np ml10">
   <div class="table_wrap_3 mt10">
      <div class="search_01 fr">
         <input type="text" value="<?php echo $ssearch;?>" class="input_type_4 search-tbox" />
         <a href="#" class="btn_small btn_type_2s search-btn"><span>Search</span></a>
         <div class="ar mt5">
            <label class="mr5">Show Entries by </label>
            <?php $this->app->show_rows(20,array(10,20,30,50,100),"name");?>       
         </div>
      </div>						
   </div>
   <!-- photo message -->
   <div class="js-message"></div>
   <?php echo $this->common->get_message("photo-message");?>
   <!-- end photo message -->
   <!-- table_wrap_3 -->
   <div class="table_wrap_3">
      <table class="tstyle_1 tfonts_4" id="photo-assets-list-table">
         <colgroup>
            <col width="20" />
            <col width="100" />
            <col width="270" />
            <col width="310" />
            <col width="90" />
         </colgroup>		
         <thead class="tborder_1 ac">
            <tr>
               <th><input type="checkbox" class="check-all" /></th>
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
               <td class="ac" valign="center"><input type="checkbox" class="row-list" value="<?php echo $rows['idx'];?>"/></td>
               <td class="ac"><?php echo $rows['category'];?></td>
               <td class="ac"><a href="<?php echo $module_path;?>photo_assets/edit_photo_asset/?id=<?php echo $rows['idx'];?>" class="tfonts_7"><?php echo $rows['item_name'];?></a></td>
               <td class="ac"><?php echo $rows['description'];?></td>
               <td class="ac"><?php echo $rows['status'];?></td>
            </tr>
            <?php }?>
            <?php } else {?>
            <tr>
               <td colspan="6" align="center">No Record</td>
            </tr>
            <?php }?>
         </tbody>
      </table>
      <div class="table_fl_50">
        <input type="button" value="Edit" class="btn cursor-pointer " id="edit-btn"/>
        <input type="button" value="Delete" class="btn cursor-pointer" id="delete-btn"/>
        <span class="message_type2 np" id="msg_select1"></span>
      </div>
      <?php echo $pager;?>
   </div>
   <!-- //table_wrap_3 -->
</div>
<!-- END inner content -->

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
