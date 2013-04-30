jQuery(document).ready(function($){
   $('.sidebar-menu').show().dcAccordion({
      eventType: 'click',
      autoClose: true,
      saveState: true,
      disableLink: true,
      speed: 'fast',
      showCount: false,
      autoExpand: false,
      cookie	: 'dcjq-accordion-1',
      classExpand	 : 'dcjq-current-parent'
   });
   
   /*triggers*/
   
   /*qtip for main menu*/
   $('.mainmenu li a[title]').qtip({ 
       style: { 
        name: 'light', tip: true ,
        fontSize: 14,
        textAlign: 'center',
        padding: 10,
        border: {
             width: 3,
             radius: 8,
             color: '#656563'
          }
       },
        position: {
          corner: {
             target: 'topMiddle',
             tooltip: 'bottomMiddle'
          }
       }
   });
   
   /*qtip for sub menu*/
   $('.side_box ul li a[title]').qtip({ 
       style: { 
        name: 'light', tip: true ,
        fontSize: 14,
        textAlign: 'center',
        padding: 10,
        border: {
             width: 3,
             radius: 8,
             color: '#656563'
          }
       },
        position: {
          corner: {
             target: 'rightMiddle',
             tooltip: 'leftMiddle'
          }
       }
   });
   
   $('.btn_vmd_2').qtip({
       content: 'Modify',
       show: 'mouseover',
       hide: 'mouseout',
       style: { 
        name: 'light', tip: true ,
        fontSize: 14,
        textAlign: 'center',
        padding: 10,
        border: {
             width: 3,
             radius: 8,
             color: '#656563'
          }
       },
        position: {
          corner: {
             target: 'topMiddle',
             tooltip: 'bottomMiddle'
          }
       }
    })

   $('.logout_link').click(function(){
    Site.logout_clicked();
   });
   
   $('#logout_confirm').live('click',function(){
    Site.page_redirect('/login/logout');
   });
   
    $('.checkall').live('click',function () {
       // $(this).parents('fieldset:eq(0)').find(':checkbox').attr('checked', this.checked);
        $('input[type="checkbox"]').attr('checked', this.checked);
    });
});

