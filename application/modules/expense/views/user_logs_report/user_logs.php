<h2 class="title nm np fl"><strong class="">User Logs Report </strong></h2>
<!-- BEGIN innder content -->
<div class="content np ml10">
   <div class="table_wrap_3 mt10">				
      <div class="search_01 fr">
         <form action="#" method="post">
            <input type="text" value="<?php echo $search;?>" class="input_type_4 search-txtbox" />
            <a href="javascript:void(0);" class="btn_small btn_type_2s search-btn"><span>Search</span></a>
         </form>
         <div class="ar mt5">
            <label class="mr5">Show Entries by </label>
            <?php $this->app->show_rows(20,array(10,20,30,50,100));?>
         </div>
      </div>						
   </div>
<div>
<?php echo $this->common->get_message("error-message");?>
</div>
   
   <!-- table_wrap_3 -->
   <div class="table_wrap_3">
   <table class="tstyle_1 tfonts_4" id="user-logs-table">
      <colgroup>
         <col width="125" />
         <col width="120" />
         <col width="125" />
         <col width="425" />
         <col width="150" />
         <col width="150" />
      </colgroup>		
      <thead class="tborder_1 ac">
         <tr>
            <th>Date</th>
            <th>User Name</th>
            <th>Position</th>
            <th>Logs</th>
            <th>From</th>
            <th>To</th>
         </tr>
      </thead>
      <tbody>
         <?php if( $alist ) {?>
         <?php foreach( $alist as $rows ) {?>
         <tr>
            <td><?php echo $rows['date_created'];?></td>
            <td class="ac"><?php echo $rows['user'];?></td>
            <td class="ac"><?php echo $rows['position'];?></td>
            <td><span class="<?php echo ( $rows['transact_type'] == "UPDATE" ) ? 'tfonts_6' : 'tfonts_7';?>" style="cursor:default;"><?php echo $rows['message_log'];?></span><!--<a href="#" class="tfonts_7">Edited <span>Expense#1002</span>: Particulars</a> --></td>
            <td><?php echo $rows['message_from'];?></td>
            <td><?php echo $rows['message_to'];?></td>
         </tr>
         <?php } ?>
         <?php }else{?>
         <tr>
            <td align="center" colspan="6"><b>No Records</b></td>
         </tr>
         <?php }?>         
      </tbody>
   </table>
   </div>
   <!-- //table_wrap_3 -->
   <?php echo $pager;?>   
   <!-- bert -->
   <div class="table_wrap_3">
      <div class="table_fl_50 tfonts_4">
         <span>Specific Period:</span>
         <div class="holder">
            <label for="calendar_from" class="label_1">From:</label>
            <input type="text" value="<?php echo $from;?>"  class="input_type_3" id="calendar_from" />
            <label for="calendar_from" class="core-icons-calendar"></label>
         </div>
         <div class="holder">
            <label for="calendar_to" class="label_1">To:</label>
            <input type="text" value="<?php echo $to;?>" class="input_type_3" id="calendar_to"/>
            <label for="calendar_to" class="core-icons-calendar"></label>
         </div>
         <b class="tfonts_6 date-range-message no-display">Invalid date range</b>
      </div>
      <div class="table_fr_30">
         <a href="<?php echo $exec_path;?>?mod=expense|user_logs_report_exec|export_user_logs<?php echo $qry_param;?>" class="btn_export fr" title="Export to Excel"><span>Export</span></a>
      </div>
   </div>
   <div class="table_wrap_3">
      <a href="#" class="btn_small btn_type_2s fl date-range-btn"><span>Apply</span></a>
   </div>
   <!-- //bert -->		
</div>
<!-- END inner content -->