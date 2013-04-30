define([
    /*libraries*/
    'underscore','backbone','text',
    
    /*view files*/
    'core/request/assets/settings/js/views/settings-view'
    ],
  function(_,backbone,text,view){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
	
		var RouterStock = Backbone.Router.extend({
			routes: {
				"*action": "display",
                "save": "test"
			},
			display: function(){
                  var myjs_view = new view();
			},
            test: function(){
                alert(2323);
            
            }
		});
		
		/*initialize the route*/
		$(function(){
		    new RouterStock();
			Backbone.history.start();
           
		});
	}
);