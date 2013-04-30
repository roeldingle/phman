(function() {
    "use strict";
	var siteModuleReq   = urls.assets_url + 'site';
    var myModuleReq     = urls.module_assets_url;
    var libsPath	    = siteModuleReq + '/js/libs';
	
    var appsPath	    = myModuleReq + 'js';
    var cssPath         = myModuleReq + 'css';
    var module_libsPath     = appsPath + '/module_libs';
	var modelsPath	    = appsPath + '/models';
	var viewsPath	    = appsPath + '/views';
	var tmplsPath	    = viewsPath + '/templates';
    var helpersPath	    = appsPath + '/helpers';

    var module_app      = urls.current_url.split("/");
	var app             = module_app[module_app.length - 1];
	var sub_module      = appsPath + '/' + app;
    var spa_submodule   = appsPath + '/sub_modules';
	
	
	requirejs.config({
		paths : {
            // Libraries path
			text: 			libsPath + '/text',
			underscore: 	libsPath + '/underscore',
			backbone: 		libsPath + '/backbone',
            libsPath:       libsPath,
            
            // Custom apps path
			appsPath:		appsPath,
			modelsPath:		modelsPath,
			viewsPath:		viewsPath,
			tmplsPath:		tmplsPath,
            helpersPath:	helpersPath,
            cssPath:        cssPath,
            siteModuleReq:  siteModuleReq,
			module_libsPath: module_libsPath,
			sub_module:     sub_module,
			sub_viewsPath:  sub_module + '/views',
			sub_helpersPath:    sub_module + '/helpers',
			sub_tmplsPath:  sub_module + '/views/templates',
			sub_modelsPath: sub_module + '/models',
			sub_module_libsPath:    sub_module + '/module_libs',
            spa_submodule:  spa_submodule
		},
		baseUrl : '/',
		shim: {
			underscore: {
			  exports: '_'
			},
			backbone: {
				deps: ['underscore'],

				exports: 'Backbone'
			}
		}
	});

	require([
				'text',
				'appsPath/main'
			]
	);
})();