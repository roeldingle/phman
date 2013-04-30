var jcrop_api;
sub_seat_flag = 'add';
var update_idx = '';
jQuery(function ($) {


initJcrop();

function initJcrop()
	{

		$('.requiresjcrop').hide();


		$('#target').Jcrop({
			onRelease:  setcoords.releaseCheck,
			onChange:   setcoords.showCoords,
			onSelect:   setcoords.showCoords,
			onSelect:   setcoords.showDialog,
			onRelease:  setcoords.clearCoords
		}, function () {

			jcrop_api = this;
			$('#can_click,#can_move,#can_size').attr('checked', 'checked');
			$('#ar_lock,#size_lock,#bg_swap').attr('checked', false);
			$('.requiresjcrop').show();

		});

	};


//EVENTS
	$('#animateTo').click(function (e) {
		setcoords.animateTo();
	});
	$('#release').click(function (e) {
		setcoords.release();
	});
	$('#disable').click(function (e) {
		setcoords.disable();
	});
	$('#enable').click(function (e) {
		setcoords.enable();
	});
//END OF EVENTS	

});


var setcoords = {
	
	releaseCheck : function()
		{
			jcrop_api.setOptions({
				allowSelect: true
			});
			$('#can_click').attr('checked', false);
		},		
	showCoords :function(c)
		{
		  $('#x1').val(c.x);
		  $('#y1').val(c.y);
		  $('#x2').val(c.x2);
		  $('#y2').val(c.y2);
		  $('#w').val(c.w);
		  $('#h').val(c.h);
		},
	clearCoords:function(c)
		{
			sub_seat_flag = 'add';
			update_idx = '';
			$('#coords input').val('');
		  	$("#release").hide();
			$('#edit_seat_coords').show();
			$("#animateTo").show();
			$('#set_seat').show();
			$('#set_size').hide();
		},
	showDialog : function ()
		{
			$('#edit_seat_coords').hide();
			setcoords.disable();
			seat_plan.set_seatno();
		},
	animateTo : function ()
		{
			$('#edit_seat_coords').hide();
			
			 var options = {
			 url : urls.ajax_url,
			 type : "post",
			 dataType: 'json',
			 data :{
				mod:"hr|seatplan_model_con|get_seatplan_src"
			 },success : function(response){
				if(response[0].tss_default_left!='' && response[0].tss_default_top!='' && response[0].tss_default_x2!='' && response[0].tss_default_y2!=''){
					jcrop_api.animateTo([response[0].tss_default_left, response[0].tss_default_top, response[0].tss_default_x2, response[0].tss_default_y2]);
				}else{
					alert('Default Size not yet set!');
				}				
			},
			 error : function(){
				console.log('ajax error');
			 }
		  };
		  
		  $.ajax(options);
			
			
			
			
		},
	release : function ()
		{	$('#set_seat').show();
			$('#set_size').hide();
			$('#edit_seat_coords').show();
			sub_seat_flag = 'add';
			update_idx = '';
			jcrop_api.release();
		},
	disable : function ()
		{
			jcrop_api.disable();
			$('#enable').show();
			$('.requiresjcrop').hide();
		},
	enable : function ()
		{
			jcrop_api.enable();
			$('#enable').hide();
			$('.requiresjcrop').show();
		}
}