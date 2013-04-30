var current_id = 0;
var expected_expenses = {    
    /*X BUTTON IN DIALOG BOXES*/
    closeForm : function(){
        $('#new_form_expense').fadeOut();
        $('#delete_form').fadeOut();
        $('#ask_form').fadeOut();
    },
    
    /*UPDATING TOTAL AMOUNT*/
    countTotal : function(){
        var total = 0;
        /*minus 1 bec of the displaying the total row, 
        start with 3, bec. 
        1 for the header, 
        2 for the no records found row*/
        for(var c_exp=3;c_exp<=Number($('#table_next_month tr').length - 1);c_exp++){
            total+=expected_expenses.removeFormat($('#table_next_month tr:eq('+c_exp+') td:eq(2)').find("span[name='exp_price']").html());   
        }   
        $('#total_expected').html(expected_expenses.currencyFormat(total.toFixed(2))); 
        
        /*Check expected planned total*/    
        $("#total_expected").removeClass("tfonts_6");
        $("#total_expected").attr("title", "This amount is not higher than the previous month's paid amount");
                
        var d = new Date();
        if(expected_expenses.removeFormat($("#total_expected").html()) > expected_expenses.removeFormat($('[name="last_payment"]').val())){
            $("#total_expected").addClass("tfonts_6");
            $("#total_expected").attr("title", "This amount is higher than the previous month's paid amount");
        }
    },
    
    /*UPDATING TOTAL AMOUNT for Last Month's Recent Expenses*/
    countRecentTotal : function(type){        
        var d = new Date();
        var total = 0;
        var strId = "recent_"+parseFloat(d.getMonth()+1) + "/" + (d.getFullYear());
        var div_check = "table[id='"+strId+"']";
        
        if(type=="planned"){
            var div_total = "recent_plannedtotal";
            var span_total = "price_number";
            var column_number = 3;
        } else {
            var div_total = "recent_paymenttotal";
            var span_total = "amnt_payment";
            var column_number = 4;
        }
        for(var c_exp=2;c_exp<=Number($(div_check+" tr").length - 1);c_exp++){
            total+=expected_expenses.removeFormat($(div_check+' tr:eq('+c_exp+') td:eq('+column_number+')').find("span[name='"+span_total+"']").html());   
        }   
        $(div_check+' [name="'+div_total+'"]').html(expected_expenses.currencyFormat(total.toFixed(2))); 
    },
        
    /*Currency Format*/
    currencyFormat : function(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    },
    
    /*Remove currency Format*/
    removeFormat : function(string)
    {        
        return parseFloat(string.replace(/[^\d.]/g,""));
    },
    
    /*EXECUTING DELETION*/
    deleteExpected : function(){
        var idx = 0;        
        var aIdx = new Array();
        /*FOR THE CHECK BOXES IN EXPECTED EXPENSES TABLE -removing*/
        $("input[name='check_expected']:checked").each(function(){
            $(this).parent().parent().remove();  
            idx = $(this).val();        

            /*Array for idx*/
            aIdx.push(idx);
            
            /*FOR THE HIDDEN ID IN RECENT EXPENSES TABLE -enabling the box*/
            $("input[name='tel_idx']").each(function(){
                if($(this).val() == idx){
                    $(this).parent().parent().find('input[name="check_recent"]').attr('disabled', false);
                    $(this).parent().parent().find('input[name="check_recent"]').attr('checked', false);
                    $(this).parent().parent().find('input[name="check_recent"]').attr('class', 'not_done');
                }
            });            
        });        
        /*Update total count*/
        expected_expenses.countTotal();
        $('#delete_form').fadeOut();
        
        if($('#table_next_month tr').length == 3 && $('#no_records').is(':hidden')){
            $('#expected_total').hide();
            $("#no_records").show(); 
        }
        
        var get_tag = {
            url : urls.ajax_url,
            type : "post",
            dataType: 'json',
            data :{
                mod:"expense|exec_budget_planning|delete_expense",
                exp_idx : aIdx, 
                exp_total : $('#total_expected').html()
            },success : function(response){ 
                if(response > 0){ 
                    site.message("Successfully Deleted Expected Expense",$(".message-container"),"success");
                }
            }
        };
        $.ajax(get_tag);
    },
    
    /*ADD and EDIT form Save button*/
    saveForm : function(){      
        if(isNaN(expected_expenses.removeFormat($('#exp_amount').val())) || expected_expenses.removeFormat($('#exp_amount').val())<1){
            site.message("Please enter valid amount.",$(".message-container"),"warning");
        } else if($('#exp_date').val() == ""){
            site.message("Please enter its Expected Payment Date.",$(".message-container"),"warning");
        } else if($('#exp_desc').val() == ""){
            site.message("Please enter its description.",$(".message-container"),"warning");
        } else {                  
            var form_ename = $('#exp_categ').find(':selected').html();
            var form_ecateg_id = $('#exp_categ').val();
            var form_eprice = expected_expenses.removeFormat($('#exp_amount').val()); //expected_expenses.currencyFormat(Number($('#exp_amount').val()).toFixed(2));
            var form_epayment = expected_expenses.removeFormat($('#exp_payment').val()); //expected_expenses.currencyFormat(Number($('#exp_payment').val()).toFixed(2));
            var form_edesc = $('#exp_desc').val();
            var form_edate = $('#exp_date').val();
            var eday = form_edate.split('/');
            var form_idx = $('input[name="check_expected"]:checked').val();
            
            if($('#title_action').html() == "Add Expected Expense"){  
                var expected_tr = "<tr>\
                                    <td class='ac'><input type='checkbox' name='check_expected' value='"+current_id+"'/></td>\
                                    <td><a href='javascript:void(0);' name='view_info' title='View Expense Information'><span name='exp_name'>"+form_ename+"</span></a>\
                                    <input type='hidden' name='exp_categ_id' value='"+form_ecateg_id+"'></td>\
                                    <td class='ac'><span name='exp_price'>"+expected_expenses.currencyFormat(Number(form_eprice).toFixed(2))+"</span>\
                                    <input type='hidden' name='exp_expesedesc' value='"+form_edesc+"'></td>\
                                    <td class='ac'><span name='exp_expesedate'>"+form_edate+"</span></td>\
                                   </tr>";
                $('#expected_table').append(expected_tr);   
                
                /*Check the number of rows.*/   
                expected_expenses.checkTable();          
                
                /*Update total count*/        
                expected_expenses.countTotal(); 
                expected_expenses.autoSave("add", form_ename, form_ecateg_id, form_eprice, 0, form_edesc, form_edate, "expected", 0); 
                site.message("Successfully Added Expected Expense!",$(".message-container"),"success");
            } else if($('#title_action').html() == "Edit Expected Expense"){  
                var parent_name = $("input[name='check_expected']:checked").parent().parent();
                parent_name.find('[name="exp_categ_id"]').val(form_ecateg_id);
                parent_name.find('[name="exp_name"]').html(form_ename);
                parent_name.find('[name="exp_expesedesc"]').val(form_edesc);
                parent_name.find('[name="exp_expesedate"]').html(form_edate);
                parent_name.find('[name="exp_price"]').html(expected_expenses.currencyFormat(Number(form_eprice).toFixed(2))); 
                parent_name.css('background-color', '#FFFFFF');
                parent_name.find('[name="exp_price"]').css('color', '#888888');
                parent_name.find('[name="exp_expesedate"]').css('color', '#888888');
                parent_name.css('font-weight', 'normal'); 
                
                /*Update total count*/
                expected_expenses.countTotal(); 
                expected_expenses.autoSave("edit", form_ename, form_ecateg_id, form_eprice, 0, form_edesc, form_edate, "expected", form_idx); 
                site.message("Successfully Updated Expected Expense!",$(".message-container"),"success");
            } else if($('#title_action').html() == "Add Expense for the current Month"){       
                /*Save to db*/
                expected_expenses.autoSave("add", form_ename, form_ecateg_id, form_eprice, form_epayment, form_edesc, form_edate, "recent", 0); 
                /*Placing to the page*/
                var recent_tr = '<tr>';                
                if($("#usergrade").val() == "000001" || $("#usergrade").val() == "000002"){
                    recent_tr += '<td class="ac"><input type="checkbox" name="check_recent"class="not_done"/></td>';
                }
                recent_tr +=  '<td class="ac"><input type="hidden" value="'+current_id+'" name="tel_idx"><span name="recent_day">'+eday[2]+'</span></td>\
                        <td><a href="javascript:void(0);" name="view_info" title="View Expense Information"><span name="categ_name">'+form_ename+'</span><input type="hidden" name="categ_id" value="'+form_ecateg_id+'">\
                        <input type="hidden" name="categ_desc" value="'+form_edesc+'"></td>\
                        <td class="ac"><span name="price_number">'+expected_expenses.currencyFormat(Number(form_eprice).toFixed(2))+'</span></td>\
                        <td class="ac"><span name="amnt_payment">'+expected_expenses.currencyFormat(Number(form_epayment).toFixed(2))+'</span></td>\
                        </tr>';        
                        
                $('#recent_table_exp').append(recent_tr); 
                site.message("Successfully Added Recent Expense!",$(".message-container"),"success");
            } else {                  
                var form_idx = $("input[name='check_recent']:checked").parent().parent().find('[name="tel_idx"]').val();
                /*Save to db*/
                expected_expenses.autoSave("edit", form_ename, form_ecateg_id, form_eprice, form_epayment, form_edesc, form_edate, "recent", form_idx); 
                /*Placing to the page*/
                var parent_name = $("input[name='check_recent']:checked").parent().parent();
                parent_name.find('[name="recent_day"]').html(eday[2]);
                parent_name.find('[name="amnt_payment"]').html(expected_expenses.currencyFormat(Number(form_epayment).toFixed(2)));
                site.message("Successfully Updated Recent Expense!",$(".message-container"),"success"); 
            }
            
            expected_expenses.cancelForm();           
            $('#new_form_expense').fadeOut();  
        }
    },
    
    autoSave : function(action, form_ename, form_ecateg_id, form_eprice, form_epayment, form_edesc, form_edate, form_type, form_idx){    
        var aCategId = new Array();
        var aAmount = new Array();
        var aPayment = new Array();
        var aDesc = new Array();
        var aDate = new Array();
        
        if(action == "add"){
            var module = "add_expected";
        } else if(action == "edit"){
            var module = "edit_expenses";
        }
        
        if(form_type == "recent"){
            var form_total = form_eprice;
        } else if(form_type == "expected"){
            var form_total = $('#total_expected').html();
        }         
        
        aCategId[0] = form_ecateg_id;
        aAmount[0] = form_eprice;
        aPayment[0] = form_epayment;
        aDesc[0] = form_edesc;
        aDate[0] = form_edate;
        var get_tag = {
            url : urls.ajax_url,
            type : "post",
            dataType: 'json',
            data :{
                mod:"expense|exec_budget_planning|"+module,
                exp_idx : form_idx,
                exp_amount : aAmount,
                exp_payment : aPayment,
                exp_desc : aDesc,
                exp_date : aDate,
                exp_categ : aCategId, 
                exp_type : form_type,
                exp_total : form_total
            },success : function(response){ 
                if(response > 0){  
                    current_id = response;
                    if((action == "add" || action == "edit") && form_type == "recent") {  
                        expected_expenses.countRecentTotal('planned');
                        expected_expenses.countRecentTotal('paid');
                        $("[name='last_payment']").val($("[name='recent_paymenttotal']").html());
                    }
                }
            }
        };
        $.ajax(get_tag);
    
    },
    
    /*ADD TO EXPECTED EXPENSES*/
    showAll : function(){         
        if($("input[name='check_recent']:checked").length >= 1) {
            var iNum = 0;            
            var aCateg = new Array();
            var aNum = new Array();
            var aAmount = new Array();  
            var iCateg = new Array(); 
            var sDesc = new Array(); 
            
            $('#expected_total').show();
            $("#no_records").hide();
            
            $("input[name='check_recent']:checked").each(function(){
                if($(this).attr('class') != 'done') 
                {
                    $(this).attr('class', 'done');
                    // $(this).attr('disabled', true);        
                    aNum[iNum] = $(this).parent().parent().find('input[name="tel_idx"]').val();
                    aCateg[iNum] = $(this).parent().parent().find('span[name="categ_name"]').html();
                    aAmount[iNum] = $(this).parent().parent().find('span[name="price_number"]').html();
                    iCateg[iNum] = $(this).parent().parent().find('input[name="categ_id"]').val();
                    sDesc[iNum] = $(this).parent().parent().find('input[name="categ_desc"]').val();
                    
                    /*Save to db*/
                    expected_expenses.autoSave("add", aCateg[iNum], iCateg[iNum], aAmount[iNum], 0, "", "", "expected", 0); 
                    var expected_tr = "<tr>\
                                        <td class='ac'><input type='checkbox' name='check_expected' value='"+current_id+"'/></td>\
                                        <td><a href='javascript:void(0);' name='view_info' title='View Expense Information'><span name='exp_name'>"+aCateg[iNum]+"</span></a>\
                                        <input type='hidden' name='exp_categ_id' value='"+iCateg[iNum]+"'></td>\
                                        <td class='ac'><span name='exp_price'>"+aAmount[iNum]+"</span>\
                                        <input type='hidden' name='exp_expesedesc' value='"+sDesc[iNum]+"'></td>\
                                        <td class='ac'><span name='exp_expesedate'></span></td>\
                                    </tr>";
                    $('#expected_table').append(expected_tr); 
                    iNum++;   
                }   
            }); 
            /*Update total count*/
            expected_expenses.countTotal();
        } else {
            site.message("Select at least one in the recent expenses table.",$(".message-container"),"warning");
        }
    },
    
    /*SAVING THE INFORMATION*/
    saveExpectedExp : function(){
        var err = 0;
        if($('#table_next_month tr').length > 3){ //total , header , no records
            var aCategId = new Array();
            var aAmount = new Array();
            var aDesc = new Array();
            var aDate = new Array();
            var aIdx = new Array();
            
            for(var c_exp=3;c_exp<=Number($('#table_next_month tr').length - 1);c_exp++){
                aIdx.push($('#table_next_month tr:eq('+c_exp+')').find("input[name='check_expected']").val());   
                aCategId.push($('#table_next_month tr:eq('+c_exp+')').find("input[name='exp_categ_id']").val());   
                aAmount.push(expected_expenses.removeFormat($('#table_next_month tr:eq('+c_exp+')').find("span[name='exp_price']").html()));   
                aDesc.push($('#table_next_month tr:eq('+c_exp+')').find("input[name='exp_expesedesc']").val()); 
                aDate.push($('#table_next_month tr:eq('+c_exp+')').find("span[name='exp_expesedate']").html()); 
                
                var td_val = $('#table_next_month tr:eq('+c_exp+')');
                if(td_val.find("span[name='exp_price']").html() == "0.00" || td_val.find("span[name='exp_expesedate']").html() == "" || td_val.find("input[name='exp_expesedesc']").val() == ""){
                    $('#table_next_month tr:eq('+c_exp+')').css('background-color', '#EED3D7');
                    $('#table_next_month tr:eq('+c_exp+')').css('color', '#B94A48');
                    $('#table_next_month tr:eq('+c_exp+')').css('font-weight', 'bold');
                    
                    site.message("Please complete the form for the selected expected expenses.",$(".message-container"),"warning");
                    err +=1;
                } 
            }    
            if(err == 0){
                var get_tag = {
                    url : urls.ajax_url,
                    type : "post",
                    dataType: 'json',
                    data :{
                        mod:"expense|exec_budget_planning|add_expected",
                        exp_amount : aAmount,
                        exp_desc : aDesc,
                        exp_date : aDate,
                        exp_categ : aCategId, 
                        exp_idx : aIdx, 
                        exp_type : "expected_list",
                        exp_total : $('#total_expected').html()
                    },success : function(response){
                        if(response >0){ 
                            site.message("Successfully Saved!",$(".message-container"),"success");                         
                            expected_expenses.cancelForm();
                        }
                    }
                };
                $.ajax(get_tag);
            } else {
                site.message("Error Saving!! Please complete the required information.",$(".message-container"),"warning");
            }            
        } else {
            site.message("Error Saving!! No record for expected expenses.",$(".message-container"),"warning");
        }
    },
    
    /*CANCEL BUTTON IN THE FORMS*/
    cancelForm : function(){
        expected_expenses.closeForm();
        $("input[name='check_expected']").attr('checked', false);
        $('select[id=select_action]').val("");
        $("[name='check_recent']").attr('checked', false);
    },
    
    /*INITIALIZATION*/
    initialization : function(){
        expected_expenses.closeForm();
        $('select[id=select_action]').val("");
        $("input[name='check_expected']").attr('checked', false);
        $("input[name='check_recent']").attr('disabled', false);
    },
    
    /*DISPLAYING THE EDIT FORM*/
    editFormDisplay : function(recent){
        var count_expected = 0;
        if(recent == 1){
            var d = new Date();
            var strId = "recent_"+parseFloat(d.getMonth()+1) + "/" + (d.getFullYear());
            var div_check = "table[id='"+strId+"'] [name='check_recent']";
            var tb_name = "Recent";
            var parent_name = $("input[name='check_recent']:checked");
        } else {
            var div_check = "input[name='check_expected']";
            var parent_name = $("input[name='check_expected']:checked");
            var tb_name = "Expected";
        }
        
        $(div_check).each(function(){
            if ($(this).is(":checked") === true){
                count_expected++;
            }             
        }); /*COUNT THE CHECKED BOXES*/
        
        if(count_expected > 1){
            site.message("Error!! Edit only 1 expense at a time.",$(".message-container"),"warning");
        } else if(count_expected == 0){
            site.message("Error!! Select one record in the "+tb_name+" expenses table.",$(".message-container"),"warning");
        } else {
            $('#title_action').html("Edit "+tb_name+" Expense");
            
            /*Putting information in dialog box*/
            if(recent == 1){
                expected_expenses.gettingRecentInformation(parent_name);     
            } else {
                expected_expenses.gettingInformation(parent_name); 
            }                
        }
    },
    
    /*Getting information in the form*/
    gettingInformation : function(main){
        /*CURRENT INFORMATION*/
        var parent_name = main.parent().parent();
        var e_categ_id = parent_name.find('[name="exp_name"]').html();
        var e_exp_date = parent_name.find('[name="exp_expesedate"]').html();
        var e_descname = parent_name.find('[name="exp_expesedesc"]').val();
        var e_priceval = parent_name.find('[name="exp_price"]').html();
        expected_expenses.setToForm(e_priceval, e_descname, e_categ_id, e_exp_date, 0);
    },
    
    /*Getting information in the form --RECENT*/ 
    gettingRecentInformation : function(main){
        /*CURRENT INFORMATION*/        
        var d = new Date();
        var parent_name = main.parent().parent();
        var e_tel_idx = parent_name.find('[name="tel_idx"]').val();
        var e_categ_id = parent_name.find('[name="categ_name"]').html();
        var e_exp_date = d.getFullYear() + "/"+ parseFloat(d.getMonth()+1) + "/" +$.trim(parent_name.find('span[name="recent_day"]').html());
        var e_descname = parent_name.find('[name="categ_desc"]').val();
        var e_priceval = parent_name.find('[name="price_number"]').html(); 
        var e_payment  = parent_name.find('[name="amnt_payment"]').html();
        expected_expenses.setToForm(e_priceval, e_descname, e_categ_id, e_exp_date, e_payment);
    },
    
    /*Putting the data to the form*/
    setToForm : function(e_priceval, e_descname, e_categ_id, e_exp_date, e_payment){
        $('#exp_amount').val(e_priceval);
        $('#exp_desc').val(e_descname);
        $('#exp_categ option:contains("' + e_categ_id + '")').prop('selected', true);
        $('#exp_date').val(e_exp_date);
        $('#exp_payment').val(e_payment);
        
        $('#new_form_expense').fadeIn();
        expected_expenses.scrollDown();
    },
    
    /*DISPLAYING THE DELETE FORM*/
    delFormDisplay : function(){
        var count_expected = 0;
        var val_expected = new Array();
        $("input[name='check_expected']").each(function(){
            if ($(this).is(":checked") === true){
                val_expected[count_expected] = $(this).val();
                count_expected++;
            }
        });
        
        if(count_expected == 0){
            site.message("Error!! Select one record in the expected expenses table.",$(".message-container"),"warning");
        } else {
            $('#num_delete').html(count_expected);
            $('#delete_form').fadeIn();    
            expected_expenses.scrollDown();            
        }
    },
  
    
    /*APPLY FOR SEARCH*/
    applySearch : function(){          
        var sort_by = $('select[name="sort_by"]').val();
        var row = $('#show_rows').val();
        var row_exp = $('#show_rows_expected').val();
        
        if($("input[name='specific_search']:checked").val() == 'search_date'){  
            var calendar_from = $('#calendar_from').val();
            var calendar_to = $('#calendar_to').val();
            window.location = urls.current_url+"?from="+calendar_from+"&to="+calendar_to+"&sort="+sort_by+"&row_rec="+row+"&row_exp="+row_exp;
        } else {
            var cutoff_from = $('#cutoff_from').val();
            var cutoff_to = $('#cutoff_to').val();
            window.location = urls.current_url+"?cutoff_from="+cutoff_from+"&cutoff_to="+cutoff_to+"&sort="+sort_by+"&row_rec="+row+"&row_exp="+row_exp;
        }
    },
    
    /*RESET ADD / EDIT FORM*/
    resetFormBox : function(){
        $('#exp_amount').val(""),
        $('#exp_desc').val(""),
        $('#exp_payment').val(""),
        $('#exp_date').val(""),
        $('#exp_categ').val("00000000001");
    },
    
    /*IF THE NO RECORD FOUND WILL BE SEEN OR NOT*/
    checkTable : function(){
        if($('#table_next_month tr').length == 3){ //total , header , no records
            $("#expected_total").hide();
            $('#no_records').show();
        } else{
            $('#no_records').hide();
            $("#expected_total").show();
        }
    },
    
    /*VIEW INFORMATION*/
    viewInfo : function(parent, type){
        $('#title_action').html("View Expense Information");
        $('#save_form').hide();
        $('.cancel_form').hide();
        $('#exp_amount').attr('disabled', true);
        $('#exp_desc').attr('disabled', true);
        $('#exp_categ').attr('disabled', true);
        $('#exp_date').attr('disabled', true);
        if(type == 1){
            expected_expenses.gettingInformation(parent);  
            $('#payment_amount_tr').hide(); 
        } else {
            expected_expenses.gettingRecentInformation(parent);   
            $('#exp_payment').attr('disabled', true);
        }
    },
    
    export_to_excel : function(){
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        var sort_by = $('select[name="sort_by"]').val();
        var row = $('#show_rows').val();
        var limit = $('#limit').val();
        var offset = $('#offset').val();
        Site.page_redirect("/expense/budget_planning/export_to_excel?from="+calendar_from+"&to="+calendar_to+"&sort="+sort_by+"&limit="+limit+"&offset="+offset);
    },
    
    show_form : function(){
        $('#save_form').show();
        $('.cancel_form').show();
        $('#exp_amount').attr('disabled', false);
        $('#exp_desc').attr('disabled', false);
        $('#exp_categ').attr('disabled', false);
        $('#exp_date').attr('disabled', false);          
        $('#exp_payment').attr('disabled', false);
    },
    
    date_picker : function(imonth){
        $('#exp_date').datepicker("destroy");
        var date = new Date();
        var minDate = new Date(date.getFullYear(), date.getMonth()+imonth, 1);
        var maxDate =  new Date(date.getFullYear(), date.getMonth() +(imonth+1) , -0);
        /*Datepicker showing the next month*/
        $('#exp_date').datepicker({
            showOn: 'button',   
            dateFormat: 'yy/mm/dd',
            buttonImage: urls.assets_url+'site/images/calendar-day.png', 
            buttonImageOnly: true, 
            hideIfNoPrevNext: true,
            minDate: minDate,
            maxDate: maxDate
        });      
        $('#exp_date').attr('readonly','readonly');
    },
    
    /*Reset Form*/
    resetForm : function(){        
        $('#calendar_from').val("");
        $('#calendar_to').val("");
        $('#cutoff_to').val("1");
        $('#cutoff_from').val("1");
        $('input[name="specific_search"]')[0].checked = true;
        $('input[name="specific_search"]')[1].checked = false;
    },
    
    scrollDown : function(){
        var div = "#new_form_expense";
        if(!$("#new_form_expense").is(":visible")){
            div = "#delete_form";
        }
        $('html, body').animate({
          scrollTop: $(div).offset().top + $('window').height()
        }, 1000);
    }
};
    
