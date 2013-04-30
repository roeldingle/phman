//default values
var saved_type = $("[name=saved_type]").val();
var etype = $("[name=page_action]").val() == "edit_real_expense" ? saved_type : "expenses";
var gqty = 0;
var total_payment = 0;
var clicky;
var target;
var blur_array = [];
var curr_page_url;
var curr_page;

jQuery(document).ready(function(){
   //get current page
   curr_page_url = window.location.pathname;
   var acurr_page = curr_page_url.split("/");
   curr_page = acurr_page[2];
        
   //default call functions
   expense.disabled();
   expense.changeType();
   
   Site.datepicker("#edate");
   Site.datepicker("#datefrom");
   Site.datepicker("#dateto");
  
   //type field options
   if($("[name=edepartment] option:selected").attr("deptname") == "Head Office"){
        var sHtml = "";
        sHtml += "<option value='Return'>Return</option>";
        $("[name=etype]").empty().append(sHtml);
   }else{
        var sHtml = "";
        var expense_selected = "";
        var inout_selected = "";
        
        if(saved_type == "expenses"){
            expense_selected = "selected";
            inout_selected = "";
        }else if(saved_type == "in-out"){
            expense_selected = "";
            inout_selected = "selected";
        }else{
            expense_selected = "";
            inout_selected = "";
        }
        if(curr_page != "add_new_expense"){
            sHtml += "<option value='expenses' "+expense_selected+">Expenses</option>";
        }
        sHtml += "<option value='in-out' "+inout_selected+">In-Out</option>";
        $("[name=etype]").empty().append(sHtml);
   }
   
   //on change 
   $("[name=edepartment]").change(function(){
        expense.disabled();
        if($("[name=edepartment] option:selected").attr("deptname") == "Head Office"){
            var sHtml = "";
            sHtml += "<option value='Return'>Return</option>";
            $("[name=etype]").empty().append(sHtml);
            etype="Return"
        }else{
            var sHtml = "";
            var expense_selected = "";
            var inout_selected = "";
        
            if(saved_type == "expenses"){
                expense_selected = "selected";
                inout_selected = "";
            }else if(saved_type == "in-out"){
                expense_selected = "";
                inout_selected = "selected";
            }else{
                expense_selected = "";
                inout_selected = "";
            }
            if(curr_page != "add_new_expense"){
                sHtml += "<option value='expenses' "+expense_selected+">Expenses</option>";
            }
            sHtml += "<option value='in-out' "+inout_selected+">In-Out</option>";
            $("[name=etype]").empty().append(sHtml);
            etype = saved_type;
        }
       expense.changeType();
       expense.removeValidations();
   });
   $("[name=etype]").live('change', function(){
        etype = $("[name=etype]").val();
        expense.changeType();
        expense.removeValidations();
   });
   $("[name=estatus]").live('change', function(){
        expense.removeValidations();
        expense.changeStatus();
   });
   $("[name=equantity]").change(function(){
        expense.costboxes();
        if($("[name=page_action]").val() == "edit_real_expense"){
            expense.get_cost_per_items_edit();
        }
   });
   $("[name=erdepositamt]").change(function(){
        if($(this).val() != ""){
            expense.check_deposit_amt();
        }
   });
   $("[name=ertransferamt]").change(function(){
        if($(this).val() != ""){
            expense.check_transfer_amt();
        }
   });
   $("[name=select_action]").change(function(){
        expense.apply_action();
   });
   $("[name=ecategory]").change(function(){
        $("[name=selected_category]").val($(this).val());
   });
   
   //on click
   $("#del_btn").click(function(){
        expense.delete_real_expenses_item_list();
   });
   $("#edit_btn").click(function(){
        expense.edit_real_expense_item();
   });
   $("#specific_period").click(function(){
        var page_action = $("[name=page_action]").val();
        expense.specific_period(page_action);
   });
   $("#detailed_export").click(function(){
        expense.export_real_expense();
   });
   $("#resettodefault").click(function(){
        expense.resettodefault();
   });
   $("[name=header_real_expenses_records]").click(function(){
        if(!$(this).attr('checked')){
            $("#msg_select1").html("");
            $("[name=real_expenses_records]").removeAttr('checked');
        }else{
            $("#msg_select1").html("");
            $("[name=real_expenses_records]").attr('checked', 'checked');
        }
   });
   
   if($("[name=page_action]").val() == "edit_real_expense"){
       expense.costboxes();
       expense.get_cost_per_items_edit();
       expense.get_request_bill_attachment();
       expense.get_receipt_attachment();
   }
   
   //on blur
   $("[name=erdepositamt]").blur(function(){
        $(this).toNumber().formatCurrency();
        var erdepositamt = $(this).val();
        var new_erdepositamt = erdepositamt.replace("$", "");
        $(this).val(new_erdepositamt);
   });
   
   $("[name=ertransferamt]").blur(function(){
        $(this).toNumber().formatCurrency();
        var ertransferamt = $(this).val();
        var new_ertransferamt = ertransferamt.replace("$", "");
        $(this).val(new_ertransferamt);
   });
   
   $("[name=ereqamount]").blur(function(){
        $(this).toNumber().formatCurrency();
        var ereqamount = $(this).val();
        var new_ereqamount = ereqamount.replace("$", "");
        $(this).val(new_ereqamount);
   });
   
   $("[name=erecamount]").blur(function(){
        $(this).toNumber().formatCurrency();
        var erecamount = $(this).val();
        var new_erecamount = erecamount.replace("$", "");
        $(this).val(new_erecamount);
   });
   
   $("[name=epayment]").blur(function(){
        $(this).toNumber().formatCurrency();
        var epayment = $(this).val();
        var new_epayment = epayment.replace("$", "");
        $(this).val(new_epayment);
   });
   
   $("[name=ephkramount]").blur(function(){
        $(this).toNumber().formatCurrency();
        var ephkramount = $(this).val();
        var new_ephkramount = ephkramount.replace("$", "");
        $(this).val(new_ephkramount);
   });
   
   $("[name=equantity]").blur(function(){
        total_payment = 0;
        blur_array.length = 0;
        $.validator.addMethod("maxqty", function(value, element, arg){
          return arg >= value;
        }, "Quantity cannot exceed 10.");
        if($("[name=page_action]").val() == "edit_real_expense"){
            $("#edit_real_expenses input[name=equantity]").rules("add", {maxqty : 10});
        }else{
            $("#addexpenseform input[name=equantity]").rules("add", {maxqty : 10});
        }
        expense.changeStatus();
   });
   
   //add all prices in cost per items and display total in payment field
    $(".costperitem").live("change",function(){
        if($("[name=estatus]").val() == "00000000003"){
            var total = 0;
            $(".costperitem").each(function(key,val){
                if($(this).val() != ""){
                    $(this).toNumber().formatCurrency();
                    var ecprice = $(this).val();
                    var new_ecprice = ecprice.replace("$", "");
                    $(this).val(new_ecprice);
                    
                    var price = new_ecprice.replace(",", "");
                    total = total + (price * 1);
                }
            });
            $("[name=epayment]").val(total);
            $("[name=epayment]").toNumber().formatCurrency();
            var epayment = $("[name=epayment]").val();
            var new_epayment = epayment.replace("$", "");
            $("[name=epayment]").val(new_epayment);
        }
    });
   
   //validate form
    $.validator.addMethod("valueNotEquals", function(value, element, arg){
      return arg != value;
     }, "Please select a department.");
    $.validator.addMethod("maxqty", function(value, element, arg){
      return arg >= value;
     }, "Quantity cannot exceed 10.");
   
   $("#addexpenseform").validate({
      validClass : "success",
      errorClass : "core-form-class-error",
      errorElement : "div"
      ,rules : {
         edate : {
            required: true
         },
         edepartment : {
            valueNotEquals : "0"
         },
         equantity  :   {
            maxqty  : 10
         }
      },messages : {
         edate : "Enter date."
      },errorPlacement : function(error,element){
        if(element.context.parentNode.id == "costperitem"){
           error.appendTo("#hide_error");
        }else{
            error.insertAfter(element);
        }
      }
   });
   
   $("#edit_real_expenses").validate({
      validClass : "success",
      errorClass : "core-form-class-error",
      errorElement : "div"
      ,rules : {
         edate : {
            required: true
         },
         edepartment : {
            valueNotEquals : "0"
         },
         equantity  :   {
            maxqty  : 10
         }
      },messages : {
         edate : "Enter date."
      },errorPlacement : function(error,element){
        if(element.context.parentNode.id == "costperitem"){
           error.appendTo("#hide_error");
        }else{
            error.insertAfter(element);
        }
      }
   });
   
    $("#seach_form").validate({
      validClass : "success",
      errorClass : "core-form-class-error",
      errorElement : "div",
      rules : {
         real_expense_search_string : {
            required: true
         }
      },messages : {
         real_expense_search_string : "Please indicate a search value."
      },
      onfocusout : false,
      onkeyup: false,
      onclick: false,
      autoHidePrompt: true,
      autoHideDelay: 3000,
      fadeDuration: 0.3,
      errorPlacement : function(error, element){
        error.insertBefore(element);
        error.fadeOut(3000, function(){
            error.remove();
        });
      }
   });
   
   
});

