<input type="hidden" id="row" value="<?php echo isset($_GET['row'])?$_GET['row']:'50';?>" />
<input type="hidden" id="page_no" value="<?php echo isset($_GET['page'])?$_GET['page']:'';?>" />
<input type="hidden" id="keyword" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:'';?>" />

<input type="hidden" id="message" value="<?php echo isset($_GET['message'])?$_GET['message']:'';?>" />
<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<div class="hr-content">
<div class="message-container"></div>
<h2 class="title np fl mb10"><strong class="">Attendance Record</strong></h2>
<div class="content np">
	<div class="search_01 fr">
		<form action="#" method="post">
			<label>Search Employee</label>
			<input type="text" value="<?php echo $keyword; ?>" class="input_type_4" id="searchtxt"/>
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
	<span class="fl mb10 np"><a href="#" id="addNew" class="btn_small btn_type_1s submitForm"><span>Add Leave/Tardiness</span></a></span>
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
            <a href="#" class="btn_small btn_type_2s" id="sort_date"><span>View</span></a>
         </form>
      </div>
	</span>
   <table border="0" cellspacing="0" cellpadding="0" class="table_02 ac" id="employee">
      <colgroup>
         <col width="60" />
         <col />
         <col width="200" />
         <col width="200" />
         <col width="200" />
         <col width="200" />
         <col width="200" />
      </colgroup>
      <thead>
         <tr>
            <th>No.</th>
            <th><a href="#">Employee Name</a></th>
            <th><a href="#">Tardiness</a></th>
            <th><a href="#">Vacation Leave</a></th>
            <th><a href="#">Sick Leave</a></th>
            <th><a href="#">LWOP</a></th>            
            <th class="last"><a href="#">AWOL</a></th>            
         </tr>
      </thead>
		<tbody class="employee_list">
<?php 

      $listcount = count($lists);
      if($listcount>=1){
         foreach($lists as $list){   
            $mi = $list->te_mname=='' ? '' : substr($list->te_mname,0,1).'.';
            $tardy = $list->tardy != '' ? $list->tardy : '0';
            $vl =    $list->vl != '' ? $list->vl : '0';
            $sl =  $list->sl != '' ? $list->sl : '0';
            $lwop =  $list->lwop != '' ? $list->lwop : '0';
            $awol =  $list->awol != '' ? $list->awol : '0';
            
            echo '<tr>';
            echo '<td>'.$listcount.'</td>';
            echo '<td><a href="#" name="'.$list->te_idx.'" class="viewhistory">'.$list->te_fname .' '.$mi.' '.$list->te_lname.'</a></td>';
            echo '<td>'. $tardy .'</td>';
            echo '<td>'. $vl .'</td>';
            echo '<td>'. $sl .'</td>';
            echo '<td>'. $lwop .'</td>';
            echo '<td class="last">'. $awol .'</td>';
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
<?php if(count($lists)!=0){
 echo $pager; 
 echo '<a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_attendance_list"><span>Export</span></a>';
 } ?>
</div>

</div>