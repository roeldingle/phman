define([
		// Libraries
		'jquery', 'backbone'		
		], 
    function(
        $,backbone
    ){		
		return {
            defAjax: Backbone.Model.extend({
                url: urls.ajax_url
            })            
		}
	}
);