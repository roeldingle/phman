<h2 class="title nm np fl"><strong class="">Dashboard </strong></h2>
<!-- BEGIN inner content -->
<div id="column_1" class="content np column">
   <?php if($asequence_arrangement) { 
      foreach( $asequence_arrangement as $rows ) {
         echo $rows;
      }
   }else{
      echo $sload_default_html;
   }?>
</div>
<!-- END inner content -->