<select class="show-per-rows" title="Rows Per Page">
<?php if($arows): ?>
<?php foreach($arows as $rows):?>
<option value="<?php echo $rows;?>" <?php echo ($irow===$rows) ? 'selected="selected"' : "";?>><?php echo $rows;?></option>
<?php endforeach;?>
<?php endif;?>
</select>