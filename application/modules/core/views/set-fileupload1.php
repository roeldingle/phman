<div class="core-fileupload-container">
   <div class="core-fileupload-addfile-button">
      <input type="button" class="core-upload-button-class core-upload-button button-<?php echo $uploadname_enc;?>" value="<?php echo $button_text;?>" />
      <input type="hidden" id="<?php echo $uploadname;?>" name="<?php echo $uploadname;?>"/>
      <span class="core-fileupload-loader no-display core-fileupload-message-loader-<?php echo $uploadname_enc; ?>">Attaching</span>
      <span class="core-fileupload-error-message no-display core-fileupload-error-message-<?php echo $uploadname_enc; ?>"></span>
   </div>
   <div class="core-fileupload-upload-list">
      <ul class="core-fileupload-temp-list ul-<?php echo $uploadname_enc;?>">
      </ul>
   </div>
</div>