   <!-- BEGIN Expenses Management Summary -->
   <div id="expense_management" class="dragbox dashboard-key">
      <div class="dashboard_wrap mt10 dragbox-content">
         <div class="title">
            <span class="header-span" title="Expense Management Summary - Click to drag">Expenses Management Summary <b><i class="<?php echo ($sshow_hide=='off') ? 'down': 'up';?>-icon"></i></b></span>
            <div class="fr" style="visibility:hidden">
               <label>Show Entries by </label>
               <select>								
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="30">30</option>
               </select>								
            </div>
         </div>
         <div class="table-container <?php echo ($sshow_hide=='off') ? 'no-display': '';?>" data-key="expense_management" data-status="<?php echo ($sshow_hide=='off') ? 'off': 'on';?>">
            <table class="tstyle_1 ac" id="expense-management-table">
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <colgroup class="tborder_1" />
               <thead class="tborder_1 tfonts_4">
                  <tr>
                     <th>Period</th>
                     <th>Planned Budget</th>
                     <th>Expenses</th>
                     <th>Difference</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach( $aexpense as $rows ) {?>
                  <tr>
                     <td><?php echo $rows['month'] . '-' . $rows['year'];?></td>
                     <td><?php echo $rows['iplanned_budget'];?></td>
                     <td><?php echo $rows['iexpenses'];?></td>
                     <td><?php echo $rows['idifference'];?></td>
                  </tr>
                  <?php }?>
                  <!--
                  <tr>
                     <td>Nov-2012</td>
                     <td>2,000.00</td>
                     <td>200.00</td>
                     <td>1,800.00</td>
                  </tr>
                  <tr>
                     <td>Oct-2012</td>
                     <td>3,000.00</td>
                     <td>900.00</td>
                     <td>2,100.00</td>
                  </tr>
                  -->
               </tbody>
               <tfoot class="tborder_1 tfonts_4">
                  <tr>
                     <td>Total</td>
                     <td><?php echo $iplanned_budget_total;?></td>
                     <td><?php echo $iexpenses_total;?></td>
                     <td><?php echo $idifference_total;?></td>
                  </tr>
               </tfoot>
            </table>
         </div>         
      </div>
   </div>
   <!-- END Expenses Management Summary -->