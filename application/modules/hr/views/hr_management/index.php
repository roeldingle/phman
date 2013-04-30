<input type="hidden" id="row" value="<?php echo isset($_GET['row'])?$_GET['row']:'50';?>" />
<input type="hidden" id="message" value="<?php echo isset($_GET['message'])?$_GET['message']:'';?>" />
<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<div class="hr-content">
<div class="message-container"></div>
<h2 class="title np fl mb10"><strong class="">Employee List</strong></h2>
<div class="content np">
	<div class="search_01 fr">
		<!--<form onsubmit="javascript:searchFn();">-->
         <!--<div class="search_filter" style="display:block">
            <label>Search Filter by : &nbsp;&nbsp;</label>
            <select class="select_type_1 nm">
               <option>Name</option>
               <option>Department</option>
               <option>Work Status</option>
            </select>
         </div>-->
			<div class="filter_name" style="display:block">
            <input type="text" value="" class="input_type_4" id="searchtxt"/>
            <a id="searchbtn" class="btn_small btn_type_2s"><span>Search</span></a>
         </div>
		<!--</form>-->
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
	<span class="fl mb10 np"><a href="#add" class="btn_small btn_type_1s"><span>Add New Employee</span></a></span>
	<table border="0" cellspacing="0" cellpadding="0" class="table_02 ac" id="employee">
		<colgroup>
			<col width="40" />
			<col width="50" />
			<col />
			<col width="140" />
			<col width="140" />
			<col width="120" />
			<col width="150" />
			<col width="150" />
			<col width="150" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" class="checkall" /></th>
				<th>No.</th>
				<th><a href="#">Employee Name</a></th>
				<th><a href="#">Department</a></th>
				<th><a href="#">Position</a></th>
				<th><a href="#">Work Status</a></th>
				<th><a href="#">Hired Date</a></th>
				<th><a href="#">Regularization Date</a></th>
				<th><a href="#">Resigned Date</a></th>
			</tr>	
		</thead>
		<tbody class="employee_list">
<?php 

      $listcount = count($employee_list);
      if($listcount>=1){
         foreach($employee_list as $list){
            $mi = $list->te_mname=='' ? '' : substr($list->te_mname,0,1).'.';
            $date_started  = $list->tecr_date_started!='0000-00-00' ? $list->tecr_date_started : '------';
            $date_probend  = $list->tecr_probationary_date_ended!='0000-00-00' ? $list->tecr_probationary_date_ended : '------';
            $date_ended = $list->tecr_date_ended!='0000-00-00' ? $list->tecr_date_ended : '------';
            echo '<tr>';
            echo '<td><input class="chkboxlist'.$listcount.'" type="checkbox" value="'.$list->te_idx.'"/></td>';
            echo '<td>'.$listcount.'</td>';
            echo '<td><a href="#modify/id'.$list->te_idx.'">'.$list->te_fname .' '.$mi.' '.$list->te_lname.'</a></td>';
            echo '<td>'.$list->td_dept_name .'</td>';
            echo '<td>'.$list->tp_position .'</td>';
            echo '<td>'.$list->tws_status_name .'</td>';
            echo '<td>'.$date_started .'</td>';
            echo '<td>'.$date_probend .'</td>';
            echo '<td class="last">'.$date_ended.'</td>';
            echo '</tr>';
            
            $listcount--;
         }
      }else{
         echo '<tr><td colspan="8">No Records(s) Found!</td></tr>';
      }
?>     

<input type="hidden" id="chkboxcount" value="<?php echo count($employee_list);?>" />
		</tbody>
	</table>
	<span class="fl nm np del"><a href="#" class="btn_small btn_type_3s"><span id="delbtn">Delete</span></a></span>
<?php if(count($employee_list)!=0){ echo $pager; } ?>
</div>

</div>