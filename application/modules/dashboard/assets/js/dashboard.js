function Dashboard() {
   this.save_state = function( order ) {
      $.ajax({
         url : urls.ajax_url,
         type : "post",
         data :{
            mod:"dashboard|dashboard_ajax|save_state",
            order: order
         },success : function(response){
         }
      });
   };
   
   this.save_max_min = function( ) {
      var sortorder = [];
      var total= new Array();
      var itemorder = $('.column').sortable('toArray');
      $('.dashboard-key').each(function(){
         var sortorder_obj = {};
         var dashboard_flags = $(this).children().children('.table-container');
         sortorder_obj[dashboard_flags.attr('data-key')] = dashboard_flags.attr('data-status');
         sortorder.push(sortorder_obj);
      });
      /**var order = $.map(sortorder, function (value, key) { return value; });**/
      $.ajax({
         url : urls.ajax_url,
         type : "post",
         data :{
         mod:"dashboard|dashboard_ajax|save_state",
         order: sortorder,
         },success : function(response){
         }
      });
   }
};

jQuery(document).ready(function($){
    var dashboard = new Dashboard();
    var tb_sorter_options1 = {
        tb_selector_id : 'recent-logs-table',
        headers: {
        }
    };
    var tb_sorter_options2 = {
        tb_selector_id : 'recent-activities-table',
        headers: {
        }
    };
    var tb_sorter_options3 = {
        tb_selector_id : 'expense-management-table',
        headers: {
        }
    };
    Site.init_tb_sorter(tb_sorter_options1);
    Site.init_tb_sorter(tb_sorter_options2);
    Site.init_tb_sorter(tb_sorter_options3);
        
    $("#recent-logs-row").change(function(e){
        window.location.href = urls.current_url + '?row=' + $(this).val() + "&type=logs#logs";
    });
    $("#recent-activities-row").change(function(e){
        window.location.href = urls.current_url + '?row=' + $(this).val() + "&type=activities#activities";
    });

    $("#stock-row").change(function(e){
        window.location.href = urls.current_url + '?row=' + $(this).val() + "&type=stock#stock";
    });
    
   /** Initialize sortable for dashboard display items**/
   $('.column').sortable({
      connectWith: '.column',
      handle: '.header-span',
      cursor: 'move',
      placeholder: 'placeholder',
      forcePlaceholderSize: true,
      opacity: 0.9,
      revert: 200,
      stop: function(event, ui){
         var sortorder = [];
         var total= new Array();
         var itemorder = $('.column').sortable('toArray');
         $('.dashboard-key').each(function(){
            var sortorder_obj = {};
            var dashboard_flags = $(this).children().children('.table-container');
            sortorder_obj[dashboard_flags.attr('data-key')] = dashboard_flags.attr('data-status');
            sortorder.push(sortorder_obj);
         });
         /**var order = $.map(sortorder, function (value, key) { return value; });**/
         dashboard.save_state(sortorder);

      },update: function(event, ui){
      }
   }).disableSelection();
   /** Create tooltip for each item display summary**/
   $('.header-span[title]').qtip({ 
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
             target: 'topRight',
             tooltip: 'leftBottom'
          }
       }
   });   
   /** Minimize dashboard item**/
   $(".up-icon").live('click',function(){
      var parent = $(this).parent();
      var parent_next = $(this).parent().parent().parent().next();
      parent_next.slideUp('fast',function(){
         parent.html('<i class="down-icon" title="Maximize"></i>');
         $(this).attr("data-status","off");
         dashboard.save_max_min();
      });
   });
   /** Maximize dashboard item**/   
   $(".down-icon").live('click',function(){
      var parent = $(this).parent();
      var parent_next = $(this).parent().parent().parent().next();
      parent_next.slideDown('fast',function(){         
         $(this).attr("data-status","on");
         parent.html('<i class="up-icon" title="Minimize"></i>');
         dashboard.save_max_min();
      });
   });
});