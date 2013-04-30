<input type="hidden" id="row" value="<?php echo isset($_GET['row'])?$_GET['row']:'';?>" />
<input type="hidden" id="page_no" value="<?php echo isset($_GET['page'])?$_GET['page']:'';?>" />
<input type="hidden" id="keyword" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>" />

<input type="hidden" id="message" value="<?php echo isset($_GET['message'])?$_GET['message']:'';?>" />
<input type="hidden" id="history_idx" value="<?php echo $history_idx;?>" />
<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<div class="hr-content">
<div class="message-container"></div>
<h2 class="title np fl mb10"><strong class="">Attendance History


<?php 
$fname = isset($employee_info[0]->te_fname) ? ' >> '.$employee_info[0]->te_fname : '';
$lname = isset($employee_info[0]->te_lname) ? $employee_info[0]->te_lname : '';

echo $fname.' '.$lname; 

?>

</strong></h2>
<div class="content np">
	<div class="show_rows fr">
		<form>
			<label>Show Rows</label>
			<select id="history_show_row">
				<option value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option value="50">50</option>
				<option value="100">100</option>
			</select>
		</form>
	</div>
   <span class="fl mb10 np"><a id="addNew" class="btn_small btn_type_1s submitForm"><span>Add Leave/Tardiness</span></a></span>
   <span class="month fr">
      <div class="">
         <form>
            <span class="date_from">
               <label>Period :</label>
               <input type="text" value="<?php echo $date_from;?>" class="input_type_3" id="date_from" name="date_from" readOnly="readOnly" />
               <img src="<?php echo $assets_path;?>site/images/calendar-day.png" class="calendar_icon" />
            </span>
            <span class="date_to">
               <span class="mr10">&ndash;</span>
               <input type="text" value="<?php echo $date_to;?>" class="input_type_3" id="date_to" name="date_to" readOnly="readOnly" />
               <img src="<?php echo $assets_path;?>site/images/calendar-day.png" class="calendar_icon" />
            </span>
            <a href="#" class="btn_small btn_type_2s" id="sort_history_date"><span>View</span></a>
         </form>
      </div>
	</span>
   <table border="0" cellspacing="0" cellpadding="0" class="table_02 ac" id="employee">
      <colgroup>
         <col width="50" />
         <col width="60" />
         <col width="180" />
         <col width="200" />
         <col />
      </colgroup>
      <thead>
         <tr>
            <th><input  class="checkall" type="checkbox" /></th>
            <th>No.</th>
            <th><a href="#">Type</a></th>
            <th><a href="#">Date</a></th>
            <th class="last"><a href="#">Reason</a></th>           
         </tr>
      </thead>
		<tbody class="employee_list">
<?php 

      $listcount = count($lists);
      if($listcount>=1){
         foreach($lists as $list){
           
            $datef = $list->tlt_time_tardy=='' ? $list->tlt_date : $list->tlt_date .' / ' . $list->tlt_time_tardy;
            $hd = $list->tlt_type_count==1 ? '' : ' - Half Day';
            echo '<tr>';
            echo '<td><input class="chkboxlist'.$listcount.'" type="checkbox" value="'.$list->tlt_idx .'"/></td>';
            echo '<td>'.$listcount.'</td>';
            echo '<td><a href="#" class="submitForm" name="'.$list->tlt_idx.'">'.$list->tltt_type .$hd.'</a></td>';
            echo '<td>'.$datef . '</td>';
            echo '<td>'.$list->tlt_reason .'</td>';
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
   <div class="action_btn fl del">
         <a href="#" class="btn_small btn_type_1s" title="Save changes" style="margin:3px"><span id="delbtn">Delete</span></a>
	</div>
<?php if(count($lists)!=0){ echo $pager; } ?>
</div>

</div>