function ReqUsage() {};
ReqUsage.prototype.exec_search = function( elem ) {
   window.location.href = urls.module_url + "req_usage_rec/?search=" + elem.val();
};
ReqUsage.prototype.exec_edit = function( id ) {
   window.location.href = urls.module_url + "req_usage_rec/edit_request_equipment/?id=" + id;
};
ReqUsage.prototype.exec_delete = function( aidx ) {
   if( aidx ) {
      $.ajax({
         url : urls.ajax_url,
         type : "post",
         data :{
            mod:"photo|ajax_request_usage|remove_request",
            ids: aidx
         },success : function(response){
            try {
               window.location.reload();
            } catch(e) {
               window.location.reload();
            }
         }
      });   
   }
};

jQuery(document).ready(function($){
   /** Create new instance of ReqUsage**/
   var recusage = new ReqUsage();
   /** Initialize table sorter for photo assets list**/
   var tb_sorter_options1 = {
      tb_selector_id : 'request-list-table',
      headers: {
         0: { sorter: false }
      }
   };
   Site.init_tb_sorter( tb_sorter_options1 );
   $("#activity-date, #returned-date").datepicker({
      dateFormat: "yy-mm-dd"
   });
   $("[name='request-form']").validate({
      rules: {
         "activity-date": {
            required: true,
         },
         "requested-by": {
            required: true,
            maxlength: 250,
         },
         location: {
            required: true,
            maxlength: 250
         },
         purpose: {
            required: true,
            maxlength: 250
         },
         "returned-date": {
            required: true
         },
         "assets-id[]": {
            required: true
         }         
      },
      messages: {
         "activity-date": "Please enter activity date.",
         "requested-by": "Please enter requested by.",
         location: {
            required: "Please enter location.",
            maxlength: "You can enter maximum of 250 characters"
         },
         purpose: {
            required:"Please enter purpose/theme.",
            maxlength: "You can enter maximum of 250 characters"
         },
         "returned-date": "Please enter returned date.",
         "assets-id[]": "Please select on assets list below."
      },errorPlacement: function(error, element){
          if(element.attr("name") == "assets-id[]"){
              error.appendTo($('.checkbox-error-message'));
          }else{
              error.insertAfter(element); 
          }
      }
   });
   
   $(".save-req-btn").click(function(){
      $("[name='request-form']").submit();
   });
   
   /** Execute search **/
   $(".search-btn").click(function(){
      recusage.exec_search( $(".search-tbox") );
   });
   
   /** Check if there is checkbox checked then edit.(One record at a time)*/
   $("#edit-btn").click(function(){
      var rows = $(".row-list");
      var row_length;
      return function() {
         row_length = rows.filter(":checked").length;
         if( row_length  > 0 ) {
            if( row_length > 1 ) {
               site.message("You cannot edit multiple records.",$(".js-message"),"warning");
               return false;
            } else {
               recusage.exec_edit( rows.filter(":checked").val() );
            }
         } else {
            site.message("Please enter record you want to edit.",$(".js-message"),"warning");
            return false;
         }
      };
   }());
   
   /** Check if there is checkbox that already checked then hide the message **/
   $(".row-list").click(function(){
      var rows = $(".row-list");
      var row_length;
      return function() {
         row_length = rows.filter(":checked").length;
         if( row_length  > 0 ) {
            site.message("",$(".js-message"),"hide");
         };
      };
   }());
   
   /** Confirm deletion dialog box.  **/   
   $("#delete-btn").click(function(){
      var rows = $(".row-list");
      var row_length;
      return function() {
         row_length = rows.filter(":checked").length;
         if( row_length === 0 ){
            site.message("Please enter record you want to delete.",$(".js-message"),"warning");
            return false;
         } else {
            site.message("",$(".js-message"),"hide");
            $(".confirm-delete-dialog").dialog({
               closeOnEscape: false,
               width: 350,
               modal: true,
               resizable: false,
               draggable: false
            });
         };
      };
   }());

   /** Execute delete function. **/
   $("#delete-confirm-btn").click(function(){
      var rows = $(".row-list");
      var aidx = [];
      var rows_length;
      return function() {
         $("#delete-loader-message").show();
         $("#delete-confirm-buttons").hide();
         $(".ui-dialog-titlebar-close").hide();
         if( rows.filter(":checked").length > 0 ) {
            rows.filter(":checked").each(function(i, v) {
               aidx.push($(v).val());
            });
            recusage.exec_delete(aidx);
            aidx = [];
         }
      };
   }());
   
   /** Cancel deletion. **/
   $("#cancel-delete-btn").click(function(){
      site.close_dialog($(".confirm-delete-dialog"));
   });
   
   $("#remove-attachment-link").click(function(){
      $(".confirm-remove-attachment-dialog").dialog({
         width: 370,
         modal: true,
         resizable: false, 
      });
   });
   
   /** Cancel deletion. **/
   $("#cancel-remove-btn").click(function(){
      site.close_dialog($(".confirm-remove-attachment-dialog"));
   });
   
   $("#remove-confirm-btn").click(function(){
      $(".li-attachment").remove();
      site.close_dialog($(".confirm-remove-attachment-dialog"));
   });
});