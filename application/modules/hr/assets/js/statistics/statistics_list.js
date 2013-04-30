var statistics_list = {  
    /*APPLY FOR SEARCH*/
    applySearch : function(menu){   
        
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        window.location = urls.current_url+"?from="+calendar_from+"&to="+calendar_to+"&menu="+menu;        
        
    },
    
    initialization : function(div){
        $('.view_graph').html('View Graph');
        $('#summary_graph').fadeOut();            
        $('#pie_probationary').fadeOut();  
        $('#pie_hired').fadeOut();  
        $('#pie_resigned').fadeOut();  
        $('#pie_all').fadeOut();  
            
        /*CONTENT*/
        // $("#"+div.attr('name')+"_container").show();
        // $("#"+div.attr('name')+"_container").siblings(':not([name="search_emp"], [name="menu_emp"])').hide(); //search div and the tab menu div
        
        /*TAB ACTIVATED*/
        $('[name="'+$("#menu").val()+'"]').attr('class', 'current');
        $('[name="'+$("#menu").val()+'"]').parent().siblings().children().attr('class', '');    

        $("#menu_tab").val(div.attr('name'));
    
    }
};
    
$(document).ready(function(){    
    $('#search_btn').click(function(){
        if($('#calendar_from').val()!="" && $('#calendar_to').val()!= "") {
            statistics_list.applySearch($('#menu_tab').val());
        } else {   
            site.message("Invalid date range.",$(".message-container"),"warning");
        }
    });
    
    $('#reset_btn').click(function(){
        $('#calendar_from').val("");
        $('#calendar_to').val("");        
        window.location = urls.current_url;
    });
    
    $('.view_reg').click(function(){
        $(this).css("color", "#fff");
        $(this).parent().siblings("a").children().css("color", "#555555");
        $("#"+$(this).attr("id")+"_container").show();
        $("#"+$(this).attr("id")+"_container").siblings("div").hide();
        $(".view_graph").html("View Graph");
        $("#summary_graph").hide();
        $("#pie_reg_by_dept").hide();
        $("#pie_reg_per_month").hide();
    });
    
    /*CALENDAR*/
    $('#calendar_from,#calendar_to').datepicker({
        showOn: 'button',        
        changeMonth: true,
        changeYear: true,
        yearRange: "-20:+0",
        showButtonPanel: true,
        dateFormat: 'yy-mm',
        buttonImage: urls.assets_url+'site/images/calendar-day.png', 
        buttonImageOnly: true, 
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });  
    
    $('#calendar_from,#calendar_to').attr('readonly','readonly');
    
    /*Search view tab*/
    var menu_name = 'ul[name="menu_emp"] li a[name="'+ $("#menu").val() +'"]';    
    statistics_list.initialization($(menu_name));
    
    /*TABS*/
    $('ul[name="menu_emp"] li a').click(function(){    
        // statistics_list.initialization($(this));
        statistics_list.applySearch($(this).attr('name'));
    });
    
    /*Export to Excel*/
    $('.export_excel').click(function(){
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        var page = $('#page_num').val();
        var type = $(this).attr("name");
        Site.page_redirect("/hr/statistics/export_to_excel?from="+calendar_from+"&to="+calendar_to+"&type="+type+"&page_num="+page);
    
    });
    
    /*VIEW GRAPH*/   
    $('.view_graph').live('click', function(){
        
        if($(this).attr("name") == "graph_hired")
        {
            var module = "hr|statistics_api|get_monthly_graph";
            var title = "Hired Employees";
            var tooltip = " : Amount of hired employees is ";
            var text_yaxis = "Number of hired employees";
            var emp_type = "";
            var div_name = "graph_hired";
            var pie_chart_id = "pie_hired";
        } else if($(this).attr("name") == "graph_probationary") {
            var module = "hr|statistics_api|get_by_department_graph";
            var title = "Probationary Employees";
            var tooltip = " : Number of probationary employees is ";
            var text_yaxis = "Number of probationary employees";
            var emp_type = "Probationary";
            var div_name = "graph_probationary";
            var pie_chart_id = "pie_probationary";
        } else if($(this).attr("name") == "graph_retired") {
            var module = "hr|statistics_api|get_monthly_graph";
            var title = "Retired Employees";
            var tooltip = " : Number of retired employees is ";
            var text_yaxis = "Number of retired employees";
            var emp_type = "Resigned";
            var div_name = "graph_retired";
            var pie_chart_id = "pie_resigned";
        } else if($(this).attr("name") == "graph_employees") {
            var module = "hr|statistics_api|get_by_department_graph";
            var title = "Number of Employees";
            var tooltip = " Department : Number of employees is ";
            var text_yaxis = "Number of employees";
            var div_name = "graph_employees";
            var emp_type = "";
            var pie_chart_id = "pie_all";
        } else if($(this).attr("name") == "graph_contractual") {
            var module = "hr|statistics_api|get_by_department_graph";
            var title = "Number of Contractual Employees";
            var tooltip = " Department : Number of employees is ";
            var text_yaxis = "Number of employees";
            var div_name = "graph_contractual";
            var emp_type = "Contractual";
            var pie_chart_id = "pie_contractual";
        } else if($(this).attr("name") == "graph_reg_per_month") {
            var module = "hr|statistics_api|get_monthly_graph";
            var title = "Regular Employees per Month";
            var tooltip = " : Number of retired employees is ";
            var text_yaxis = "Number of retired employees";
            var emp_type = "Regular";
            var div_name = "graph_reg_per_month";
            var pie_chart_id = "pie_reg_per_month";
        } else if($(this).attr("name") == "graph_reg_by_dept") {
            var module = "hr|statistics_api|get_by_department_graph";
            var title = "Regular Employees by Department";
            var tooltip = " Department : Number of employees is ";
            var text_yaxis = "Number of regular employees";
            var emp_type = "Regular";
            var div_name = "graph_reg_by_dept";
            var pie_chart_id = "pie_reg_by_dept";
        } 
        /*attendance*/
        else if($(this).attr("name") == "graph_by_dept_vacation_leave" || $(this).attr("name") == "graph_by_dept_sick_leave" || 
        $(this).attr("name") == "graph_by_dept_awol" || $(this).attr("name") == "graph_by_dept_lwop" || $(this).attr("name") == "graph_by_dept_tardiness") {
            var module = "hr|statistics_api|get_by_department_graph_attendance";
            var div_name = $(this).attr("name");
            var title = $("#attend_title").html();
            var tooltip = " Department : Number of employees is ";
            var text_yaxis = "Number of employees";
            var emp_type = "";
            var pie_chart_id = $(this).attr("name").replace("graph", "pie");
        }

		else if($(this).attr("name") == "graph_per_month_vacation_leave" || $(this).attr("name") == "graph_per_month_sick_leave" || 
        $(this).attr("name") == "graph_per_month_awol" || $(this).attr("name") == "graph_per_month_lwop" || $(this).attr("name") == "graph_per_month_tardiness") {
            var module = "hr|statistics_api|get_monthly_leave_graph";
            var div_name = $(this).attr("name");
            var title = $("#attend_month_title").html();
            var tooltip = " Leaves : Number of employees is ";
            var text_yaxis = "Number of employees";
			
			if(div_name == "graph_per_month_vacation_leave"){
				var emp_type = "1";
			}else if(div_name == "graph_per_month_sick_leave"){
				var emp_type = "2";
			}else if(div_name == "graph_per_month_tardiness"){
				var emp_type = "3";
			}else if(div_name == "graph_per_month_lwop"){
				var emp_type = "4";
			}else if(div_name == "graph_per_month_awol"){
				var emp_type = "5";
			}
							
            var pie_chart_id = $(this).attr("name").replace("graph", "pie");
        }
        
        if($('#summary_graph').is(':visible')){
            $(this).html('View Graph');
            $('#summary_graph').fadeOut();            
            $('#'+pie_chart_id).fadeOut();            
        } else {
            $(this).html('Hide Graph');
            $('#summary_graph').fadeIn();
            $('#'+pie_chart_id).fadeIn();               
                
                var get_tag = {
                    url : urls.ajax_url,
                    type : "get",
                    dataType: 'json',
                    data :{
                        mod:module,
                        page_num : $('#page_num').val(),
                        from : $('#calendar_from').val(),
                        to : $('#calendar_to').val(),
                        type : emp_type
                    },success : function(response){
                        var obj_pie_info = [];                        
                        var categ = [];
                        var myear = [];
                        var aseries = [];
                        var total_id = [];
                        
                        if( div_name == "graph_retired" || 
							div_name == "graph_hired" || 
							div_name == "graph_reg_per_month" ||
						    div_name == "graph_per_month_vacation_leave" ||
							div_name == "graph_per_month_sick_leave" ||
							div_name == "graph_per_month_tardiness" ||
							div_name == "graph_per_month_lwop" ||
							div_name == "graph_per_month_awol") {
							
                            $.each(response, function(i, object) {
                                $.each(object, function(property, value) {
                                    total_id = [];
                                    categ.push("");
                                    if(value['total_ids'] == null){
                                        value['total_ids'] = "0";
                                    }
                                    total_id.push(parseFloat(value['total_ids']));
                                    myear.push(value['month']+" "+value['year']);
                                    
                                    aseries.push({
                                        name: value['month'].substring(0,3)+" "+value['year'],
                                        data: total_id,
                                        dataLabels: {
                                            enabled: true,
                                            color: '#000000',
                                            align: 'center',
                                            x : 2,
                                            formatter: function() {
                                                return (this.series.name).replace(" ", "<br/>");
                                            },
                                            style: {
                                                font: 'normal 10px arial,sans-serif'
                                            }
                                        }
                                    });
                                });
                            });
                            
                            /*PIE CHART*/
                            $.each(response, function(i, object) {    
                                $.each(object, function(property, value) {
                                    if(value['total_ids'] == null){
                                        value['total_ids'] = "0";
                                    }
                                    obj_pie_info[property-1] = [value['month'].substring(0,3)+" "+value['year'], parseFloat(value['total_ids'])];
                                });
                            });                            
                            
                        }
                        else {
                            if(div_name == "graph_by_dept_vacation_leave" || div_name == "graph_by_dept_sick_leave" || 
                                div_name == "graph_by_dept_awol" || div_name == "graph_by_dept_lwop" || div_name == "graph_by_dept_tardiness"){
                                var name = div_name.replace("graph_by_dept_", "");
                            } else {
                                var name = 'total_ids';
                            }
                            
                            $.each(response, function(k, val) {
                                total_id = [];
                                categ.push("");
                                if(val[name] == null){
                                    val[name] = "0.00";
                                }
                                total_id.push(parseFloat(val[name]));
                                
                                aseries.push({
                                    name: val['dept_name'],
                                    data: total_id,
                                    dataLabels: {
                                        enabled: true,
                                        color: '#000000',
                                        align: 'center',
                                        x : 2,
                                        formatter: function() {
                                            return (this.series.name).replace(" ", "<br/>");
                                        },
                                        style: {
                                            font: 'normal 10px arial,sans-serif'
                                        }
                                    }                                
                                });
                            });                            
                            
                            /*PIE CHART*/
                            $.each(response, function(property, value) {
                                if(value[name] == null){
                                    value[name] = "0";
                                }
                                obj_pie_info[property] = [value['dept_name'], parseFloat(value[name])];
                            });
                        } 
                        
                        /*series for pie chart*/
                        var aseries_pie = [{
                            type: 'pie',
                            name: 'Percentage number of employees',
                            data: obj_pie_info
                        }];
                            
                        /*BAR CHART SERIES*/  
                        var achart = {
                            'title' : title + " Bar Chart",              // title
                            'text_tooltip' : tooltip,                   //'value in x axis'  : Amount is  'value in y axis';
                            'text_yAxis' : text_yaxis,                  //text in y axis                            
                            'container': 'summary_graph',               //id of the container
                            'graph_type' : 'column',                   //graph type
                            'categ' : categ,                           //categories(array)
                            'aseries' : aseries                       //values(array)
                        };
                        
                        /*PIE CHART SERIES*/
                        var achart_pie = {
                            'title' : title + " Pie Chart",                            // title
                            'text_tooltip' : tooltip,                     //'value in x axis'  : Amount is  'value in y axis';
                            'text_yAxis' : text_yaxis,                    //text in y axis                            
                            'container': pie_chart_id,                   //id of the container
                            'graph_type' : 'pie',                         //graph type
                            'aseries' : aseries_pie                       //values(array)
                        };                
                
                        Site.showChart(achart); //Column-Bar Chart                    
                        Site.showChart(achart_pie); //Pie Chart                     
                    }
                };
                
                $.ajax(get_tag);
                
        }   
    });
    
});