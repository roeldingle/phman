define([
    /*libraries*/
    'underscore','backbone','text',
    
    /*view files*/
    'core/request/assets/dashboard/js/views/dashboard-view'
    ],
  function(_,backbone,text,view){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
	
		var RouterStock = Backbone.Router.extend({
			routes: {
				"": "display"
				//"*action": "displayPage"
				//"*menu": "test"
			},
			display: function(){
                  var myjs_view = new view();
			}
		});
		
		
		/*initialize the route*/
		$(function(){
		    new RouterStock();
			Backbone.history.start();
           
		});
	}
);