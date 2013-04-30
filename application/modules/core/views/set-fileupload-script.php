(function() {
   var modulename = "<?php echo $modulename;?>";
   var upload_id = "<?php echo $uploadname_enc;?>";
   var extensions = [<?php echo $extensions;?>];
   var total_upload = <?php echo $total_upload;?>;  
   var action =  urls.request_url + "u/" + modulename;
   var counter = 1;
   var li = "";
   upclick({
      dataname : "core-file",
      element : $(".button-" + upload_id).get(0),
      action : action,
      zindex : 999999,
      name : "<?php echo $uploadname;?>",
      action_params : {
         perform : "0",
         upload_id: upload_id
      },onstart : function(filename,f) {
         var filename =  filename.replace(/^.*[\\\/]/, '');
         var ext = filename.split('.').pop();
         $(".core-fileupload-error-message-"+upload_id).hide();
         $(".core-fileupload-message-loader-"+upload_id).hide();         
         if( ext !== "" ) {
            if(counter <= total_upload) {
               if( $.inArray(ext,extensions) !== -1 ) {
                  $(".core-fileupload-message-loader-"+ upload_id ).html("Attaching " + filename + "...").fadeIn();
                  f.submit();            
               } else {
                  $(".core-fileupload-error-message-"+ upload_id ).html("Invalid file format.").fadeIn(100);
                  return false;
               }
           } else {
               $(".core-fileupload-error-message-"+ upload_id ).html("Total uploads exceeded.").fadeIn(100);
           }
         }else{
            $(".core-fileupload-error-message-"+ upload_id ).html("Invalid file").fadeIn(100);
            return false;         
         }
      },oncomplete : function(response) {
         try {
            var j = jQuery.parseJSON( response ); 
            if( j.status==='ok' ) {
               var filename = j.filename;
               var newname = j.newname;
               var extension = j.extension; 
               var class_type = (site.class_ext[extension]===undefined) ? "core-icons-default" : site.class_ext[extension];
               li = '<li><span class="'+class_type+'"></span><span>'+filename+'</span><input type="hidden" value="'+newname+'" name="'+upload_id+'-new[]"/><input type="hidden" value="'+filename+'" name="'+upload_id+'-name[]"/><a href="javascript:void(0);" class="core-fileupload-remove-file-button-<?php echo $uploadname_enc;?>" alt="'+newname+'">x</a></li>';
               $(".ul-"+upload_id).append(li);            
               $("#<?php echo $uploadname;?>").val(counter);
               counter += 1;
            }else if( j.status==='large' ) {
               $(".core-fileupload-error-message-"+upload_id).html("File size exceeded.").fadeIn();
            
            }else if( j.status==='invalid' ) {
               $(".core-fileupload-error-message-"+upload_id).html("Invalid file format.").fadeIn();
            }
         } catch ( err ) {
            $(".core-fileupload-error-message-"+upload_id).html("Sorry there is an error uploading your file.").fadeIn();
            return false;
         }
         $(".core-fileupload-message-loader-"+upload_id).hide();
      }
   });

   $(".core-fileupload-remove-file-button-<?php echo $uploadname_enc;?>").live('click',function() {
      var that = $(this);   
      that.parent().remove();
      counter -= 1;
   });
})();