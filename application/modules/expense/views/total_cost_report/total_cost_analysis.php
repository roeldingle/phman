<div id="content" class="np">
    <div id="main" class="np ml10">
        <div class="breadcrumbs">
            
        </div>	
        <!-- BEGIN inner content -->
        <div class="content np">
            <div class="table_wrap_3 mt10">
                <h2 class="title nm np fl"><strong class="">Total Cost Report</strong>

            </div>
            <!-- table_wrap_2 -->
            <div class="table_wrap_2">
                <input type="hidden" name="page_action" value="<?php echo $this->uri->segment(3); ?>" />
                <?php if($year != null){ ?>
                <table class="tstyle_1 tfonts_4 ar">
                    <colgroup>
                        <col class="width_1"/>
                        <col class="width_1"/>
                        <col/>
                        <col/>
                        <col/>
                        <col/>
                    </colgroup>
                    <thead class="tborder_1 ac">
                        <tr>
                            <td></td>
                            <th colspan="5">Compare <strong>Total Cost</strong></th>
                        </tr>
                        <tr>
                            <td></td>
                            <th>Period</th>
                            <?php foreach ($year as $y){ 
                                    $ctr = 0;
                            ?>
                                <th><?php echo $y->pyear; ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($month as $m){ 
                                $ctr++;
                        ?> 
                            <?php if(in_array($m, $quarter_month)){ ?>
                                <tr>
                                    <?php if($m == "January"){ ?>
                                        <td class="ac" rowspan=3 style="font-weight:bold">1st Quarter</td>
                                    <?php }else if($m == "April"){ ?>
                                        <td class="ac" rowspan=3 style="font-weight:bold">2nd Quarter</td>
                                    <?php }else if($m == "July"){ ?>
                                        <td class="ac" rowspan=3 style="font-weight:bold">3rd Quarter</td>
                                    <?php }else if($m == "October"){ ?>
                                        <td class="ac" rowspan=3 style="font-weight:bold">4th Quarter</td>
                                    <?php }else{ ?>
                                        <td class="ac"></td>
                                    <?php } ?>
                                    
                                    <td class="al"><?php echo $m; ?></td>
                                  
                                    <?php foreach($year as $y){  ?>
                                        <?php foreach($monthly_cost as $mc){ ?>
                                            <?php if($mc['year'] == $y->pyear && $mc['month'] == $m){ ?>
                                                <td>
                                                    <?php
                                                        if(strtotime("01 " . $m . $y->pyear) <= strtotime("now") && $m . " " . $y->pyear != date('F') . " " . date('Y')){
                                                                if($mc['total_payment']==null){
                                                                    echo "0.00"; 
                                                                }else{
                                                                    echo $mc['total_payment']; 
                                                                }
                                                        }
                                                    ?>
                                                </td>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                </tr>
                            <?php }else{ ?>
                                <tr>
                                    <td class="al"><?php echo $m; ?></td>
                                    
                                     <?php foreach($year as $y){ ?>
                                        <?php foreach($monthly_cost as $mc){ ?>
                                            <?php if($mc['year'] == $y->pyear && $mc['month'] == $m){ ?>
                                                <td>
                                                    <?php 
                                                        if(strtotime("01 " . $m . $y->pyear) <= strtotime("now") && $m . " " . $y->pyear != date('F') . " " . date('Y')){
                                                                if($mc['total_payment']==null){
                                                                    echo "0.00"; 
                                                                }else{
                                                                    echo $mc['total_payment']; 
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        
                        <tr style="background-color:#FFE65D">
                            <td></td>
                            <th class="al">Total Cost</th>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($total_cost as $tc){ ?>
                                        <?php if($y->pyear == $tc['year']){ 
                                            echo $tc['total_cost'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <th class="al">Annual Savings</th>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($annual_savings as $ac){ ?>
                                        <?php if($y->pyear == $ac['year']){ 
                                            if($ac['annual_savings'] < 0.00){
                                                echo "<span style='color:#FF0000'>{$ac['annual_savings']}</span>";
                                            }else{
                                                echo "<span style='color:#000000'>{$ac['annual_savings']}</span>";
                                            }
                                            
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="al"><strong>Monthly Average</strong> of Total Cost</td>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($monthly_average as $ma){ ?>
                                        <?php if($y->pyear == $ma['year']){ 
                                            echo $ma['monthly_average'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="al"><strong>Yearly Average</strong> of Total Cost</td>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($yearly_average as $ya){ ?>
                                        <?php if($y->pyear == $ya['year']){ 
                                            echo $ya['yearly_average'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="al">Q1 quarterly average total cost:</td>
                             <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($quarterly_average1 as $qa1){ ?>
                                        <?php if($y->pyear == $qa1['year']){ 
                                            echo $qa1['quarterly_average1'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                       
                        <tr>
                            <td></td>
                            <td class="al">Q2 quarterly average total cost:</td>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($quarterly_average2 as $qa2){ ?>
                                        <?php if($y->pyear == $qa2['year']){ 
                                            echo $qa2['quarterly_average2'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                         
                        <tr>
                            <td></td>
                            <td class="al">Q3 quarterly average total cost:</td>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($quarterly_average3 as $qa3){ ?>
                                        <?php if($y->pyear == $qa3['year']){ 
                                            echo $qa3['quarterly_average3'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="al">Q4 quarterly average total cost:</td>
                            <?php foreach($year as $y){ ?>
                                <td>
                                    <?php foreach($quarterly_average4 as $qa4){ ?>
                                        <?php if($y->pyear == $qa4['year']){ 
                                            echo $qa4['quarterly_average4'];
                                        } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td></td>
                            <?php foreach($year as $y){ ?>
                                <td></td>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td class="al">Budget Forecasts:</td>
                            <td class="al">Current <strong>monthly average cost</strong></td>
                            <?php
                                $i=0;
                                $len = count($year);
                            ?>
                            <?php foreach($year as $y){ ?>
                                <?php if($i == $len - 1){ ?>
                                    <td><strong><?php echo $current_monthly_average; ?></strong></td>
                                <?php }else{ ?>
                                        <td></td>
                                <?php }
                                    $i++;
                                ?>
                            <?php } ?>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="al">Previous - Current month average total cost</td>
                            <?php
                                $i=0;
                                $len = count($year);
                            ?>
                            <?php foreach($year as $y){ ?>
                                <?php if($i == $len - 1){ ?>
                                    <td><strong><?php echo $previous_current_monthly_average; ?></strong></td>
                                <?php }else{ ?>
                                        <td></td>
                                <?php }
                                    $i++;
                                ?>
                            <?php } ?>
                        </tr>
                       
                        <tr>
                            <td></td>
                            <td class="al">Previous - Current Quarterly average total cost</td>
                           <?php
                                $i=0;
                                $len = count($year);
                            ?>
                            <?php foreach($year as $y){ ?>
                                <?php if($i == $len - 1){ ?>
                                    <td><strong><?php echo $previous_current_quarterly_average; ?></strong></td>
                                <?php }else{ ?>
                                        <td></td>
                                <?php }
                                    $i++;
                                ?>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <?php foreach($year as $y){ ?>
                                <td></td>
                            <?php } ?>
                        </tr>
                       
                    </tbody>
                </table>
               
            </div>
            <!-- //table_wrap_2 -->
            <div class="table_wrap_3 mt10 mb10">
                <div class="table_fl_30">
                    <select class="select_type_1 nm np" id="tcr_view">
                        <option value="0" <?php if(!isset($_GET['view'])){echo "selected"; } ?>>View More</option>
                        <option value="3" <?php if(isset($_GET['view'])==3){echo "selected";} ?>>Last 3 Years</option>
                        <option value="5" <?php if(isset($_GET['view'])==5){echo "selected";} ?>>Last 5 Years</option>
                        <option value="10" <?php if(isset($_GET['view'])==10){echo "selected";} ?>>Last 10 Years</option>
                        <option value="all" <?php if(isset($_GET['view'])=="all"){echo "selected";} ?>>All Years</option>
                    </select>	
                    <span class="message_type2 np" id="msg_tcr_view"></span>
                </div>			
            </div>
            <div class="table_wrap_3">
                <div class="table_fl_30">
                    <div style="margin-bottom:10px;font-weight:bold">
                        Specific Period:
                        <span class="message_type2 np" id="msg_mb10"></span>	
                    </div>
                    <div class="holder mb10">
                        <span class="label_2 twidth_1">From:</span>
                        <select class="select_type_2 np" id="specific_period_from">
                            <option value="0">Year</option>
                            <?php foreach($year as $y){ ?>
                                <option value="<?php echo $y->pyear; ?>" <?php if(isset($_GET['from'])==$y->pyear){echo "selected";} ?>><?php echo $y->pyear; ?></option>
                            <?php } ?>
                        </select>
                        <span class="message_type2 np" id="msg_specific_period_from"></span>
                    </div>
                    <div class="holder mb10">
                        <span class="label_2 twidth_1">To:</span>
                        <select class="select_type_2 np" id="specific_period_to">
                            <option value="0">Year</option>
                            <?php foreach($year as $y){ ?>
                                <option value="<?php echo $y->pyear; ?>" <?php if(isset($_GET['to'])==$y->pyear){echo "selected";} ?>><?php echo $y->pyear; ?></option>
                            <?php } ?>
                        </select>
                        <span class="message_type2 np" id="msg_specific_period_to"></span>
                    </div>							
                </div>
                <div class="table_fr_30">
                    <a href="javascript:void(0)" class="btn_export fr" id="total_cost_report_export" title="Export to Excel"><span>Export</span></a>
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
                            <input type="text" class="input_type_3 fl" name="datefrom" id = "datefrom" />
                       </div>
                       <span class="message_type2 np" id="msg_datefrom"></span>
                    </div>
                    <div class="holder">
                        <label for="calendar_to" class="label_2 twidth_1">To:</label>
                        <div class="nm np" style="display:inline-block">
                            <input type="text" class="input_type_3 fl" name="dateto" id = "dateto" />
                        </div>
                        <span class="message_type2 np" id="msg_dateto"></span>
                    </div>							
                </div>
                -->
            </div>
            <div class="table_wrap_3">
                <a href="javascript:void(0)" class="btn_small btn_type_2s fl" id="specific_period_apply"><span>Apply</span></a>
                <a href="<?php echo base_url() . "expense/total_cost_report/total_cost_graph"; ?>" class="btn_small btn_type_2s fr"><span>View Graph</span></a>	
            </div>
        </div>
        <?php }else{ ?>
            <span style="text-align:center;font-weight:bold">No records found for total cost report.</span>
        <?php } ?>
        <!-- END inner content -->
    </div>
    <!-- END main -->
</div>