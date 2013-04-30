<style>
.menu_second_level{display:none;}

</style>
<h2 id="menu_title" class="title nm np fl"><strong class="">Menu </strong>
   <span class="subtext">The menu settings page.</span></h2>
   
    
    <div class="table_wrap_3 mt10">
         								
      </div>
   <!-- BEGIN inner content -->
<div class="content np">
<div id="sub_content">
    <div class="message_wrap"><?php echo $this->common->get_message("message-container");?></div>
    <div id="real_content">
        <div class="menu_container">
            <div class="menu_inner" >
                <div class="menu_listing fl ml10" >
                
                <!--loop starts-->
                <?php 
                    $aModuleData = $this->getclass->select('tbl_module','tm_active = 1 && tm_active = 1 ORDER BY tm_sequence');
                    foreach($aModuleData as $key=>$val){ ?>
                
                    <div class="menu_first_level">
                        <a href="javascript:void(0);" class="menu_first_level_a" name="<?php echo $val['tm_idx'];?>" title="<?php echo $val['tm_label'];?>" ><?php echo ucwords($val['tm_label']);?></a>
                        
                        <?php 
                        $aSubMenu = $this->getclass->select('tbl_submenu','tsu_tm_idx = '.$val['tm_idx'].' ORDER BY tsu_sequence');
                        
                        if(!empty($aSubMenu)){
                            foreach($aSubMenu as $k=>$v){ ?>
                                <div class="menu_second_level">
                                    <a href="javascript:void(0);" name="<?php echo $v['tsu_idx'];?>" title="<?php echo $v['tsu_label'];?>" ><?php echo ucwords($v['tsu_label']);?></a>
                                </div>
                        <?php 
                            }
                        } ?>
                        
                    </div>
                
                <?php } ?>
                <!--loop ends-->
                
                </div>
				<div class="selector fl mt10 ml5">
					<a class="select_up"></a>
					<a class="select_down"></a>
				</div>
                <div class="box_1 fl ml10">
                    <p class="box_1_title">Menu Information</p>
                    <form name="menu_info" id="menu_info" >
                        <p>
                        <label>Menu Label:</label>
                        <input type="hidden" class="input_type_5" id="txt_menu_idx" />
                        <input type="text" class="input_type_5" id="txt_menu_label" name="txt_menu_label"   readonly />
                        </p>
                        
                    </form>
                   <!-- <a href="javascript:void(0);" class="link_1">Delete</a> -->
                </div>
    
            </div>
        </div>
    </div>
    <div class="category_mgmt_bottom_btns">
        <a href="#" class="btn btn_type_3 mr10" id="btn_save_menu"><span>Save</span></a><a href="javascript:void(0)" id="return_default"class="link_1">Return to Default</a>
    </div>			
    <!-- END inner content -->
    </div>
<!--container for dialogbox-->
<div class="d_box"></div>
</div>