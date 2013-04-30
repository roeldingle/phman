(function() {
    "use strict";
	var myModuleReq = urls. module_assets_url;
	var libsPath	= myModuleReq + 'js/libs';
	var appsPath	= myModuleReq + 'js/apps';
	var modelsPath	= appsPath + '/model';
	var viewsPath	= appsPath + '/view';
	var tmplsPath	= viewsPath + '/template';

	requirejs.config({
		paths : {
			text: 			libsPath + '/text',
			jquery: 		'http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min',
			underscore: 	libsPath + '/underscore',
			backbone: 		libsPath + '/backbone',
			appsPath:		appsPath,
			modelsPath:		modelsPath,
			viewsPath:		viewsPath,
			tmplsPath:		tmplsPath
		},
		baseUrl : '/',
		shim: {
			underscore: {
			  exports: '_'
			},
			backbone: {
				deps: ['underscore', 'jquery'],

				exports: 'Backbone'
			}
		}
	});

	require([
				'jquery', 'text',
				'appsPath/main'
			]
	);
})();