var Site = {
test: function(){
alert(0);

},
     /*set table sorter
     *
     *implementation
     *  
     include this to your controller 
     -> $this->app->use_js(array("source"=>"site/libs/table.sorter","cache"=>false));
     
         var tb_sorter_options = {
            tb_selector_id : 'tb_user_list',
            headers: {
                0: { sorter: false },
                1: { sorter: false }
            }
        }
        
        Site.init_tb_sorter(tb_sorter_options);
     *
     *
     **/
    init_tb_sorter: function(options){
        $('#'+options.tb_selector_id).addClass('table_hover');
       
        /*set the jquery table sorter*/
        $('#'+options.tb_selector_id).tablesorter({
            headers: options.headers
        });
    },

    /*for logout*/
    logout_clicked: function(){
        
        /*html content for logout*/
        var shtmlcontent = '<p>Are you sure you want to logout and leave this page?</p>';
            shtmlcontent += '<a href="#" id="logout_confirm" class="btn btn_type_2 btn_space fr mt10 mb10" ><span>Continue</span></a>';
            
         /*ui dialog options*/
        var adialogbox_options = {
            scontainer : 'message',
            aoption : {
                title: 'Message',
                width:350,
                resizable: false,
                modal: true
            },
            scontent : shtmlcontent
        }
       
       Site.dialog_box(adialogbox_options);
        
                
    },
    
    /*
        HTML 
        <input type="text" id="sample_datepicker" />
        
        JS
        Site.datepicker("#sample_datepicker");  
    */
    
    datepicker: function(selector){
        $( selector ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "1970:+nnnn",
                showOn: "button",
                buttonImage: urls.assets_url+"site/images/calendar-day.png",
                buttonImageOnly: true
         });
         
         $(selector).attr('readonly','true');
    
    },
    
    /*popup dialogbox
    *   options.dialogId = id for the dialogbox 
    *   options.scontainer = dom selector for the dialog box
    *   options.aoption = ui dialog options
    *   options.scontent = content of the dialog box
    
    * @message option is for small dialog usually use to display message ex.are you sure u want to delete these item(s)
    */
    dialog_box: function(options){
	var dialogId = options.dialogId==null ? '' : ' id="'+options.dialogId+'" ';
        if(options.scontainer == null){
           options.scontainer = '.popup_wrap';
            $(options.scontainer).remove();
            var sDialogBox = '<div class="popup_wrap" '+dialogId+' style="display:none;" />';
            $('body').append(sDialogBox);
        }else if(options.scontainer == 'message'){
            options.scontainer = '.mess_box';
            $(options.scontainer).remove();
            var sDialogBox = '<div class="mess_box" '+dialogId+' style="display:none;" />';
            $('body').append(sDialogBox);
        }
       $(options.scontainer).html(options.scontent);
       $(options.scontainer).dialog(options.aoption);
    },
    
    /*to redirect
    *   sUrl = url of the page you want to redirect
    */
    page_redirect: function(sUrl){
        $(location).attr('href',sUrl);
    },
	
	paginationSp: function(curPage,rowsPerPage,totalRows,uri){
	
		var pageRows	= Math.ceil(totalRows / rowsPerPage);
		var rowTmpl		= '';
		var prev, next, totalpage, pagerMode;
		curPage			= parseInt(curPage);
		
		for(var acntr=1; acntr<=pageRows; acntr++){
			if(acntr === curPage){
				rowTmpl	+=	'<li><a class="current" href="#'+uri+''+acntr+'">'+acntr+'</a></li>';
			}else{
				if(acntr < curPage){
					// Left side
					if((curPage - 3) > 1){
						// With dots
						if(acntr < curPage && acntr >= (curPage - 2)){
							if(acntr === (curPage - 2)){
								rowTmpl	+=	'<li><a class="num" href="#'+uri+'1">1</a></li>';
								rowTmpl	+=	'<li><span>...</span></li>';
							}
							rowTmpl	+=	'<li><a class="num" href="#'+uri+''+acntr+'">'+acntr+'</a></li>';
						}
					}else{
						rowTmpl	+=	'<li><a class="num" href="#'+uri+''+acntr+'">'+acntr+'</a></li>';
					}
				}else{
					// Right side
					if((curPage + 3) < pageRows){
						if(acntr > curPage && acntr <= (curPage + 2)){
							rowTmpl	+=	'<li><a class="num" href="#'+uri+''+acntr+'">'+acntr+'</a></li>';
							if(acntr === (curPage + 2)){
								rowTmpl	+=	'<li><span>...</span></li>';
								rowTmpl	+=	'<li><a class="num" href="#'+uri+''+pageRows+'">'+pageRows+'</a></li>';
							}
						}
					}else{
						rowTmpl	+=	'<li><a class="num" href="#'+uri+''+acntr+'">'+acntr+'</a></li>';
					}
				}
			}
		}
		
		
		// Previous
		if(curPage > 1){
			prev	= curPage - 1;
		}else{
			prev	= 1;
		}
		// Next
		if(curPage < pageRows){
			next	= curPage + 1;
		}else{
			next	= pageRows;
		}
		
        var prev_exist  = '';
        var next_exist  = '';
        
        if (curPage !== 1) {
            prev_exist  = '<a href="#'+uri+''+prev+'">&lt; Prev</a>';
        } else {
            prev_exist  = '<a class="inactive" href="#'+uri+''+prev+'">&lt; Prev</a>';
        }
        
        if (curPage == pageRows) {
            next_exist  = '<a class="inactive" href="#'+uri+''+next+'">Next &gt;</a>';
        } else {
            next_exist  = '<a href="#'+uri+''+next+'">Next &gt;</a>';
        }
        
		var elem	=	'<ul>\
								<li>'+ prev_exist +'</li>'+rowTmpl+'<li>'+ next_exist +'</li>\
							</ul>\
						';         
		return elem;
	},
	
	removeURLParam : function(url, param)
    {
     var urlparts= url.split('?');
     if (urlparts.length>=2)
     {
      var prefix= encodeURIComponent(param)+'=';
      var pars= urlparts[1].split(/[&;]/g);
      for (var i=pars.length; i-- > 0;)
       if (pars[i].indexOf(prefix, 0)==0)
        pars.splice(i, 1);
      if (pars.length > 0)
       return urlparts[0]+'?'+pars.join('&');
      else
       return urlparts[0];
     }
     else
      return url;
    },
	
	getURLParameter : function(name){
		return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
	},
	
	fileExists : function(url) {
		if(url){
			var req = new XMLHttpRequest();
			req.open('GET', url, false);
			req.send();
			return req.status==200;
		} else {
			return false;
		}
	},
    
    showChart : function(achart)
    {
    
        if(achart['graph_type']!="pie"){
            var tooltip_type = 
                {
                    formatter: function() {
                        return ''+ this.series.name +" "+
                            this.x + achart['text_tooltip'] + this.y +'.';
                    },              
                
                    style: {
                        color: '#333',
                        font: 'bold 8px arial,sans-serif'
                    }
                };
                
            var plots = {
                column: {
                    pointPadding: 0.1,
                    borderWidth: 0
                }            
            };
            
            var legend_type = {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                color : '#333333',
                borderColor: '#000',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 0,
                floating: true,
                shadow: true,
                itemStyle: {
                    cursor: 'pointer',
                    color: '#333'
                }
            };
            
            var column_info_xaxis = {
                    categories: achart['categ'],
                    labels: {
                        style: {
                            font: 'bold 9px arial,sans-serif',
                            color : '#333'
                        }
                    }
                };
                
            var column_info_yaxis = {
                    min: 0,
                    title: {
                        text: achart['text_yAxis'],
                        style: {
                            font: 'bold 13px arial,sans-serif',
                            color : '#333'
                        }
                    },
                    labels: {
                        style: {
                            font: 'bold 10px arial,sans-serif',
                            color : '#333'
                        }
                    }
                };
                
        } else if(achart['graph_type']=="pie"){
            var tooltip_type = { 
                    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                    percentageDecimals: 2
                };
                
            var plots = {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true,
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ parseFloat(this.percentage).toFixed(2) +' %';
                        }
                    }
                }                
            };
            var column_info_xaxis = {};
            var column_info_yaxis = {};
            var legend_type = {};                
        }
        
        chart = new Highcharts.Chart({
            chart: {
                renderTo: achart.container,
                type: achart['graph_type']
            },
            xAxis: column_info_xaxis,
            yAxis: column_info_yaxis,
            colors: [
                '#33A6AB', 
                '#DC4B42', 
                '#89A54E', 
                '#80699B', 
                '#3D96AE', 
                '#DB843D', 
                '#92A8CD', 
                '#A47D7C', 
                '#B5CA92'
            ],
            credits: {
                enabled: false
            },                            
            title: {
                text: achart['title'],
                style: {
                    font: 'bold 17px arial,sans-serif',
                    color : '#333'
                }
            },
            legend: legend_type,            
            tooltip: tooltip_type,            
            plotOptions: plots,
            series: achart['aseries']
        }); 
    }
        
}