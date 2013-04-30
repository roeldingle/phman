<style>
    .ui-datepicker-calendar { display: none; }
</style>
<div class="message-container"></div>

<div class="content np">
    <div class="table_wrap_3 mt10">
        <h2 class="title nm np fl"><strong class="">Budget Comparing Summary</strong>

    </div>
    
    <!--Show Rows-->
    <div class="show_rows fr">        
        <form>
            <input type="hidden" id="limit" value="<?php echo $alimit['limit']; ?>">
            <input type="hidden" id="offset" value="<?php echo $alimit['offset']; ?>">
            <label>Number of Years</label>
            <select id="show_rows">
                <option value="1" <?php if($ilimit == "1"){ echo "SELECTED"; } ?>>1</option>
                <option value="2" <?php if($ilimit == "2"){ echo "SELECTED"; } ?>>2</option>
                <option value="3" <?php if($ilimit == "3"){ echo "SELECTED"; } ?>>3</option>
                <option value="4" <?php if($ilimit == "4"){ echo "SELECTED"; } ?>>4</option>
            </select>
        </form>
    </div>
    
    <!-- table_wrap_3 -->	
    <div class="table_wrap_3">
        <table class="tstyle_1 tfonts_4 ac">
            
            <colgroup />
            <?php foreach($years as $kyear=>$vyear) { 
                if($kyear == count($bgcolor)){
                    $kyear = 0;
                }?>
                <colgroup span="3" class="tborder_1 tbground_<?php echo $bgcolor[$kyear]; ?>"/>
            <?php }?>
            <thead class="tborder_1 ">
                <tr>
                    <th>Period</th>
                    <?php if(empty($years)) { ?>
                        <th colspan="2"></th>
                        <th>Difference</th>
                    <?php } else { ?>
                        <?php foreach($years as $kyear=>$vyear) { ?>
                            <th colspan="2"><?php echo $vyear->teb_year; ?></th>
                            <th>Difference</th>
                        <?php }?>
                    <?php }?>
                </tr>
                <tr>
                    <th></th>
                    <?php if(empty($years)) { ?>
                        <th>Planned Budget</th>
                        <th>Real Expense</th>
                        <th></th>
                    <?php } else { ?>
                        <?php foreach($years as $kyear=>$vyear) { ?>
                        <th>Planned Budget</th>
                        <th>Real Expense</th>
                        <th></th>
                        <?php } ?>
                    <?php } ?>
                </tr>
            </thead>
            <tfoot class="tborder_1 tbground_8">
                <tr>
                    <th class="al">Total</th>
                    <?php if(empty($years)) { ?>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                    <?php } else { ?>
                        <?php foreach($years as $kyear=>$vyear) { ?>
                        <td><?php echo $total_amounts[$kyear]['total_planned_budget']; ?></td> <!--Total Planned Budget-->
                        <td><?php echo $total_amounts[$kyear]['total_real']; ?></td> <!--Total Real Expense-->
                        <td class="<?php if($total_amounts[$kyear]['total_difference'] <0) { ?>tfonts_6<?php }?>"><?php echo $total_amounts[$kyear]['total_difference']; ?></td> <!--Total Difference-->
                        <?php } ?>
                    <?php } ?>
                    
                </tr>
            </tfoot>
            <tbody>
                <input type="hidden" id="obj_exist" value="<?php echo count($years); ?>">
                <?php for($i=0;$i<12;$i++){ ?>
                <tr>
                    <th class="al"><?php echo $months[($i+1)]; ?></th>    
                    <?php if(empty($years)) { ?>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                    <?php } else { ?>
                    <?php foreach($years as $kyear=>$vyear) { ?>                     
                        <td><?php 
                        $planned_budget = "0.00";
                        for($ii=0;$ii<12;$ii++){                        
                            if(!empty($lists[$kyear][$ii]->tel_month) && $lists[$kyear][$ii]->tel_month == $months[($i+1)]) {                        
                                $planned_budget = number_format($lists[$kyear][$ii]->planned_budget, 2, '.', ',');
                            } 
                        } 
                        echo $planned_budget; ?></td> <!--Planned Budget-->
                        
                         <td><?php 
                        $real_expense = "0.00";
                        for($ii=0;$ii<12;$ii++){                        
                            if(!empty($lists[$kyear][$ii]->tel_month) && $lists[$kyear][$ii]->tel_month == $months[($i+1)]) {                        
                                $real_expense = number_format($lists[$kyear][$ii]->real_exp, 2, '.', ',');
                            } 
                        } 
                        echo $real_expense; ?></td> <!--Real Expense-->
                        
                        <td class="check <?php if($kyear == (count($years)-1)) { ?>ar<?php } ?>">
                        <?php $difference = "0.00"; 
                        for($ii=0;$ii<12;$ii++){ 
                            if(!empty($lists[$kyear][$ii]->tel_month) && $lists[$kyear][$ii]->tel_month == $months[($i+1)]) {                        
                                $difference = number_format($lists[$kyear][$ii]->difference, 2, '.', ',');                           
                            } 
                        } echo $difference; ?> </td> <!--Difference-->
                        
                    <?php } ?>
                    <?php } ?>
                </tr>  
                <?php } ?>
            </tbody>
        
            
        </table>
        
        <?php echo $pager; ?>
        
    </div>
    <!-- //table_wrap_3 -->
    
    
    <div class="table_wrap_3" id="summary_graph" style="display:none;min-width: 400px; height: 400px; margin: 0 auto;margin-top:10px;"></div>
    
    <div class="table_wrap_3">
        <div class="table_fl_50 tfonts_4">
            <form action="">
                <span>Specific Period:</span>
                <div class="holder">
                    <label for="calendar_from" class="label_1">From:</label>
                    <input type="text" value="<?php echo $date_from; ?>" class="input_type_3" id="calendar_from" />
                </div>
                <div class="holder">
                    <label for="calendar_to" class="label_1">To:</label>
                    <input type="text" value="<?php echo $date_to; ?>" class="input_type_3" id="calendar_to"/>
                    
                </div>
            </form>
        </div>
        <div class="table_fr_30">
            <a href="javascript:void(0)" class="btn_export fr" title="Export to Excel" id="export_excel"><span>Export</span></a>
        </div>
    </div>
    <div class="table_wrap_3">
        <a href="javascript:void(0)" class="btn_small btn_type_2s fl"><span id="apply_sort">Apply</span></a>
        <a href="javascript:void(0)" class="btn_small btn_type_2s fl"><span id="reset_search">Reset</span></a>
        <a href="javascript:void(0)" class="btn_small btn_type_2s fr"><span id="view_graph">View Graph</span></a>
    </div>			
</div>
<!-- END inner content -->
</div>
<!-- END main -->