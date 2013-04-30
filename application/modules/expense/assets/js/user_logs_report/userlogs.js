function UserLogs() {
   // Initialize search
   this.search = function(e){
      window.location.href = urls.current_urll = "?search="+$(".search-txtbox").val();
   }
   // Initialize date range search
   this.date_range_search = function(e){
      var from = $("#calendar_from");
      var to = $("#calendar_to");
      // Initialize default error
      var error = 0;
      // Check from value
      if( from.val() == "" ) {
         error += 1;
         from.css({border:"solid 1px red"});
      }
      // Check to value
      if( to.val() == "" ) {
         error += 1;
         to.css({border:"solid 1px red"});
      }
      // If error is zero check date equality
      if( error == 0 ) {
         if( Date.parse(from.val()) > Date.parse(to.val() ) ) {
            $(".date-range-message").show();
            return false;
         } else {
            window.location.href = urls.current_urll = "?from="+from.val() + "&to=" + to.val();
         }      
      }
   }
}
// Initialize document ready
jQuery(document).ready(function($){
   var userlogs = new UserLogs();
   // Create sorter object
   var tb_sorter_options = {
      tb_selector_id : 'user-logs-table',
      headers: {
      }
   };   
   // Initialize table sorter
   Site.init_tb_sorter(tb_sorter_options);
   // Initialize date picker
   $('#calendar_from,#calendar_to').datepicker({});
   // Initialize search
   $(".search-btn").click(function(){
      userlogs.search();
   });
   // Initialize search date range
   $(".date-range-btn").click(function(){
      userlogs.date_range_search();
   });
   // Check if there is value for #calendar_from
   $("#calendar_from").change(function(){
      $(this).css({border:"solid 1px #ACACAC"});
   });
   // Check if there is value for #calendar_to
   $("#calendar_to").change(function(){
      $(this).css({border:"solid 1px #ACACAC"});
   });
});