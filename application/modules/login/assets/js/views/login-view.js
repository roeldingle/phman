define([
		/*libraries*/
        'underscore','backbone','text',
        
        /*custom lib*/
        'helpersPath/devtool-1.0.0',
        
        /*template*/
        'text!tmplsPath/login-form.html',

        /*model*/
        'modelsPath/login-model'		
		], 
         
    function(_,backbone,text,devtool,tpl,model){	
        
        var defaultPage = 'dashboard';
        
        /***minor/helper functions***/
        var helperView = {
        
            /*welcome the logedin user*/
            access_granted: function(auserdata){
                /*animate*/
                $('#login_form').hide();
                
                /*add welcome elements*/
                var sHtml = '<img id="success_icon" src="'+urls.assets_url+'site/images/check_icon.png" /><h1>Welcome '+auserdata.tu_username+'!</h1>';
                $('#message_wrap').html(sHtml);
                
                /*desin animate*/
                devtool.animate_up('#message_wrap');
                devtool.animate_up('#success_icon');
                $('#message_wrap h1').animate({opacity: 1}, {queue: false, duration: 1500});
                
                /*redirect*/
                window.setTimeout ( function() {
                    var url = "/"+defaultPage;    
                    $(location).attr('href',url);
                },1500);
                
            },
            
            access_denied: function(){
                /*add welcome elements*/
                var sHtml = '';
                sHtml += '<div class="core-message-warning" style="position:absolute;top:0;left:3.6%;width:85%;">';
                sHtml += '<span class="core-message-server-text">Incorrect username and password combination!</span>';
                sHtml += '<a class="core-message-close core-message-server-close-button" href="javascript:$(\'.core-message-warning\').remove()">x</a>';
                sHtml += '</div>';
                $('#message_wrap').html(sHtml).animate({opacity: 1}, {queue: false, duration: 'medium'});
                $('#message_wrap').animate({ top: "-10px" }, 'medium');
                $('.txtbox').addClass('input_required');
                //devtool.animate_up('#message_wrap');
                $('#link_forgot_pass').show();
            },
            
            /*validate the text elem*/
            validate: function(e){
                devtool.dt_validate(e.currentTarget);
            }
        }
						
		var loginview =  Backbone.View.extend({
                
                /*define model object*/
				model: new model.defAjax(), 
                
                /*define template*/
				template: _.template(tpl),
                
                /*backbone js construct*/
				initialize: function(){
					this.init_display();
				},
                
                /*backbone selector*/
                el: "body",
                
                /*backbone events*/
				events: {
					"click .btn":"press_login",
                    
                    /***minor events***/
                    "keyup form#login_form input":  helperView.validate,
                    "focus .txtbox" : devtool.highlight,
                    "blur .txtbox" : devtool.unhighlight
				},
                
                 /*on load function*/
				init_display: function(){
          
                    $("#content").html(this.template);
                    devtool.animate_up('#content');  
                    
				},
                press_login: function(){
                    /*set selectors*/
                    var ousername = $('#user_login');
                    var opassword = $('#user_pass');
                    
                    /*validate the data*/
                    var u_valid = devtool.dt_validate(ousername);
                    var p_valid = devtool.dt_validate(opassword);
                    
                    /*check if valid is true*/
                    if(u_valid == true && p_valid == true){
                        
                        /*set required data for ajax*/
                        var formdata    = {
                            url : urls.ajax_url,
                            mod : "login|api|sample_test",
                            username: $.trim(ousername.val()),
                            password: $.trim(opassword.val())
                        };
                        
                            
                        /*ajax data*/
                        devtool._ajax(this.model,formdata).success(function(response){
                            /*ajax response checker*/
                            if(jQuery.isEmptyObject(response) == false){
                                helperView.access_granted(response);/*welcome message*/
                            }else{
                                helperView.access_denied();/*welcome message*/
                            }
                        });
                        
                    }
                }
                
		});
        
        return loginview;
        
	}
);