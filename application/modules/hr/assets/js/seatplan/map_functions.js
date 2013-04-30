var table_id = '';
var showdialog = true;
var seat_usage = '';
var flag = '';
var dept_idx = '';
var dept_name = '';
var emp_idx = '';
var seatno = '';

$('.img').live('click',function(){
		$(".ui-dialog-titlebar").show();
		showdialog = true;
		table_id = $(this).attr("id");
		ajaxres.getseatno(table_id);
});

$('.img').bind({
	mouseenter : function(){
		showdialog = false;
		table_id = $(this).attr("id");
		ajaxres.getseatno(table_id);
		var p = $(this).position();
		$('.seat_info').dialog({
		  autoOpen: false,
		  position: [p.left+50,p.top],
		  resizable: false,
		  minHeight: 20,
		  width : 250
		}); 
		$('.seat_info').dialog("open");
		$('.seat_form').hide();
		setTimeout("$('#loader').fadeOut();",500);
		setTimeout("$('.seat_form').fadeIn();",600);
	},
	mousemove : function(event){
	$(".ui-dialog-titlebar").hide();
	  $('.seat_info').dialog("option","position", {
		 my: "left",
		 at: "right",
		 of: event,
		 offset: "50 50"
	  });
	},
	mouseleave : function(){
		$('.seat_form').hide();
		$('#loader').show();
		$('.seat_info').dialog('close');
	}
});

if($('#usergradeid').val()!=1 && $('#usergradeid').val()!=4)
{
	$('.img').unbind('click');
}


$('#department_list').change(function(){
	ajaxres.emp_choice();
});

$('#submitForm').click(function(){
	
	  var options = {
		 url : urls.ajax_url,
		 type : "post",
		 data :{
			mod:"hr|seatplan_model_con|submitDetail",
			flag : flag,
			seat_no : seatno,
			seat_usage : $('#seat_usage').val(),
			emp_idx : $('#employee_list').val(),
			seat_coords_id : table_id
		 },success : function(response){
			var uri = urls.module_url+'seat_plan/map?message=saved';
			Site.page_redirect(uri);
		 },
		 error : function(){
			console.log('ajax error');
		 }
	  };
	  
	  $.ajax(options);
	
	
});

$('#seat_usage').change(function(){
	if($('#seat_usage').val()==1 || $('#seat_usage').val()==0 || $('#seat_usage').val()==2 || flag=='update_usage'){
		$('.emp_sit_detail').hide();
	}else{
		$('.emp_sit_detail').show();
	}
});


var ajaxres = {
    /* get seat no */
	getseatno : function(e){
		var get_seatno = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_seatno",
				seat_idx : e
			 },success : function(get_seatno_response){
				seatno = get_seatno_response[0].tsc_seat_no;
				$('#seat-number').val(seatno);
				$('#seat_no').text(seatno);
				ajaxres.checkusedseat(seatno);
			 },
			 error : function(get_seatno_response){
				console.log(get_seatno_response);
			 }
		};
		$.ajax(get_seatno);
	},
	/* get department using employee id */
	get_dept : function(e){
		var get_dept = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_dept",
				emp_id : e
			 },success : function(get_dept_response){
				dept_idx = get_dept_response != '' ? get_dept_response[0].td_idx : '';
				dept_name = get_dept_response != '' ? get_dept_response[0].td_dept_name : '--------';
				ajaxres.dept_choice(dept_idx);
				$('#department').text(dept_name);
			 },
			 error : function(get_dept_response){
				console.log(get_dept_response);
			 }
		};
		  
		$.ajax(get_dept);
	
	},
	/* get employee info employee idx */
	get_emp_info : function(e){
		var get_einfo = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_einfo",
				emp_id : e
			 },success : function(get_einfo_response){
					var employee_name = get_einfo_response != "" ? get_einfo_response[0].te_lname+', ' + get_einfo_response[0].te_fname : '--------';
					$('#employee').text(employee_name);
			 },
			 error : function(get_einfo_response){
				console.log(get_einfo_response);
			 }
		};
		  
		$.ajax(get_einfo);
	
	},
	/* check seat availability */
	checkusedseat : function(e){
	
		var check_seat_used = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|check_seat",
				seatnum :e
			 },success : function(check_seat_used_res){
				emp_idx = check_seat_used_res != '' ? check_seat_used_res[0].ts_te_idx : '';
				ajaxres.get_dept(emp_idx);
				seat_usage = check_seat_used_res[0].tsc_seat_usage;
				if(seat_usage=='0'){
					$('#availability').text('Not Available');
				}else if(seat_usage=='1'){
					$('#availability').text('Vacant PC');
				}else if(seat_usage=='2'){
					$('#availability').text('Vacant Table');
				}else if(seat_usage=='3'){
					$('#availability').text('Used');
				}
				ajaxres.get_emp_info(emp_idx);
			 },
			 error : function(check_seat_used_res){
				console.log(check_seat_used_res);
			 }
		  };
		  
		$.ajax(check_seat_used);
	},
	/* get department list */
	dept_choice : function(e){
	$('#department_list').empty();
		var choose_dept = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_department"
			 },success : function(deparment_list_response){
				var dpt_list ='';
				for(var li in deparment_list_response){
					var selected = deparment_list_response[li].td_idx == dept_idx ? ' selected ' : '';
					dpt_list += '<option value="' + deparment_list_response[li].td_idx+'"'+selected+'>' + deparment_list_response[li].td_dept_name +'</option>';
				}
				$('#department_list').append(dpt_list);
				ajaxres.emp_choice();
			 },
			 error : function(deparment_list_response){
				console.log(deparment_list_response);
			 }
		  };
		  
		$.ajax(choose_dept);
	},
	/* get employee list */	
	emp_choice : function(e){
		$('#employee_list').empty();
		var choose_emp = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_emp",
				dept_id : $('#department_list').val()
			 },success : function(response){
			 	// console.log(emp_idx);
				var employee_list ='';
				for(var li in response){
					var selected = response[li].te_idx == emp_idx ? ' selected ' : '';
					employee_list += '<option value="' + response[li].te_idx+'"'+selected+'>' + response[li].te_lname +', '+ response[li].te_fname +'</option>';
				}
				$('#employee_list').append(employee_list);
				// $('#employee').text($('#employee_list id:'+emp_idx).text());
				
				 if(showdialog==true){
						if(seat_usage==3){
							flag = 'update';
							$('.set_seatinfo').dialog({
								title : 'Modify Detail',
								resizable : false,
								modal : true
							});
						}else if(seat_usage==1){
							// $('.sitting_usage').hide();
							flag = 'add';
							$('.set_seatinfo').dialog({
								title : 'Add Detail',
								resizable : false,
								modal : true
							});
						}else{
							flag = 'update_usage';
							$('.set_seatinfo').dialog({
								title : 'Edit Availability',
								resizable : false,
								modal : true
							});
							$('#seat_usage option[value=3]').hide();
							$('#seat_usage option[value='+seat_usage+']').attr('selected','selected');
							$('.emp_sit_detail').hide();
						}
				}
				
			 },
			 error : function(response){
				console.log(response);
			 }
		  };
		  
		$.ajax(choose_emp);
	}
}