<div style="width:900px;display:inline-block;">
<?php
   $this->common->get_message('my-save-message');
?>
</div><br />
<?php
   $this->app->show_rows(5,array(1,2,3,4,5));
?>
<table class="content-table">
<colgroup>
<col width="120px"/>
<col width="120px"/>
<col width="120px"/>
<col width="150px"/>
<col width="100px"/>
</colgroup>
<thead>
   <tr><th>First Name <a href="<?php echo $this->common->list_sorter('f');?>">Sort</a></th><th>Middle Name <a href="<?php echo $this->common->list_sorter('m');?>">Sort</a></th><th>Last Name <a href="<?php echo $this->common->list_sorter('l');?>">Sort</a></th><th>Address</th><th>Date Created</th></tr>
</thead>
<tbody>
<?php if($aresult):?>
   <?php foreach($aresult as $rows):?>
   <tr><td><?php echo $rows->fname;?></td><td><?php echo $rows->mname;?></td><td><?php echo $rows->lname;?></td><td><?php echo $rows->address;?></td><td><?php echo $rows->date_created;?></td></tr>
   <?php endforeach;?>
<?php else: ?>
<?php endif;?>
</tbody>
</table>
<?php echo $pager;?>