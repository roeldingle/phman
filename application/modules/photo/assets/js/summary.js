jQuery(document).ready(function(){
   var tb_sorter_options1 = {
      tb_selector_id : 'photo-assets-list-table'
   };
   Site.init_tb_sorter( tb_sorter_options1 );
   
   /** Initialize table sorter for photo assets list**/
   var tb_sorter_options2 = {
      tb_selector_id : 'request-list-table'
   };
   Site.init_tb_sorter( tb_sorter_options2 );   
});