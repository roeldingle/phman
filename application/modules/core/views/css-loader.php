<?php
if($acss_source){
  foreach($acss_source as $rows){
   $sattributes = "";
   if(isset($rows['attributes'])){
      foreach($rows['attributes'] as $key=>$val){
         $sattributes .= " " . $key . '="' . $val . '"'; 
      }
   }
?>
<link rel="stylesheet" href="<?php echo $rows['sfile'];?><?php echo ( $rows['cache'] === true ) ? "?cac=true" : "";?>" <?php echo $sattributes;?>/>
<?php
  }
}