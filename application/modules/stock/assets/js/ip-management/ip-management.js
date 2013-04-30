;(function(){
   function IPManagement() {};
   IPManagement.prototype.exec_delete = function(aidx, ajax_url) {
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
   window.IPManagement = IPManagement;
}());