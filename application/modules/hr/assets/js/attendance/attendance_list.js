if($('#usergradeid').val()!=1 && $('#usergradeid').val()!=4 && $('#usergradeid').val()!=9)
{
	$('#addNew').hide();
	$('.del').hide();
	$('input[type=checkbox]').parent().hide();
	$('col[width=50]').hide();
	$('.submitForm').attr('class','');
}

$('#show_rows option[value='+$('#row').val()+']').attr('selected','selected');
$('#history_show_row option[value='+$('#row').val()+']').attr('selected','selected');

/*table sorter for attendance*/
var tb_sorter_options = {
            tb_selector_id : 'employee',
            headers: {
				0: { sorter: false }
            }
        }
        
Site.init_tb_sorter(tb_sorter_options);

/*table sorter for attendance history*/
var tb_sorter_options_history = {
            tb_selector_id : 'history_tbl',
            headers: {
				0: { sorter: false },
				1: { sorter: false },
				4: { sorter: false }
            }
        }
        
Site.init_tb_sorter(tb_sorter_options_history);

/*find the differences of two dates*/
var date_between = new Array();
function y2k(number) { return (number < 1000) ? number + 1900 : number; }	
function padout(number) { return (number < 10) ? '0' + number : number; }
function showDates(startYear,startMonth,startDay,endYear,endMonth,endDay) {
	startDate = new Date(startYear,startMonth - 1,startDay);
	endDate = new Date(endYear,endMonth - 1,endDay);

	for (;;) {
		if (startDate > endDate) {
			return;
		}
		date_between.push(y2k(startDate.getYear()) + '-' + padout(startDate.getMonth() + 1) + '-' + padout(startDate.getDate()));
		startDate = new Date(startDate.getTime() + 1*24*60*60*1000);
	}
}
	/*Export to Excel*/
$('.export_excel').click(function(){

	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
	var row = $('#row').val()!='' ? '&row='+$('#row').val() : '';
	var page = $('#page_no').val()!='' ? '&page='+$('#page_no').val() : '';
	var keyword = $('#keyword').val()!='' ? '&keyword='+$('#keyword').val() : '';

	// console.log('/hr/attendance/export_to_excel?datefrom='+date_from+'&dateto='+date_to+row+page+keyword);
	Site.page_redirect('/hr/attendance/export_to_excel?datefrom='+date_from+'&dateto='+date_to+row+page+keyword);

});

/*Message*/
if($('#message').val()=='saved'){
	site.message("Saved Successfully!",$(".message-container"),"success");
}
if($('#message').val()=='deleted'){
	site.message("Deleted Successfully!",$(".message-container"),"success");
}

/*Search*/
$('#searchbtn').click(function(){
	if($('#searchtxt').val()==''){
		$('#searchtxt').attr('style','border:1px solid;border-color:#ff0000');
	}else{
		var uri = urls.module_url+'attendance?keyword='+$('#searchtxt').val();
		Site.page_redirect(uri);
	}
});

$('#searchtxt').keyup(function(){
	$('#searchtxt').attr('style','');
});

/*Show Rows*/
$('#show_rows').change(function(){
	var row = $('#show_rows').val();
	var key = escape('row'); value = escape(row);
    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; var x; while(i--) 
    {
    	x = kvp[i].split('=');

    	if (x[0]==key)
    	{
    		x[1] = value;
    		kvp[i] = x.join('=');
    		break;
    	}
    }

    if(i<0){
		kvp[kvp.length] = [key,value].join('=');
	}
	
	if(document.URL.indexOf("?") != -1) {
		var uri1 = urls.module_url+'attendance?'+kvp.join('&');
		var uri = Site.removeURLParam(uri1, 'page')
		Site.page_redirect(uri);
	} else {
		var uri1 = urls.module_url+'attendance'+kvp.join('?');
		var uri = Site.removeURLParam(uri1, 'page')
		Site.page_redirect(uri);
	} 

});

