define([
		/*libraries*/
        'underscore','backbone','text',
        
        /*custom lib*/
        'helpersPath/devtool-1.0.0'
        
        /*template
        'text!tmplsPath/addnew.html',
        'text!tmplsPath/viewstock.html',
        'text!tmplsPath/modifystock.html',
        'text!tmplsPath/deletestock.html',*/
        
        /*model	
        'modelsPath/stock-model'	*/
		], 
         
    function(
            /*libs*/
        _,backbone,text,
            
            /*helpers*/
        helpers
        
            /*tpls
        tpl_addnew,
        tpl_viewstock,
        tpl_modifystock,
        tpl_deletestock,
        */
            /*models
        model*/
        
        ){	
        
        //var model = new model.defAjax();
        
		var stat_view =   Backbone.View.extend({
                
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
                   helpers.init_tablesorter(
                       'by_category',
                            {0: { sorter: false }}
                       );
                  
                  helpers.init_tablesorter(
                       'by_model',
                            {0: { sorter: false }}
                       );
                       Site.datepicker("#from_date")
                       Site.datepicker("#to_date")
                   
				}
              
		});
        
        return stat_view;
	}
);