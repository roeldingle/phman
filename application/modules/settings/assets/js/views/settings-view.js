define([
		/*libraries*/
        'underscore','backbone','text',
        
        /*custom lib*/
        'helpersPath/devtool-1.0.0',
        
        /*template*/
        'text!tmplsPath/user-list.html',
        'text!tmplsPath/settings-addform.html',
        'text!tmplsPath/settings-editform.html',
        'text!tmplsPath/user-grade-legend.html',
        'text!tmplsPath/preview-user-info.html',
        'text!tmplsPath/message.html',
        
        'text!tmplsPath/menu-default.html',

        /*model*/
        'modelsPath/settings-model'		
		], 
         
    function(_,backbone,text,devtool,tpl_list,tpl_addform,tpl_editform,tpl_usgrle,tpl_preview,tpl_message,tpl_menudefault,model){	
        
        var defaultPage = 'dashboard';
        
        var model = new model.defAjax();
						
		var settingsview =   Backbone.View.extend({
                
                /*backbone selector*/
                el: "body",
                
                /*backbone events*/
				events: {
                    'click #add_user_link' : 'add_user_clicked',
                    
                    //'keyup form#add_user_form input':  'validate',
                    'click #submit_user' : 'save_user',
                    'click #save_user' : 'save_user',
                    'click #preview_cancel' : devtool.close_dialog,
                    'click #change_link' : devtool.remove_readonly,
                    
                    'click #delete_btn' : 'delete_clicked',
                    'click #continue_delete' : 'delete_user',
                    
                    'click .btn_modify_user' : 'preview_user',
                    'click #change_password' : 'change_password',
                    
                    /*settings -> menu*/
                    'click .menu_listing a' : 'menu_first_level_clicked',
                    'click .menu_first_level_a' : 'menu_first_level_accordion',
                    'click .select_up' : 'select_up_clicked',
                    'click .select_down' : 'select_down_clicked',
                    
                    'click #btn_save_menu' : 'save_menu_manage',
                    'click #return_default' : 'return_default',
                    'keyup #txt_menu_label' : 'onkeyup_change_label'
                    /*end settings -> menu*/
                    
				},
                
                 /*on load function*/
                initialize:  function(){
				    _.bindAll(this, 'render');
                    this.render();
				},

				render:  function(){
                   devtool.init_tablesorter();
                   devtool.accordion_sortable();
                   Site.datepicker("#sample_datepicker");  
				},
                
                /*settings -> menu*/
                return_default: function(){
                    
                    $('#txt_menu_label').val('');
                    
                    var menu_def_tpl =  _.template(tpl_menudefault); /*parsed template*/
                    
                    $('.menu_listing').html(menu_def_tpl);
                    
                },
                
                menu_first_level_clicked: function(e){
                
                    /*give style to div and a tags*/
                    $('.menu_listing div').removeClass('current_menu');
                    $(e.currentTarget).parent('div').addClass('current_menu');
                    $('.menu_listing a').removeClass('current');
                    $(e.currentTarget).addClass('current');
                    
                    /*give text value to form textbox*/
                    $('#txt_menu_idx').val($.trim($(e.currentTarget).attr('name')));
                    $('#txt_menu_label').val($.trim($(e.currentTarget).text())).attr('readonly',false).focus();
                    
                    
                },
                
                menu_first_level_accordion: function(){
                    
                    /*accordion the div*/
                    $('.menu_second_level').slideUp();
                    if($('.current_menu').children('.menu_second_level').is(':visible') === false){
                        $('.current_menu').children('.menu_second_level').slideDown();return;
                    }
                },
                
                select_up_clicked: function(){
                    var $curr = $(".current_menu");
                        $curr.after($curr.prev('div'));

                },
                
                select_down_clicked: function(){
                    var $curr = $(".current_menu");
                     $curr.before($curr.next('div'));

                },
                
                save_menu_manage: function(){
                
                    var obj = $('.menu_listing').children('div');
                    var obj2 = $('.menu_listing').find('.menu_second_level');
                    var aMenuData = $.makeArray(obj);
                    var aSubMenuData = $.makeArray(obj2);
                    var aData = new Array();
                    var aSubData = new Array();
                    
                    $.each(aMenuData, function(k,v) {
                        raw_id = $(v).children('a').attr('name');
                        raw_label = $(v).children('a').text();
                        aData.push({
                            tm_idx: raw_id,
                            tm_label: raw_label
                        });
                    });
                    
                    $.each(aSubMenuData, function(a,b) {
                        subraw_id = $(b).children('a').attr('name');
                        subraw_label = $(b).children('a').text();
                        aSubData.push({
                            tsu_idx: subraw_id,
                            tsu_label: subraw_label
                        });
                    });
                    
                    /*set required data for ajax (get_user)*/
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "settings|api|update_module_sequence",
                        moduledata: aData,
                        submoduledata: aSubData
                    };
                    
                     /*ajax data*/
                    devtool._ajax(model,formdata).success(function(response){
                        /*ajax response checker*/
                        if(response != 0){
                            Site.page_redirect(urls.current_url+'/message_return?type=add');
                        }else{
                            Site.page_redirect(urls.current_url+'/message_return?type=nochange');
                        }
                            
                    });
                },
                
                onkeyup_change_label: function(){
                
                    var up_mod_idx = $('#txt_menu_idx').val();
                    var up_mod_lbl = $('#txt_menu_label').val();
                    if(up_mod_lbl.length <= 0){
                        $('.menu_listing .current').text($('.menu_listing .current').attr('title'));
                    }else{
                        $('.menu_listing .current').text(up_mod_lbl);
                    }
                },
                /*end settings -> menu*/
                /*settings -> user*/
               change_password: function(){
                    
                    if($('#change_password').text() == 'Cancel'){
                        $('#change_pass_wrap').html('');
                        $('#pass_note').html('');
                        $('#change_password').text('Change password');
                    }else{
                        $('#change_pass_wrap').html('<input type="password" class="input_type_1" id="employee_password" name="employee_password" validate="required|password" />');
                        $('#pass_note').html('Minimum of 4 and maximum of 12 characters long (at least 1 letter and 1 number)');
                        $('#employee_password').focus();
                        $('#change_password').text('Cancel');
                    }
                },
                
                preview_user: function(e){
                    var user_id = $(e.currentTarget).attr('alt');
                    
                    /*set required data for ajax (get_user)*/
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "settings|api|get_user",
                        user_id: user_id
                    };
                        
                    /*ajax data*/
                    devtool._ajax(model,formdata).success(function(response){
                        //console.log(response);
                        /*ajax response checker*/
                        if(jQuery.isEmptyObject(response) == false){
                        
                            /*ui dialog options*/
                            var adialogbox_options = {
                                scontainer : null,
                                aoption : {
                                    title: 'Edit User',
                                    width:520,
                                    resizable: false,
                                    modal: true
                                },
                                scontent : _.template(tpl_editform, {user_data_rows: response.employee,user_grade_rows:response.grade}) /*parsed template*/
      
                            }
                            
                            /*open a ui dialog box*/
                            Site.dialog_box(adialogbox_options);
                            
                        }else{
                            console.log('error');
                        }
                    });
                },
                
                /*function if add user button is clicked*/
                add_user_clicked: function(){
                   /*set required data for ajax (get_user)*/
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "settings|api|get_grade_emp"
                    };
                        
                    /*ajax data*/
                    devtool._ajax(model,formdata).success(function(response){
                    
                        /*ajax response checker*/
                        if(jQuery.isEmptyObject(response) == false){
                        
                            /*ui dialog options*/
                            var adialogbox_options = {
                                scontainer : null,
                                aoption : {
                                    title: 'Add User',
                                    width:520,
                                    resizable: false,
                                    modal: true
                                },
                                scontent : _.template(tpl_addform, {user_grade_rows: response.grade}) /*parsed template*/
       
                            }
                            
                            /*open a ui dialog box*/
                            Site.dialog_box(adialogbox_options);
                            
                            /*give ui auto complete to get employee list*/
                            devtool.autocomplete_emp(response.employee);
                            //console.log(response.employee);
                        }else{
                            console.log('error');
                        }
                            
                    });
                },
                
                /*function to submit add new user*/
				submit_clicked: function(){
                    var new_user = [];
                    new_user.iemp_id = $.trim($('#employee_id').val());
                    new_user.iemp_name = $.trim($('#employee_listed').val());
                    new_user.susername = $.trim($('#employee_username').val());
                    new_user.spassword = $.trim($('#employee_password').val());
                    new_user.iuser_grade = $('#user_grade').val();
                    
                    var bValid = $('#add_user_form').validateForm();
                    
                    if(bValid === true){
                        /*ui dialog options*/
                        var adialogbox_options = {
                            scontainer : 'message',
                            aoption : {
                                title: 'Info',
                                width:400,
                                resizable: false,
                                modal: true
                            },
                            scontent : '<h1>yeah!</h1>' /*parsed template*/
                        }
                            
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                        
                         var parsedTemplate 	= _.template(tpl_preview, {rows: new_user});
                         $(".mess_box").html(parsedTemplate);
                    
                    }else{
                        console.log('error');
                    }
				},
                
                /*
                    save user via ajax and redirect returns message
                */
                save_user: function(){
                
                    var bValid = $('#add_user_form').validateForm();
                    
                 
                    if(bValid === true){
                    
                        /*set required data for ajax*/
                        var formdata    = {
                            url : urls.ajax_url,
                            mod : "settings|api|check_username",
                            process: $('#save_user').attr('title'),
                            useridx: ($('#employee_id').length) ? $('#employee_id').val() : 'null',
                            username: $('#employee_username').val()
                        }
                        
                         /*ajax insert new user data*/
                        devtool._ajax(model,formdata).success(function(response){
                            /*ajax response checker*/
                            if(response != true){
                                var elemdata = {
                                    form_action: $('#form_action').val(),
                                    emp_id: $('#employee_id').val(),
                                    username: $('#employee_username').val(),
                                    password: ($('#employee_password').is(':visible') == 0) ? $('#hidden_employee_password').val() : $('#employee_password').val(),
                                    user_grade: $('#user_grade').val(),
                                    date_created: $('#date_created').val(),
                                    change_pass_flag: ($('#employee_password').is(':visible') == 0) ? 0 : 1 /*change password flag*/
                                }
                            
                                /*set required data for ajax*/
                                var formdata    = {
                                    url : urls.ajax_url,
                                    mod : "settings|api|save_user",
                                    getdata: elemdata
                                }
                                
                                 /*ajax insert new user data*/
                                devtool._ajax(model,formdata).success(function(response){
                                    /*ajax response checker*/
                                    if(response == true){
                                        Site.page_redirect(urls.current_url+'/message_return?type='+elemdata.form_action);
                                    }else{
                                        console.log('error');/*error login*/
                                    }
                                });
                            }else{
                                site.message("Username already exist.",$(".message_wrap_dialog"),"warning");
                            }
                        });
                    
                         
                    }
                
                },
                
                
                /*
                    on first click of delete button
                    check if there is a checked checkbox
                    display dialog box
                */
                delete_clicked: function(){
                    
                    var iAppId = new Array;//$('input[name="aa_idx[]"]').attr('id');
                    
                    var iAppId = new Array();
                    $('#tb_user_list input[name="user_idx[]"]:checked').each(function() {
                        iAppId.push($(this).val());
                    });
                    
                    if(iAppId.length === 0) {
                        /*ui dialog options*/
                        var adialogbox_options = {
                            scontainer : 'message',
                            aoption : {
                                title: 'Message',
                                width:300,
                                resizable: false,
                                modal: true
                            },
                            scontent : _.template(tpl_message, {mess_type:2,message: 'Please choose user(s) to delete'}) /*parsed templatetpl_message*/
                        }
                            
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                    }else{
                        /*ui dialog options*/
                        var adialogbox_options = {
                            scontainer : 'message',
                            aoption : {
                                title: 'Message',
                                width:300,
                                resizable: false,
                                modal: true
                            },
                            scontent : _.template(tpl_message, {mess_type:1,message: 'Are you sure you want to delete user(s)?'}) /*parsed templatetpl_message*/
                        }
                            
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                    }
                    
                },
                
                /*
                    remove user from list by changing inactive field to false
                */
                delete_user: function(){
                
                    var iAppId = new Array;
                    
                    var iAppId = new Array();
                    $('#tb_user_list input[name="user_idx[]"]:checked').each(function() {
                        iAppId.push($(this).val());
                    });
                    
                    /*set required data for ajax*/
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "settings|api|delete_user",
                        user_idx: iAppId
                    }
                    
                     /*ajax insert new user data*/
                    devtool._ajax(model,formdata).success(function(response){
                        /*ajax response checker*/
                        if(response == true){
                            Site.page_redirect(urls.current_url+'/message_return?type=delete');
                        }else{
                            console.log('error');/*error login*/
                        }
                    });
                    
                },
                /*end settings -> user*/
                
                /*validate the text elem*/
            validate: function(e){
                devtool.dt_validate(e.currentTarget);
            }
		});
        
        return settingsview;
	}
);