$('#history_show_row').change(function(){
	var row = $('#history_show_row').val();
	var key = escape('row'); value = escape(row);
    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; var x; while(i--) 
    {
    	x = kvp[i].split('=');

    	if (x[0]==key)
    	{
    		x[1] = value;
    		kvp[i] = x.join('=');
    		break;
    	}
    }

    if(i<0){
		kvp[kvp.length] = [key,value].join('=');
	}
	
	if(document.URL.indexOf("?") != -1) {
		var uri1 = urls.module_url+'attendance/history?'+kvp.join('&');
		var uri = Site.removeURLParam(uri1, 'page')
		Site.page_redirect(uri);
	} else {
		var uri1 = urls.module_url+'attendance/history'+kvp.join('?history_idx='+$('#history_idx').val()+'&');
		var uri = Site.removeURLParam(uri1, 'page')
		Site.page_redirect(uri);
	} 

});

/*redirect to attendance history with form submit*/
$('.viewhistory').click(function(){

	id = $(this).attr('name');
	var params = {history_idx: id};

	var form = document.createElement('form');
	form.action = urls.module_url+'attendance/history?history_idx='+id;
	form.method = 'post';

	for (var key in params) {
		if (params.hasOwnProperty(key)) {
			var field = document.createElement('input');
			field.type = 'hidden';
			field.name = key;
			field.value = params[key]

			form.appendChild(field);
		}
	}

	document.body.appendChild(form);
	form.submit();


});

/* Show dialog (Add or Modify Leave/Tardiness) */

