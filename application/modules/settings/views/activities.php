<h2 class="title nm np fl"><strong class="">User Activities </strong></h2>
<!-- BEGIN innder content -->
<div class="content np ml10">
   <div class="table_wrap_3 mt10">
      <!--
      <div class="table_fl_50 fl">
         <select class="select_type_1 nm np" id="select1">
            <option>Select Action</option>
            <option>Select All</option>
            <option>Select None</option>
            <option>Delete</option>
            <option>Edit</option>
            <option>Add New Expense</option>
         </select>					
         <a href="#" class="btn_small btn_type_1s"><span>Apply</span></a>							
      </div>
      -->
      <div class="search_01 fr">
         <!--
         <form action="#" method="post">
            <input type="text" value="" class="input_type_4" />
            <a href="#" class="btn_small btn_type_2s"><span>Search</span></a>
         </form>
         -->
         <div class="ar mt5">
            <label class="mr5">Show Entries by </label>
            <?php $this->app->show_rows(20,array(10,20,30,50,100),"name");?>
            <!--
            <select class="select_type_2 nm np">
               <option>10</option>
               <option>25</option>
               <option>50</option>
               <option>100</option>
            </select>
            -->            
         </div>
      </div>						
   </div>
   <!-- table_wrap_3 -->
   <div class="table_wrap_3">
   <table class="tstyle_1 tfonts_4" id="user-activities-table">
      <colgroup>
         <col width="155" />
         <col width="150" />
         <col width="120" />
         <col />
         <col width="110" />
         <col width="110" />
      </colgroup>		
      <thead class="tborder_1 ac">
         <tr>
            <th>Date</th>
            <th>User</th>
            <th>User Level</th>
            <th>Logs</th>
            <th>From</th>
            <th>To</th>
         </tr>
      </thead>
      <tbody>
         <?php if( $alist ) { ?>
            <?php foreach( $alist as $rows ) {?>
            <tr>
               <td><?php echo $rows['date_created'] ?></td>
               <td class="ac"><?php echo $rows['user'] ?></td>
               <td class="ac"><?php echo $rows['user_level'] ?></td>
               <td><span class="tfonts_7" style="cursor:default;"><?php echo $rows['message_log'];?><!--Change <span>Expense#1002</span>: Particulars</a>--></span></td>
               <td><?php echo $rows['message_from'];?></td>
               <td><?php echo $rows['message_to'];?></td>
            </tr>
            <?php }?>
         <?php } else {?>
            <tr>
               <td colspan="6" align="center">No Record</td>
            </tr>
         <?php }?>
      </tbody>
   </table>
   </div>
   <!-- //table_wrap_3 -->
   <?php echo $pager;?>
   <!--
   <div class="pagination center">
      <ul>
         <li class="first"><a class="inactive" href="#">&lt; Prev</a></li>
         <li><a class="current" href="#">1</a></li>
         <li><a href="#">2</a></li>
         <li><a href="#">3</a></li>
         <li><a href="#">4</a></li>
         <li><a href="#">5</a></li>
         <li><span>...</span></li>
         <li><a href="#">7</a></li>
         <li><a href="#">8</a></li>
         <li><a href="#">9</a></li>
         <li><a href="#">10</a></li>
         <li class="last"><a href="#">Next &gt;</a></li>
      </ul>
   </div>
   -->
   <!-- bert -->
   <div class="table_wrap_3">
      <!--
      <div class="table_fl_50 tfonts_4">
         <span>Specific Period:</span>
         <div class="holder">
            <label for="calendar_from" class="label_1">From:</label>
            <input type="text" value="" class="input_type_3" id="calendar_from" />
            <input type="image" src="<?php echo $assets_path;?>site/images/calendar-day.png" class="calendar_icon" />
         </div>
         <div class="holder">
            <label for="calendar_to" class="label_1">To:</label>
            <input type="text" value="" class="input_type_3" id="calendar_to"/>
            <input type="image" src="<?php echo $assets_path;?>site/images/calendar-day.png" class="calendar_icon" />								
         </div>
      </div>
      -->
      <div class="table_fr_30">
         <a href="<?php echo $exec_path;?>?mod=settings|activities_exec|export" class="btn_export fr" title="Export to Excel"><span>Export</span></a>
      </div>
   </div>
   <!--
   <div class="table_wrap_3">
      <a href="#" class="btn_small btn_type_2s fl"><span>Apply</span></a>
   </div>
   -->
   <!-- //bert -->		
</div>
<!-- END inner content -->