define([
		/*libraries*/
        'underscore','backbone','text',
        
        /*custom lib*/
        'helpersPath/devtool-1.0.0',
        
        /*template*/
        'text!tmplsPath/addnew.html',
        'text!tmplsPath/viewstock.html',
        'text!tmplsPath/modifystock.html',
        'text!tmplsPath/deletestock.html',
        'text!tmplsPath/addnew_category.html',
        'text!tmplsPath/modify_category.html',
        
        /*model*/	
        'modelsPath/stock-model'	
		], 
         
    function(
            /*libs*/
        _,backbone,text,
            
            /*helpers*/
        helpers,
        
            /*tpls*/
        tpl_addnew,
        tpl_viewstock,
        tpl_modifystock,
        tpl_deletestock,
        tpl_addnew_category,
        tpl_modify_category,
        
            /*models*/
        model
        
        ){	
      
        var model = new model.defAjax();
        
		var catman_view =   Backbone.View.extend({
                /*backbone selector*/
                el: "body",
                
                /*backbone events*/
				events: {
                /*main page events*/
                'click .menu_listing a' : 'menu_first_level_clicked',
                'click .menu_first_level_a' : 'menu_first_level_accordion',
                'click #add_subcategory_btn' : 'add_subcategory_clicked',
                'click .save_sub_category' : 'subcategory_save',
                'click .menu_second_level_a' : 'second_level_clicked',
                'click #edit_subcategory' : 'editSubCategory',
                'click #delete_subcategory' : 'deleteSubCategoryClicked',
                'click .delete_subcat_confirmed' : 'deleteSubCategory',
                
                /*sub page events*/
                'click .get_stock' : 'getStock',
                'click .add_hardware_stock' : 'addHardwareStock',
                'click .savestock_btn' : 'saveStock',
                'click .delete-btn' : 'deleteClicked',
                'click .delete_confirmed' : 'deleteStock',
                'change #search_option_select' : 'searchOptions'
				},
                
                 /*on load function*/
                initialize:  function(){
				    _.bindAll(this, 'render');
                    this.render();
				},

				render:  function(){
                   helpers.init_tablesorter(
                       'tb_stock_list',
                           {
                                0: { sorter: false },
                                1: { sorter: false },
                                5: { sorter: false },
                                6: { sorter: false }
                            }
                        );
                    helpers.accordion_sortable();
				},
                
                /*main page*/
                add_subcategory_clicked: function(){
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "stock|api|get_maincategory_data",
                    };
                   helpers._ajax(model,formdata).success(function(response){
                    /*ui dialog options*/
                    if(response.length != 0){
                        var adialogbox_options = {
                            scontainer : null,
                            aoption : {
                                title: 'Add New Sub Category',
                                width:350,
                                resizable: false,
                                modal: true
                            },
                            scontent : _.template(tpl_addnew_category,{rows: response}) /*parsed template*/
                        }
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                        }
                    });
                },
                
                subcategory_save: function(){
                    var bValid = $('.sub_category_form').validateForm();
                    var form_insert_data = $(".sub_category_form").serialize();
                    if(bValid === true){
                        var formdata    = {
                            url : urls.ajax_url,
                            mod : "stock|api|subcategory_save",
                            data: form_insert_data
                        };
                       helpers._ajax(model,formdata).success(function(response){
                            if(response === true){
                                    Site.page_redirect(urls.current_url+'?mess_return_type=add');
                            }else{
                                Site.page_redirect(urls.current_url+'?mess_return_type=nochange');
                            }
                       });
                    }
                },
                
                 menu_first_level_clicked: function(e){
                    //helpers.cat_mngt_view_info($(e.currentTarget));
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
                
                second_level_clicked: function(e){
                    var elem = $(e.currentTarget);
                    
                    $('#category_id_textbox').val(elem.attr('name'));
                    $('#category_name_textbox').val(elem.attr('title'));
                    
                    $('.action-box').show();
                
                },
                
                editSubCategory: function(){
                    var sub_cat_id = $('#category_id_textbox').val();
                    
                    if(sub_cat_id != ''){
                        var formdata    = {
                            url : urls.ajax_url,
                            mod : "stock|api|modify_subcat_data",
                            sub_cat_id: sub_cat_id
                        };
                       helpers._ajax(model,formdata).success(function(response){
                        /*ui dialog options*/
                        //console.log(response);
                            if(response.length != 0){
                                var adialogbox_options = {
                                    scontainer : null,
                                    aoption : {
                                        title: 'Modify Sub Category',
                                        width:350,
                                        resizable: false,
                                        modal: true
                                    },
                                    scontent : _.template(tpl_modify_category,{rows: response}) /*parsed template*/
                                }
                                /*open a ui dialog box*/
                                Site.dialog_box(adialogbox_options);
                            }
                        });
                    }else{
                        alert('undefined');
                    }
                    
                
                },
                
                deleteSubCategoryClicked: function(){
                    var sub_cat_name = $('#category_name_textbox').val();
                    var scontent = '';
                    scontent += 'Are you sure you want to remove sub category <strong>'+sub_cat_name+'</strong> and all of its item(s)? <br /><br />';
                    scontent += '<a href="javascript:void(0);" class="btn btn_type_3 delete_subcat_confirmed fr" ><span>Delete</span></a>';
                    var adialogbox_options = {
                        scontainer : null,
                        aoption : {
                            title: 'Delete Sub Category',
                            width:350,
                            resizable: false,
                            modal: true
                        },
                        scontent : scontent/*parsed template*/
                    }
                    /*open a ui dialog box*/
                    Site.dialog_box(adialogbox_options);
                    
                    
                },
                deleteSubCategory: function(){
                    var sub_cat_id = $('#category_id_textbox').val();
                    
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "stock|api|delete_subcategory_data",
                        sub_cat_id: sub_cat_id
                    };
                    helpers._ajax(model,formdata).success(function(response){
                        if(response === true){
                            Site.page_redirect(urls.current_url+'?mess_return_type=delete');
                        }else{
                            Site.page_redirect(urls.current_url+'?mess_return_type=nochange');
                        }
                   });
                
                },
                
                /*endmain page*/
                
                /*sub pages*/
                addHardwareStock: function(){
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "stock|api|get_category_data",
                       category_id: $('#main_category_id').val()
                    };
                   helpers._ajax(model,formdata).success(function(response){
                        /*ui dialog options*/
                        var adialogbox_options = {
                            scontainer : null,
                            aoption : {
                                title: 'Add New '+response.main_cat_data.tsmc_name,
                                width:350,
                                resizable: false,
                                modal: true
                            },
                            scontent : _.template(tpl_addnew, {rows: response}) /*parsed template*/
                        }
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                   });
                },
                
                getStock: function(e){
                    var process = $(e.currentTarget).attr('alt');
                    if(process == 'view'){
                        var tpl = tpl_viewstock;
                        var mod = "stock|api|get_stock_data";
                        var title = "View";
                    }else{
                        var tpl = tpl_modifystock;
                        var mod = "stock|api|get_modstock_data";
                        var title = "Modify";
                    }
                     var formdata    = {
                        url : urls.ajax_url,
                        mod : mod,
                        stockitem_id: $(e.currentTarget).attr('stockitem_id'),
                        category_id: $('#main_category_id').val()
                    };
                   helpers._ajax(model,formdata).success(function(response){
                        /*ui dialog options*/
                        var adialogbox_options = {
                            scontainer : null,
                            aoption : {
                                title: title+' stock',
                                width:350,
                                resizable: false,
                                modal: true
                            },
                            scontent : _.template(tpl, {rows: response}) /*parsed template*/
                        }
                        
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                   });
                },
             
                saveStock: function(e){
                    var bValid = $('.save_stock').validateForm();
                    var form_insert_data = $(".save_stock").serialize();
                    if(bValid === true){
                        var process = $(e.currentTarget).attr('alt');
                        var formdata    = {
                            url : urls.ajax_url,
                            mod : "stock|api|save_stock_data",
                            data: form_insert_data,
                            category_id: $('#main_category_id').val(),
                            process: process,
                            mod_stock_id: ($('#mod_stock_id').length == 0) ? 'null' : $('#mod_stock_id').val(),
                            mod_history_id: ($('#mod_history_id').length == 0) ? 'null' : $('#mod_history_id').val()
                        };
                       helpers._ajax(model,formdata).success(function(response){
                       console.log(response);
                            if(response === true){
                                if($('#mod_stock_id').length != 0){
                                    //Site.page_redirect(urls.current_url+'?mess_return_type=edit');
                                    alert('Edited successfully');
                                    window.location.href = location.href;
                                    
                                }else{
                                    //Site.page_redirect(urls.current_url+'?mess_return_type=add');
                                    alert('Added successfully');
                                    window.location.href = location.href;
                                }
                            }else{
                                //Site.page_redirect(urls.current_url+'?mess_return_type=nochange');
                                alert('No change');
                                    window.location.href = location.href;
                            }
                       });
                    }
                },
                
                deleteClicked: function(e){
                    /*ui dialog options*/
                        var adialogbox_options = {
                            scontainer : null,
                            aoption : {
                                title: 'Message',
                                width:300,
                                resizable: false,
                                modal: true
                            },
                            scontent :  _.template(tpl_deletestock, {type_of_delete: $(e.currentTarget).attr('stockitem_id')}) /*parsed template*/

                        }
                        /*open a ui dialog box*/
                        Site.dialog_box(adialogbox_options);
                },
                
                deleteStock: function(){
                    var bDeleteBtn = $('#type_of_delete').val();
                    if(bDeleteBtn != 'undefined'){
                        var row_stock_ids = bDeleteBtn;
                    }else{
                        var row_stock_ids = [];
                            $("input:checkbox[name=row_stock_id]:checked").each(function() {
                            row_stock_ids.push($(this).val());
                        });
                    }
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "stock|api|delete_stock_data",
                        row_stock_ids: row_stock_ids
                    };
                    helpers._ajax(model,formdata).success(function(response){
                        if(response === true){
                            Site.page_redirect(urls.current_url+'?mess_return_type=delete');
                        }else{
                            Site.page_redirect(urls.current_url+'?mess_return_type=nochange');
                        }
                   });
                },
                
                searchOptions: function(e){
                    var main_cat_id = $('#main_category_id').val();
                    var search_by = $(e.currentTarget).val();
                    var formdata    = {
                        url : urls.ajax_url,
                        mod : "stock|api|search_option",
                        data: {
                            main_cat_id: main_cat_id,
                            search_by: search_by
                        }
                    };
                   helpers._ajax(model,formdata).success(function(response){
                        if(response.length > 0){
                            $(e.currentTarget).attr('disabled',true);
                            $('#search_option_hidtxt').val(search_by);
                            var sData = '';
                            sData += '<select class="search" name="search_item" >';
                            $.each(response,function(index, value){
                                if(value.id === ""){
                                    sData += '<option value="'+value.label+'" >'+value.label+'</option>';
                                }else{
                                    sData += '<option value="'+value.id+'" >'+value.label+'</option>';
                                }
                            });
                            sData += '</select>';
                            sData += '<input type="submit" value="Search" />';
                            $('#search-item-wrap').html(sData);
                        }else{
                            $('#search-item-wrap').html('<b style="color:red;">Nothing to search</b>');
                        }  
                   });
                }
                /*endsub pages*/
                
              
		});
        
        return catman_view;
	}
);