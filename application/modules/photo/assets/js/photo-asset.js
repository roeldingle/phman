/** Photo Assets constructor function **/
function PhotoAsset() {};
PhotoAsset.prototype.exec_search = function( elem ) {
   window.location.href = urls.module_url + "photo_assets/?search=" + elem.val();
};
PhotoAsset.prototype.exec_edit = function( id ) {
   window.location.href = urls.module_url + "photo_assets/edit_photo_asset/?id=" + id;
};
PhotoAsset.prototype.exec_delete = function( aidx ) {
   if( aidx ) {
      $.ajax({
         url : urls.ajax_url,
         type : "post",
         data :{
            mod:"photo|ajax_photo_asset|remove_photo",
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
jQuery(document).ready(function( $ ) {
   /** Create new instance of PhotoAsset**/
   var photoasset = new PhotoAsset();
   /** Initialize table sorter for photo assets list**/
   var tb_sorter_options1 = {
      tb_selector_id : 'photo-assets-list-table',
      headers: {
         0: { sorter: false }
      }
   };
   Site.init_tb_sorter( tb_sorter_options1 );
   /** Initialize validation **/
   $("[name='photo-asset-form']").validate({
      validClass : "success",
      errorClass : "error",
      rules: {
         category: {
            required: true
         },
         item: {
            required: true
         },
         description: {
            required: true,
            maxlength: 300
         },
         remarks: {
            required: true,
            maxlength: 300
         },
         status:{
            required: true
         }
      },
      messages: {
         category: "Please select category.",
         item: "Please enter item name.",
         status: "Please enter status."
      }
   });
   
   /** Execute save **/   
   $(".save-photo-btn").click(function() {
      $("[name='photo-asset-form']").submit();
   });
   
   /** Execute search **/
   $(".search-btn").click(function(){
      photoasset.exec_search( $(".search-tbox") );
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
               photoasset.exec_edit( rows.filter(":checked").val() );
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
            site.message("Please select record you want to delete.",$(".js-message"),"warning");
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
            photoasset.exec_delete(aidx);
            aidx = [];
         }
      };
   }());
   
   /** Cancel deletion. **/
   $("#cancel-delete-btn").click(function(){
      site.close_dialog($(".confirm-delete-dialog"));
   });
});
