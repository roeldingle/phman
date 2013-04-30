define([
    /*libraries*/
    'underscore','backbone','text',
    
    /*view files*/
    'core/request/assets/stock/js/views/catman-view',
    'core/request/assets/stock/js/views/stat-view'
    ],
  function(_,backbone,text,catman_view,stat_view){
		Backbone.emulateHTTP = true;
		Backbone.emulateJSON = true;
	
		var RouterStock = Backbone.Router.extend({
			routes: {
				"*action": "display"
			},
			display: function(){
                var subPage = urls.current_url.replace(urls.base_url,"");
                subPage = subPage.split("/");
                
                switch(subPage[1]){
                    case "category_management":
                    var myjs_view = new catman_view();
                    break;
                    
                    case "statistic":
                    var myjs_view = new stat_view();
                    break;
                    
                }
                  
			}
		});
		
		/*initialize the route*/
		$(function(){
		    new RouterStock();
			Backbone.history.start();
           
		});
	}
);