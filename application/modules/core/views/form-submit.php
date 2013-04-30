<?php
if($aoptions){
   $soptions = "";
   foreach($aoptions as $key=>$val){      
      if(!in_array($key,array("module","controller","method","method_type"))){
         $soptions .= $key . '=' . "'{$val}' ";
      }else{   
         if($key==="method_type"){
            $soptions .= "method" . '=' . "'{$val}' ";         
         }
      }
   }
   
?>
<form action="<?php echo $this->environment->exec_path;?>" <?php echo  $soptions ?>>
<input type="hidden" name="form-token" value="<?php echo $sform_token;?>"/>
<input type="hidden" value="<?php echo $aoptions['module'] . '|' . $aoptions['controller'] . '|' . $aoptions['method'];?>" name="mod"/>
<?php
}
?>
