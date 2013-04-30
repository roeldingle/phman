<h2 class="title nm np fl">
   <strong class="">Request & Usage Record</strong>
   <span class="subtext"><!-- nothing here --> </span>
</h2>
<a href="<?php echo $module_path;?>req_usage_rec/request_equipment" class="btn btn_type_1 fl ml5"><span>Request Equipment</span></a>	
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
   <?php echo $this->common->get_message("request-message");?>
   <!-- end photo message -->
   <!-- table_wrap_3 -->
   <div class="table_wrap_3">
      <table class="tstyle_1 tfonts_4" id="request-list-table">
         <colgroup>
            <col width="20" />
            <col width="160" />
            <col width="270" />
            <col width="310" />
            <col width="310" />
            <col width="220" />
         </colgroup>		
         <thead class="tborder_1 ac">
            <tr>
               <th><input type="checkbox" class="check-all" /></th>
               <th>Activity Date</th>
               <th>Requested By</th>
               <th>Location Shoot</th>
               <th>Purpose</th>
               <th>Items</th>
            </tr>
         </thead>
         <tbody>
            <?php if( $arequest_list ) {?>
            <?php foreach( $arequest_list as $rows ) {?>
            <tr>
               <td class="ac" valign="center"><input type="checkbox" class="row-list" value="<?php echo $rows['idx']?>"/></td>
               <td class="ac"><a href="<?php echo $module_path;?>req_usage_rec/edit_request_equipment/?id=<?php echo $rows['idx'];?>" class="tfonts_7"><?php echo date('Y-m-d',$rows['activity_date']);?></a></td>
               <td class="ac"><?php echo $rows['requested_by'];?></td>
               <td class="ac"><?php echo $rows['location'];?></td>
               <td class="ac"><?php echo $rows['purpose'];?></td>
               <td class="ac">
                  <?php if( $rows['items_list'] ) {?>
                  <?php $i = 1;?>
                  <?php foreach($rows['items_list'] as $items_rows ) {?>
                  <b style="color:gray"><?php echo $items_rows->tpal_item_name . "#{$i}";?></b> <br />
                  <?php $i++;?>
                  <?php }?>
                  <?php } else {?>
                  -no items-
                  <?php }?>
               </td>
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