$('.submitForm').click(function(){
var idx = $(this).attr('name');
	
	var disabled = $('#history_idx').val()!=null ? 'disabled' : '';
	var form = '<form name="tardiness_leave" id="leave_tardiness">\
				<table class="table_form al" border="0">\
				<colgroup>\
					<col width="140px" />\
					<col />\
				</colgroup>\
				<tr><th colspan="2" id="message_leave"></th></tr>\
				<tr>\
					<th><label for="empid">Employee</label></th>\
					<td>\
						<select class="select_type_1 nm np" '+disabled+' id="empid">\
						</select>\
					</td>\
				</tr>\
				<tr>\
					<th><label for="emptype_id">Type</label></th>\
					<td>\
						<select class="select_type_1 nm np" id="emptype_id">\
						</select>\
						<select id="day_type">\
						<option value="1">Whole day</option>\
						<option value="0.5">Half day</option>\
						</select>\
					</td>\
				</tr>\
				<tr>\
					<th>Date</th>\
					<td class="date_leave">\
						<p class="np nm">\
							From : <input type="text" value="" class="input_type_3 nm" id="from_date" name="from_date" readOnly="readOnly" />\
							<img src="'+urls.assets_url+'site/images/calendar-day.png" class="calendar_icon" />\
						</p><br/>\
						<p class="np nm">\
							To : <input type="text" value="" class="input_type_3 nm" id="to_date" name="to_date" readOnly="readOnly" />\
							<img src="'+urls.assets_url+'site/images/calendar-day.png" class="calendar_icon" />\
						</p>\
					</td>\
					<td class="date_tardy" style="display:none">\
						<p class="np nm">\
							<input type="text" value="" class="input_type_3 nm" id="tardy_date" name="tardy_date" readOnly="readOnly" />\
							<img src="'+urls.assets_url+'site/images/calendar-day.png" class="calendar_icon" />\
						</p>\
					</td>\
				</tr>\
				<tr class="tardy_time">\
					<th><label for="time">Time</label></th>\
					<td class="time_text">\
						<select id="hour"></select> : <select id="min"></select><select id="hour_format"><option value="AM">AM</option><option value="PM">PM</option></select>\
					</td>\
				</tr>\
				<tr>\
					<th><label for="reasonL">Reason</label></th>\
					<td>\
						<textarea class="textarea_2" id="reasonL"></textarea>\
					</td>\
				</tr>\
			</table>\
			<ul class="control_buttons np nl">\
				<li><a href="#" class="btn_small btn_type_3s" id="submit_leave_tardiness"><span>Save</span></a></li>\
				<li><a href="#" class="link_1 mt5 fl">Undo Changes</a></li>\
			</ul></form>';
			
			var employeeList = {
			 url : urls.ajax_url,
			 type : "post",
			 dataType: 'json',
			 data :{
				mod:"hr|attendance_api|getEmployee"
			 },success : function(response){
				var emp_name = '';
				for(var i in response){
					var selected = response[i].te_idx==$('#history_idx').val() ? 'selected' : '';
					var mi_s = response[i].te_mname.split('');
					var mi = mi_s[0]!=null ? mi_s[0]+'.' : '';
					emp_name+='<option '+selected+' value="'+response[i].te_idx+'">'+response[i].te_fname+' '+mi+' '+response[i].te_lname+'</option>';
				}
				$('#empid').html(emp_name);
			 },
			 error : function(response){
				console.log(response);
			 }
		};
		$.ajax(employeeList);
		
		var type = {
			 url : urls.ajax_url,
			 type : "post",
			 dataType: 'json',
			 data :{
				mod:"hr|attendance_api|getType"
			 },success : function(response){
				var tl_type = '';
				for(var i in response){
					tl_type+='<option value="'+response[i].tltt_idx+'">'+response[i].tltt_type+'</option>';
				}
				$('#emptype_id').html(tl_type);
			 },
			 error : function(response){
				console.log(response);
			 }
		};
		$.ajax(type);
		
		var hour = new Array();
		var min = new Array();
		
		for(var i=1;i<=12;i++){
			if(i<=9){
				hour.push('0'+i);
			}else{
				hour.push(i);
			}
		}
		
		for(var i=0;i<60;i++){
			if(i<=9){
				min.push('0'+i);
			}else{
				min.push(i);
			}
		}
			var hour_d ='';
				for(var i in hour)
				{
				var selected = hour[i]=='08' ? 'selected' : '';
				hour_d+= '<option value="'+hour[i]+'" '+selected+'>'+hour[i]+'</option>';
				}
			var min_d ='';
				for(var i in min)
				{
				var selected = min[i]=='01' ? 'selected' : '';
				min_d+= '<option value="'+min[i]+'" '+selected+'>'+min[i]+'</option>';
				}

			
		if(idx!=null){
			var modify_flag = 'update';
				var adialogbox_options = {
					dialogId : 'Edit',
					aoption : {
					title : 'Edit Leave/Tardiness',
					width: 500.733,
					resizable: false
					},
					scontent : form
				}
				
				Site.dialog_box(adialogbox_options);
				
				$('.tardy_time').hide();
					var getHistoryInfo = {
						 url : urls.ajax_url,
						 type : "post",
						 dataType: 'json',
						 data :{
							mod:"hr|attendance_api|getHistoryInfo",
							modify_id : idx
						 },success : function(response){
							$('#emptype_id option[value='+response[0].tlt_tltt_type+']').attr('selected','selected');
							$('#tardy_date').val(response[0].tlt_date);
							$('#reasonL').val(response[0].tlt_reason);
							if(response[0].tlt_tltt_type==3){
								$('.tardy_time').show();
								$('#hour').html(hour_d);
								$('#min').html(min_d);
								$('.date_tardy').show();
								$('.date_leave').hide();
								$('#day_type').hide();
								var edit_hour = response[0].tlt_time_tardy.split(':');
								var edit_min = edit_hour[1].split(' ');
								$('#hour option[value='+edit_hour[0]+']').attr('selected','selected');
								$('#min option[value='+edit_min[0]+']').attr('selected','selected');
								$('#hour_format option[value='+edit_min[1]+']').attr('selected','selected');
							}else{
								$('.tardy_time').hide();
								$('.date_tardy').show();
								$('.date_leave').hide();
								$('#day_type').show();
								$('#day_type option[value="'+response[0].tlt_type_count+'"]').attr('selected','selected');
								$('#from_date').val(response[0].tlt_date);
								$('#to_date').val(response[0].tlt_date);
							}
							
						 },
						 error : function(response){
							console.log(response);
						 }
					};
					$.ajax(getHistoryInfo);
		}else{
			var modify_flag = 'add';
				var adialogbox_options = {
					dialogId : 'addNewDialog',
					aoption : {
					title : 'Add Leave/Tardiness',
					width: 500.733,
					resizable: false
					},
					scontent : form
				}
				
				Site.dialog_box(adialogbox_options);
				
				$('.tardy_time').hide();
		}
		
		
	$('#emptype_id').change(function(){
		if($('#emptype_id').val()==3 || idx!=null){
			$('#emptype_id').val()==3 ? $('.tardy_time').show() : $('.tardy_time').hide();
			$('#hour').html(hour_d);
			$('#min').html(min_d);
			$('.date_tardy').show();
			$('.date_leave').hide();
			$('#emptype_id').val()==3 ? $('#day_type').hide() : $('#day_type').show();
		}else if($('#emptype_id').val()==5){
			$('#day_type').hide();
		}else{
			$('.tardy_time').hide();
			$('.date_tardy').hide();
			$('.date_leave').show();
			$('#day_type').show();
		}

	});
	
$('#submit_leave_tardiness').click(function(){

	var dateObj = new Date();
	var month = dateObj.getUTCMonth()+1;
	var day = dateObj.getUTCDate()<10 ? '0'+dateObj.getUTCDate() : dateObj.getUTCDate();
	var year = dateObj.getUTCFullYear();

	var curr_date = year + "-" + month + "-" + day;
	
	if($('#reasonL').val()==''){
		$('#reasonL').css('border-color','#ff0000');
	}
	
	if($('#from_date').val()>$('#to_date').val()){
		alert('Incorrect date range format!');
	}
		
	if($('#emptype_id').val()==3 || idx!=null){
		if($('#tardy_date').val()==''){
			$('#tardy_date').css('border-color','#ff0000');
		}
		var tardy_time = $('#emptype_id').val()!=3 ? '' : $('#hour').val()+':'+$('#min').val()+' '+$('#hour_format').val();
		var date = $('#tardy_date').val();
		var type_count  = idx!=null ? $('#day_type').val() : 1;
	}else{
	
		if($('#from_date').val()==''){
			$('#from_date').css('border-color','#ff0000');
		}else if($('#to_date').val()==''){
			$('#to_date').css('border-color','#ff0000');
		}else{
			var tardy_time = '';
			var from_date = $('#from_date').val().split('-');
			var to_date = $('#to_date').val().split('-');
		
			var sy = from_date[0];
			var sm = from_date[1];
			var sd = from_date[2];
			var ey = to_date[0];
			var em = to_date[1];
			var ed = to_date[2];
			
			showDates(sy,sm,sd,ey,em,ed);

			var date_of_leave = new Array();
			var weekdays = new Array(1,2,3,4,5);
			for(var i in date_between){
				var datenew = date_between[i].split('-');
				var current_date = new Date(datenew[0],datenew[1]-1,datenew[2]);
				$.inArray(current_date.getDay(),weekdays)<0 ? '' : date_of_leave.push(date_between[i]);
			}
			
			var date = date_of_leave.toString();
			var type_count  = $('#day_type').val();
		}
	}
		if($('#tardy_date').val()>curr_date){
			alert('Advance date not allowed!');
		}else if(tardy_time!=null && date!='' && $('#reasonL').val()!=''){
		
			$('#addNewDialog').dialog('close');

			var history_idx = idx!=null ? idx : '';
			
			var submitLt = {
				 url : urls.ajax_url,
				 type : "post",
				 dataType: 'json',
				 data :{
					mod:"hr|attendance_api|submitForm",
					empid : $('#empid').val(),
					type : $('#emptype_id').val(),
					flag : modify_flag,
					modify_id : history_idx,
					tardy : tardy_time,
					date : date,
					type_count : type_count,
					reason : $('#reasonL').val()
				 },success : function(response){
					if(Site.getURLParameter('history_idx')==null){
						var url = urls.module_url+'attendance?message=saved';
					}else{
						var url = urls.module_url+'attendance/history?history_idx='+Site.getURLParameter('history_idx')+'&message=saved';
					}
					Site.page_redirect(url);
				 },
				 error : function(response){
					console.log(response);
				 }
			};
			$.ajax(submitLt);
			
			
		}

});	
	

});


