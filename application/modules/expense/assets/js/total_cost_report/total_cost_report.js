jQuery(document).ready(function(){
    //on load functions
    Site.datepicker("#datefrom");
    Site.datepicker("#dateto");
     Site.datepicker("#gdatefrom");
    Site.datepicker("#gdateto");
    
    //on change
    $("#tcr_view").change(function(){
        total_cost_report.year_view();
    });
    $("#tcrg_view").change(function(){
        total_cost_report_graph.view_graph();
    });
    
    //on click
    $("#specific_period_apply").click(function(){
        total_cost_report.specific_period();
    });
    $("#specific_period_graph_apply").click(function(){
        total_cost_report_graph.view_graph();
    });
    $("#total_cost_report_export").click(function(){
        total_cost_report.total_cost_report_export();
    });
    
    //graph
    if($("[name=page_action]").val() != ""){
        total_cost_report_graph.view_graph();
    }
    
});

var total_cost_report = {
    year_view : function(){
        var view = $("#tcr_view").val();
        if(view=="0"){
            $("#msg_tcr_view").html("Please choose how many years to display.");
        }else{
            $("#msg_tcr_view").html("");
            Site.page_redirect("total_cost_report?view="+view);
        }
    },
    
    specific_period : function() {
        var specific_period_from = $("#specific_period_from").val();
        var specific_period_to = $("#specific_period_to").val();
    
        if(Number(specific_period_from) > Number(specific_period_to)){
            $("#msg_mb10").html("Invalid year range.");
        }else{
            $("#msg_mb10").html("");
        } 
        
        if(specific_period_from == "0"){
            $("#msg_specific_period_from").html("Please choose a year.");
        }else{
            $("#msg_specific_period_from").html("");
        }
        
        if(specific_period_to == "0"){
            $("#msg_specific_period_to").html("Please choose a year.");
        }else{
            $("#msg_specific_period_to").html("");
        }
        
        if(specific_period_from != "0" && specific_period_to != "0" && Number(specific_period_from) <= Number(specific_period_to)){
            Site.page_redirect("total_cost_report?from="+specific_period_from+"&to="+specific_period_to);
        }
    },
    
    total_cost_report_export : function() {
        var view = $("#tcr_view").val();
        var sp_checked_option = $("[name=specific_period]:checked").val();
        var specific_period_from = $("#specific_period_from").val();
        var specific_period_to = $("#specific_period_to").val();
        var date_from = $("#date_from").val();
        var date_to = $("#date_to").val();
        
        Site.page_redirect("total_cost_report/total_cost_report_export?view="+view+"&specific_period_from="+specific_period_from+"&specific_period_to="+specific_period_to+"&date_from="+date_from+"&date_to="+date_to);
        
    }
}

var total_cost_report_graph = {
    view_graph : function(){
        var gsp_from = $("#gspecific_period_from").val();
        var gsp_to = $("#gspecific_period_to").val();
        var view_year = $("#tcrg_view").val();
        
        $('#total_cost_graph').fadeIn();
        var get_tag = {
            url : urls.ajax_url,
            type : "post",
            dataType: 'json',
            data :{
                mod:"expense|total_cost_report_exec|getYear",
                gsp_from : gsp_from,
                gsp_to : gsp_to,
                view_year : view_year
            },success : function(response){
            
                var categ = [];
                var total_cost = [];
                var annual_savings = [];
                var monthly_average = [];
                var yearly_average = [];
                var quarterly_average_1 = [];
                var quarterly_average_2 = [];
                var quarterly_average_3 = [];
                var quarterly_average_4 = [];
                $.each(response.year, function(key,val){
                    categ.push(val.pyear);
                });
                $.each(response.total_cost, function(key,val){
                    total_cost.push(parseFloat(val));
                });
                $.each(response.annual_savings, function(key,val){
                    annual_savings.push(parseFloat(val));
                });
                $.each(response.monthly_average, function(key,val){
                    monthly_average.push(parseFloat(val));
                });
                $.each(response.yearly_average, function(key,val){
                    yearly_average.push(parseFloat(val));
                });
                $.each(response.quarterly_average_1, function(key,val){
                    quarterly_average_1.push(parseFloat(val));
                });
                $.each(response.quarterly_average_2, function(key,val){
                    quarterly_average_2.push(parseFloat(val));
                });
                $.each(response.quarterly_average_3, function(key,val){
                    quarterly_average_3.push(parseFloat(val));
                });
                $.each(response.quarterly_average_4, function(key,val){
                    quarterly_average_4.push(parseFloat(val));
                });
                
                var aseries = [{
                    name: 'Total Cost',
                    data: total_cost
                }, {
                    name: 'Annual Savings',
                    data: annual_savings

                }, {
                    name: 'Monthly Average Total Cost',
                    data: monthly_average

                }, {
                    name: 'Yearly Average Total Cost',
                    data: yearly_average

                }, {
                    name: 'Q1 Quarterly average cost',
                    data: quarterly_average_1

                }, {
                    name: 'Q2 Quarterly average cost2',
                    data: quarterly_average_2

                }, {
                    name: 'Q3 Quarterly average cost',
                    data: quarterly_average_3

                }, {
                    name: 'Q4 Quarterly average cost4',
                    data: quarterly_average_4

                }];
                
                var achart = {
                    'title' : 'Total Cost Report Graph',        //title
                    'text_tooltip' : ' : Amount is ',           //'value in x axis'  : Amount is  'value in y axis';
                    'text_yAxis' : 'Total Payment Cost',        //text in y axis                            
                    'container': 'total_cost_graph',            //id of the container
                    'graph_type' : 'column',                    //graph type
                    'categ' : categ,                            //categories(array)
                    'aseries' : aseries                         //values(array)
                };
                Site.showChart(achart);                       
            }
        };
        $.ajax(get_tag);
    }
}