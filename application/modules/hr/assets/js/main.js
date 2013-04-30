define(function(require){
    Backbone.emulateHTTP 	= true;
	Backbone.emulateJSON 	= true;
	
	var main = require('sub_module/main');
	$(function(){
		new main.mod_router();
		Backbone.history.start();
	});	
});