/*show date picker*/
$('.calendar_icon').live('click',function(e){
	var year = new Date();
	var add_year = year.getFullYear()+1;
	$(this).prev().datepicker({
		dateFormat:"yy-mm-dd",
		changeMonth:true,
		changeYear:true,
		yearRange : "2010:"+add_year
	});

	$(this).datepicker('show');
});

/*sorting of date*/
$('#sort_date').click(function(){
	
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
	var row = $('#row').val()!='' ? '&row='+$('#row').val() : '';
	var page = $('#page_no').val()!='' ? '&page='+$('#page_no').val() : '';
	var keyword = $('#keyword').val()!='' ? '&keyword='+$('#keyword').val() : '';
	

	if(date_from <= date_to){
		Site.page_redirect('?datefrom='+date_from+'&dateto='+date_to+row+page+keyword);
	}else{
		site.message("Incorrect range of date!",$(".message-container"),"warning");
	}
	
});

$('#sort_history_date').click(function(){
	
	var date_from = $('#date_from').val();
	var date_to = $('#date_to').val();
	var row = $('#row').val()!='' ? '&row='+$('#row').val() : '';
	var page = $('#page_no').val()!='' ? '&page='+$('#page_no').val() : '';
	id = $('#history_idx').val();
	
	if(date_from <= date_to){
		// var params = {history_idx: id,datefrom: date_from,dateto:date_to};
		// var form = document.createElement('form');
		// form.action = urls.module_url+'attendance/history';
		// form.method = 'post';

		// for (var key in params) {
			// if (params.hasOwnProperty(key)) {
				// var field = document.createElement('input');
				// field.type = 'hidden';
				// field.name = key;
				// field.value = params[key]

				// form.appendChild(field);
			// }
		// }

		// document.body.appendChild(form);
		// form.submit();
		Site.page_redirect('history?history_idx='+id+'&datefrom='+date_from+'&dateto='+date_to+row+page);
	}else{
		site.message("Incorrect range of date!",$(".message-container"),"warning");
	}
	
});

