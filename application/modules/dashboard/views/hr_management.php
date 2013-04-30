<!-- BEGIN HR Management Summary -->
<div id="hr_management" class="dragbox dashboard-key">
   <div class="dashboard_wrap mt10 dragbox-content">
      <div class="title">
         <span class="header-span" title="HR Management Summary - Click to drag">HR Management Summary  <b><i class="<?php echo ($sshow_hide=='off') ? 'down': 'up';?>-icon"></i></b></span>
         <div class="fr">
            <label  style="visibility: hidden;">Show Entries by </label>
            <select style="visibility: hidden;">								
               <option value="5">5</option>
               <option value="10">10</option>
               <option value="20">20</option>
               <option value="30">30</option>
            </select>							
         </div>
      </div>
      <div class="twrap table-container <?php echo ($sshow_hide=='off') ? 'no-display': '';?>" data-key="hr_management" data-status="<?php echo ($sshow_hide=='off') ? 'off': 'on';?>">
         <div id="hr_1" class="column_sub">
            <div id="hr_item_1" class="dragbox">
               <!-- BEGIN Hired Employees -->
               <div class="tcontainer dragbox-content">
                  <table class="tstyle_7 ac mr10 mb10">
                     <caption class="tfonts_4">Hired Employees (Last 6 months)</caption>
                     <thead class="tborder_1 tfonts_4">
                        <tr>
                           <th>Month</th>
                           <th>Year</th>
                           <th>Total</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if( $ahired ) {?>
                        <?php foreach( $ahired as $rows ) {?>
                        <tr>
                           <td><?php echo $rows['month'];?></td>
                           <td><?php echo $rows['year'];?></td>
                           <td><?php echo $rows['total'];?></td>
                        </tr>
                        <?php }?>
                        <?php } else {?>
                        <?php }?>
                     </tbody>
                     <tfoot class="tborder_1 tfonts_4">
                        <tr>
                           <th>Total</th>
                           <th></th>
                           <th><span class="tfonts_6"><?php echo $itotal_hired;?></span></th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
               <!-- END Hired Employees -->
            </div>
            <div id="hr_item_2" class="dragbox">
               <!-- BEGIN Current Number of Employees -->
               <div class="tcontainer dragbox-content">
                  <table class="tstyle_7 ac mr10 mb10">
                     <caption class="tfonts_4">Current Number of Employees</caption>
                     <thead class="tborder_1 tfonts_4">
                        <tr>
                           <th>Employee</th>
                           <th>Total</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if( $adepartment ) { ?>
                        <?php foreach( $adepartment as $rows ) {?>
                        <tr>
                           <td><?php echo $rows['department_name'];?></td>
                           <td><?php echo $rows['total_current_employees'];?></td>
                        </tr>
                        <?php }?>
                        <?php } else {?>
                        <tr>
                           <td colspan="2"><b style="color:black">No Record</b></td>
                        </tr>
                        <?php }?>
                     </tbody>
                     <?php if( $adepartment > 0 ) {?>
                     <tfoot class="tborder_1 tfonts_4">
                        <tr>
                           <th>Total</th>
                           <th><span class="tfonts_6"><?php echo $itotal_current_employees;?></span></th>
                        </tr>
                     </tfoot>
                     <?php }?>      
                  </table>									
               </div>
               <!-- END Current Number of Employees -->
            </div>
         </div>

         <div id="hr_2" class="column_sub">
            <div id="hr_item_3" class="dragbox">
               <!-- BEGIN Probationary Employees -->
               <div class="tcontainer dragbox-content">
                  <table class="tstyle_7 ac mr10 mb10">
                     <caption class="tfonts_4">Probationary Employees</caption>
                     <thead class="tborder_1 tfonts_4">
                        <tr>
                           <th>Employee</th>
                           <th>Total</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if( $adepartment ) { ?>
                        <?php foreach( $adepartment as $rows ) {?>
                        <tr>
                           <td><?php echo $rows['department_name'];?></td>
                           <td><?php echo $rows['total_probationary_employees'];?></td>
                        </tr>
                        <?php }?>
                        <?php } else {?>
                        <tr>
                           <td colspan="2"><b style="color:black">No Record</b></td>
                        </tr>
                        <?php }?>
                     </tbody>
                     <?php if( $adepartment > 0 ) {?>
                     <tfoot class="tborder_1 tfonts_4">
                        <tr>
                           <th>Total</th>
                           <th><span class="tfonts_6"><?php echo $itotal_probationary_employees;?></span></th>
                        </tr>
                     </tfoot>
                     <?php }?>
                  </table>									
               </div>
               <!-- END Probationary Employees -->
            </div>
            <div id="hr_item_4" class="dragbox">
               <!-- BEGIN Retired Employees -->
               <div class="tcontainer dragbox-content">
                  <table class="tstyle_7 ac mr10 mb10">
                     <caption class="tfonts_4">Retired Employees</caption>
                     <thead class="tborder_1 tfonts_4">
                        <tr>
                           <th>Month</th>
                           <th>Year</th>
                           <th>Total</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if( $aretired ) {?>
                        <?php foreach( $aretired as $rows ) {?>
                        <tr>
                           <td><?php echo $rows['month'];?></td>
                           <td><?php echo $rows['year'];?></td>
                           <td><?php echo $rows['total'];?></td>
                        </tr>
                        <?php }?>
                        <?php } else {?>
                        <?php }?>
                     </tbody>
                     <tfoot class="tborder_1 tfonts_4">
                        <tr>
                           <th>Total</th>
                           <th></th>
                           <th><span class="tfonts_6"><?php echo $itotal_retired;?></span></th>
                        </tr>
                     </tfoot>
                  </table>									
               </div>
               <!-- END Retired Employees -->
            </div>
            <div id="hr_item_5" class="dragbox">
               <!-- BEGIN New Employees -->
               <div class="tcontainer dragbox-content">
               <table class="tstyle_7 ac mr10 mb10">
                  <caption class="tfonts_4">New Employees (Last 30 days)</caption>
                  <thead class="tborder_1 tfonts_4">
                     <tr>
                        <th>Employee</th>
                        <th>Total</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if( $adepartment ) { ?>
                     <?php foreach( $adepartment as $rows ) {?>
                     <tr>
                        <td><?php echo $rows['department_name'];?></td>
                        <td><?php echo $rows['total_new_employees'];?></td>
                     </tr>
                     <?php }?>
                     <?php } else {?>
                     <tr>
                        <td colspan="2"><b style="color:black">No Record</b></td>
                     </tr>
                     <?php }?>
                  </tbody>
                  <?php if( $adepartment > 0 ) {?>
                  <tfoot class="tborder_1 tfonts_4">
                     <tr>
                        <th>Total</th>
                        <th><span class="tfonts_6"><?php echo $itotal_new_employees;?></span></th>
                     </tr>
                  </tfoot>
                  <?php }?>
               </table>								
               </div>
               <!-- END New Employees  -->
            </div>
         </div>

         <div id="hr_3" class="column_sub">
            <div id="hr_item_6" class="dragbox">
               <!-- BEGIN Absences -->
               <div class="tcontainer dragbox-content">
               <table class="tstyle_7 ac mr10 mb10">
                  <caption class="tfonts_4">Absences (Last 6 months)</caption>
                  <thead class="tborder_1 tfonts_4">
                     <tr>
                        <th>Employee</th>
                        <th>Total</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if( $adepartment ){?>
                     <?php foreach( $adepartment as $rows ) {?>
                     <tr>
                        <td><?php echo $rows['department_name'];?></td>
                        <td><?php echo $rows['total_absences'];?></td>
                     </tr>
                     <?php }?>
                     <?php } else {?>
                     <?php }?>
                  </tbody>
                  <?php if( $adepartment > 0 ) {?>
                  <tfoot class="tborder_1 tfonts_4">
                     <tr>
                        <th>Total</th>
                        <th><span class="tfonts_6"><?php echo $itotal_absences;?></span></th>
                     </tr>
                  </tfoot>
                  <?php }?>
                  </table>								
               </div>
               <!-- END Absences  -->
            </div>
            <div id="hr_item_7" class="dragbox">
               <!-- BEGIN Tardiness -->
               <div class="tcontainer dragbox-content">
               <table class="tstyle_7 ac mr10 mb10">
                  <caption class="tfonts_4">Tardiness (Last 6 months)</caption>
                  <thead class="tborder_1 tfonts_4">
                     <tr>
                        <th>Period</th>
                        <th>Total</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if( $atardiness ) {?>
                     <?php foreach( $atardiness as $rows) {?>
                     <tr>
                        <td><?php echo $rows['month'] . '-' . $rows['year']; ?></td>
                        <td><?php echo $rows['total'];?></td>
                     </tr>
                     <?php }?>
                     <?php } else {?>
                     <?php }?>
                  </tbody>
                  <tfoot class="tborder_1 tfonts_4">
                     <tr>
                        <th>Total</th>
                        <th><span class="tfonts_6"><?php echo $itotal_tardiness; ?></span></th>
                     </tr>
                  </tfoot>         
               </table>								
               </div>
               <!-- END Tardiness  -->
            </div>
         </div>
      </div>							
   </div>
</div>
<!-- END HR Management Summary -->