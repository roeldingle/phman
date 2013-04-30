define([
		/*libraries*/
        'underscore','backbone','text',
        
        /*custom lib*/
        'helpersPath/devtool-1.0.0',
        
        /*template*/
        'text!tmplsPath/account_form.html',

        /*model*/
        'modelsPath/dashboard-model'		
		], 
         
    function(_,backbone,text,devtool,tpl_account_form,model){	
        
       // var defaultPage = 'dashboard';
        
        var model = new model.defAjax();
						
		var dashboardview =   Backbone.View.extend({
                
                /*backbone selector*/
                el: "body",
                
                /*backbone events*/
				events: {
                    'click #link_change_pass' : 'change_password',
                    
                    'click #btn_save_update' : 'save_update'
                    
				},
                
                 /*on load function*/
                initialize:  function(){
				    _.bindAll(this, 'render');
                    this.render();
				},

				render:  function(){
                    
				},
            
                
                change_password: function(){
                    
                    if($('#update_form').is(':visible') == true){
                        $('#link_change_pass span').text('Update Account Info');
                        $('#update_form').hide("slide", { direction: "left" }, 500);
                    }else{
                        $('#link_change_pass span').text('Cancel update');
                        var tpl =  _.template(tpl_account_form); /*parsed template*/
                        $('#update_form').html(tpl).show("slide", { direction: "left" }, 500);
                    }
                },
                
                save_update: function(){
                    var form_data = $('#update_form').serializeArray();
                    form_data.unshift( {name:'tu_idx',value: $('#hidden_tu_idx').val()});
                    var bValid = $('#update_form').validateForm();
                    if(bValid === true){
                    
                        /*set required data for ajax (get_user)*/
                        var formdata    = {
                            url : urls.ajax_url,
                            mod : "dashboard|api|update_account",
                            getdata: form_data
                        };
                            
                        /*ajax data*/
                        devtool._ajax(model,formdata).success(function(response){
                        
                            if(response <= 0){
                                site.message("No changes had been made.",$(".message-container"),"warning");
                            }else{
                                site.message("Saved successfully!",$(".message-container"),"success");
                            }
                        });
                    
                    
                    }
                     
                
                }
                
		});
        
        return dashboardview;
	}
);