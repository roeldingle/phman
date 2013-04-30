<!-- BEGIN Stock Management Summary -->
<div id="stock_management" class="dragbox dashboard-key">
   <a href="#" name="stock"></a>
   <div class="dashboard_wrap mt10 dragbox-content">
      <div class="title">
         <span class="header-span" title="Stock Management Summary - Click to drag">Stock Management Summary  <b><i class="<?php echo ($sshow_hide=='off') ? 'down': 'up';?>-icon"></i></b></span>
         <div class="fr">
            <label>Show Entries by </label>
            <select class="select_type_2 nm np" id="stock-row">
               <option <?php echo ($stype=='stock' && $irow == 5) ? 'selected="selected"' : ""; ?>>5</option>
               <option <?php echo ($stype=='stock' && $irow == 10) ? 'selected="selected"' : ""; ?>>10</option>
               <option <?php echo ($stype=='stock' && $irow == 20) ? 'selected="selected"' : ""; ?>>20</option>
               <option <?php echo ($stype=='stock' && $irow == 30) ? 'selected="selected"' : ""; ?>>30</option>
            </select>						
         </div>
      </div>
      <div class="twrap table-container <?php echo ($sshow_hide=='off') ? 'no-display': '';?>" data-key="stock_management" data-status="<?php echo ($sshow_hide=='off') ? 'off': 'on';?>">
         <?php if( $amain_category ) {?>
            <?php foreach( $amain_category as $rows) {?>
            <div class="tcontainer" style="width:500px;">
               <table class="tstyle_7 ac mr10 mb10">
                  <caption class="tfonts_4"><?php echo $rows['category_name'];?></caption>
                  <thead class="tborder_1 tfonts_4">
                     <tr>
                        <th>Category</th>
                        <th>Total</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if(isset($asub_category[$rows['main_idx']])){?>
                     <?php foreach( $asub_category[$rows['main_idx']] as $rows_sub ) {?>
                     <tr>
                        <td><?php echo $rows_sub['sub_category_name'];?></td>
                        <td><?php echo $rows_sub['total_item'];?></td>
                     </tr>
                     <?php }?>
                     <?php }else{?>
                     <tr>
                        <td colspan="2"><b style="color:black">No Record</b></td>
                     </tr>            
                     <?php }?>
                  </tbody>
                  <tfoot class="tborder_1 tfonts_4">
                     <?php if($rows['total'] > 0){?>
                     <tr>
                        <th>Total</th>
                        <th><span class="tfonts_6"><?php echo $rows['total'];?></span></th>
                     </tr>
                     <?php }?>
                  </tfoot>      
               </table>         
            </div>
            <?php }?>
         <?php }?>
      </div>
   </div>
</div>
<!-- END Stock Management Summary -->