var expense = {
    //remove validations
    removeValidations : function(){
        var remove_qty = Number($("[name=equantity]").val());
        if(remove_qty == "" || remove_qty < 1){
            remove_qty = 1;
        }
        
        $("[name=addexpenseform] select").attr("disabled",false);
        $("[name=addexpenseform] input").attr("disabled",false);
        $("[name=addexpenseform] input").removeAttr("readonly");
        $("[name=edit_real_expenses] select").attr("disabled",false);
        $("[name=edit_real_expenses] input").attr("readonly",false);
        $("[name=edit_real_expenses] input").removeAttr("readonly");
        $("#edit_real_expenses input").css("background-color","#FFFFFF");
        $("#addexpenseform input").css("background-color","#FFFFFF");
        $("#receipt").show();
        $("#requestbill").show();
        $("#receipt_message").empty();
        $("#requestbill_message").empty();
        $("#tr_erdepositamt").hide();
        $("#tr_ertransferamt").hide();
        
        if($("[name=page_action]").val() == "edit_real_expense"){
            $("#edit_real_expenses input[name='ecprice[]']").each(function(){
                $(this).rules("remove");
            });
            $("#edit_real_expenses input[name='ecitem[]']").each(function(){
                $(this).rules("remove");
            });
            $("#edit_real_expenses input[name=ereqamount]").rules("remove");
            $("#edit_real_expenses input[name=erecamount]").rules("remove");
            $("#edit_real_expenses input[name=epayment]").rules("remove");
            $("#edit_real_expenses input[name=equantity]").rules("remove");
            $("#edit_real_expenses input[name=esupplier]").rules("remove");
            $("#edit_real_expenses input[name=eparticulars]").rules("remove");
            $("#edit_real_expenses input[name=ephkramount]").rules("remove");
        }else{
            $("#addexpenseform input[name='ecprice[]']").rules("remove");
            $("#addexpenseform input[name='ecitem[]']").rules("remove");
            $("#addexpenseform input[name=ereqamount]").rules("remove");
            $("#addexpenseform input[name=erecamount]").rules("remove");
            $("#addexpenseform input[name=epayment]").rules("remove");
            $("#addexpenseform input[name=equantity]").rules("remove");
            $("#addexpenseform input[name=esupplier]").rules("remove");
            $("#addexpenseform input[name=eparticulars]").rules("remove");
            $("#addexpenseform input[name=ephkramount]").rules("remove");
            $("#addexpenseform input[name=erdepositamt]").rules("remove");
        }
    },
    
    resettodefault  : function(){
        etype = "expenses";
        $("[name=edepartment]").val("0");
        expense.disabled();
        expense.changeType();
        $("#receipt").show();
        $("#requestbill").show();
        $("#receipt_message").empty();
        $("#requestbill_message").empty();
        $("#tr_erdepositamt").hide();
        $("#tr_ertransferamt").hide();
        
        $("[name=addexpenseform] input[type='text']").val("");
        $("[name=edit_real_expenses] input[type='text']").val("");
        
        var sHtml = "";
        var expense_selected = "";
        var inout_selected = "";
        var addvalidator = $('#addexpenseform');
        var editvalidator = $('#edit_real_expenses');
        
        if(saved_type == "expenses"){
            expense_selected = "selected";
            inout_selected = "";
        }else if(saved_type == "in-out"){
            expense_selected = "";
            inout_selected = "selected";
        }else{
            expense_selected = "";
            inout_selected = "";
        }
        if(curr_page != "add_new_expense"){
            sHtml += "<option value='expenses' "+expense_selected+">Expenses</option>";
        }
        sHtml += "<option value='in-out' "+inout_selected+">In-Out</option>";
        $("[name=etype]").empty().append(sHtml);
        
        if($("[name=page_action]").val() == "edit_real_expense"){
            editvalidator.validate().resetForm();
        }else{
            addvalidator.validate().resetForm();
        }
    },
    
    //disables particular options in the form depending on the department, type and status selected
    disabled : function(){
        if($("[name=edepartment] option:selected").attr("deptname") == "Head Office"){
            $("[name=erecamount]").attr("readonly",true);
            $("[name=epayment]").attr("readonly",true);
            $("#receipt").hide();
            $("#receipt_message").empty().append("Uploading receipt not allowed.");
            $("[name=equantity]").attr("readonly",true);
            $("[name='ecprice[]']").each(function(){
                $(this).attr("readonly",true);
            });
            $("[name='ecitem[]']").each(function(){
                $(this).attr("readonly",true);
            });
            $("[name=esupplier]").attr("readonly",true);
        }else{
            $("[name=addexpenseform] select").attr("disabled",false);
            $("[name=addexpenseform] input").attr("readonly",false);
            $("[name=addexpenseform] input").removeAttr("readonly");
            $("[name=edit_real_expenses] select").attr("disabled",false);
            $("[name=edit_real_expenses] input").attr("disabled",false);
            $("[name=edit_real_expenses] input").removeAttr("readonly");
            $("#receipt").show();
            $("#requestbill").show();
            $("#receipt_message").empty();
            $("#requestbill_message").empty();
        }
    },
    
    changeType : function(){
        var saved_status = $("[name=saved_status]").val()
        etype = etype == null ? "expenses" : etype;
       
        if(etype == "expenses" && curr_page != "add_new_expense"){
            $.ajax({
                url : "/expense/exec/getExpensesStatus",
                datatype : "json",
                type : "POST",
                data : {
                    saved_status : saved_status
                },
                success : function(info){
                  $("[name=estatus]").empty().append(info);
                  expense.changeStatus();
                }
            });
        }else if(etype == "Return"){
            $.ajax({
                url : "/expense/exec/getReturn",
                datatype : "json",
                type : "POST",
                data : {
                    saved_status : saved_status
                },
                success : function(info){
                  $("[name=estatus]").empty().append(info);
                  expense.changeStatus();
                }
            });
        }else{
             $.ajax({
                url : "/expense/exec/getInOutStatus",
                datatype : "json",
                type : "POST",
                data : {
                    page_action : $("[name=pageaction]").val(),
                    saved_status : saved_status
                },
                success : function(info){
                  $("[name=estatus]").empty().append(info);
                  expense.changeStatus();
                }
            });
        }
    },
    
    //when status selection is changed
    changeStatus : function(){
        var status = $("[name=estatus]").val();
        
        switch(status){
            //Requesting Funds
            case "00000000001" :    //disable respected fields
                                    $("[name=erecamount]").attr("readonly",true);
                                    $("[name=erecamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    $("#receipt").hide();
                                    $("#receipt_message").empty().append("Uploading receipt not allowed for this department.");
                                    
                                    //remove value of deposit and transfer amount
                                    $("[name=ertransferamt]").val("");
                                    $("[name=erdepositamt]").val("");
                                    
                                    //add rules for validation
                                     if($("[name=page_action]").val() == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=ereqamount]").rules("add", {required : true});
                                     }else{
                                        $("#addexpenseform input[name=ereqamount]").rules("add", {required : true});
                                     }
                                    break;
                        
            //Request Funds Denied
            case "00000000002" :    //disable respected fields
                                    $("[name=erecamount]").attr("readonly",true);
                                    $("[name=erecamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    $("[name=equantity]").attr("readonly",true);
                                    $("[name=equantity]").css("background-color","#F0F0F0");
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name=esupplier]").attr("readonly",true);
                                    $("[name=esupplier]").css("background-color","#F0F0F0");
                                    $("[name=ecategory]").attr("disabled",true);
                                    $("#receipt").hide();
                                    $("#receipt_message").empty().append("Uploading receipt not allowed for this department.");
                                    
                                    //remove value of deposit and transfer amount
                                    $("[name=ertransferamt]").val("");
                                    $("[name=erdepositamt]").val("");
                                    
                                    //add rules for validation
                                    if($("[name=page_action]").val() == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=ereqamount]").rules("add", {required : true});
                                     }else{
                                        $("#addexpenseform input[name=ereqamount]").rules("add", {required : true});
                                     }
                                    break;
                        
            //Purchase Payments
            case "00000000003"  :   //disable respected fields
                                    $("[name=ereqamount]").attr("readonly",true);
                                    $("[name=ereqamount]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    
                                    //remove value of deposit and transfer amount
                                    $("[name=ertransferamt]").val("");
                                    $("[name=erdepositamt]").val("");
                                    
                                    //get quantity
                                    var pp_qty = Number($("[name=equantity]").val());
                                    if(pp_qty == "" || pp_qty < 1){
                                        pp_qty = 1;
                                    }
                                    
                                    //add rules for validation
                                    if(curr_page == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=epayment]").rules("add", {required : true});
                                        $("#edit_real_expenses input[name=erecamount]").rules("add", {required : true});
                                        $("#edit_real_expenses input[name='ecprice[]']").each(function(){
                                            $(this).rules("add", {
                                                required : true
                                            });
                                        });
                                        $("#edit_real_expenses input[name='ecitem[]']").each(function(){
                                            $(this).rules("add", {
                                                required : true
                                            });
                                        });
                                    }else{
                                        $("#addexpenseform input[name=erecamount]").rules("add", {required : true});
                                        $("#addexpenseform input[name=epayment]").rules("add", {required : true});
                                        $("#addexpenseform input[name='ecprice[]']").each(function(){
                                            $(this).rules("add", {
                                                required : true
                                            });
                                        });
                                        $("#addexpenseform input[name='ecitem[]']").each(function(){
                                            $(this).rules("add", {
                                                required : true
                                            });
                                        });
                                    }
                                    break;
            
            //PH Received Funds            
            case "00000000004"  :   //disable respected fields
                                    $("[name=ereqamount]").attr("readonly",true);
                                    $("[name=ereqamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    
                                    //remove value of deposit and transfer amount
                                    $("[name=ertransferamt]").val("");
                                    $("[name=erdepositamt]").val("");
                                    
                                    //add rules for validation
                                    if($("[name=page_action]").val() == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=erecamount]").rules("add", {required : true});
                                    }else{
                                        $("#addexpenseform input[name=erecamount]").rules("add", {required : true});
                                    }
                                    break;
                   
            //PH Deposit COH to Bank Account
            case "00000000005"  :   //disable respected fields
                                    $("[name=ereqamount]").attr("readonly",true);
                                    $("[name=ereqamount]").css("background-color","#F0F0F0");
                                    $("[name=erecamount]").attr("readonly",true);
                                    $("[name=erecamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    $("[name=equantity]").attr("readonly",true);
                                    $("[name=equantity]").css("background-color","#F0F0F0");
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name=esupplier]").attr("readonly",true);
                                    $("[name=esupplier]").css("background-color","#F0F0F0");
                                    $("#receipt").hide();
                                    $("#receipt_message").empty().append("Uploading receipt not allowed for this department/status.");
                                    $("#requestbill").hide();
                                    $("#requestbill_message").empty().append("Uploading request form not allowed for this department/status.");
                                    
                                    //show deposit field
                                    $("#tr_erdepositamt").show();
                                    
                                    //validation
                                    if($("[name=page_action]").val() == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=erdepositamt]").rules("add", {
                                            required : true
                                          });
                                    }else{
                                        var date = $("[name=edate]").val();
                                        var diff = 0.00;
                                        var year = 0;
                                        var month = 0;
                                    
                                        date = date.split("/");
                                        year = date[2];
                                        month = date[0];
                                        
                                        $.ajax({
                                            url : "/expense/exec/check_deposit_amt",
                                            dataType : "json",
                                            type : "POST",
                                            data :  {
                                                        year : year,
                                                        month : month
                                                    },
                                            success : function(info){
                                                $.validator.addMethod("depositcheck", function(value, element){
                                                    var cashonhand = 0.00;
                                                    cashonhand = parseInt(info,10);
                                                    
                                                    var depositamt = $("[name=erdepositamt]").val();
                                                    depositamt = depositamt.replace(",", "");
                                                    depositamt = parseInt(depositamt,10);
                                                    
                                                    if(depositamt > cashonhand){
                                                        return false;
                                                    }else{
                                                        return true;
                                                    }   
                                                }, "Deposit amount exceeds cash on hand.");
                                                
                                                $("#addexpenseform input[name=erdepositamt]").rules("add", {
                                                    required : true,
                                                    depositcheck : true
                                                });
                                                
                                                // diff = depositamt - cashonhand;
                                                // alert(diff);
                                                 
                                                // if(diff > 0){
                                                    // $("#msg_erdepositamt").html("Deposit amount exceeds cash on hand.");
                                                // }else{
                                                    // $("#msg_erdepositamt").html("");
                                                // }
                                            }
                                        });
                                    }
                                    break;
                                    
            case "00000000006"  :   //disable respected fields
                                    $("[name=ereqamount]").attr("readonly",true);
                                    $("[name=ereqamount]").css("background-color","#F0F0F0");
                                    $("[name=erecamount]").attr("readonly",true);
                                    $("[name=erecamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    $("[name=equantity]").attr("readonly",true);
                                    $("[name=equantity]").css("background-color","#F0F0F0");
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name=esupplier]").attr("readonly",true);
                                    $("[name=esupplier]").css("background-color","#F0F0F0");
                                    $("#receipt").hide();
                                    $("#receipt_message").empty().append("Uploading receipt not allowed for this department/status.");
                                    $("#requestbill").hide();
                                    $("#requestbill_message").empty().append("Uploading request form not allowed for this department/status.");
                                    
                                    //show deposit field
                                    $("#tr_ertransferamt").show();
                                    
                                    //validation
                                    var date = $("[name=edate]").val();
                                    var transferamt = $("[name=ertransferamt]").val();
                                    var diff = 0.00;
                                    var year = 0;
                                    var month = 0;
                                    
                                    date = date.split("/");
                                    year = date[2];
                                    month = date[0];
                                    
                                    $.ajax({
                                        url : "/expense/exec/check_transfer_amt",
                                        dataType : "json",
                                        type : "POST",
                                        data :  {
                                                    year : year,
                                                    month : month
                                                },
                                        success : function(info){
                                            $.validator.addMethod("transfercheck", function(value, element){
                                                var unionbankbal = parseInt(info,10);
                                                
                                                var transferamt = $("[name=ertransferamt]").val();
                                                transferamt = transferamt.replace(",", "");
                                                transferamt = parseInt(transferamt,10);
                                                
                                                if(transferamt > unionbankbal){
                                                    return false;
                                                }else{
                                                    return true;
                                                }   
                                            }, "Transfer amount exceeds union bank balance.");
                                            
                                            $("#addexpenseform input[name=ertransferamt]").rules("add", {
                                                required : true,
                                                transfercheck : true
                                            });
                                            
                                            
                                            // diff = transferamt - unionbankbal;
                                            // if(diff > 0){
                                                // $("#msg_ertransferamt").html("Transfer amount exceeds union bank balance.");
                                            // }else{
                                                // $("#msg_ertransferamt").html("");
                                            // }
                                        }
                                    });
                                    break;
                                    
            case "00000000007"  :   //disable respected fields
                                    $("[name=ereqamount]").attr("readonly",true);
                                    $("[name=ereqamount]").css("background-color","#F0F0F0");
                                    $("[name=erecamount]").attr("readonly",true);
                                    $("[name=erecamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=equantity]").attr("readonly",true);
                                    $("[name=equantity]").css("background-color","#F0F0F0");
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name=esupplier]").attr("readonly",true);
                                    $("[name=esupplier]").css("background-color","#F0F0F0");
                                    $("#receipt").hide();
                                    $("#receipt_message").empty().append("Uploading receipt not allowed for this department/status.");
                                    
                                    //remove value of deposit and transfer amount
                                    $("[name=ertransferamt]").val("");
                                    $("[name=erdepositamt]").val("");
                                    
                                    //add rules for validation
                                    if($("[name=page_action]").val() == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=ephkramount]").rules("add", {required : true});
                                    }else{
                                        $("#addexpenseform input[name=ephkramount]").rules("add", {required : true});
                                    }
                                    break;
            
            //PH Received Payments
            case "00000000008"  :   //disable respected fields
                                    $("[name=ereqamount]").attr("readonly",true);
                                    $("[name=ereqamount]").css("background-color","#F0F0F0");
                                    $("[name=epayment]").attr("readonly",true);
                                    $("[name=epayment]").css("background-color","#F0F0F0");
                                    $("[name=ephkramount]").attr("readonly",true);
                                    $("[name=ephkramount]").css("background-color","#F0F0F0");
                                    $("[name=equantity]").attr("readonly",true);
                                    $("[name=equantity]").css("background-color","#F0F0F0");
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecprice[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).attr("readonly",true);
                                    });
                                    $("[name='ecitem[]']").each(function(){
                                        $(this).css("background-color","#F0F0F0");
                                    });
                                    $("[name=esupplier]").attr("readonly",true);
                                    $("[name=esupplier]").css("background-color","#F0F0F0");
                                    $("#receipt").hide();
                                    $("#receipt_message").empty().append("Uploading receipt not allowed for this department/status.");
                                    $("#requestbill").hide();
                                    $("#requestbill_message").empty().append("Uploading request form not allowed for this department/status.");
                                    
                                    //remove value of deposit and transfer amount
                                    $("[name=ertransferamt]").val("");
                                    $("[name=erdepositamt]").val("");
                                    
                                    //add rules for validation
                                    if($("[name=page_action]").val() == "edit_real_expense"){
                                        $("#edit_real_expenses input[name=erecamount]").rules("add", {required : true});
                                    }else{
                                        $("#addexpenseform input[name=erecamount]").rules("add", {required : true});
                                    }
                                    break;
                                    
        }
    },
    
    //appends textboxes under label of 'cost per item' depending in the entered amount in quantity text box
    costboxes : function(){
        var sHtml = "";
        var qty = $("[name=equantity]").val();
        
        if(qty == "" || qty == 0){
            qty = 1;
        }
        
        if(qty <=10){
            for(var i=1;i<=qty;i++){
                sHtml += "<input type='hidden' class='input_type_3 fl mb5' name='ecid[]'/>";
                sHtml += "<input type='text' class='input_type_3 fl mb5 costperitem' name='ecprice[]' placeholder='Price' class='required number' id='price"+i+"'/>";
                sHtml += "<input type='text' class='input_type_1 fl mb5' name='ecitem[]' id='item"+i+"' placeholder='Item Name' /><br/>";
            }
            $("#costperitem").empty().append(sHtml);
        }else{
            sHtml += "<input type='hidden' class='input_type_3 fl mb5' name='ecid[]'/>";
            sHtml += "<input type='text' class='input_type_3 fl mb5 costperitem' name='ecprice[]' placeholder='Price' class='required number' id='price"+i+"' />";
            sHtml += "<input type='text' class='input_type_1 fl mb5' name='ecitem[]' id='item"+i+"' placeholder='Item Name' /><br/>";
            $("#costperitem").empty().append(sHtml);
        }
    },
    
    //checks cash on hand available for a specific month when user will be depositing to bank
    check_deposit_amt : function(){
        var date = $("[name=edate]").val();
        var depositamt = $("[name=erdepositamt]").val();
        var diff = 0.00;
        var year = 0;
        var month = 0;
        
        if(date == ""){
            $("#msg_erdepositamt").html("Please specify a date first.");
            $("[name=erdepositamt]").val("");
        }
        
    },
    
    //checks the union bank balance for a specific month when user transfers money to korean source bank
    check_transfer_amt : function(){
        var date = $("[name=edate]").val();
        var transferamt = $("[name=ertransferamt]").val();
        var diff = 0.00;
        var year = 0;
        var month = 0;
        
        if(date == ""){
            $("#msg_ertransferamt").html("Please specify a date first.");
        }
    },
    
    //gets the id of all checked items in the real expense list to delete
    delete_real_expenses_item_list : function(){
        var delete_real_expenses = [];
        $("[name=real_expenses_records]:checked").each(function(){
            delete_real_expenses.push($(this).val());
        });
        
        if(delete_real_expenses.length == 0){
            $("#msg_select1").html("No items selected to delete.");
        }else{
            $("#msg_select1").html("");
            sContainer = null;
            /*ui dialog options*/
            var aOptions = { 
                title: 'Delete Real Expenses Item',
                height: 170,
                width:300,
                resizable: false,
                modal: true,
                buttons: { 
                        "Delete": function(){
                            $.ajax({
                                url : "/expense/exec/delete_real_expense_item_list",
                                dataType : "json",
                                type : "POST",
                                data :  {
                                            delete_aid : delete_real_expenses
                                        },
                                success : function(info){
                                    if(info == true){
                                        Site.page_redirect('/expense');
                                    }
                                }
                            });
                        },
                        "Cancel": function() {
                            $(this).dialog("close"); 
                        }
                    }
            }
            
            var adialogbox_options = {
                scontainer : null,
                aoption : aOptions,
                scontent : '<b>Are you sure you want to delete the selected item/s?</b>'

            }
           
            Site.dialog_box(adialogbox_options);
        }
       
    },
    
    //checks if there are selected items for editing and redirecting to the editing page if there is a selected item
    edit_real_expense_item : function(){
        var edit_ctr = 0;
        var edit_real_expenses_id = 0;
        $("[name=real_expenses_records]:checked").each(function(){
            edit_ctr++;
        });
        if(edit_ctr == 0){
            $("#msg_select1").html("Please select an item for editing.");
        }else if(edit_ctr > 1){
            $("#msg_select1").html("Real expense reports can only be edited one at a time.");
        }else{
            edit_real_expenses_id = $("[name=real_expenses_records]:checked").val();
            $("#msg_select1").html("");
            Site.page_redirect('/expense/edit_real_expense/' + edit_real_expenses_id);
        }
    },
    
    get_cost_per_items_edit : function(){
        var qty = $("[name=equantity]").val();
        var edit_id = $("[name=edit_id]").val();
        
        if(qty!=""){
            $.ajax({
                url : "/expense/exec/get_cost_per_items_edit",
                dataType : "json",
                type : "POST",
                data :  {
                            edit_id : edit_id
                        },
                success : function(info){
                    var aexisting_items = new Array();
                   
                    $.each(info, function(key, val){
                        aexisting_items.push(val.teil_idx);
                    });
                    
                    $("[name=existing_items]").val(aexisting_items);
                    if(info.length > 0){
                        var price_ctr = 0;
                        var item_ctr = 0;
                        var id_ctr = 0;
                        $("[name='ecprice[]']").each(function(){
                            if(price_ctr < info.length){
                                $(this).val(info[price_ctr].teil_price);
                                price_ctr++;
                            }
                        });
                        $("[name='ecitem[]']").each(function(){
                            if(item_ctr < info.length){
                                 $(this).val(info[item_ctr].teil_name);
                                item_ctr++;
                            }
                        });
                        $("[name='ecid[]']").each(function(){
                            if(id_ctr < info.length){
                                $(this).val(info[id_ctr].teil_idx);
                                id_ctr++;
                            }
                        });
                        // $.each(info, function(key,val){
                            // $("[name='ecprice[]']").map(function(){
                                    // return $(this).val(val.teil_price);
                            // });
                        // });
                        // // $("[name=ecid]").val(info[i].teil_idx);
                        // $("[name=ecprice]").val(info[i].teil_price);
                        // $("[name=ecitem]").val(info[i].teil_name);
                    }
                }
            });
        }
    },
    
    get_request_bill_attachment : function(){
        var edit_id = $("[name=edit_id]").val();
        
        if($("#requestbill").is(':visible') == true){
            $.ajax({
                url : "/expense/exec/get_request_bill_attachment",
                dataType : "json",
                type : "POST",
                data :  {
                            edit_id : edit_id
                        },
                success : function(info){
                    var sHtml = "";
                    sHtml += "<ul style='list-style-type: none;text-align:left'>";
                        $.each(info, function(key,val){
                            sHtml += "<li>"+val.tea_filename+"<a href='javascript:expense.delete_attach(\""+val.tea_idx+"\")' style='margin-left:10px;color:#FF0000'>x</a></li>";
                        });
                    sHtml += "</ul>";
                    $("#requestbill_uploaded").empty().append(sHtml);
                }
            });
        }
    },
    
    get_receipt_attachment : function(){
        var edit_id = $("[name=edit_id]").val();
       
        if($("#receipt").is(':visible') == true){
            $.ajax({
                url : "/expense/exec/get_receipt_attachment",
                dataType : "json",
                type : "POST",
                data :  {
                            edit_id : edit_id
                        },
                success : function(info){
                    var sHtml = "";
                    sHtml += "<ul style='list-style-type: none;text-align:left'>";
                        $.each(info, function(key,val){
                            sHtml += "<li>"+val.tea_filename+"<a href='javascript:expense.delete_attach(\""+val.tea_idx+"\")' style='margin-left:10px;color:#FF0000'>x</a></li>";
                        });
                    sHtml += "</ul>";
                    $("#receipt_uploaded").empty().append(sHtml);
                }
            });
        }
    },
    
    delete_attach : function(id){
        sContainer = null;
        /*ui dialog options*/
        var aOptions = { 
            title: 'Delete Real Expenses Item',
            height: 170,
            width:300,
            resizable: false,
            modal: true,
            buttons: { 
                    "Delete": function(){
                        $.ajax({
                            url : "/expense/exec/delete_attach",
                            dataType : "json",
                            type : "POST",
                            data :  {
                                        del_id : id
                                    },
                            success : function(info){
                                if(info == true){
                                    expense.get_request_bill_attachment();
                                    expense.get_receipt_attachment();
                                }
                            }
                        });
                    },
                    "Cancel": function() {
                        $(this).dialog("close"); 
                    }
                }
        }
        
        var adialogbox_options = {
            scontainer : null,
            aoption : aOptions,
            scontent : '<b>Are you sure you want to delete the attached item?</b>'
        }
       
        Site.dialog_box(adialogbox_options);
    },
    
    specific_period : function(page_action){
        var period_from = $("#datefrom").val();
        var period_to = $("#dateto").val();
        
        if(period_from == ""){
            $("#msg_datefrom").html("Please specify a date.");
        }
        if(period_to == ""){
            $("#msg_dateto").html("Please specify a date.");
        }
        if(period_from != ""){
            $("#msg_datefrom").html("");
        }
        if(period_to != ""){
            $("#msg_dateto").html("");
        }
        if(period_from != "" && period_to != ""){
            if(page_action == "real_expense_spreadsheet"){
                Site.page_redirect('/expense/real_expense_spreadsheet?pf=' + period_from + '&pt=' + period_to);
            }else{
                Site.page_redirect('/expense?pf=' + period_from + '&pt=' + period_to);
            }
        }
    },
    
    export_real_expense : function(){
        var month = $("#detailed_export").attr('month');
        var year = $("#detailed_export").attr('year');
        var view = $("#detailed_export").attr('view');
        
        if(month!="" || year!=""){
            Site.page_redirect("/expense/export_expense?year="+year+"&month="+month+"&view="+view);
        }else{
            Site.page_redirect("/expense/export_expense?view="+view);
        }
        
    }
}