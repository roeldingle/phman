
<?php
$aoptions = array(
   "id" =>"add-form",
   "name" =>"add-form",
   "class" =>"myform",
   "method_type" =>"post",
   "module" =>"example",
   "controller" => "exec",
   "method" => "save"   
);
$this->common->form_submit($aoptions);
?>
   <table>
   <tr><td>First Name:</td><td><input type="text" name="firstname"/></td></tr>
   <tr><td>Middle Name:</td><td><input type="text" name="middlename"/></td></tr>
   <tr><td>Last Name:</td><td><input type="text" name="lastname"/></td></tr>
   <tr><td>Address:</td><td><input type="text" name="address"/></td></tr>
   <tr><td colspan="2"><input type="submit" value="Save"/></td></tr>
   </table>
<form>
