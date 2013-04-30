define([ // Libraries
         'backbone',
         
         // Models
         'sub_modelsPath/hr_management'
         ], 
    function(
    		// Libraries
    		backbone,
    		
    		// Models
    		hr_management
			){
		var _router= Backbone.Router.extend();			
        return {
            formSubmit:function() {
			
            $('#employee_record').validate({
				 errorPlacement: function(error, element) {
					element.parent().append(error);
				 },
            	submitHandler: function(form) {
				/*company record*/
					var emp_image = $("iframe").contents().find('form [name$="save_filename"]').val();
					var position = $('#opt-position').val();
					var department = $('#opt-department').val();
					var emp_id = $('#employee-id').val();
					var current_salary = $('#current-salary').val();
					var lname = $('#last-name').val();
					var fname = $('#first-name').val();
					var mname = $('#middle-name').val();
					var nickname = $('#nick-name').val();
					var address = $('#address').val();
					var prov_address = $('#prov_address').val();
					var home_no = $('#home_no').val();
					var mobile_no = $('#contact-number').val();
					var email = $('#email-add').val();
					var gender = $('[name=gender]:checked').val();
					var status = $('[name=marital]:checked').val();
					var bday = $('#bday').val();
					var status_work = $('#status_work').val();
					var date_start = $('#date_start').val();
					var date_end = $('#date_end').val();
					var date_prob = $('#date_prob').val();
					var emp_type = $('#employment-type').val();
					var sss = $('#sss-num').val();
					var tin = $('#tin-num').val();
					var philhealth = $('#philhealth-num').val();
					var pag_track = $('#pagibigtrack-num').val();
					var pag_mid = $('#pagibigMid-num').val();
					var bank_name = $('#bank_name').val();
					var bank_account = $('#bank_account').val();
					
				/*Educational Background*/	
					var school = $('#school_name').val();
					var school_address = $('#school_location').val();
					var course = $('#course').val();
					var inc_dates = $('#inc_dates').val();
					var year_grad = $('#year_grad').val();
					var certi_deg = $('#certi_deg').val();
					var certi_deg_completed = $('#certi_deg_completed').val();
					
				/*Employment History*/
					var tot_year = $('#tot_year').val();
					var tot_month = $('#tot_month').val();
					var employ_from = $('#employ_from').val();
					var employ_to = $('#employ_to').val();
					var prev_company_name = $('#prev_company_name').val();
					var prev_company_pos = $('#prev_company_pos').val();
					var prev_company_res = $('#prev_company_res').val();
					var prev_company_contact = $('#prev_company_contact').val();
					var prev_company_add = $('#prev_company_add').val();
					var prev_start_salary = $('#prev_start_salary').val();
					var prev_last_salary = $('#prev_last_salary').val();
					var reason_leave = $('#reason_leave').val();
					
				/*Dependents*/
				
					var depen_name_arr = [];	
						$('.depen_name').each(function(){
							depen_name_arr.push($(this).val().replace(/,/g , "="));
						});	
					var depen_name = depen_name_arr.toString();
					
					var depen_bday_arr = [];	
						$('.depen_bday').each(function(){
							depen_bday_arr.push($(this).val());
						});	
					var depen_bday = depen_bday_arr.toString();
					
					var depen_rel_arr = [];	
						$('.depen_rel').each(function(){
							depen_rel_arr.push($(this).val().replace(/,/g , "="));
						});
					var depen_rel = depen_rel_arr.toString();
					
				/*In Case of Emergency*/
					
					var notify_name = $('#notify_name').val();
					var notify_relation = $('#notify_relation').val();
					var notify_no = $('#notify_no').val();
					var notify_no2 = $('#notify_no2').val();
					var notify_add = $('#notify_add').val();
					
        			var fetch           = new hr_management.defAjax();
                    var formdata        = {};
                    formdata['mod']     = 'hr|hr_api|submitForm';
					formdata['flag']    =  flag;
					formdata['emp_image']    =  emp_image;
					formdata['modify_id']    =  modify_id;
					formdata['position']= 	position;
					formdata['department'] = department;
					formdata['emp_id']  = emp_id;
					formdata['current_salary'] = current_salary;
					formdata['lname'] = lname;
					formdata['fname'] = fname;	
					formdata['mname'] = mname;
					formdata['nickname'] = nickname;
					formdata['address'] = address;
					formdata['prov_address'] = prov_address;
					formdata['home_no'] = home_no;
					formdata['mobile_no'] = mobile_no;
					formdata['email'] = email;
					formdata['gender'] = gender;
					formdata['status'] = status;
					formdata['bday'] = bday;
					formdata['status_work'] = status_work;
					formdata['date_start'] = date_start;
					formdata['date_end'] = date_end;
					formdata['date_prob'] = date_prob;
					formdata['emp_type'] = emp_type;
					formdata['sss'] = sss;
					formdata['tin'] = tin;
					formdata['philhealth'] = philhealth;
					formdata['pag_track'] = pag_track;
					formdata['pag_mid'] = pag_mid;
					formdata['bank_name'] = bank_name;
					formdata['bank_account'] = bank_account;
					formdata['school'] = school;
					formdata['school_address'] = school_address;
					formdata['course'] = course;
					formdata['inc_dates'] =	inc_dates;
					formdata['year_grad'] = year_grad;
					formdata['certi_deg'] = certi_deg;
					formdata['certi_deg_completed'] = certi_deg_completed;
					formdata['tot_year'] = tot_year;
					formdata['tot_month'] = tot_month;
					formdata['employ_from'] = employ_from;
					formdata['employ_to'] = employ_to;
					formdata['prev_company_name'] = prev_company_name;
					formdata['prev_company_pos'] = prev_company_pos;
					formdata['prev_company_res'] = prev_company_res;
					formdata['prev_company_contact'] = prev_company_contact;
					formdata['prev_company_add'] = prev_company_add;
					formdata['prev_start_salary'] = prev_start_salary;
					formdata['prev_last_salary'] = prev_last_salary;
					formdata['reason_leave'] = reason_leave;
					formdata['depen_name'] = depen_name;
					formdata['depen_bday'] = depen_bday;
					formdata['depen_rel'] = depen_rel;
					formdata['notify_name'] = notify_name;
					formdata['notify_relation'] = notify_relation;
					formdata['notify_no'] = notify_no;
					formdata['notify_no2'] = notify_no2;
					formdata['notify_add'] = notify_add;
					
					if($('#employ_from').val()>$('#employ_to').val()){
						$('#employ_from').css('border-color','#ff0000');
						$('#employ_to').css('border-color','#ff0000');
					}else{
						fetch.save(null, {
							dataType:'json',
							data:formdata,
							error:	function(model,response){
								console.log('error');
							},
							success:	function(model, response){
								var row_val = Site.getURLParameter('row');
								var row = row_val!=null ? '&row='+row_val : '';
								var url = urls.module_url+'employee?message=saved'+row;
								 Site.page_redirect(url);
							}
						});
					}
    			 }
            });
        }
    }
});