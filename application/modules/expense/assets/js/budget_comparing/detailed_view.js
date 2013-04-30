var detailed_view = {    
    /*APPLY FOR SEARCH*/
    applySearch : function(){        
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        var sort_by = $('select[name="sort_by"]').val();
        var keyword = $('#keyword').val();
        var row = $('#show_rows').val();
        window.location = urls.current_url+"?from="+calendar_from+"&to="+calendar_to+"&sort="+sort_by+"&keyword="+keyword+"&row="+row;
    },
    
    /*CANCEL BUTTON IN THE FORMS*/
    cancelForm : function(){
        $('#update_form').fadeOut();
    },
    
    /*Save Comment*/
    saveComment : function(){    
        if($('#text_comments').val()!= ""){            
            $('#text_comments').css({"border-color":"#ACACAC"});
            var get_tag = {
                url : urls.ajax_url,
                type : "post",
                dataType: 'json',
                data :{
                    mod:"expense|exec_budget_comparing|add_comments",
                    comments : $('#text_comments').val(),
                    monthyear : $('input[name="updateform_month_name"]').val()
                },success : function(response){
                    if(response == true){
                        site.message("Successfully Saved!",$("#comment_err"),"success");
                        location.href=location.href;
                        this.cancelForm();
                    }
                }
            };
            $.ajax(get_tag);
        } else {
            site.message("Please enter the comment.",$("#comment_err"),"warning");
            $('#text_comments').css({"border-color":"red"});
        }
    },
    
    resetSearch : function(){
        $('#calendar_from').val("");
        $('#calendar_to').val("");
        $('select[name="sort_by"]').val("");
        $('#keyword').val(""); 
        location.href = urls.current_url;
    },
    
    export_to_excel : function(){
        var calendar_from = $('#calendar_from').val();
        var calendar_to = $('#calendar_to').val();
        var sort_by = $('select[name="sort_by"]').val();
        var keyword = $('#keyword').val();
        var row = $('#show_rows').val();
        var limit = $('#limit').val();
        var offset = $('#offset').val();
        Site.page_redirect("/expense/budget_comparing_detailed/export_to_excel?from="+calendar_from+"&to="+calendar_to+"&sort="+sort_by+"&keyword="+keyword+"&limit="+limit+"&offset="+offset);
    },
    
    scrollDown : function(){
        $('html, body').animate({
          scrollTop: $("#update_form").offset().top + $('window').height()
        }, 1000);
    }
};

$(document).ready(function(){ 
    if($('#obj_exist').val() == 0){
        $('#export_excel').hide();
    } else { 
        $('#export_excel').show();    
    }    
    
    /*Centered Form*/
    $('#update_form').css({top:'50%',left:'50%',margin:'-'+($('#update_form').height() / 2)+'px 0 0 -'+($('#update_form').width() / 2)+'px'});
    
    $('#text_comments').css({"border-color":"#ACACAC"});
    $('#apply_search').click(function(){
        if(($('#calendar_from').val() !="" && $('#calendar_to').val()!="")){
           detailed_view.applySearch();
            $('#calendar_from').css({"border-color":"#ACACAC"});
            $('#calendar_to').css({"border-color":"#ACACAC"});
        } else {
            $('#calendar_from').css({"border-color":"red"});
            $('#calendar_to').css({"border-color":"red"});
            site.message("Enter valid specific period or sort by.",$(".message-container"),"warning");
        }
    });
    
    $('#show_rows, select[name="sort_by"]').change(function(){
        detailed_view.applySearch();
    });
    
    $('#export_excel').click(function(){
        detailed_view.export_to_excel();
    });
    
    $('.cancel_form').click(function(){
        detailed_view.cancelForm();
    });
    
    $('#reset_search').click(function(){
        detailed_view.resetSearch();
    });
    
    $('[name="edit_link"]').click(function(){
        var scomment = $(this).parent().parent().parent().parent().parent().find('span[name="month_comment"]').html();
        var smonth = $(this).parent().parent().parent().parent().find('input[name="month_name"]').val();
        $('#title_form').html($(this).html()+" Budget Comparing Comments");   
        if(scomment == "No existing comment."){
            scomment = "";
        }     
        $('#text_comments').val(scomment);         
        $('input[name="updateform_month_name"]').val(smonth);
        $('#text_comments').keyup();
        $('#update_form').fadeIn();
        detailed_view.scrollDown();
        $('#text_comments').focus();  
    });
    
    $('#text_comments').keyup(function(){
        var count = $(this).val().length;
        var max = $(this).attr('maxlength');
        $('span[name="char_num"]').html(max - count);
     });
     
    $('#save_update_form').click(function(){
        detailed_view.saveComment();
    });
    
    $('#search_btn').click(function(){
        if($('#keyword').val() !=""){
           detailed_view.applySearch();
        } else {
            site.message("Enter a keyword to be searched.",$(".message-container"),"warning");
        }
    });
    
    $('#go_sheet_view').click(function(){
        location.href = urls.module_url+"budget_comparing_spreadsheet";
    });
    
    $('#go_detailed_view').click(function(){
        location.href = urls.module_url+"budget_comparing_detailed";
    });
});