/*delete*/

$('#delbtn').click(function(){

var aidx = new Array();
	for (var i = 1; i <= $('#chkboxcount').val(); i++) {
		if ($('.chkboxlist' + i + ':checked').val()) {
		  aidx.push($('.chkboxlist' + i + ':checked').val());
		}
	}

	    if(aidx.length==0){
			site.message("Please make a selection from the list!",$(".message-container"),"warning");
        }else{
			var delpop = '';
				delpop +='<span>';
				delpop +='<p>'+aidx.length+' History list(s) are selected. Are you sure you want to delete?</p></br>';
				delpop +='<div class="action_btn fr">';
				delpop +='<a href="#" class="btn_small btn_type_1s" title="Save changes" style="margin:3px"><span id="delbtn">Delete</span></a>';
				delpop +='<a href="javascript:cancel();"  class="btn_small btn_type_1s" title="Return to Users" style="margin:3px"><span>Cancel</span></a>';
				delpop +='</div>';
				delpop +='</span>';
		
			var adialogbox_options = {
				dialogId : 'delHistory',
				aoption : {
				title : 'Delete History',
				width:348.467,
				height: 182.467,
				resizable: false
				},
				scontent : delpop
			}
       
       Site.dialog_box(adialogbox_options);
            
          $('span #delbtn').click(function () {
			var delHistory = {
				 url : urls.ajax_url,
				 type : "post",
				 dataType: 'json',
				 data :{
					mod:"hr|attendance_api|delHistory",
					idx : aidx
				 },success : function(response){
						var url = urls.module_url+'attendance/history?history_idx='+$('#history_idx').val()+'&message=deleted';
						Site.page_redirect(url);
				 },
				 error : function(response){
					console.log(response);
				 }
			};
			$.ajax(delHistory);
          });
        }
	
	
	
});