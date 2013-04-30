define([
        /*libraries*/
        'backbone'

], function(backbone){		
		var tempo = "medium";			
		return  {
        
            init_tablesorter: function(){
             /*table sorter from site.js*/
                var tb_sorter_options = {
                    tb_selector_id : 'tb_user_list',
                    headers: {
                        0: { sorter: false },
                        1: { sorter: false },
                        6: { sorter: false }
                    }
                }
                
                Site.init_tb_sorter(tb_sorter_options);
            },
            
            /*animate fade in and up*/
            animate_up: function(selector){
                
                $(selector).animate({opacity: 1}, {queue: false, duration: tempo});
                $(selector).animate({ top: "-10px" }, tempo);
                
                $(':text').focus();
                $(':text').addClass('input_highlight');
                
            },
            
            /*validate if not empty*/
            dt_validate: function(e){
            
                var bValid = true;
                
                if($.trim($(e).val()).length <= 0){
                    $(e).addClass('input_required');
                    bValid = false;
                }else{
                    $(e).removeClass('input_required');
                }
                
                return bValid;
                
            },
            
            fade_out: function(selector){
                $(selector).animate({opacity: 0}, {queue: false, duration: tempo});
            },
           
            /*remove class on blur*/
            unhighlight: function(event){
                $(event.target).removeClass('input_highlight');
            },
            /*give class on focus*/
            highlight: function(event){
                $(event.target).addClass('input_highlight');
            },
            
            autocomplete_emp: function(adata){
            
                $( "#employee_listed" ).autocomplete({
                    minLength: 2,
                    source: adata,
                    focus: function( event, ui ) {
                        $( "#employee_listed" ).val( ui.item.te_fname+ ' '+ui.item.te_lname+' - '+ui.item.tp_position );
                        return false;
                    },
                    select: function( event, ui ) {
                        $( "#employee_listed" ).val( ui.item.te_fname + " " + ui.item.te_lname+' - '+ui.item.tp_position );
                        $( "#employee_id" ).val( ui.item.te_idx );
                        $( "#employee_listed" ).attr( 'readonly',true );
                        $( "#employee_listed" ).addClass('readonly_gray');

                        return false;
                    }
                })
                .data( "autocomplete" )._renderItem = function( ul, item ) {
                    return $( "<li ></li>" )
                        .data( "item.autocomplete", item )
                        .append( "<a class='fl'>" + item.te_fname + " " + item.te_lname +" - "+item.tp_position+"</a>" )
                        .appendTo( ul );
                };
            },
            
            remove_readonly: function(){
                var selector = "#employee_listed";
                $( selector ).val('');
                $( selector ).removeClass('readonly_gray');
                $( selector ).attr( 'readonly',false );
                $( selector ).focus();
            
            },
	
            _ajax: function(model,formdata){
                
                return model.save(null,{ 
                    data: formdata,
                    error:	function(model,response){
                        console.log(response);
                      }
                });
            },
            
            checkStr: function(str) {
                if (str.length < 6) {
                    return("___too short (mininum of 6 char)___");
                } else if (str.length > 15) {
                    return("___too long (maximum of 15 char)___");
                } else if (str.search(/\d/) == -1) {
                    return("___has no number (at least 1 number is required)___");
                } else if (str.search(/[a-zA-Z]/) == -1) {
                    return("___has no letter (at least 1 letter is required)___");
                } else if (str.search(/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\_\+]/) != -1) {
                    return("___invalid character (spaces is restricted)___");
                }
                return true;
            },
            
            message: function(){
                
                /*add welcome elements*/
                var sHtml = '';
                sHtml += '<div class="core-message-warning" style="position:absolute;top:0;left:0;width:93%;">';
                sHtml += '<span class="core-message-server-text">Incorrect username and password combination!</span>';
                sHtml += '<a class="core-message-close core-message-server-close-button" href="javascript:$(\'.core-message-warning\').remove()">x</a>';
                sHtml += '</div>';
                $('#message_wrap').html(sHtml).animate({opacity: 1}, {queue: false, duration: 'medium'});
                $('#message_wrap').animate({ top: "-10px" }, 'medium');
                
            },
            
            /*close the dialog box*/
            close_dialog: function(){
                $('.mess_box').dialog('close');
                $('.popup_wrap').dialog('close');
            },
            
            accordion_sortable: function(){
                $(function() {
                    $( "#accordion" )
                        .accordion({
                            header: "> div > h3"
                        })
                        .sortable({
                            axis: "y",
                            handle: "h3",
                            stop: function( event, ui ) {
                                // IE doesn't register the blur when sorting
                                // so trigger focusout handlers to remove .ui-state-focus
                                ui.item.children( "h3" ).triggerHandler( "focusout" );
                            }
                        });
                });
            },
            
            /*javascript print_r*/
            print_r: function(theObj){
              if(theObj.constructor == Array ||
                 theObj.constructor == Object){
                document.write("<ul>")
                for(var p in theObj){
                  if(theObj[p].constructor == Array||
                     theObj[p].constructor == Object){
            document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
                    document.write("<ul>")
                    this.print_r(theObj[p]);
                    document.write("</ul>")
                  } else {
            document.write("<li>["+p+"] => "+theObj[p]+"</li>");
                  }
                }
                document.write("</ul>")
              }
            }
        
        }
	}
);