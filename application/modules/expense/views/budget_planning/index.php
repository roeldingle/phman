<!-- BEGIN content -->


    <!-- BEGIN main -->			
    <div id="main" class="np ml10">
        <div class="breadcrumbs">
            <a href="javascript:void(0)" class="main">Expense Management</a>&gt;
            <a href="javascript:void(0)">Budget Expense Planning </a>&gt;
            <a href="javascript:void(0)"><?php echo $today;?></a>
        </div>
        <div class="message-container"></div>
        
        <h2 class="title nm np fl"><strong class="">Budget Expense Planning</strong></h2>		
        <!-- BEGIN inner content -->
        <div class="content np">
            <!-- table_wrap_3 -->
            <div class="table_wrap_3">
                <!-- table_fl_50 -->
                <div class="table_fl_50">
                    <!-- table_3 -->
                    
                    <!--Show Rows-->
                    <div class="show_rows fr">                            
                            <input type="hidden" id="limit" value="<?php echo $limit; ?>">
                            <input type="hidden" id="offset" value="<?php echo $offset; ?>">
                            <input type="hidden" id="usergrade" value="<?php echo $usergrade; ?>">
                            <?php 
                            if(!empty($alists)){ ?>
                            <form>
                                <label>Number of Months</label>                        
                                <select class="nm np" id="show_rows">
                                    <option value="3" <?php if($ilimit == "3"){ echo "SELECTED"; } ?>>3</option>
                                    <option value="6" <?php if($ilimit == "6"){ echo "SELECTED"; } ?>>6</option>
                                    <option value="12" <?php if($ilimit == "12"){ echo "SELECTED"; } ?>>12</option>
                                    <option value="24" <?php if($ilimit == "24"){ echo "SELECTED"; } ?>>24</option>
                                    <option value="36" <?php if($ilimit == "36"){ echo "SELECTED"; } ?>>36</option>
                                    <option value="48" <?php if($ilimit == "48"){ echo "SELECTED"; } ?>>48</option>
                                    <option value="60" <?php if($ilimit == "60"){ echo "SELECTED"; } ?>>60</option>
                                </select>
                            </form>
                            <?php } ?>
                            
                            <label>Sort By</label>
                            <select class="select_type_1 nm np" name="sort_by">
                                <option value="recent" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'recent') {?> selected <?php } ?>>Most Recent</option>
                                <option value="oldest" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'oldest') {?> selected <?php } ?>>Oldest</option>
                                <option value="amnt_h_l" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'amnt_h_l') {?> selected <?php } ?>>Amount H-L</option>
                                <option value="amnt_l_h" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'amnt_l_h') {?> selected <?php } ?>>Amount L-H</option>
                            </select>                       
                        
                    </div>        
                    
                    <!-- Searching -->                    
                    <div class="table_fl_50 mb10">
                        <form action="">
                            <input type="radio" <?php if(empty($cutoff_from)){ echo "checked=checked"; } ?> name="specific_search" value="search_date">
                            
                            <label>Specific Period:</label>
                            <div class="holder">
                                <label for="calendar_from" class="label_1">From:</label>
                                <input type="text" value="<?php echo $from; ?>" class="input_type_3" id="calendar_from" />
                            </div>
                            <div class="holder">
                                <label for="calendar_to" class="label_1">To:</label>
                                <input type="text" value="<?php echo $to; ?>" class="input_type_3" id="calendar_to"/>
                            </div>                                            
                    </div>        
                    <div class="table_fl_50 mb10">        
                            <input type="radio" name="specific_search" value="search_cutoff" <?php if(!empty($cutoff_from)){ echo "checked=checked"; } ?>>
                            
                            <label>Specific Cut Off Period:</label>
                            <div class="holder">
                                <label for="calendar_from" class="label_1">From:</label>
                                    <select id="cutoff_from" class="input_type_3">
                                        <?php for($i=1;$i<=31;$i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php if(!empty($cutoff_from) && $cutoff_from == $i){ echo 'selected="selected"';} ?>><?php echo $i;?></option>
                                        <?php } ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="btn_small btn_type_2s" id="apply_search"><span>Apply</span></a>
                            </div>
                            <div class="holder">
                                <label for="calendar_to" class="label_1">To:</label>
                                <select id="cutoff_to" class="input_type_3">
                                    <?php for($i=1;$i<=31;$i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if(!empty($cutoff_to) && $cutoff_to == $i){ echo 'selected="selected"';} ?>><?php echo $i;?></option>
                                    <?php } ?>
                                </select>
                                
                                &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="btn_small btn_type_2s" id="apply_reset"><span>Reset</span></a>
                            </div>
                        </form>                        
                    </div>
                     <!-- end Show Rows Sorting -->
                    
                    <?php 
                    if(!empty($alists)){ ?> 
                    <h2 class="title np fl mb10"><strong class="">Recent Expenses</strong></h2>
                    <div class="table_wrap_3 mt10 mb10">
                        <a href="javascript:void(0);" class="btn_small btn_type_2s" name="add_recent"><span>Add Recent Expense</span></a>
                        <a href="javascript:void(0);" class="btn_small btn_type_2s" name="edit_recent"><span>Edit Recent Expense</span></a>  
                        <a href="javascript:void(0);" class="btn_small btn_type_2s" name="add_to_recent"><span>Add to Expected Expenses</span></a>  
                    </div>
                    
                    <input type="hidden" name="last_payment" value="<?php echo $lastmonthspayment; ?>"/>
                    <?php foreach($alists as $vmonths) { ?>
                    <table class="tstyle_2" name="recent_table" id="recent_<?php echo $vmonths->tel_month."/".$vmonths->tel_year?>">
                        <colgroup>
                            <?php if($usergrade == "000001" || $usergrade == "000002") { ?><col width="40" /><?php } ?>
                            <col width="75" />
                            <col />
                            <col width="80" />
                            <col width="80" />
                        </colgroup>		
                        <thead class="tbground_1 tborder_6 ac">
                            <tr>
                                <?php if($usergrade == "000001" || $usergrade == "000002") { ?><td></td><?php } ?>
                                <th colspan=<?php if(!empty($vmonths->lists)) { echo "2"; } else { echo "4"; } ?>><?php if(!empty($cutoff_from)){
                                    $scutOffTo = $vmonths->tel_year."-".$vmonths->tel_month."-".$cutoff_to;
                                    $scutOffFrom = $vmonths->tel_year."-".$vmonths->tel_month."-".$cutoff_from;
                                    
                                    if ($cutoff_from == $cutoff_to || $cutoff_from > $cutoff_to){ 
                                        $scutOffTo = date('Y-m-d', strtotime($scutOffTo . "+1 month")); 
                                    }   
                                    echo date("M d, 'y", strtotime($scutOffFrom)) ." to ".date("M d, 'y", strtotime($scutOffTo));
                                } else {
                                    echo substr($months[$vmonths->tel_month], 0,3)." ". $vmonths->tel_year;
                                } ?><?php if(!empty($vmonths->lists)) { ?>&nbsp;&nbsp;&nbsp;&nbsp;Category<?php } ?></th>
                                <?php if(!empty($vmonths->lists)) { ?><th>Planned Amount</th><?php } ?>
                                <?php if(!empty($vmonths->lists)) { ?><th>Paid Amount</th><?php } ?>
                            </tr>
                        </thead>
                        
                        <?php if(!empty($vmonths->lists)) { ?>
                        <tfoot class="tborder_2 tfonts_1 tfonts_3 tfonts_4">                        
                            <tr>
                                <?php if($usergrade == "000001" || $usergrade == "000002") { ?><td></td><?php } ?>                                
                                <th class="ac">Total</th>						
                                <th></th>					
                                <th class="ac" <?php if(date("m-Y", strtotime($vmonths->lists[0]->tep_expected_date)) == date("m-Y")) { ?> name="recent_plannedtotal" <?php } ?>>
                                    <?php if(!empty($vmonths->paid_total)){ echo number_format($vmonths->paid_total, 2, '.', ','); }	?></th>		
                                <th class="ac" <?php if(date("m-Y", strtotime($vmonths->lists[0]->tep_expected_date)) == date("m-Y")) { ?> name="recent_paymenttotal" <?php } ?>>
                                    <?php if(!empty($vmonths->payment_total)){ echo number_format($vmonths->payment_total, 2, '.', ','); }	?></th>						
                            </tr>
                        </tfoot>
                        <?php } ?>
                        
                        <tbody class="tborder_2" id="recent_table_exp"> 
                            <?php if(!empty($vmonths->lists)) { ?>
                                <?php foreach($vmonths->lists as $vlists) { ?>
                                <tr>
                                    <?php if($usergrade == "000001" || $usergrade == "000002") { ?>
                                        <td class="ac"><input type="checkbox" name="check_recent"class="not_done"/></td>
                                    <?php } ?>
                                    <td class="ac"><input type="hidden" value="<?php echo $vlists->tep_idx; ?>" name="tel_idx"><span name="recent_day">
                                        <?php if(!empty($cutoff_from)){
                                            echo date("m-d-Y", strtotime($vlists->tep_expected_date)); 
                                        } else {
                                            echo date("d", strtotime($vlists->tep_expected_date));
                                        } ?>
                                        </span></td>
                                    <td><a href="javascript:void(0);" name="view_info" title="View Expense Information"><span name="categ_name"><?php echo $vlists->tec_name; ?></span>
                                        <input type="hidden" name="categ_id" value="<?php echo $vlists->tep_tec_idx; ?>">
                                        <input type="hidden" name="categ_desc" value="<?php echo $vlists->tep_desc; ?>"></td>
                                    <td class="ac"><span name="price_number"><?php echo number_format($vlists->tep_planned_amount, 2, '.', ','); ?></span></td>
                                    <td class="ac"><span name="amnt_payment"><?php echo number_format($vlists->tep_payment_amount, 2, '.', ','); ?></span></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?> 
                                <tr><td class="ac" colspan=<?php if($usergrade == "000001" || $usergrade == "000002") { echo "5"; } else { echo "4"; } ?>>No records found.</td></tr>                                
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                    
                    <!--Pagination-->
                    <?php echo $pager;?>
                    <?php if($usergrade == "000001" || $usergrade == "000002") { ?>
                        <div class="table_wrap_3 mt10 mb10">
                            <a href="javascript:void(0);" class="btn_small btn_type_2s" name="add_recent"><span>Add Recent Expense</span></a>
                            <a href="javascript:void(0);" class="btn_small btn_type_2s" name="edit_recent"><span>Edit Recent Expense</span></a>  
                            <a href="javascript:void(0);" class="btn_small btn_type_2s" name="add_to_recent"><span>Add to Expected Expenses</span></a>  
                        </div>
                    <?php } ?>
                </div>
                <?php } else {?>
                     <table class="tstyle_2">
                        <thead class="tbground_1 tborder_6 ac">
                            <tr><th>No recent expenses found.</th></tr>
                        </thead>
                    <table>   
                </div>  
                <br/>                
                <br/>                
                <?php } ?>
                
                <?php if(!empty($alists)){ ?><div class="table_fr_50"> <?php } ?>
                    <div style="margin:0px 0px 10px 0px;">
                        <h2 class="title np fl mb10"><strong class="">Expected Expenses</strong></h2>&nbsp;                    
                        <a href="javascript:void(0);" class="btn_small btn_type_2s" id="add_action"><span>Add New Expenses</span></a>
                        
                        <!--Show Rows-->
                        <div class="fr">                            
                                <?php 
                                if(!empty($aexpected)){ ?>
                                <form>
                                    <label>Number of Rows</label>                        
                                    <select class="nm np" id="show_rows_expected">
                                        <option value="10" <?php if($ilimit_expected == "10"){ echo "SELECTED"; } ?>>10</option>
                                        <option value="15" <?php if($ilimit_expected == "15"){ echo "SELECTED"; } ?>>15</option>
                                        <option value="20" <?php if($ilimit_expected == "20"){ echo "SELECTED"; } ?>>20</option>
                                        <option value="30" <?php if($ilimit_expected == "30"){ echo "SELECTED"; } ?>>30</option>
                                        <option value="40" <?php if($ilimit_expected == "40"){ echo "SELECTED"; } ?>>40</option>
                                        <option value="50" <?php if($ilimit_expected == "50"){ echo "SELECTED"; } ?>>50</option>
                                        <option value="60" <?php if($ilimit_expected == "60"){ echo "SELECTED"; } ?>>60</option>
                                    </select>
                                </form>
                                <?php } ?>                     
                            
                        </div>        
                    </div>
                        
                    <!-- table_3 -->
                    <table class="tstyle_2" id="table_next_month">
                        <colgroup>
                            <?php if($usergrade == "000001" || $usergrade == "000002") { ?><col width="65" /><?php }?>
                            <col />
                            <col width="80" />
                        </colgroup>		
                        <thead class="tbground_1 tborder_6 ac">
                            <tr>
                                <?php if($usergrade == "000001" || $usergrade == "000002") { ?><th><input type="checkbox" name="check_all" title="Check All">Next Month</th><?php } ?>
                                <th>Category</th>
                                <th>Planned Amount</th>
                                <th>Expected Date</th>
                            </tr>
                        </thead>
                        <tfoot class="tborder_2 tfonts_1 tfonts_3 tfonts_4" id="expected_total">
                            <tr>
                                <th>Total</th>	
                                <input type="hidden" value="" name="total_copy">
                                <?php if($usergrade == "000001" || $usergrade == "000002") { ?><td></td><?php } ?>
                                <th class="ac" id="total_expected">0</th>						
                            </tr>
                        </tfoot>
                        <tbody class="tborder_2" id="expected_table">    
                            <tr id="no_records" style="display:none"><td class='ac' colspan = 4>No record for expected expenses.</td></tr>
                            <?php
                                if(!empty($aexpected)){
                                    foreach($aexpected as $key=>$expected)
                                    {
                                        ?><tr>
                                            <?php if($usergrade == "000001" || $usergrade == "000002") { ?>
                                                <td class='ac'><input type='checkbox' name='check_expected' value='<?php echo $expected->tep_idx; ?>'/></td>
                                            <?php } ?>
                                            <td><a href="javascript:void(0);" name="view_info" title="View Expense Information"><span name='exp_name'><?php echo $expected->tec_name; ?></span></a>
                                            <input type='hidden' name='exp_categ_id' value='<?php echo $expected->tep_tec_idx; ?>'></td>
                                            <td class='ac'><span name='exp_price'><?php echo number_format($expected->tep_planned_amount, 2, '.', ','); ?></span>
                                                <input type='hidden' name='exp_expesedesc' value='<?php echo $expected->tep_desc; ?>'>
                                            </td>
                                            <td class='ac'><span name='exp_expesedate'><?php echo str_replace("-", "/", (String)$expected->tep_expected_date); ?></span></td>
                                        </tr><?php
                                    }
                                }
                            ?>
                        </tbody>
                        
                    </table>	
                    <!--Pagination-->
                    <?php echo $pager_expected;?>
                    
                    
                    <?php if($usergrade == "000001" || $usergrade == "000002") { ?>
                        <div class="table_wrap_3 mt10 mb10">
                            <a href="javascript:void(0);" class="btn_small btn_type_2s" id="delete_action"><span>Delete Expenses</span></a>
                            <a href="javascript:void(0);" class="btn_small btn_type_2s" id="edit_action"><span>Edit Expenses</span></a>                        
                            <a href="javascript:void(0);" class="btn_small btn_type_2s fr" id="save_expform"><span>Save</span></a>
                        </div>			
                    <?php } ?>
                    
                    <div class="popup_wrap" style="display:none;top:15%;left:25%;margin:20px 0;" id="delete_form">
						<div class="popup">
							<a href="javascript:void(0);" class="btn_close fr close_form"><span>X</span></a>
							<h2><strong class="title fn mt10">Delete Expected Expense</strong></h2>
                            <form action="">
								<table class="table_form al" border="0">
									<colgroup>
										<col width="300px;" />
										<col />
									</colgroup>
									<tr>
										<th></br><span id="num_delete"></span> expected expense(s) are selected. </br></br>Are you sure you want to delete?</th>
                                    </tr>
                                </table>
                                <div class="btn_div">
									<a href="javascript:void(0);" id="delete_expected" class="btn btn_type_3 btn_space"><span>Yes</span></a>
									<a href="javascript:void(0);" class="btn btn_type_3 btn_space cancel_form"><span>No</span></a>
								</div>
                            </form>
                        </div>                    
                    </div>
                    
                    
                    <!-- ADD_NEW_EXPECTED_EXPENSE_POPUP-->
					<div class="popup_wrap" style="display:none;" id="new_form_expense">
						<div class="popup">
							<a href="javascript:void(0);" class="btn_close fr close_form"><span>X</span></a>
							<h2><strong class="title fn mt10" id="title_action">Edit Expected Expense</strong></h2>
							<form action="" name="action_form" id="action_form">
                            <div id="form_err" style="max-width:90%;"></div>
								<table class="table_form al" border="0">
									<colgroup>
										<col width="150px" />
										<col />
									</colgroup>
									<tr>
										<th><label for="select3">Category <span class="required">*</span></label></th>
										<td><input type="hidden" id="recent_actions" value="">
											<select class="select_type_3 nm np" id="exp_categ" name="exp_categ">    
                                            
                                            <!--CATEGORIES-->
                                            <?php foreach($acategory as $value_categ){ ?>
                                                <option value="<?php echo $value_categ->tec_idx; ?>"><?php echo $value_categ->tec_name;?></option>
                                            <?php } ?>  	
											
											</select>
										</td>
									</tr>
									<tr>
										<th><label for="select3">Planned Amount <span class="required">*</span></label></th>
										<td>
											<div class="fl nm np" style="display:inline-block">
												<input type="text" class="input_type_3 fl" id="exp_amount" name="exp_amount">
											</div>
										</td>
									</tr>
                                    <tr id="payment_amount_tr">
										<th><label for="select3">Payment Amount <span class="required">*</span></label></th>
										<td>
											<div class="fl nm np" style="display:inline-block">
												<input type="text" class="input_type_3 fl" id="exp_payment" name="exp_payment">
											</div>
										</td>
									</tr>
                                    <tr>
										<th><label for="select3">Expected Payment Date <span class="required">*</span></label></th>
										<td>
											<div class="fl nm np" style="display:inline-block">
												<input type="text" class="input_type_3 fl" id="exp_date" name="exp_date">
											</div>
										</td>
									</tr>
									<tr>
										<th><label for="textarea2">Description <span class="required">*</span></label></th>
										<td>
											<textarea class="textarea_2" id="exp_desc" name="exp_desc"></textarea>
										</td>
									</tr>
								</table>
								<div class="btn_div">
									<a href="javascript:void(0);" class="btn btn_type_3 btn_space" id="save_form"><span>Save</span></a>
									<a href="javascript:void(0);" class="btn btn_type_3 btn_space cancel_form"><span>Cancel</span></a>
								</div>
							</form>
						</div>
					</div>
					<!-- //ADD_NEW_EXPECTED_EXPENSE_POPUP-->
                    <br/>
                    <a href="javascript:void(0)" class="btn_export fr" title="Export to Excel" id="export_excel"><span>Export</span></a>
                </div>
                <!-- //table_fr_50 -->
            </div>
            <!-- //table_wrap_3 -->	
        </div>
        <!-- END inner content -->
    </div>
    <!-- END main -->
</div>
