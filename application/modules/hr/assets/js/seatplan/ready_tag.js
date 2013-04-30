$(document).ready(function(){
  var get_tag = {
	 url : urls.ajax_url,
	 type : "get",
	 dataType: 'json',
	 data :{
		mod:"hr|seatplan_model_con|get_seatplan_coordinates"
	 },success : function(response){
		for (var i in response) {
			var id = String('#'+response[i].tsc_idx);
			var left = String(response[i].tsc_left +'px');
			var top = String(response[i].tsc_top +'px');
			var width = String(response[i].tsc_width +'px');
			var height= String(response[i].tsc_height +'px');
			$(id).css({'position' : 'absolute' , 'left' : left , 'top' : top , 'width': width , 'height' : height});
		}
		
	 },
	 error : function(response){
		console.log(response);
	 }
  };
  
  $.ajax(get_tag);

});