define([
            // Libraries
			'backbone',
			
			// Templates
            'text!tmplsPath/header.html',			
			
			// Views
			'viewsPath/user_form'
		],
	function(
	    // Libraries
		backbone,
		
		// Templates
        header, 		
		
		// Views
		user_form
	){
	    Backbone.emulateHTTP 	= true;
		Backbone.emulateJSON 	= true;
		
		var crud                = {};
		
		// Destroy ghost view
        Backbone.View.prototype.close = function () {
		    this.unbind();
	        this.undelegateEvents();
        };
		
		crud.Router	= Backbone.Router.extend({
		    initialize: function(p) {
				$('div#content').append(header);
				this.bind('all', this.removeUserForm);
			},
			
		    routes: {
				'':			 'landPage',
				'create':    'create',
				'read':      'read',
				'update':    'update'
			},
			
			create:   function(p) {
			    var init_form = new user_form.userForm();
			},
			
			read:   function(p) {
			    console.log('Read');
			},
			
			update:   function(p) {
			    console.log('Update');
			},
			
			removeUserForm:  function(p) {
			    // If user form exists, removed it everytime router is triggered
				var route    = p.split(':')[1];
				
			    if ($('#user_form').length > 0 && route !== 'create') {
				    $('#user_form').remove();
				}				
			}
		});
		
	    $(function(){
		    new crud.Router();
			
			Backbone.history.start();
		});
	}
);