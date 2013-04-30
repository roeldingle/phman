define([
        /*libraries*/
        'backbone'

], function(backbone){		
		var tempo = "medium";			
		return  {
       
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
            
            remove_readonly: function(selector){
                
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