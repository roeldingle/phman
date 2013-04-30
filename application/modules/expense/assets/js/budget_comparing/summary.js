var summary = {    
    /*APPLY FOR SEARCH*/
    applySearch : function(){        
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        var row = $('#show_rows').val();
        window.location = urls.current_url+"?from="+calendar_from+"&to="+calendar_to+"&row="+row;
    },        
    
    addClass : function(){        
        $('.check').each(function() {            
            if($(this).html().indexOf('-') > 0){
                $(this).addClass('tfonts_6');
            }
        });   
        $('.tfonts_6').each(function() {
            $(this).html('('+$.trim($(this).html().replace('-', ''))+')');
        });  
    },
    
    resetSearch : function(){
        $('#calendar_from').val("");
        $('#calendar_to').val("");
        location.href = urls.current_url;
    },
        
    export_to_excel : function(){
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        var row = $('#show_rows').val();
        Site.page_redirect("/expense/budget_comparing_summary/export_to_excel?from="+calendar_from+"&to="+calendar_to+"&row="+row);
    }
};

var chart;
$(document).ready(function(){ 
    summary.addClass();

    if($('#obj_exist').val() == 0){
        $('#view_graph').hide();
        $('#export_excel').hide();
    } else {
        $('#view_graph').show();    
        $('#export_excel').show();    
    }
    
    $('#reset_search').click(function(){
        summary.resetSearch();
    });
    
    $('#export_excel').click(function(){
        summary.export_to_excel();
    });
    
    $('#show_rows').change(function(){
        summary.applySearch();
    });
    
    $('#apply_sort').click(function(){
        if($('#calendar_from').val() !="" && $('#calendar_to').val()!=""){
           summary.applySearch();
            $('#calendar_from').css({"border-color":"#ACACAC"});
            $('#calendar_to').css({"border-color":"#ACACAC"});
        } else {
            $('#calendar_from').css({"border-color":"red"});
            $('#calendar_to').css({"border-color":"red"});
            site.message("Enter valid specific period or sort by.",$(".message-container"),"warning");
        }
    });
    
    $('#view_graph').live('click', function(){
        if($('#summary_graph').is(':visible')){
            $(this).html('View Graph');
            $('#summary_graph').fadeOut();            
        } else {
            $(this).html('Hide Graph');
            $('#summary_graph').fadeIn();
            
                var get_tag = {
                    url : urls.ajax_url,
                    type : "post",
                    dataType: 'json',
                    data :{
                        mod:"expense|exec_budget_comparing|get_summary_graph",
                        offset : $('#offset').val(),
                        limit : $('#limit').val(),
                        calendar_from : $('#calendar_from').val(),
                        calendar_to : $('#calendar_to').val()
                    },success : function(response){
                        var categ = [];
                        var year = [];
                        var chyear = '';
                        var real_exp = [];
                        var planned_budget = [];
                        var amonths = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        
                        $.each(response, (function(key,val){
                            if(val.tel_year!=chyear){
                                year.push(val.tel_year);
                                chyear = val.tel_year;
                            }
                        }));
                        
                        var num = 0;
                        $.each(year, (function(kyear,vyear){ 
                                for(var ii=0;ii<12;ii++){ 
                                    categ.push(amonths[ii]+" "+vyear);
                                }
                                $.each(response,(function(key,val){
                                    for(var ii=0;ii<12;ii++){
                                        if(val.tel_month == amonths[ii] && val.tel_year == vyear) {   
                                            planned_budget[parseFloat(ii+num)] = parseFloat(val.planned_budget);
                                            real_exp[parseFloat(ii+num)] = parseFloat(val.real_exp);
                                        }                           
                                    }
                                })); 
                            num+=12;                           
                        }));
                        $.each(planned_budget, function(kplanned, vplanned) {
                            if (vplanned == undefined) {
                                planned_budget[kplanned] = 0;
                            }
                        });
                        $.each(real_exp, function(kreal, vreal) {
                            if (vreal == undefined) {
                                real_exp[kreal] = 0;
                            }
                        });
                                                
                        var aseries = [{
                            name: 'Real Expenses',
                            data: real_exp
                        }, {
                            name: 'Planned Budget',
                            data: planned_budget

                        }];
                        
                        var achart = {
                            'title' : 'Budget Comparing Summary Graph', //title
                            'text_tooltip' : ' : Amount is ',           //'value in x axis'  : Amount is  'value in y axis';
                            'text_yAxis' : 'Amount',                   //text in y axis                            
                            'container': 'summary_graph',              //id of the container
                            'graph_type' : 'column',                  //graph type
                            'categ' : categ,                          //categories(array)
                            'aseries' : aseries                      //values(array)
                        };
                        Site.showChart(achart);                       
                    }
                };
                $.ajax(get_tag);
        }   
    });
    
});