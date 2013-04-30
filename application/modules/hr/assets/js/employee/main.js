define([
        // Libraries
		'backbone',
        
        // Views
        'sub_viewsPath/employee_form'
	],
	function(
	    // Libraries
		backbone,
		
        // Views
		employee_form
	){		
	    return {
		
	    	mod_router: Backbone.Router.extend({
			    initialize: function(p) {
			    	$('.date_started').show();
	        		$('.date_ended').hide();
	        		$('.probation_ended').show();
					var flag='';
					var modify_id = '';
					var modify_page = '';
				},
				
			    routes: {
	                'add'			: 'addFn',
					'modify/id:id'  : 'modifyFn'
	            },
	            addFn:function(){
					flag='add';
					modify_id = '';
	            	new employee_form.form_render();
					
	            },
	            modifyFn:function(id){
					flag='update';
					modify_id = id;
	            	new employee_form.form_render();
	            }
			})
	    }
	}
);