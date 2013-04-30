define([
        // Libraries
        'backbone',
        
        // Templates
        'text!sub_tmplsPath/tmpl_form.html',

		// Models
        'sub_modelsPath/hr_management',
        
        // Helpers
        'sub_helpersPath/employee_helper'
    ], 
    function(
        // Libraries
        backbone,
        
        // Templates
        form,

        // Models
        hr_management,
        
        //Helpers
        employee_helper
    ){
        return {
            form_render: Backbone.View.extend({
            	initialize: function(){
            		_.bindAll(this, 'render');
            	    
                    this.render();
            	},
            	el:'.hr-content',
            	events: {
                    'change [name=work_status]' : 'statusManipulate',
                    'change [name=employment_type]' : 'employmentManipulate',
                    'click .calendar_icon' : 'dateManipulate',
					'click #save_form' : 'saveForm',
					'click .export_word' : 'exportWord',
					'click .add_depen' : 'add_dependentsFn',
					'click .rmv_depen' : 'remove_dependentsFn',
					'click #reset_depen' : 'reset_depenFn',
					'click #backList' : 'backListFn'
					
                },
                render:function(){
				
                	var fetch           = new hr_management.defAjax();
                    var formdata        = {};
                    formdata['mod']     = 'hr|hr_api|getFormData';
                	fetch.fetch({
                		data:formdata,
                        error:	function(model,response){
                            console.log('error');
                        },
                        success:	function(model, response){
							var usergradeid = response.usergradeid;
                            var work_status		= response.work_status;
                            var employment_type = response.employment_type;
                            var position = response.position;
                            var department = response.department;
							var emp = [];
							
							var parsedTemplate 	= _.template(form, {
								work_status: work_status,
								employment_type: employment_type,
								department:department,
								position:position,
								assets_path: urls.assets_url,
								module_url:urls.module_url,
								modify_flag : flag
							});
							$('.hr-content').html(parsedTemplate);
						
							if(flag=='update'){
								var fetch           = new hr_management.defAjax();
								var formdata        = {};
								formdata['mod']     = 'hr|hr_api|getEmployee';
								formdata['emp_id']  = modify_id;
								fetch.fetch({
									data:formdata,
									error:	function(model,response){
										console.log('error');
									},
									success:	function(model, response){
										var emp_info = response.employee_info;
	
										if(emp_info[0].te_image_path!=''){
										
											var emp_img_path = emp_info[0].te_image_path;
											setTimeout(function(){
												if(Site.fileExists(urls.getfile_url+'hr/uploads/emp_image/'+emp_img_path)==true){
													$("iframe").contents().find("form [class$='emp_img_con']").html('<img src="'+urls.getfile_url+'hr/uploads/emp_image/'+emp_img_path+'" style="position:absolute;width:170px;height:170px;z-index:99"/>');
												}
												$("iframe").contents().find("form [name$='save_filename']").val(emp_img_path);
											},500);
										
										}
									
										var pos_opt = '';
											for (var i in position){
												var selected = position[i].tp_idx == emp_info[0].tecr_tp_idx ? 'selected' : '';
												pos_opt +='<option '+selected+' value="'+position[i].tp_idx+'">'+position[i].tp_position+'</option>';
											}
										$('#opt-position').html(pos_opt);
										
										var dept_opt = '';
											for (var i in department){
												var selected = department[i].td_idx == emp_info[0].tecr_td_idx ? 'selected' : '';
												dept_opt +='<option '+selected+' value="'+department[i].td_idx+'">'+department[i].td_dept_name+'</option>';
											}
										$('#opt-department').html(dept_opt);
										
										$('#usergradeid').val()!=1 && $('#usergradeid').val()!=9 ? $('.restricted').hide() : '';
										$('#emp_id').val(emp_info[0].te_idx);
										$('#employee-id').val(emp_info[0].te_employee_id);
										$('#current-salary').val(emp_info[0].tecr_basic_salary==0 ? '':emp_info[0].tecr_basic_salary);
										$('#last-name').val(emp_info[0].te_lname);
										$('#first-name').val(emp_info[0].te_fname);
										$('#middle-name').val(emp_info[0].te_mname);
										$('#nick-name').val(emp_info[0].te_nickname)
										$('#address').val(emp_info[0].te_address);
										$('#prov_address').val(emp_info[0].te_prov_address);
										$('#home_no').val(emp_info[0].te_home_no);
										$('#contact-number').val(emp_info[0].te_contact_number);
										$('#email-add').val(emp_info[0].te_email_address);
										$("input[value='"+emp_info[0].te_gender+"']").attr('checked', true);
										$("input[value='"+emp_info[0].te_status+"']").attr('checked', true);
										$('#bday').val(emp_info[0].te_bdate);

										
										var workstat_opt = '';
											for (var i in work_status){
												var selected = work_status[i].tws_idx == emp_info[0].tecr_tews_work_status ? 'selected' : '';
												workstat_opt +='<option '+selected+' value="'+work_status[i].tws_idx+'">'+work_status[i].tws_status_name+'</option>';
											}
										$('#status_work').html(workstat_opt);
										
										
										if (emp_info[0].tecr_tews_work_status == 000001) {
											$('.date_started').show();
											$('.date_ended').hide();
											$('.probation_ended').hide();
										}else if (emp_info[0].tecr_tews_work_status == 000002) {
											$('.date_started').show();
											$('.date_ended').hide();
											$('.probation_ended').show();
										}else if (emp_info[0].tecr_tews_work_status == 000003) {
											$('.date_started').show();
											$('.date_ended').show();
											$('.probation_ended').show();
										}else if (emp_info[0].tecr_tews_work_status == 000004) {
											$('.date_started').show();
											$('.date_ended').show();
											$('.probation_ended').hide();
										}
										else {
											$('.date_started').show();
											$('.date_ended').hide();
											$('.probation_ended').hide();
										}
										
										$('#date_start').val(emp_info[0].tecr_date_started);
										var date_end = emp_info[0].tecr_date_ended!='0000-00-00' ? emp_info[0].tecr_date_ended : '';
										$('#date_end').val(date_end);
										var date_probend = emp_info[0].tecr_probationary_date_ended!='0000-00-00' ? emp_info[0].tecr_probationary_date_ended : '';
										$('#date_end').val(date_end);
										$('#date_prob').val(date_probend);
										
										
										var emp_type = '';
											for (var i in employment_type){
												var selected = employment_type[i].tet_idx == emp_info[0].tecr_tet_idx ? 'selected' : '';
												emp_type +='<option '+selected+' value="'+employment_type[i].tet_idx+'">'+employment_type[i].tet_type_name+'</option>';
											}
										$('#employment-type').html(emp_type);
										
										if(emp_info[0].tecr_tet_idx == 000001 || emp_info[0].tecr_tet_idx == '' ) {
											$('.experienced').hide();
											$('.requirements').show();
											$('.sss-label').find('.necessary').hide();
											$('#sss-num').removeClass('required');
											$('#sss-num').removeClass('error');
											$('#sss-num').nextAll().remove();
											$('.tin-label').find('.necessary').hide();
											$('#tin-num').removeClass('required');
											$('#tin-num').removeClass('error');
											$('#tin-num').nextAll().remove();
											$('.philhealth-label').find('.necessary').hide();
											$('#philhealth-num').removeClass('required');
											$('#philhealth-num').removeClass('error');
											$('#philhealth-num').nextAll().remove();
											$('.pagibig-label').find('.necessary').hide();						
											$('#pagibigtrack-num').removeClass('required');
											$('#pagibigtrack-num').removeClass('error');
											$('#pagibigtrack-num').nextAll().remove();						
											$('#pagibigMid-num').removeClass('required');
											$('#pagibigMid-num').removeClass('error');
											$('#pagibigMid-num').nextAll().remove();
										}else if (emp_info[0].tecr_tet_idx == 000002){
											$('.experienced').show();
											$('.sss-label').find('.necessary').show();
											$('#sss-num').addClass('required');
											$('.tin-label').find('.necessary').show();
											$('#tin-num').addClass('required');
											$('.philhealth-label').find('.necessary').show();
											$('#philhealth-num').addClass('required');
											$('.pagibig-label').find('.necessary').show();
											$('#pagibigMid-num').addClass('required');
											$('#pagibigtrack-num').addClass('required');
										}else{
											$('.experienced').hide();
										}
										
										
										$('#sss-num').val(emp_info[0].tecr_sss);
										$('#tin-num').val(emp_info[0].tecr_tin);
										$('#philhealth-num').val(emp_info[0].tecr_philhealth);
										$('#pagibigtrack-num').val(emp_info[0].tecr_pag_track);
										$('#pagibigMid-num').val(emp_info[0].tecr_pag_midno);
										$('#bank_name').val(emp_info[0].tecr_bank_name);
										$('#bank_account').val(emp_info[0].tecr_bank_account_number);
								        $('#school_name').val(emp_info[0].te_school);
										$('#school_location').val(emp_info[0].te_school_add);
										$('#course').val(emp_info[0].te_course);
										$('#inc_dates').val(emp_info[0].te_inc_dates);
										$('#year_grad').val(emp_info[0].te_year_grad);
										$('#certi_deg').val(emp_info[0].te_certi_deg);
										$('#certi_deg_completed').val(emp_info[0].te_certi_deg_completed);
										$('#tot_year').val(emp_info[0].teeh_tot_year);
										$('#tot_month').val(emp_info[0].teeh_tot_month);
										$('#employ_from').val(emp_info[0].teeh_employ_from);
										$('#employ_to').val(emp_info[0].teeh_employ_to);
										$('#prev_company_name').val(emp_info[0].teeh_company_name);
										$('#prev_company_pos').val(emp_info[0].teeh_position);
										$('#prev_company_res').val(emp_info[0].teeh_responsibility);
										$('#prev_company_contact').val(emp_info[0].teeh_contact);
										$('#prev_company_add').val(emp_info[0].teeh_address);
										$('#prev_start_salary').val(emp_info[0].teeh_salary_start==0 ? '':emp_info[0].teeh_salary_start);
										$('#prev_last_salary').val(emp_info[0].teeh_salary_last==0? '':emp_info[0].teeh_salary_last);
										$('#reason_leave').val(emp_info[0].teeh_reason_leave);

										$('#notify_name').val(emp_info[0].te_notify_name);
										$('#notify_relation').val(emp_info[0].te_notify_rel);
										$('#notify_no').val(emp_info[0].te_notify_no);
										$('#notify_no2').val(emp_info[0].te_notify_no2);
										$('#notify_add').val(emp_info[0].te_notify_add);
									
									var splited_dname = emp_info[0].tecr_depen_name.split(',');
									var splited_dbday = emp_info[0].tecr_depen_bday.split(',');
									var splited_drel = emp_info[0].tecr_depen_relation.split(',');

									var dependents = '';
										for(var i=0 ; i<=splited_dname.length-1; i++){
										dependents +='<tr class="dependents">\
														<td>\
															<input type="text" class="input_type_5 nm depen_name" name="depen_name[]" value="'+splited_dname[i].replace(/=/g , ",")+'"/>\
														</td>\
														<td>\
															<input type="text" value="'+splited_dbday[i]+'" class="input_type_1 depen_bday" style="width:180px" name="depen_bday[]" readOnly="readOnly"/>\
															<img src="'+urls.assets_url+'site/images/calendar-day.png" class="calendar_icon" />\
														</td>\
														<td>\
															<input type="text" value="'+splited_drel[i].replace(/=/g , ",")+'" class="input_type_5 nm depen_rel" name="depen_rel[]"/>\
														</td>\
														</tr>';
										}				
									$('.dependents_child').html(dependents);	
									
										$('#word_export').html('<a href="javascript:void(0)" class="btn_export_word fr export_word" title="Export to Word"></a>');
									}
								});
								
							}
												
                            $('.date_started').hide();
                    		$('.date_ended').hide();
                    		$('.probation_ended').hide();
                    		
							setTimeout(function(){
							if(flag=='update'){
								$('.dependents').siblings().append('<td><img class="rmv_depen" style="cursor:pointer;" src="'+urls.assets_url+'site/images/btn_remove.png" /></td>');
							}	
								$('.dependents').last().append('<td><img class="add_depen" style="cursor:pointer;" src="'+urls.assets_url+'site/images/btn_add.png" /></td>');
								$('.dependents [class=rmv_depen]').parent().last().remove();
							},500);
							
							
							if(usergradeid!=1 && usergradeid!=4 && usergradeid!=9){
								$('input').attr('readonly','readonly');
								$('input[type=radio]').attr('disabled','disabled');
								$('select').attr('disabled','disabled');
								setTimeout(function(){
									$('.rmv_depen').hide();
									$('.add_depen').hide();
									$('.calendar_icon').hide();
								},1000);
								$('#employee-label').hide();
								$('.info').hide();
								$('#save_form').hide();
							}
							
                        }
                    });
                	
                },
                statusManipulate:function(e) {
                	if ($('[name=work_status]').val() == 1) {
                		$('.date_started').show();
                		$('.date_ended').hide();
                		$('.probation_ended').hide();
						$('#date_end').val('');
						$('#date_prob').val('');
                	}else if ($('[name=work_status]').val() == 2) {
                		$('.date_started').show();
                		$('.date_ended').hide();
						$('#date_end').val('');
						$('#date_prob').val('');
                		$('.probation_ended').show();
                	}else if ($('[name=work_status]').val() == 3) {
                		$('.date_started').show();
                		$('.date_ended').show();
                		$('.probation_ended').show();
						$('#date_end').val('');
						$('#date_prob').val('');
                	}else if ($('[name=work_status]').val() == 4) {
                		$('.date_started').show();
                		$('.date_ended').show();
                		$('.probation_ended').hide();
						$('#date_end').val('');
						$('#date_prob').val('');
                	}else {
                		$('.date_started').show();
                		$('.date_ended').hide();
                		$('.probation_ended').hide();
                	}
                },
                dateManipulate:function(e) {
					var year = new Date();
					var add_year = year.getFullYear()+3;
					$(e.currentTarget).prev().datepicker({
						dateFormat:"yy-mm-dd",
						changeMonth:true,
						changeYear:true,
						yearRange : "1940:"+add_year
					});
					
					$(e.currentTarget).datepicker('show');
                },
                employmentManipulate:function(e) {
                	if($('#employment-type').val() == 1 || $('#employment-type').val() == '' ) {
						$('.experienced').hide();
						$('.requirements').show();
                		$('.sss-label').find('.necessary').hide();
						$('#sss-num').removeClass('required');
						$('#sss-num').removeClass('error');
						$('#sss-num').nextAll().remove();
                		$('.tin-label').find('.necessary').hide();
						$('#tin-num').removeClass('required');
						$('#tin-num').removeClass('error');
						$('#tin-num').nextAll().remove();
                		$('.philhealth-label').find('.necessary').hide();
						$('#philhealth-num').removeClass('required');
						$('#philhealth-num').removeClass('error');
						$('#philhealth-num').nextAll().remove();
						$('.pagibig-label').find('.necessary').hide();						
						$('#pagibigtrack-num').removeClass('required');
						$('#pagibigtrack-num').removeClass('error');
						$('#pagibigtrack-num').nextAll().remove();						
						$('#pagibigMid-num').removeClass('required');
						$('#pagibigMid-num').removeClass('error');
						$('#pagibigMid-num').nextAll().remove();
                	}else if ($('#employment-type').val() == 2){
						$('.experienced').show();
                		$('.sss-label').find('.necessary').show();
                		$('#sss-num').addClass('required');
                		$('.tin-label').find('.necessary').show();
                		$('#tin-num').addClass('required');
                		$('.philhealth-label').find('.necessary').show();
                		$('#philhealth-num').addClass('required');
						$('.pagibig-label').find('.necessary').show();
						$('#pagibigMid-num').addClass('required');
						$('#pagibigtrack-num').addClass('required');
                	}else{
						$('.experienced').hide();
					}
                },
				
				add_dependentsFn: function(){
					var dependent_length = $('.dependents').length+1;
					var depent_tpl = '\
						<tr class="dependents">\
							<td>\
								<input type="text" class="input_type_5 nm depen_name" name="depen_name[]"/>\
							</td>\
							<td>\
								<input type="text" value="" class="input_type_1 depen_bday" style="width:180px" name="depen_bday[]" readOnly="readOnly"/>\
								<img src="'+urls.assets_url+'site/images/calendar-day.png" class="calendar_icon" />\
							</td>\
							<td>\
								<input type="text" class="input_type_5 nm depen_rel" name="depen_rel[]"/>\
							</td>\
							<td>\
							<img class="add_depen" style="cursor:pointer;" src="'+urls.assets_url+'site/images/btn_add.png" />\
							<img class="rmv_depen" style="cursor:pointer;" src="'+urls.assets_url+'site/images/btn_remove.png" />\
							</td>\
						</tr>\
					';
					
						$('.dependents_table tbody').append(depent_tpl);
				},
				
				remove_dependentsFn: function(e){
					$(e.currentTarget).parent().parent().remove();
				},
				
				reset_depenFn: function(e){
					$('.depen_name').val('');
					$('.depen_bday').val('');
					$('.depen_rel').val('');
				},
				
				backListFn : function(e){
					var row_val = Site.getURLParameter('row');
					var row = row_val!=null ? '?row='+row_val : '';
					var url = urls.module_url+'employee'+row;
					Site.page_redirect(url);
				},
				
				saveForm:function(e){
					employee_helper.formSubmit();
				},
				exportWord:function(e){
					var emp_id = $('#emp_id').val();
					var url = urls.module_url+'employee/export_to_word?emp_id='+emp_id;
					Site.page_redirect(url);
				}
            })
        }
    }
);