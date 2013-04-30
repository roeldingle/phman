<!-- BEGIN sidebar -->
<div id="side" class="mr10 np">	
   <?php echo $this->template->login_status();?>
   <div class="side_box expense-sidebar-container no-display">
      <ul class="smb fnt nl np nm expense-sidebar-ul">
        <?php foreach($main_menu as $menu){ ?>            
            <?php if($menu->tsu_action == "menu"){ ?>
                <li class="<?php echo( $this->uri->rsegment(1) == 'expense' ) ? 'current_sidebar' : "current";?>"><a href="<?php echo base_url() . "expense/"; ?>" title="<?php echo $menu->tsu_desc; ?>"><?php echo $menu->tsu_label; ?></a>
                    <ul>
                        <?php
                        foreach($year as $y){
                            echo "<li><a href='#'>".$y->menu_year."</a>";
                                echo "<ul style='list-style-type:none' class='np nm'>";
                                foreach($month as $m){
                                    if($m->menu_year == $y->menu_year){
                                        if($this->uri->segment(2) == 'real_expense_spreadsheet'){
                                            echo "<li><a href='".base_url()."expense/real_expense_spreadsheet/".$y->menu_year."/".$m->i_menu_month."'>".$m->menu_month."</a></li>";
                                        }else{
                                            echo "<li><a href='".base_url()."expense/index/".$y->menu_year."/".$m->i_menu_month."'>".$m->menu_month."</a></li>";
                                        }
                                    }
                                }
                                echo "</ul>";
                            echo"</li>";
                        }
                        ?>
                    </ul>
                </li>
            <?php }else{ ?>
                
                <li class="<?php echo( $this->uri->rsegment(1) == $menu->tsu_action || ($this->uri->rsegment(1) == "budget_comparing_spreadsheet") && $menu->tsu_action == "budget_comparing_detailed" ) ? 'current_sidebar' : "current";?>"><a href="<?php echo base_url() . "expense/" . $menu->tsu_action; ?>" title="<?php echo $menu->tsu_desc; ?>"><?php echo $menu->tsu_label; ?></a></li>
            <?php } ?>
        <?php } ?>
      </ul>
   </div>
</div>
<!-- END sidebar -->