$(document).ready(function(){    
    $(".ui-datepicker-calendar").show();
    $('#exp_amount, #exp_payment').keyup(function(){        
        var value = expected_expenses.removeFormat($(this).val());
        
        var check_num = $(this).val().substring($(this).val().length, $(this).val().indexOf(".")+1); //0.0 ->erk
        var end = $(this).val().substr($(this).val().length-1, 1);
        
        if(isNaN($(this).val().replace(/,/g, '')) && (isNaN(end) && end != "." || check_num.indexOf(".")>=0)){    //check_num!="" && check_num.indexOf(".")>=0) // && (isNaN(end) && end != "." || check_num.indexOf(".")>=0)
            $(this).val($(this).val().substring(0, $(this).val().length-1));
            site.message("Please enter valid amount.",$("#form_err"),"warning");
        } else {
            if(($(this).val().indexOf(".")<0 || $(this).val()!="") && (isNaN($(this).val().replace(/,/g, '')) || (end != "." && end != 0))){ //$(this).val().indexOf(".")<0 && 
                var value = expected_expenses.removeFormat($(this).val());
                $(this).val(expected_expenses.currencyFormat(value));
            }
            
        }
    });
    
    $('#exp_amount, #exp_payment').blur(function(){
        var value = expected_expenses.removeFormat($(this).val());
        $(this).val(expected_expenses.currencyFormat(value.toFixed(2)));
    });
    
    expected_expenses.checkTable();
    expected_expenses.initialization();    
    expected_expenses.countTotal();    
    
    /*Centered Form*/
    $('#new_form_expense, #delete_form').css({top:'50%',left:'50%',margin:'-'+($('#new_form_expense').height() / 2)+'px 0 0 -'+($('#new_form_expense').width() / 2)+'px'});    

    $('input[name="check_all"]').click(function(){
        if($(this).is(':checked') == true){
            $("input[name=check_expected]").attr('checked', true);
        }else {
            $("input[name=check_expected]").attr('checked', false);  
        } 
    });
    
    $('#show_rows_expected, #show_rows, select[name="sort_by"]').change(function(){
        expected_expenses.applySearch();
    });
    
    $('#export_excel').click(function(){
        expected_expenses.export_to_excel();
    });
    
    $('#table_next_month, table[name="recent_table"]').delegate("[name='view_info']", "click", function(){
        var type = 0;
        if($(this).parent().parent().parent().attr('id') == 'expected_table'){
            type = 1;
        }
        expected_expenses.viewInfo($(this), type);
    });
    
    $('#save_form').click(function(){
        expected_expenses.saveForm();
    });
    
    $('#apply_reset').click(function(){
        expected_expenses.resetForm();
        window.location = urls.current_url;
    });
    
    $('#apply_search').click(function(){
        if($("input[name='specific_search']:checked").val() == 'search_date'){
            var to = $('#calendar_to');
            var from = $('#calendar_from');
            var desc = "";
        } else {
            var to = $('#cutoff_to');
            var from = $('#cutoff_from');   
            var desc = " cut off";
        }
        if(from.val() !="" && to.val()!=""){
            expected_expenses.applySearch();
            from.css({"border-color":"#ACACAC"});
            to.css({"border-color":"#ACACAC"});
        } else {
            // site.message("Enter valid specific"+desc+" period.",$(".message-container"),"warning");        
            from.css({"border-color":"red"});
            to.css({"border-color":"red"});
        }
        
    });
    
    $('a[name="add_to_recent"]').click(function(){
        expected_expenses.showAll();
    });
    
    $('#delete_expected').click(function(){
        expected_expenses.deleteExpected();
    });
    
    $('.close_form').click(function(){
        expected_expenses.closeForm();
    });
    
    $('.cancel_form').click(function(){
        expected_expenses.cancelForm();
    });
    
    $('#save_expform').click(function(){
        expected_expenses.saveExpectedExp();
    });
    
    $('#add_action').click(function(){
        $("#payment_amount_tr").hide();
        expected_expenses.show_form();
        $('#title_action').html("Add Expected Expense");
        expected_expenses.resetFormBox();              
        expected_expenses.date_picker(1);   
        $('#new_form_expense').fadeIn();
        expected_expenses.scrollDown();
    });    
    
    $('a[name="add_recent"]').click(function(){
        $("#recent_actions").val("add_recent");
        $("#payment_amount_tr").show();
        expected_expenses.show_form();
        $('#title_action').html("Add Expense for the current Month");
        expected_expenses.resetFormBox();  
        expected_expenses.date_picker(0);        
        $('#new_form_expense').fadeIn();
        expected_expenses.scrollDown();
    });
    
    $('a[name="edit_recent"]').click(function(){
        $("#recent_actions").val("edit_recent");
        $("#payment_amount_tr").show();
        expected_expenses.show_form();        
        expected_expenses.date_picker(0);          
        $('#exp_payment').show();
        $('#exp_date').show();
        $('#exp_amount').attr('disabled', true);
        $('#exp_desc').attr('disabled', true);
        $('#exp_categ').attr('disabled', true);
        expected_expenses.editFormDisplay(1);
    });
    
    $('#delete_action').click(function(){
        expected_expenses.show_form();
        expected_expenses.delFormDisplay();
    });
    
    $('#edit_action').click(function(){
        expected_expenses.date_picker(1);
        $("#payment_amount_tr").hide();
        expected_expenses.show_form();
        expected_expenses.editFormDisplay(0);
    });    
});