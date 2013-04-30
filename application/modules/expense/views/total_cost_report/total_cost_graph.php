<div id="content" class="np">
    <!-- BEGIN main -->
    <div id="main" class="np ml10">
        <div class="breadcrumbs">
           
        </div>
        <h2 class="title nm np fl"><strong class="">Total Cost Report </strong></h2>
        <!-- BEGIN inner content -->
        <div class="content np">
            <input type="hidden" name="page_action" value="<?php echo $this->uri->segment(3); ?>" />
            <div class="table_wrap_3" id="total_cost_graph" style="display:none;min-width: 400px; height: 400px; margin: 0 auto;margin-top:10px;"></div>
            <div class="table_wrap_3 mt10 mb10">
                <div class="table_fl_30">
                    <select class="select_type_1 nm np" id="tcrg_view">
                        <option value="0" <?php if(!isset($_GET['view'])){echo "selected"; } ?>>View More</option>
                        <option value="3" <?php if(isset($_GET['view'])==3){echo "selected";} ?>>Last 3 Years</option>
                        <option value="5" <?php if(isset($_GET['view'])==5){echo "selected";} ?>>Last 5 Years</option>
                        <option value="10" <?php if(isset($_GET['view'])==10){echo "selected";} ?>>Last 10 Years</option>
                        <option value="all" <?php if(isset($_GET['view'])=="all"){echo "selected";} ?>>All Years</option>
                    </select>	
                    <span class="message_type2 np" id="msg_tcrg_view"></span>
                </div>			
            </div>
            <div class="table_wrap_3">
                <div class="table_fl_30">
                    <div style="margin-bottom:10px;font-weight:bold">Specific Period:</div>
                    <div class="holder mb10">
                        <span class="label_2 twidth_1">From:</span>
                        <select class="select_type_2 np" id="gspecific_period_from">
                            <option value="0">Year</option>
                            <?php foreach($year as $y){ ?>
                                <option value="<?php echo $y->pyear; ?>" <?php if(isset($_GET['from'])==$y->pyear){echo "selected";} ?>><?php echo $y->pyear; ?></option>
                            <?php } ?>
                        </select>
                        <span class="message_type2 np" id="msg_specific_period_from"></span>
                    </div>
                    <div class="holder mb10">
                        <span class="label_2 twidth_1">To:</span>
                        <select class="select_type_2 np" id="gspecific_period_to">
                            <option value="0">Year</option>
                            <?php foreach($year as $y){ ?>
                                <option value="<?php echo $y->pyear; ?>" <?php if(isset($_GET['to'])==$y->pyear){echo "selected";} ?>><?php echo $y->pyear; ?></option>
                            <?php } ?>
                        </select>
                        <span class="message_type2 np" id="msg_specific_period_to"></span>
                    </div>							
                </div>
                <div class="table_fr_30">
                    
                </div>
                <!--
                <div class="table_fr_30">
                    <div class="twidth_2 mb10">
                        <input id="option2" class="radio_type_2 np" name="specific_period" type="radio" name="option" value="specific_period_cut_off" />
                        <label for="option2">Specific Cut Off Period:</label>				
                    </div>
                    <div class="holder mb10">
                        <label for="calendar_from"  class="label_2 twidth_1">From:</label>
                        <div class="nm np" style="display:inline-block">
                            <input type="text" class="input_type_3 fl" name="gdatefrom" id = "gdatefrom" />
                       </div>
                       <span class="message_type2 np" id="msg_datefrom"></span>
                    </div>
                    <div class="holder">
                        <label for="calendar_to" class="label_2 twidth_1">To:</label>
                        <div class="nm np" style="display:inline-block">
                            <input type="text" class="input_type_3 fl" name="gdateto" id = "gdateto" />
                        </div>
                        <span class="message_type2 np" id="msg_dateto"></span>
                    </div>							
                </div>
                -->
            </div>
            <div class="table_wrap_3">
                <a href="javascript:void(0)" class="btn_small btn_type_2s fl" id="specific_period_graph_apply"><span>Apply</span></a>
                <a href="<?php echo base_url() . "expense/total_cost_report"; ?>" class="btn_small btn_type_2s fr"><span>View Spreadsheet</span></a>	
            </div>
        <!-- BEGIN inner content -->
    </div>
    <!-- BEGIN main -->
</div>