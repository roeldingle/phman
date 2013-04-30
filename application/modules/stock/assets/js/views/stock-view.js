define([
		/*libraries*/
        'underscore','backbone','text'
        
        /*custom lib
        'helpersPath/devtool-1.0.0',
        
        /*template
        'text!tmplsPath/user-list.html',
        'text!tmplsPath/settings-addform.html',
        'text!tmplsPath/settings-editform.html',
        'text!tmplsPath/user-grade-legend.html',
        'text!tmplsPath/preview-user-info.html',
        'text!tmplsPath/message.html',
        
        'text!tmplsPath/menu-default.html',

        /*model
        'modelsPath/settings-model'	*/	
		], 
         
    function(_,backbone,text){	
        
        //var defaultPage = 'dashboard';
        
       // var model = new model.defAjax();
						
		var stocksview =   Backbone.View.extend({
                
                /*backbone selector*/
                el: "body",
                
                /*backbone events*/
				events: {
                    
				},
                
                 /*on load function*/
                initialize:  function(){
				    _.bindAll(this, 'render');
                    this.render();
				},

				render:  function(){
                   console.log('sdsds');
				}
                
              
		});
        
        return stocksview;
	}
);