<input type="hidden" id="row" value="<?php echo isset($_GET['row'])?$_GET['row']:'';?>" />
<input type="hidden" id="message" value="<?php echo isset($_GET['message'])?$_GET['message']:'';?>" />
<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<div class="hr-content">
<div class="message-container"></div>
<h2 class="title np fl mb10"><strong class="">Department List</strong></h2>
<div class="content np">
	<div class="search_01 fr">
		<form action="#" method="post">
			<label>Search Department</label>
			<input type="text" value="" class="input_type_4" id="searchtxt"/>
			<a id="searchbtn" class="btn_small btn_type_2s"><span>Search</span></a>
		</form>
	</div>
	<div class="show_rows fr">
		<form>
			<label>Show Rows</label>
			<select id="show_rows">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
		</form>
	</div>
	<span class="fl mb10 np"><a href="#" id="addNew" name="" class="btn_small btn_type_1s"><span>Add New Department</span></a></span>
	<table border="0" cellspacing="0" cellpadding="0" class="table_02 ac" id="employee">
		<colgroup>
			<col width="40" />
			<col width="50" />
			<col />
			<col width="150" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" class="checkall" /></th>
				<th>No.</th>
				<th><a href="#">Department</a></th>
				<th><a href="#">Date</a></th>
			</tr>	
		</thead>
		<tbody class="employee_list">
<?php 

      $listcount = count($lists);
      if($listcount>=1){
         foreach($lists as $list){
            echo '<tr>';
            echo '<td><input class="chkboxlist'.$listcount.'" type="checkbox" value="'.$list->td_idx .'"/></td>';
            echo '<td>'.$listcount.'</td>';
            echo '<td><a href="#" class="update_dept" name="'.$list->td_idx .'">'.$list->td_dept_name .'</a></td>';
            echo '<td class="last">'.$list->td_date_created .'</td>';
            echo '</tr>';
            
            $listcount--;
         }
      }else{
         echo '<tr><td colspan="8">No Records(s) Found!</td></tr>';
      }
?>     

<input type="hidden" id="chkboxcount" value="<?php echo count($lists);?>" />
		</tbody>
	</table>
	<span class="fl nm np del"><a href="#" class="btn_small btn_type_3s"><span id="delbtn">Delete</span></a></span>
<?php if(count($lists)!=0){ echo $pager; } ?>
</div>

</div>