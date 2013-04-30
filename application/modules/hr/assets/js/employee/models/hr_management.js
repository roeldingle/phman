define([
        
        //libraries
        'backbone'
        
    ], function(
    		
    	//libraries
		backbone
		
	){
	
	return {
        defAjax: Backbone.Model.extend({
            url: urls.ajax_url
        })
	}
});