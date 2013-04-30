<div class="category-label">Select assets list below: <b class="checkbox-error-message"></b></div>
<?php foreach($acategory_list as $key => $rows ) { ?>
<div class="category-container">
   <b class="category-header"><?php echo $rows['category']?></b>
   <ul class="category-list">
      <?php if( $rows['sub_list'] ) {?>
      <?php foreach( $rows['sub_list'] as $sub_rows ) {?>
      <li><input id="li-<?php echo $sub_rows->tpal_idx;?>" name="assets-id[]" value="<?php echo $sub_rows->tpal_idx;?>" type="checkbox"/> <label for="li-<?php echo $sub_rows->tpal_idx;?>"><?php echo $sub_rows->tpal_item_name;?></label></li>
      <?php }?>
      <?php } else {?>
      <li>-No Record-</li>
      <?php }?>
   </ul>
</div>
<?php }?>