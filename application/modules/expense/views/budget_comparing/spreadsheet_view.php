<style>
    .ui-datepicker-calendar { display: none; }
</style>
<div class="message-container"></div>

<!-- BEGIN inner content -->
<div class="content np">
    <div class="table_wrap_3 mt10">
        <h2 class="title nm np fl"><strong class="">Budget Comparing - Spread Sheet View</strong>
        <span class="subtext"><?php echo $today;?></span></h2>
        <div class="search_01 fr">
            <form action="#" method="post">
                <input type="text" value="<?php echo $keyword;?>" class="input_type_4" id="keyword" placeholder="Search Category Name"/>
                <a href="javascript:void(0);" class="btn_small btn_type_2s" id="search_btn"><span>Search</span></a>
            </form>
        </div>						
    </div>
    <div class="table_wrap_3">
        <div class="table_fr_50 ar">
            <a href="javascript:void(0);" class="link_1" id="go_detailed_view">&gt;&gt;Switch to Detailed View</a>	
        </div>						
    </div>
    <!-- table_wrap_3 -->	
    
    <!--Show Rows-->
    <div class="show_rows fr">
        <input type="hidden" id="limit" value="<?php echo $limit; ?>">
        <input type="hidden" id="offset" value="<?php echo $offset; ?>">
        <form>
            <label>Number of Months</label>
            <select id="show_rows">
                <option value="5" <?php if($ilimit == "5"){ echo "SELECTED"; } ?>>5</option>
                <option value="10" <?php if($ilimit == "10"){ echo "SELECTED"; } ?>>10</option>
                <option value="15" <?php if($ilimit == "15"){ echo "SELECTED"; } ?>>15</option>
                <option value="20" <?php if($ilimit == "20"){ echo "SELECTED"; } ?>>20</option>
                <option value="50" <?php if($ilimit == "50"){ echo "SELECTED"; } ?>>50</option>
            </select>
        </form>
    </div>
    <input type="hidden" id="obj_exist" value="<?php echo count($alists); ?>">                    
        <?php  
        
        if(!empty($alists)){       
        foreach($alists as $kmonths=>$vmonths) {?>
        <div class="table_wrap_3">
			<table class="tstyle_2 tborder_2 tfonts_4">
                <colgroup>
                    <col width="70" />
                    <col width="70" />
                    <col />
                    <col width="85" />
                    <col width="85" />
                    <col width="130" />
                </colgroup>
                
                <thead class="ac tfonts_5">
                    <tr>
                        <th colspan="<?php if(!empty($vmonths->lists)){ echo "3"; } else { echo "6";}?>"><input type="hidden" value="<?php echo $vmonths->tel_month.",".$vmonths->tel_year;?>" name="month_name"><?php echo $vmonths->tel_month." ". $vmonths->tel_year;?> Real Expense (Total Cost)</th>
                        <?php if(!empty($vmonths->lists)){ ?>
                        <th>Planned Budget</th>
                        <th>Difference</th>
                        <th>Comment <span class="edit"></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($vmonths->lists)){
                    foreach($vmonths->lists as $klists=>$vlists) { ?>  
                    <tr>
                        <td class="al"><?php echo date('m/d/Y', $vlists->tel_date); ?></td> <!--Date--->
                        <td class="ar"><?php echo number_format($vlists->tel_payment, '2', '.', ','); ?></td> <!--Payment--->
                        <td class="al"><?php echo $vlists->tec_name; ?></td> <!--Category--->                    
                        
                        <?php if($klists == 0){ ?>
                        <td class="ar"><?php echo $vmonths->planned_budget;?></td> <!--Planned Budget--->
                        <td rowspan="<?php echo count($vmonths->lists); ?>"></td>
                        <td rowspan="<?php echo count($vmonths->lists)+1;?>"><span name="month_comment"><?php if($vmonths->comment!=""){ echo $vmonths->comment; } else { echo "No existing comment.";}?></span></td> <!--COMMENT-->
                        <?php } ?>
                    </tr>
                    <?php } ?>
                    
                    <tr>
                        <th class="al">Total</th>
                        <th class="ar"><?php echo $vmonths->total;?></th>
                        <th></th>
                        <th class="ar"><?php echo $vmonths->planned_budget;?></th>
                        <th class="ar <?php if($vmonths->difference < 0){ echo "tfonts_6"; }?>"><?php if($vmonths->difference < 0){ echo "(".substr($vmonths->difference,1).")"; } else { echo $vmonths->difference; }?></th>
                    </tr>
                    <?php  
                    } else {?>  
                        <tr><td class="al" colspan="6">No record found.</td></tr> <!--search-->
                    <?php } ?>   
                </tbody>
            </table>	
        </div>
        <?php } 
        } else {?>  
        <div class="table_wrap_3">
            <table class="tstyle_2 tborder_2 tbground_5 tfonts_4">
                <thead class="tbground_1 ac tfonts_5">
                    <tr><th>No record found.</th></tr>
                </thead>
            </table>
        </div>
        <?php } ?>   
        
        <?php if(!empty($alists)){
            echo $pager;
        }?>
        <!-- //sorting dd-->
            <div class="table_wrap_3 mt10 mb10">
                <label>Sort By</label>
                <select class="select_type_1 nm np" name="sort_by">
                    <option value="recent" <?php if(isset($sort) && $sort == 'recent') {?> selected <?php } ?>>Most Recent</option>
                    <option value="oldest" <?php if(isset($sort) && $sort == 'oldest') {?> selected <?php } ?>>From Oldest</option>
                    <option value="positive_diff" <?php if(isset($sort) && $sort == 'positive_diff') {?> selected <?php } ?>>Positive Differences</option>
                    <option value="negative_diff" <?php if(isset($sort) && $sort == 'negative_diff') {?> selected <?php } ?>>Negative Differences</option>
                </select>						
            </div>
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
                    <a href="javascript:void(0);" class="btn_export fr" title="Export to Excel" id="export_excel"><span>Export</span></a>
                </div>
            </div>
            <div class="table_wrap_3">
                <a href="javascript:void(0);" class="btn_small btn_type_2s fl" id="apply_search"><span>Apply</span></a>
                <a href="javascript:void(0);" class="btn_small btn_type_2s fl" id="reset_search"><span>Reset</span></a>
            </div>
        </div>
   
<!-- END inner content -->