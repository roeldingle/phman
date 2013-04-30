 <?php
    $pyear = $this->uri->segment(3) != "" ? $this->uri->segment(3) : date('Y');
    $pmonth = $this->uri->segment(3) != "" ? $this->uri->segment(4) : date('m');
?>
<div style="width:900px;display:inline-block;">
<?php
   $this->common->get_message('my-save-message');
?>
</div><br />
<h2 class="title nm np fl"><strong class="">Real Expenses </strong>
<span class="subtext">Spread Sheet View</span></h2>
<?php if($this->session->userdata('usergradeid') == "000001" || $this->session->userdata('usergradeid') == "000002"){ ?>
    <a href="<?php echo base_url() . "expense/add_new_expense"; ?>" class="btn btn_type_1 fl ml5"><span>Add New Expense</span></a>	
<?php } ?>
<!-- BEGIN inner content -->
<div class="content np">
    <div class="table_wrap_3 mt10">
        <input type="hidden" name="year" value="<?php echo $year; ?>" />
        <input type="hidden" name="month" value="<?php echo $month; ?>" />
        <input type="hidden" name="limit" value="<?php echo $alimit['limit']; ?>" />
        <input type="hidden" name="offset" value="<?php echo $alimit['offset']; ?>" />
        <input type="hidden" name="page_action" value="<?php echo $this->uri->segment(2); ?>" />
        <ul class="sort_links fl">
            <li class="first" id="all">
                <a href="<?php echo base_url() . "expense/real_expense_spreadsheet"; ?>">All 
                    <span class="number">(<?php echo $all; ?>)</span>
                </a>
            </li>
            <?php
                $num = count($department_links['dept']);
                $dept = $department_links['dept'];
                $dept_id = $department_links['dept_id'];
                $count = $department_links['count'];
                
                for($i=0;$i<$num;$i++){
                    if($i==($num-1)){
                        echo "<li class='last'";
                            if(!isset($_GET['dept'])){
                                echo "style='font-weight:bold'";
                            }
                        echo ">";
                    }else{
                        echo "<li id='".$dept_id[$i]."' class='dept_links'";
                            if(isset($_GET['dept'])){
                                if($_GET['dept']==$dept_id[$i]){
                                    echo "style='font-weight:bold'";
                                }
                            }
                        echo ">";
                    }
                        echo "<a href=" . base_url() . "expense/real_expense_spreadsheet?dept=" . $dept_id[$i] . ">" . $dept[$i];
                            echo "<span class='number'> (". $count[$i] .")</span>";
                        echo "</a>";
                    echo "</li>";
                }
            ?>
        </ul>					
        <div class="search_01 fr">
            <form action="<?php echo base_url() . "expense/real_expense_spreadsheet/{$pyear}/{$pmonth}"; ?>" method="post">
               <input type="text" value="" class="input_type_4" name="real_expense_search_string" />
               <input type="submit" value="Search" class="btn"/>
            </form>
            <div class="ar mt5">
               <label class="mr5">Show Entries by </label>
               <?php
                   $this->app->show_rows(5,array(5,10,15,20));
                ?>							
            </div>
        </div>						
    </div>
    <div class="table_wrap_3">
        <?php if($this->session->userdata('usergradeid') == "000001" || $this->session->userdata('usergradeid') == "000002"){ ?>
       
        <?php } ?>
        <div class="table_fr_50 ar">
            <a href="<?php echo base_url() . "expense/index/{$pyear}/{$pmonth}"; ?>" class="link_1">&gt;&gt;Switch to Detailed View</a>	
        </div>						
    </div>
    <div>
        <?php  
            $searchmsg = $searchstr != "" ? "Search results for '{$searchstr}'" : "";
            echo "<strong>{$searchmsg}</strong>";
        ?>
    </div>   
    <!-- BEGIN table_wrap_2 -->
    <div class="table_wrap_2">
        <!-- table_2 -->
        <table class="tstyle_1 ac">
            <colgroup span="3" />
            <colgroup span="2" class="tborder_1" />
            <colgroup span="2" class="tborder_1" />
            <colgroup span="2" />
            <thead class="tborder_1 tfonts_4">
                <tr>
                    <th rowspan="2">Department</th>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Transaction</th>
                    <th colspan="2">Union Bank</th>
                    <th colspan="2">Cash on Hand</th>
                    <th rowspan="2">Evidence <br/> (in case of payment)</th>
                    <th rowspan="2">Description (indispensable)</th>
                </tr>
                <tr>
                    <th>Deposit</th>
                    <th>Transfer</th>
                    <th>Received</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>  
                <?php 
                    if(!empty($list)){
                        foreach($list as $row){
                            echo "<tr>";
                                echo "<td><span class='tfonts_7'>".$row->td_dept_name."</span></td>";
                                echo "<td>".$row->new_tel_date."</td>";
                                echo "<td>".$row->tel_type."</td>";
                                if($row->tel_tes_idx == '00000000001'){
                                    if($row->tel_request_amt != "0.00"){
                                        echo "<td>".$row->tel_request_amt."</td>";
                                    }else{
                                        echo "<td>0.00</td>";
                                    }
                                }else{
                                    if($row->tel_deposit_amt != "0.00"){
                                        echo "<td>".$row->tel_deposit_amt."</td>";
                                    }else{
                                        echo "<td>0.00</td>";
                                    }
                                }
                                if($row->tel_tes_idx == '00000000003'){
                                    if($row->tel_payment != "0.00"){
                                        echo "<td>".$row->tel_payment."</td>";
                                    }else{
                                        echo "<td>0.00</td>";
                                    }
                                }else{
                                    if($row->tel_transfer_amt != "0.00"){
                                        echo "<td>".$row->tel_transfer_amt."</td>";
                                    }else{
                                        echo "<td>0.00</td>";
                                    }
                                }
                                if($row->tel_receive_amt != "0.00"){
                                    echo "<td>".$row->tel_receive_amt."</td>";
                                }else{
                                    echo "<td>0.00</td>";
                                }
                                if($row->tel_payment != "0.00"){
                                    echo "<td>".$row->tel_payment."</td>";
                                }else{
                                    echo "<td>0.00</td>";
                                }
                                echo "<td class='ac' style='text-align:left'>";
                                    foreach($attachment as $a){
                                        if($a->tea_tel_idx == $row->tel_idx){
                                            if($a->tea_filename != "" && $a->tea_attachment_type == "receipt"){
                                                $afile_type = explode(".",$a->tea_filename);
                                                if($afile_type[1] == "pdf"){
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-pdf.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }elseif($afile_type[1] == "doc"){
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-word.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }elseif($afile_type[1] == "xls"){
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-csv.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }elseif($afile_type[1] == "ppt"){
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-pesentation.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }elseif($afile_type[1] == "txt"){
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-text.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }elseif($afile_type[1] == "jpg" || $afile_type[1] == "jpeg" || $afile_type[1] == "png" || $afile_type[1] == "gif"){
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-image.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }else{
                                                    echo "<a href='".$getfile_path."expense/uploads/request_billing_forms/".$a->tea_newname."?download=true'><img src='".$assets_path."expense/images/core-simple.png' alt='req'/>".$a->tea_filename."</a><br /><br />";
                                                }
                                            }
                                        }else{
                                            echo "";
                                        }
                                    }
                                echo "</td>";
                                echo "<td class='al'>".$row->tel_particulars."</td>";
                            echo "</tr>";
                        }
                    }else{
                        echo "<tr>";
                            echo "<td colspan=9 style='font-weight:bold;text-align:center'>No records found.</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
            <?php
                if(!empty($list)){
                ?>
                    <tfoot class="tborder_1 tfonts_4">
                            <tr class="tbground_4">
                            <th colspan="2" class="al tfonts_1 tfonts_2 tfonts_4"><?php echo $year . " " . $month ; ?> TOTAL</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="3" class="al tfonts_2">TOTAL</th>
                            <td><?php echo $ubdeposit; ?></td>
                            <td><?php echo $ubtransfer; ?></td>
                            <td><?php echo $cashreceive; ?></td>
                            <td><?php echo $cashpayment; ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="3" class="al tfonts_2">BALANCE</th>
                            <th class="ar tfonts_2">UB(B):</th>
                            <?php if($unionbankbal > 0){ ?>
                                <td><?php echo $unionbankbal; ?></td>
                            <?php }else{ ?>
                                <td>0.00</td>
                            <?php } ?>
                            <th class="ar tfonts_2">Cash on Hand</th>
                            <?php if($cashonhand > 0){ ?>
                                <td><?php  echo $cashonhand;?></td>
                            <?php }else{ ?>
                                <td>0.00</td>
                            <?php } ?>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                <?php
                }
            ?>
        </table>
        <!-- table_2 -->
    </div>
    <!-- END table_wrap_2 -->
    <div class="pagination center">
        <?php if(!empty($list)){echo $pager;}?>
    </div>
    <!-- BEGIN sorting -->
    <div class="table_wrap_3 mt10 mb10">
        <div class="table_fl_30">
            <select class="select_type_1 nm np" id="sortby" onchange="javascript: window.location.href = '<?php echo base_url() . "expense/real_expense_spreadsheet?sort="; ?>' + $('#sortby').val();">
                <option value="0">Sort By</option>
                <option value="1" <?php if(isset($_GET['sort'])){if($_GET['sort'] == "1"){echo "selected"; }} ?>>Department</option>
                <option value="4" <?php if(isset($_GET['sort'])){if($_GET['sort'] == "4"){echo "selected"; }} ?>>Date</option>
            </select>
            <span class="message_type2 np" id="msg_sortby"></span>
        </div>			
    </div>
    <div class="table_wrap_3">
         <div class="table_fl_50 tfonts_4">
            <div style="margin-bottom:10px;font-weight:bold">Specific Period:</div>
            <div class="holder">
               <label for="calendar_from" class="label_1">From:</label>
               <div class="nm np" style="display:inline-block">
                    <input type="text" class="input_type_3 fl" name="datefrom" id = "datefrom" />
               </div>
               <span class="message_type2 np" id="msg_datefrom"></span>
            </div>
            <div class="holder">
               <label for="calendar_to" class="label_1">To:</label>
               <div class="nm np" style="display:inline-block">
                    <input type="text" class="input_type_3 fl" name="dateto" id = "dateto" />
               </div>
               <span class="message_type2 np" id="msg_dateto"></span>
            </div>
         </div>
         <div class="table_fr_30">
            <a href="javascript:void(0)" id="detailed_export" class="btn_export fr" title="Export to Excel" month="<?php echo $this->uri->segment(4); ?>" year="<?php echo $this->uri->segment(3); ?>"><span>Export</span></a>
         </div>
      </div>
      <div class="table_wrap_3">
         <a href="javascript: void(0)" class="btn_small btn_type_2s fl" id="specific_period"><span>Apply</span></a>
      </div>
    <!-- BEGIN sorting -->		
</div>