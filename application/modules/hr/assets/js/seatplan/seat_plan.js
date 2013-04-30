jQuery(function ($) {
	$(".seatplan_list").hide();
	$("#release").hide();
	$('#coords').hide();
	
	if($('#usergradeid').val()!=1 && $('#usergradeid').val()!=4 && $('#usergradeid').val()!=9)
		{
			$('.setting_toggle').hide();
			$('.subtext').hide();
		}
});

/* FUNCTIONS */
var seat_plan = {
	
	set_seatno:function(){
		
		$("#release").show();
		
		 if(sub_seat_flag != 'default_size'){ 
			var ui_title = 'Set seat information';
		 }else{
			var ui_title = 'Set Default Table Size';
			$('#set_seat').hide();
			$('#set_size').show();
			$('#set_x1').val($('#x1').val());
			$('#set_y1').val($('#y1').val());
			$('#set_x2').val($('#x2').val());
			$('#set_y2').val($('#y2').val());
			
			$('#release').show();
			$('#animateTo').hide();
			$('#edit_seat_coords').hide();
		}
  
		$('.set_seatno').dialog({
			title : ui_title,
			resizable : false,
			close: function() {
                setcoords.enable();
              }
		});
		
		
	}
}

/*EVENTS*/

$('.t_settings, .settings_icon').live('click',function(){
	$(".seatplan_list").slideToggle();
});
$('#animateTo').live('click',function(){
	$('.set_seatno').dialog('close');
	$("#release").show();
	$("#animateTo").hide();
});
$('#release').live('click',function(){
	$('.set_seatno').dialog('close');
	$("#release").hide();
	$("#animateTo").show();
});

$('#upload_new_map').live('click',function(){
	$(".seatplan_list").hide();
	$('.upload_dialog').dialog({
		title : 'Upload New Map',
		resizable : false,
		modal:false
	});

});

$('#submit_seatno').live('click',function(){

	var match_no = /^\d*\.?\d*$/;
	if(($('#seat_no').val()!='' || sub_seat_flag=='default_size') && match_no.test($('#seat_no').val())){
	// if(match_no.test($('#seat_no').val())){
	  var options = {
		 url : urls.ajax_url,
		 type : "post",
		 data :{
			mod:"hr|seatplan_model_con|save_coords",
			seatno : $('#seat_no').val(),
			left : $('#x1').val(),
			top : $('#y1').val(),
			x2 : $('#x2').val(),
			y2 : $('#y2').val(),
			width : $('#w').val(),
			height : $('#h').val(),
			usage : $('#seat_usage').val(),
			sub_seat_flag : sub_seat_flag,
			update_idx : update_idx
		 },success : function(response){
				setcoords.release();
				$('.set_seatno').dialog('close');
				$('#seat_no').val('');
				var currSrc = $("#target").attr("src");
				$("#target").attr("src", currSrc);
				sub_seat_flag!='default_size' ? site.message("Saved Successfully!",$(".message-container"),"success") : '';
				setTimeout("$('.message-container').empty();",2500);
		 },
		 error : function(){
			site.message("Seat no. already in the list!",$(".message-container"),"warning")
		 }
	  };
	  
	  $.ajax(options);
  }else{
	$('#seat_no').css('border-color','#ff0000');
  }

});

$('#seat_no').keyup(function(){
	$(this).css('border-color','');
})



$('.cancel').live('click',function(){
	$('.set_seatno').dialog('close');
	setcoords.enable();
});

$('.viewas').live('click',function(){

 	id = $(this).attr('id');
	var params = {viewas: id};

	var form = document.createElement('form');
	form.action = urls.module_url+'seat_plan';
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

$('#edit_seat_coords').live('click',function(){

		$('.edit_seatno').dialog({
			title : 'Edit Seat Plan Position',
			resizable : false,
			modal : true
		});
		
		
		var options = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_seatplan_coordinates",
			 },success : function(response){
				var seatno_option = '';	
					for (var n in response) {
						seatno_option +='<option value="'+response[n].tsc_idx+'">'+response[n].tsc_seat_no+'</option>';
					}
					$('#select_seatno').html(seatno_option);
			 },
			 error : function(){
				console.log('ajax error');
			 }
		};
	  
	  $.ajax(options);
		
});

$('#submit_edit_seat').live('click',function(){
$('.edit_seatno').dialog('close');
sub_seat_flag = 'update';
update_idx = $('#select_seatno').val();

		var options = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_seatno",
				seat_idx : update_idx
			 },success : function(response){
				// console.log(response);
				$('#seat_no').val(response[0].tsc_seat_no);
				
					jcrop_api.animateTo([response[0].tsc_left, response[0].tsc_top, response[0].tsc_x2, response[0].tsc_y2]);
					setcoords.showDialog();
					setcoords.enable();
				
			 },
			 error : function(){
				console.log('ajax error');
			 }
		};
	  
	  $.ajax(options);	

});

$('#submit_del_seat').live('click',function(){
$('.edit_seatno').dialog('close');
var del_idx= $('#select_seatno').val();
$('.alert_msg').dialog({
	title : 'Delete Seatplan No.',
	resizable : false,
	modal : true
});
$('#alert_message').text('Are you you want to delete this?');

$('#alert_submit').attr('id','del_seatno');

$('#del_seatno').click(function(){

		var options = {
			 url : urls.ajax_url,
			 type : "get",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|del_coords",
				seat_idx : del_idx
			 },success : function(response){
				$('.alert_msg').dialog('close');
				var currSrc = $("#target").attr("src");
				$("#target").attr("src", currSrc);
				site.message("Deleted Successfully!",$(".message-container"),"success");
			 },
			 error : function(){
				console.log('ajax error');
			 }
		};
	  
	  $.ajax(options);	
});

});

$('.reset').live('click',function(){
	$(this).closest('div').dialog('close');
})

$('#set_dt').live('click',function(){
	sub_seat_flag = 'default_size';
	jcrop_api.animateTo([42, 32, 60, 70]);
})

$('#submit_upload').live('click',function(){
	$('#seatplan_upload').val()=='' ? $('.core-fileupload-upload-list').append('<ul class="error_msg"><li style="color:#ff0000" align="center">Please Select a file!</li></ul>') : '';
	
	if($('#seatplan_upload').val()!='' && $('#map-name').val()!=''){
		$('.alert_msg').dialog({
			title : 'WARNING!',
			resizable : false,
			modal : true
		});
	 }else{
		$('#map-name').val()=='' ? $('#map-name').css('border-color','#ff0000') : '';
	 }
});

$('#alert_submit').live('click',function(){
	$('#test-form').submit();
});

$('[name=core-file]').live('click',function(){
	$('.error_msg').remove();
})

$('#map-name').keyup(function(){
	$(this).css('border-color','');
})
