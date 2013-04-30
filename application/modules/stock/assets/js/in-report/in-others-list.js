jQuery(document).ready(function(){
   var inreport = new InReport();
   /** Initialize date picker for search range.**/
   $("#calendar-to, #calendar-from").datepicker({
      dateFormat: "yy-mm-dd"
   });
   
   /** Initialize table sorter for photo assets list**/
   var tb_sorter_options1 = {
      tb_selector_id : 'others-list-table',
      headers: {
         0: { sorter: false }
      }
   };
   Site.init_tb_sorter( tb_sorter_options1 );
   
   $(".search-btn").click(function(){
      var start_date = $("#calendar-from");
      var end_date = $("#calendar-to");
      var search_string = "";
      return function() {
         if(start_date.val() != '' || end_date.val() != '') {

            if( start_date.val() == '') {
               start_date.addClass('error');
               return false;
            };

            if( end_date.val() == '') {
               end_date.addClass('error');
               return false
            };
            
            if( Date.parse( start_date.val() ) > Date.parse( end_date.val() ) ) {
               start_date.addClass('error');
               end_date.addClass('error');
               site.message("Please enter a valid date range.", $(".js-message"), 'warning');
               return false;
            } else {
               start_date.removeClass('error');
               end_date.removeClass('error');
               site.message("", $(".js-message"), 'hide');
               search_string += "?start=" + start_date.val() + "&end=" + end_date.val();
            }
         } else {
            search_string = '?blank=';
         }
         window.location.href = search_string + $(this).attr('alt');
      }
   }());
   
   /** Check if there is checkbox that already checked then hide the message **/
   $(".row-list, .check-all").click(function(){
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
   $(".delete-btn").click(function(){
      var rows = $(".row-list");
      var row_length;
      return function() {
         site.message("", $(".php-message"),"hide");
         row_length = rows.filter(":checked").length;
         if( row_length === 0 ){
            site.message("Please select record you want to delete.",$(".js-message"),"warning");
            return false;
         } else {
            site.message("",$(".js-message"),"hide");
            $(".confirm-delete-dialog").dialog({
               title: 'Delete Record',
               closeOnEscape: false,
               width: 350,
               modal: true,
               resizable: false
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
            inreport.exec_delete(aidx,"stock|ajax_in_report|remove_others");
            aidx = [];
         }
      };
   }());
   
   /** Cancel deletion. **/
   $("#cancel-delete-btn").click(function(){
      site.close_dialog($(".confirm-delete-dialog"));
   });
});