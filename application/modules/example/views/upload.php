

<div class="add-new-dialog no-display">
<form id="test-form" name="test-form" method="post" action="<?php echo $exec_path;?>">
<input type="hidden" name="mod" value="example|exec|upload"/>
<?php
$this->common->get_message("upload-succcess");
?><?php

$this->app->set_fileupload(
   array(
      "modulename" => "example",
      "uploadname"=>"first",
      "button_text"=>"Add File",
      "directory" => "bills-attachment",
      "extensions"=>array("odt",'txt',"gif","jpg","png","exe","bz2","pdf"),
      "total_upload" => 3,
      "file_size" => array("pdf" =>"5000000")
   )
);

$this->app->set_fileupload(
   array(
      "modulename" => "example",
      "uploadname"=>"second",
      "button_text"=>"Add File",
      "directory" => "receipt-attachment",
      "extensions"=>array("odt",'txt',"gif","jpg","png","exe","bz2","pdf"),
      "total_upload" => 3,
      "file_size" => array("pdf" =>"5000000")
   )
);
?>
<a href="javascript:document.forms['test-form'].submit();">Save</a>
</form>
</div>

<br />
<a href="javascript:void(0);" class="up-test"> Add New</a> <br />
<a href="javascript:void(0);" class="up-test1"> Test</a> <br />
