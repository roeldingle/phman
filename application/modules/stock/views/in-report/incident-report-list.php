<h2 class="title nm np fl"><strong class="">Incident Report</strong><span class="subtext"></span></h2>
<a title="Add New Incident" href="<?php echo $module_path?>in_report/add_incident_report?type=<?php echo ($stype=='office') ? 'office' : 'others';?><?php echo ($icategory_id) ? "&category={$icategory_id}" : "";?>" class="btn_small btn_type_1s fl ml10"><span>Add New Incident</span></a>
<div class="category_container">							
      <!-- BEGIN inner content -->
      <div class="content np">
         <!-- TABS-->
         <ul class="tabmenu">
            <li><a title="Office Equipments List" href="?type=office" <?php echo ($stype=='office') ? 'class="current"' : '';?>>Office Equipments</a></li>
            <li><a title="Others List" href="?type=others" <?php echo ($stype=='others') ? 'class="current"' : '';?>>Others</a></li>
         </ul>
         <?php echo $shtml_list;?>
      </div>
<!-- END inner content -->
</div> <!-- END category container -->