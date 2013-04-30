;jQuery(document).ready(function($){
   var ipmanagement = new IPManagement();
   var aidx_temp = [];
   
   /** Initialize table sorter for photo assets list**/
   var tb_sorter_options1 = {
      tb_selector_id : 'ip-list-table',
      headers: {
         0: { sorter: false },
         8: { sorter: false }
      }
   };
   Site.init_tb_sorter( tb_sorter_options1 );
   /** Initialize date picker for search range.**/
   $("#calendar-to, #calendar-from").datepicker({
      dateFormat: "yy-mm-dd"
   });
   
   $(".ip-form-btn").click(function(){
      $(".ip-form-dialog").dialog({
         title: "Add New IP",
         width: 400,
         modal: true,
         resizable: false
      });
   });
   
   $(".search-btn").click(function(){
      var search_string = "";
      var search = $("#search-txtbox");
      var start_date = $("#calendar-from");
      var end_date = $("#calendar-to");
      var department = $("#department");
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
               search_string += "&start=" + start_date.val() + "&end=" + end_date.val();
            }
         }
         if( department.val() != '') {
            search_string += "&department=" + department.val();
         }
         window.location.href = "?search=" + search.val() + search_string;
      }
   }());
   
   $(".reset-search-btn").click(function(){
      var search = $("#search-txtbox");
      var department = $("#department");
      var start_date = $("#calendar-from");
      var end_date = $("#calendar-to");
      return function() {
         search.val('');
         department.val('');
         start_date.val('').removeClass('error');
         end_date.val('').removeClass('error');
      };
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
            ipmanagement.exec_delete(aidx, "stock|ajax_ip_management|remove_ip");
            aidx = [];
         };
      };
   }());
   
   /** Cancel deletion. **/
   $(".cancel-delete-btn").click(function(){
      site.close_dialog($(".confirm-delete-dialog"));
      site.close_dialog($(".single-confirm-delete-dialog"));
   });
   
   $(".delete-single-btn").click(function(){
      site.message("",$(".php-message"),"hide");
      site.message("",$(".js-message"),"hide");
      aidx_temp.length = 0;
      aidx_temp.push($(this).attr('data-delete-id'));
      $(".single-confirm-delete-dialog").dialog({
         title: 'Delete Record',
         closeOnEscape: false,
         width: 350,
         modal: true,
         resizable: false
      });
   });
   
      /** Execute delete function. **/
   $("#single-delete-confirm-btn").click(function(){
      return function() {
         $("#delete-loader-message").show();
         $("#delete-confirm-buttons").hide();
         $(".ui-dialog-titlebar-close").hide();
         ipmanagement.exec_delete(aidx_temp, "stock|ajax_ip_management|remove_ip");
      };
   }());
   
   $(".modify-btn").click(function(e){
      var data = $(this).attr('data-info');
      data = $.parseJSON(data);
      $("#modify-id").val(data['idx']);
      $("#modify-employee-id").val(data['employee_id']);
      $("#modify-seat-no").val(data['seat_no']);
      $("#modify-department-name").val(data['department']).attr('readonly', false);
      $("#modify-assign-ip").val(data['assign_ip']).attr('readonly', false);
      $("#modify-gateway").val(data['gateway']).attr('readonly', false);
      $("#modify-external-ip").val(data['external_ip']).attr('readonly', false);
      
      $(".modify-option").show();
      $(".modify-ip-form-dialog").dialog({
         title: "Modify IP",
         width: 400,
         modal: true,
         resizable: false
      });
      e.preventDefault();
   });
   
   $(".view-btn").click(function(e){
      var data = $(this).next().attr('data-info');
      data = $.parseJSON(data);
      $("#modify-employee-id").val(data['employee_id']);
      $("#modify-seat-no").val(data['seat_no']);
      $("#modify-department-name").val(data['department']).attr('readonly', true);
      $("#modify-assign-ip").val(data['assign_ip']).attr('readonly', true);
      $("#modify-gateway").val(data['gateway']).attr('readonly', true);
      $("#modify-external-ip").val(data['external_ip']).attr('readonly', true);
      $(".modify-option").hide();
      $(".modify-ip-form-dialog").dialog({
         title: "View IP",
         width: 400,
         modal: true,
         resizable: false
      });
      e.preventDefault();
   });
   
   /*qtip for main menu*/
   $('.ip-management-list-option a.btn_vmd_1, .ip-management-list-option a.btn_vmd_3').qtip({ 
       style: { 
        name: 'light', tip: true ,
        fontSize: 14,
        textAlign: 'center',
        padding: 10,
        border: {
             width: 3,
             radius: 8,
             color: '#656563'
          }
       },
        position: {
          corner: {
             target: 'topMiddle',
             tooltip: 'bottomMiddle'
          }
       }
   });
});