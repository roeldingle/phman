<script type="text/javascript">
(function(){
   window.urls = {
      base_url : '<?php echo base_url();?>',
      current_url : '<?php echo current_url();?>',
      module_url : '<?php echo $module_path;?>',
      module_assets_url : '<?php echo $module_assets_path;?>',
      assets_url : '<?php echo $assets_path;?>',
      ajax_url : '<?php echo $ajax_path;?>',
      exec_url : '<?php echo $exec_path;?>',
      request_url : '<?php echo $request_path;?>',
      getfile_url : '<?php echo $getfile_path;?>'   
   }
})();
</script>
<?php
if($ajs_source):
   foreach($ajs_source as $rows):
   $aoptions = array();   
      $sattributes = "";
      if(isset($rows['attributes'])){
         foreach($rows['attributes'] as $key=>$val){
            $sattributes .= " " . $key . '="' . $val . '"'; 
         }
      }
      if($rows['cache']===true){
         $aoptions["cac"] = "true";
      }
      if($slang_options){
         $aoptions['local'] = $slang_options;
      }
      $sqry_string = ( ($aoptions) ? "?" : "" ).  http_build_query($aoptions);
?>
<script <?php echo $sattributes;?> type="text/javascript" src="<?php echo $rows['sfile'];?><?php echo $sqry_string;?>"></script>
<?php
  endforeach;
endif;
?>
<script type="text/javascript">
jQuery(document).ready(function($){
   $(".show-per-rows").change(function(){
      this.query_string = "<?php echo preg_replace("/\&page=[0-9]/","",$this->common->qry_str_builder('row')); ?>";
      site.row(this);    
   });
   $(".check-all").click(function(){
      var this_row = $(this);
      var is_checked = this_row.is(":checked");
      $('.row-list').attr("checked",is_checked);
   });
});
</script>
