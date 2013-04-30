(function( window ) {
   function InReport() {};   
   InReport.prototype.get_sub_category = function(id, element ) {
      element.html('<option value="">loading..</option>');
      $.ajax({
         url: urls.ajax_url,
         type: 'post',
         data: {
            id: id,
            mod: 'stock|ajax_in_report|get_sub_category_by_id'
         },
         success: function(response) {
            try {
               var data = $.parseJSON(response);
               if( data['status'] && data['sub_categories'] ) {
                  var sub_cat_length = data['sub_categories'].length;
                  var fragment = document.createDocumentFragment(); 
                  var option = document.createElement('option');
                  var text_content = document.createTextNode("Select Sub Category");
                  option.value = "";
                  option.appendChild(text_content);
                  fragment.appendChild(option);
                  for( var i = 0; i < sub_cat_length; i += 1 ) {
                     option = document.createElement('option');
                     text_content = document.createTextNode(data['sub_categories'][i]['name']);
                     option.appendChild(text_content);
                     option.value = data['sub_categories'][i]['id'];
                     fragment.appendChild(option);
                  };
                  element.html(fragment);
               }else {
                  element.html('<option value="">Select Sub Category</option>');
               }
            }catch(e){
               site.message("Sorry, there is a problem retrieving model option.", $(".js-message"),"warning");
               element.html('<option value="">Select Sub Category</option>');
            }
         }
      });
   };
   
   InReport.prototype.get_stock_item_by_cat_id = function(id, element ) {
      element.html('<option value="">loading..</option>');
      $.ajax({
         url: urls.ajax_url,
         type: 'post',
         data: {
            id: id,
            mod: 'stock|ajax_in_report|get_stock_item_by_sub_id'
         },
         success: function(response) {
            try {
               var data = $.parseJSON(response);
               if( data['status'] && data['stock_items'] ) {
                  var stock_item_length = data['stock_items'].length;
                  var fragment = document.createDocumentFragment(); 
                  var option = document.createElement('option');
                  var text_content = document.createTextNode("Select Serial");
                  option.value = "";
                  option.appendChild(text_content);
                  fragment.appendChild(option);
                  for( var i = 0; i < stock_item_length; i += 1 ) {
                     option = document.createElement('option');
                     text_content = document.createTextNode(data['stock_items'][i]['serial']);
                     option.appendChild(text_content);
                     option.value = data['stock_items'][i]['id'];
                     $(option).attr("data-user-id", data['stock_items'][i]['user_id']);
                     $(option).attr("data-purchased", data['stock_items'][i]['purchased_date']);
                     fragment.appendChild(option);
                  };
                  element.html(fragment);
               }else {
                  element.html('<option value="">Select Serial</option>');
               }
            }catch(e){
               site.message("Sorry, there is a problem retrieving stock serial.", $(".js-message"),"warning");
               element.html('<option value="">Select Serial</option>');
            }
         }
      });
   };
   
   InReport.prototype.get_assign = function(id, element) {
      element.val('loading..');
      $.ajax({
         url: urls.ajax_url,
         type: 'post',
         data: {
            id: id,
            mod: 'stock|ajax_in_report|get_assign_on_item'
         },
         success: function(response) {
            try {
               var data = $.parseJSON(response);
               if( data['status'] && data['employee'] ) {
                  element.val(data['employee']['fullname']);
               }else {
                  element.val('');
               }
            }catch(e){
               site.message("Sorry, there is a problem retrieving assigned employee.", $(".js-message"),"warning");
               element.val('');
            }
         }
      });
   };
   
   InReport.prototype.exec_delete = function(aidx, ajax_url) {
      if( aidx ) {
         $.ajax({
            url : urls.ajax_url,
            type : "post",
            data :{
               mod:ajax_url,
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
   window.InReport = InReport;
}(window));
