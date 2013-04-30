if($('#usergradeid').val()!=1 && $('#usergradeid').val()!=4 && $('#usergradeid').val()!=9)
{
	$('#addNew').hide();
	$('.del').hide();
	$('input[type=checkbox]').parent().hide();
	$('col[width=40]').hide();
	$('.update_dept').attr('class','');
}

$('#show_rows option[value='+$('#row').val()+']').attr('selected','selected');

var tb_sorter_options = {
            tb_selector_id : 'employee',
            headers: {
                0: { sorter: false },
                1: { sorter: false }
            }
        }
        
Site.init_tb_sorter(tb_sorter_options);


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
		var uri = urls.module_url+'department?keyword='+$('#searchtxt').val();
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
		var uri1 = urls.module_url+'department?'+kvp.join('&');
		var uri = Site.removeURLParam(uri1, 'page')
		Site.page_redirect(uri);
	} else {
		var uri1 = urls.module_url+'department'+kvp.join('?');
		var uri = Site.removeURLParam(uri1, 'page')
		Site.page_redirect(uri);
	} 

});

/*Delete*/

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
				delpop +='<p>'+aidx.length+' Department list(s) are selected. Are you sure you want to delete?</p></br>';
				delpop +='<div class="action_btn fr">';
				delpop +='<a href="#" class="btn_small btn_type_1s" title="Save changes" style="margin:3px"><span id="deletebtn">Delete</span></a>';
				delpop +='<a href="javascript:cancel();"  class="btn_small btn_type_1s" title="Return to Users" style="margin:3px"><span>Cancel</span></a>';
				delpop +='</div>';
				delpop +='</span>';
		
			var adialogbox_options = {
				dialogId : 'delDepartment',
				aoption : {
				title : 'Delete Department',
				width:348.467,
				height: 182.467,
				resizable: false,
				modal : true
				},
				scontent : delpop
			}
       
       Site.dialog_box(adialogbox_options);
            
          $('#deletebtn').click(function () {
			var delDepartment = {
				 url : urls.ajax_url,
				 type : "post",
				 dataType: 'json',
				 data :{
					mod:"hr|department_api|delDepartment",
					idx : aidx
				 },success : function(response){
					 if(response=='denied'){
						$('#delDepartment').dialog('close');
						site.message("Cannot be deleted, One of Department(s) selected is already in use!",$(".message-container"),"warning");
					 }else{
						var url = urls.module_url+'department?message=deleted'
						Site.page_redirect(url);
					}
				 },
				 error : function(response){
					console.log(response);
				 }
			};
			$.ajax(delDepartment);
          });
        }
	
	
	
});

$('#addNew, .update_dept').click(function(){
var delpop = '';
				delpop +='<span>';
				delpop +='<p> Department Name</p><input type="text" id="dept_name" /></br></br></br>';
				delpop +='<div class="action_btn fr">';
				delpop +='<a href="#" class="btn_small btn_type_1s" title="Save changes" style="margin:3px"><span id="submitBtn">Submit</span></a>';
				delpop +='<a href="javascript:cancel();"  class="btn_small btn_type_1s" title="Return to Users" style="margin:3px"><span>Cancel</span></a>';
				delpop +='</div>';
				delpop +='</span>';
				
		var modify_id = $(this).attr('name');		
		if(modify_id!=''){
			var flag = 'update';
			var title = 'Edit';
			
			var getInfo = {
				 url : urls.ajax_url,
				 type : "post",
				 dataType: 'json',
				 data :{
					mod:"hr|department_api|getInfo",
					modify_id : modify_id
				 },success : function(response){
					$('#dept_name').val(response[0].td_dept_name);
				 },
				 error : function(response){
					console.log(response);
				 }
			};
			$.ajax(getInfo);
			
		}else{
		    var flag = 'add';
			var title = 'Add';
		}		
			var adialogbox_options = {
				dialogId : 'Department',
				aoption : {
				title : title+' Department',
				width:348.467,
				height: 182.467,
				resizable: false,
				modal : true
				},
				scontent : delpop
			}
       Site.dialog_box(adialogbox_options);
	   
	   $('span #submitBtn').click(function () {
	   
	   var trim_val = $('#dept_name').val().trim().length;
	   if($('#dept_name').val()=='' || trim_val==0 || $('#dept_name').val().match(/[^a-zA-Z0-9 ]/g)){
			$('#dept_name').css('border-color','#ff0000');
	   }else{
			$('#Department').dialog('close');
				var submitForm = {
					 url : urls.ajax_url,
					 type : "post",
					 dataType: 'json',
					 data :{
						mod:"hr|department_api|submitForm",
						dept_name : $('#dept_name').val(),
						flag : flag,
						modify_id : modify_id
					 },success : function(response){
							var url = urls.module_url+'department?message=saved'
							Site.page_redirect(url);
					 },
					 error : function(response){
						console.log(response);
					 }
				};
				$.ajax(submitForm);
			}
       });
	   
	    $('#dept_name').keyup(function(){
			$('#dept_name').attr('style','');
		});

});

function cancel(){
	$('#Department').dialog('close');
}
