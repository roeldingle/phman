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
<span class="subtext">Detailed View</span></h2>
<?php if($this->session->userdata('usergradeid') == "000001" || $this->session->userdata('usergradeid') == "000002"){ ?>
   <a href="<?php echo base_url() . "expense/add_new_expense"; ?>" class="btn btn_type_1 fl ml5 add-expense-button"><span>Add New Expense</span></a>	
<?php } ?>

<!-- BEGIN inner content -->
<div class="content np">
  <div class="table_wrap_3 mt10">
     <input type="hidden" name="year" value="<?php echo $year; ?>" />
     <input type="hidden" name="month" value="<?php echo $month; ?>" />
     <ul class="sort_links fl">
        <li class="first" id="all" 
            <?php
            if(!isset($_GET['dept'])){
                echo "style='font-weight:bold'";
            }
            ?>
        >
            <a href="<?php echo base_url() . "expense/"; ?>">All 
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
                        if(isset($_GET['dept'])){
                            if($_GET['dept']==$dept_id[$i]){
                                echo "style='font-weight:bold'";
                            }
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
                    echo "<a href=" . base_url() . "expense?dept=" . $dept_id[$i] . ">" . $dept[$i];
                        echo "<span class='number'> (". $count[$i] .")</span>";
                    echo "</a>";
                echo "</li>";
            }
        ?>
     </ul>					
     <div class="search_01 fr">
        <form id="seach_form" action="<?php echo base_url() . "expense/index/{$pyear}/{$pmonth}"; ?>" method="post">
           <input type="text" value="" class="input_type_4" name="real_expense_search_string" style="background-color:#FFFFFF"/>
           <input type="submit" value="Search" class="btn" id="src_btn"/>
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
     <div class="table_fl_50">
        <input type="button" value="Delete" class="btn" id="del_btn"/>
        <input type="button" value="Edit" class="btn" id="edit_btn"/>
        <span class="message_type2 np" id="msg_select1"></span>
     </div>
    <?php }?>
     <div class="table_fr_50 ar">
        <a href="<?php echo base_url() . "expense/real_expense_spreadsheet/{$pyear}/{$pmonth}"; ?>" class="link_1">&gt;&gt;Switch to Spread Sheet View</a>	
     </div>		
  </div>
    <div>
        <?php  
            $searchmsg = $searchstr != "" ? "Search results for '{$searchstr}'" : "";
            echo "<strong>{$searchmsg}</strong>";
        ?>
    </div>     
  <!-- table_wrap_1 -->
  <div class="table_wrap_1">
     <!-- table_1 -->
     <div><table class="tstyle_1">
        <thead class="tborder_3 tbground_1 ac tfonts_1 tfonts_2">
           <tr>
              <td><input type='checkbox' name='header_real_expenses_records' value='' /></td>
              <th>Dept</th>
              <th>Date</th>
              <th>Type</th>
              <th>Status</th>
              <th>Req. Amt</th>
              <th>Req. Form / Bill</th>
              <th>Receipts</th>
              <th>ReceivedAmt</th>
              <th>Payment</th>
              <th>CshonHnd</th>
              <th>Qty</th>
              <th>Cstpritm</th>
              <th>supplier</th>
              <th>Category</th>
              <th>Particulars</th>
           </tr>
        </thead>
        <tbody  class="tfonts_3">  
              <?php
                if(empty($list)){
                    echo "<tr>";
                        echo "<td colspan=16 style='font-weight:bold;text-align:center'>No records found.</td>";
                    echo "</tr>";
                }else{
                    foreach($list as $row){
                        echo "<tr id='".$row->tel_idx."'>";
                            echo "<td><input type='checkbox' name='real_expenses_records' value='".$row->tel_idx."' /></td>";
                            echo "<td><span class='tfonts_7'>".$row->td_dept_name."</span></td>";
                            echo "<td>".$row->new_tel_date."</td>";
                            echo "<td>".$row->tel_type."</td>";
                            echo "<td>".$row->tes_status."</td>";
                            echo "<td class='ar'><span class='tfonts_7'>".$row->tel_request_amt."</span></td>";
                            echo "<td class='ac' style='text-align:left'>";
                                foreach($attachment as $a){
                                    if($a->tea_tel_idx == $row->tel_idx){
                                        if($a->tea_filename != "" && $a->tea_attachment_type == "request"){
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
                            echo "<td class='ar'>".$row->tel_receive_amt."</td>";
                            if($row->tel_tes_idx == '00000000005'){
                                echo "<td class='ar'>".$row->tel_deposit_amt."</td>";
                            }elseif($row->tel_tes_idx == '00000000006'){
                                echo "<td class='ar'>".$row->tel_transfer_amt."</td>";
                            }elseif($row->tel_tes_idx == '00000000007'){
                                echo "<td class='ar'>".$row->tel_returned_amt."</td>";
                            }else{
                                echo "<td class='ar'>".$row->tel_payment."</td>";
                            }
                            echo "<td class='ar'>".$row->cashonhand."</td>";
                            echo "<td class='ac'>".$row->tel_quantity."</td>";
                            echo "<td class='ar' class='costperitem'>"; 
                                echo "<table border=0 width=100%>";
                                foreach($items as $i){
                                    if($i->teil_tel_idx == $row->tel_idx){
                                            echo "<tr>";
                                                echo "<td style='border:none;width:60%'>".$i->teil_name."</td>";
                                                echo "<td style='border:none;width:30%'>".$i->teil_price."</td>";
                                            echo "</tr>";
                                    }
                                }
                                echo "</table>";
                            echo "</td>";
                            echo "<td class='ar'>".$row->tel_supplier_name."</td>";
                            echo "<td>".$row->tec_name."</td>";
                            echo "<td>".$row->tel_particulars."</td>";
                        echo "</tr>";
                    }
                }
                
              ?>
        </tbody>
        <?php
            if(!empty($list)){
            ?>
            <tfoot class="tborder_1 tfonts_4">
               <tr class="tbground_4">
                  <th scope="row" colspan="5" class="tfonts_1 tfonts_2 tfonts_4"><?php echo $year . " " . $month ; ?> Total </th>
                  <td class="ar"><?php echo $requested_amt; ?></td>
                  <td colspan="2"></td>
                  <td class="ar separate"><?php echo $received_amt; ?></td>
                  <td class="ar"><?php echo $payment; ?></td>
                  <?php if($cashonhand >= 0){ ?>
                    <td class="ar"><?php  echo $cashonhand;?></td>
                  <?php }else{ ?>
                    <td class="ar" style="color:#FF0000"><?php  echo $cashonhand; ?></td>
                  <?php } ?>
                  <td class="ac"><?php echo $quantity; ?></td>
                  <td class="ar"></td>
                  <td colspan="3"></td>
               </tr>
               <tr>
                  <th scope="row" colspan="5" class="tfonts_1 tfonts_2 tfonts_4">Bank Balance </th>
                  <td class="ar tfonts_1 tfonts_2 tfonts_4" colspan="2">UB(B) :</td>
                  <?php if($unionbankbal > 0){ ?>
                    <td class="ar" ><?php echo $unionbankbal; ?></td>
                  <?php }else{ ?>
                    <td class="ar" >0.00</td>
                  <?php } ?>
                  <td colspan="8"></td>
               </tr>
            </tfoot>
            <?php
            }
        ?>
     </table></div>
     <!-- //table_1 -->
  </div>
  <!-- //table_wrap_1 -->
  <?php if(!empty($list)){echo $pager;}?>
  <!-- BEGIN sorting -->
  <div class="table_wrap_3 mt10 mb10">
     <div class="table_fl_30">
        <select class="select_type_1 nm np" id="sortby" onchange="javascript: window.location.href = '<?php echo base_url() . "expense?sort="; ?>' + $('#sortby').val();">
           <option value="0">Sort By</option>
           <option value="1" <?php if(isset($_GET['sort'])){if($_GET['sort'] == "1"){echo "selected"; }} ?>>Department</option>
           <option value="2" <?php if(isset($_GET['sort'])){if($_GET['sort'] == "2"){echo "selected"; }} ?>>Status</option>
           <option value="3" <?php if(isset($_GET['sort'])){if($_GET['sort'] == "3"){echo "selected"; }} ?>>Type</option>
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
        <a href="javascript:void(0)" id="detailed_export" class="btn_export fr" title="Export to Excel" month="<?php echo $this->uri->segment(4); ?>" year="<?php echo $this->uri->segment(3); ?>" view="<?php echo $this->uri->segment(2) ?>"><span>Export</span></a>
     </div>
  </div>
  <div class="table_wrap_3">
     <a href="javascript: void(0)" class="btn_small btn_type_2s fl" id="specific_period"><span>Apply</span></a>
  </div>
  <div class="table_wrap_3">
  <!-- END sorting -->					
</div>

