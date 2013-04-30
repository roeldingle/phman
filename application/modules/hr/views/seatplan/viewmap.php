<br/>
<h1 class="title nm np fl">
<strong class=""><?php echo ucwords($aresult[0]->tss_map_name);?></strong>
<span class="subtext" style="font-size:15px">Manage View</span>
</h1>


   <?php if($aresult[0]->tss_map_src!=null){ ?>

    <div class="content np">
      <iframe src="<?php echo $module_path;?>seat_plan/Map" class="map_size" scrolling="no"></iframe>
    </div>

   <?php }else{ echo '<br><br><br>Image not set yet';} ?>
