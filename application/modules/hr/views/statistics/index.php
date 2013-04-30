<style>
    .ui-datepicker-calendar { display: none; }
</style>

<h2 class="title np fl mb10"><strong class="">View Statistics</strong></h2>
<div class="message-container"></div>
   <div class="content np">
      <div class="search_02" name="search_emp">
         <form>
            <div class="holder">
               <label>Period :</label>
               <input type="text" value="<?php echo $from; ?>" class="input_type_3" id="calendar_from" />
            </div>
            <div class="holder">
               <span class="mr10">&ndash;</span>
               <input type="text" value="<?php echo $to; ?>" class="input_type_3" / id="calendar_to">
            </div>
            <a href="javascript:void(0)" class="btn_small btn_type_2s" id="search_btn"><span>View</span></a>&nbsp;&nbsp;
            <a href="javascript:void(0)" class="btn_small btn_type_2s" id="reset_btn"><span>Reset</span></a>
         </form>
      </div>
      
      <ul class="tabmenu" name="menu_emp">      
         <li><a class="current" href="javascript:void(0)" name="hired_emp">Hired</a></li>
         <li><a href="javascript:void(0)" name="reg_emp">Regular</a></li>
         <li><a href="javascript:void(0)" name="prob_emp">Probationary</a></li>
         <li><a href="javascript:void(0)" name="contract_emp">Contractual</a></li>
         <li><a href="javascript:void(0)" name="retired_emp">Resigned</a></li>
         <li><a href="javascript:void(0)" name="total_emp">All Employees</a></li>
         <li><a href="javascript:void(0)" name="vacation_leave">Vacation Leave</a></li>
         <li><a href="javascript:void(0)" name="sick_leave">Sick Leave</a></li>
         <li><a href="javascript:void(0)" name="tardiness">Tardiness</a></li>
         <li><a href="javascript:void(0)" name="lwop">LWOP</a></li>
         <li><a href="javascript:void(0)" name="awol">AWOL</a></li>
      </ul>
        
      
    
    <?php if(empty($menu) || $menu == 'hired_emp') { ?>      
      <!--hired employees-->
    <div id="hired_emp_container">
      
        <div class="table_wrap_3">
         <div class="table_fl_50">
            <table class="tstyle_1 tborder_2 tfonts_4 ac">
               <colgroup>
                  <col width="85" />
                  <col width="85" />
                  <col width="85" />
                  <col />
               </colgroup>
               
               <thead>
                  <tr class="tbground_3">
                     <td colspan="4" class="al">Hired Employees</td>
                  </tr>
                  <tr class="tbground_2 tfonts_2 tfonts_1">
                     <th>Month</th>
                     <th>Year</th>
                     <th>TOTAL</th>
                  </tr>		
               </thead>
               <tfoot>
                  <tr class="tbground_2">
                     <td></td>
                     <th class="tfonts_2 tfonts_1">Total</th>
                     <td><?php if(!empty($ahiredEmp)){ echo $total_ids_hired; } else { echo "0";} ?></td>
                  </tr>			
               </tfoot>
               <tbody>
                           
                <?php if(!empty($ahiredEmp)){
                    for($yr=(int)min($years_hired);$yr<=(int)max($years_hired);$yr++) { ?>                                            
                <?php for($mon=1;$mon<=12;$mon++) { ?>                                            
                    <tr class="tbground_3">                            
                        <td><?php echo $months[$mon]; ?></td>
                        <td><?php echo $yr;?></td> 
                        <td>
                        <?php $total_ids = "0";
                        foreach($ahiredEmp as $khired => $vhired) { ?>   
                            <?php if($vhired->month == $months[$mon] && $vhired->year == $yr) { 
                                $total_ids = $vhired->total_ids;
                            } ?>  
                        <?php } 
                        echo $total_ids; ?>
                        </td>                          
                    </tr>	 
                <?php } ?>
                <?php } 
                } else {?>
                    <tr class="tbground_3">                            
                        <td colspan=3>No results found.</td>
                    </tr>
                
                <?php } ?>
                
               </tbody>
            </table>
            <?php 
            
            if($to=="" || $from=="") {
                echo $pager_hired; 
            }
            ?>
         </div>
         
         <div id="pie_hired" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
         
      </div>
      
      <?php if(!empty($ahiredEmp)){ ?>
          <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_hired">View Graph</span></a> 
          <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_hired"><span>Export</span></a>
      <?php } ?>
      </div>
    <?php } ?>
      
    <?php if(isset($menu) && $menu == 'reg_emp') { ?>
    <!--regular employees-->
    <div id="reg_emp_container">
        <div class="table_wrap_3">
            <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_reg" style="color:#fff" id="reg_per_month">View statistics per month</span></a>
            <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-left: 20px;margin-bottom : 20px;"><span class="view_reg" id="reg_by_dept">View statistics by department</span></a> 
            
            <div id="reg_per_month_container" style="margin-top:50px;">
                
                <div class="table_fl_50">
                    <table class="tstyle_1 tborder_2 tfonts_4 ac">
                       <colgroup>
                          <col width="85" />
                          <col width="85" />
                          <col width="85" />
                          <col />
                       </colgroup>
                       
                       <thead>
                          <tr class="tbground_3">
                             <td colspan="4" class="al">Regular Employees per Month</td>
                          </tr>
                          <tr class="tbground_2 tfonts_2 tfonts_1">
                             <th>Month</th>
                             <th>Year</th>
                             <th>TOTAL</th>
                          </tr>		
                       </thead>
                       <tfoot>
                          <tr class="tbground_2">
                             <td></td>
                             <th class="tfonts_2 tfonts_1">Total</th>
                             <td><?php if(!empty($aRegEmp)){ echo $total_ids_reg; } else { echo "0";} ?></td>
                          </tr>			
                       </tfoot>
                       <tbody>        
                        <?php if(!empty($aRegEmp)){                        
                            for($yr=(int)min($years_reg);$yr<=(int)max($years_reg);$yr++) { ?>                                            
                        <?php for($mon=1;$mon<=12;$mon++) { ?>                                            
                            <tr class="tbground_3">                            
                                <td><?php echo $months[$mon]; ?></td>
                                <td><?php echo $yr;?></td> 
                                <td>
                                <?php $total_ids = "0";
                                foreach($aRegEmp as $kreg => $vreg) { ?>   
                                    <?php if($vreg->month == $months[$mon] && $vreg->year == $yr) { 
                                        $total_ids = $vreg->total_ids;
                                    } ?>  
                                <?php } 
                                echo $total_ids; ?>
                                </td>                          
                            </tr>	 
                        <?php } ?>
                        <?php } 
                        } else {?>
                            <tr class="tbground_3">                            
                                <td colspan=3>No results found.</td>
                            </tr>
                        
                        <?php } ?>
                        
                       </tbody>
                    </table> 
                    <?php 
                    if($to=="" || $from=="") {
                        echo $pager_reg_per_month; 
                    } ?>
                    
                    <br/>
                    
                    <?php if(!empty($aRegEmp)){ ?>
                    <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_reg_per_month">View Graph</span></a> 
                    <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_reg_per_month"><span>Export</span></a>
                    <?php }?>
                    
                </div>
                
                <div id="pie_reg_per_month" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
            </div>
            
            <div id="reg_by_dept_container" style="display:none;margin-top:50px;">
                
                <div class="table_fl_50">
                    <table class="tstyle_1 tborder_2 tfonts_4 ac" id="prob_table">
                       <colgroup>
                          <col width="145" />
                          <col />
                       </colgroup>
                       <thead>
                          <tr class="tbground_3">
                             <td colspan="3" class="al">Regular Employees by Department</td>
                          </tr>
                          <tr class="tbground_2 tfonts_2 tfonts_1">
                             <th>Department</th>
                             <th>TOTAL</th>
                          </tr>		
                       </thead>
                       <tbody>
                            <?php foreach($regular_by_dept as $kreg_dept=>$vreg_dept) { ?>
                            <tr class="tbground_3">
                                <td><?php echo $vreg_dept->dept_name; ?></td>
                                <td><?php echo (empty($vreg_dept->total_ids)) ? "0" : $vreg_dept->total_ids; ?></td>
                            </tr>
                            <?php } ?>
                       </tbody>
                       <tfoot>
                          <tr class="tbground_2">
                             <th class="tfonts_2 tfonts_1">Total</th>
                             <td><?php echo $total_ids_reg_dept; ?></td>
                          </tr>
                       </tfoot>
                    </table>
                    
                    <br/>
                    <?php if($total_ids_reg_dept!=0) {?>
                    <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_reg_by_dept">View Graph</span></a> 
                    <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_reg_by_dept"><span>Export</span></a>
                    <?php } ?>
                </div>
                
                <div id="pie_reg_by_dept" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
            </div>        
      </div>
            
    </div>

    <?php } ?>
    
    <?php if(isset($menu) && $menu == 'retired_emp') { ?>
    <!--retired employees-->
    <div id="retired_emp_container">
      <!-- retired tab content-->
        <div class="table_wrap_3">
         <div class="table_fl_50">
            <table class="tstyle_1 tborder_2 tfonts_4 ac">
               <colgroup>
                  <col width="85" />
                  <col width="85" />
                  <col width="85" />
                  <col />
               </colgroup>
               <thead>
                  <tr class="tbground_3">
                     <td colspan="4" class="al">Retired Employees</td>
                  </tr>
                  <tr class="tbground_2 tfonts_2 tfonts_1">
                     <th>Month</th>
                     <th>Year</th>
                     <th>TOTAL</th>
                  </tr>		
               </thead>
               <tfoot>
                  <tr class="tbground_2">
                     <td></td>
                     <th class="tfonts_2 tfonts_1">Total</th>
                     <td><?php if(!empty($aresignedEmp)){ echo $total_ids_resigned; } else { echo "0";} ?></td>
                  </tr>			
               </tfoot>
               <tbody>
                
                           
                <?php if(!empty($aresignedEmp)){
                    for($yr=(int)min($years_resigned);$yr<=(int)max($years_resigned);$yr++) { ?>                                            
                <?php for($mon=1;$mon<=12;$mon++) { ?>                                            
                    <tr class="tbground_3">                            
                        <td><?php echo $months[$mon]; ?></td>
                        <td><?php echo $yr;?></td> 
                        <td>
                        <?php $total_ids = "0";
                        foreach($aresignedEmp as $kresigned => $vresigned) { ?>   
                            <?php if($vresigned->month == $months[$mon] && $vresigned->year == $yr) { 
                                $total_ids = $vresigned->total_ids;
                            } ?>  
                        <?php } 
                        echo $total_ids; ?>
                        </td>                         
                    </tr>	 
                <?php } ?>
                <?php } 
                } else {?>
                    <tr class="tbground_3">                            
                        <td colspan=3>No results found.</td>
                    </tr>
                
                <?php } ?>
               </tbody>
            </table>
            <?php 
            if($to=="" || $from=="") {
                echo $pager_resigned; 
            } ?>
         </div>
         
         <div id="pie_resigned" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
      
      </div>
      
      <?php if(!empty($aresignedEmp)){ ?>      
          <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_retired">View Graph</span></a> 
          <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_retired"><span>Export</span></a>
      <?php } ?>
      
      </div>
    <?php } ?>
      
    <?php if(isset($menu) && $menu == 'prob_emp') { ?>
    <!--probationary employees-->  
    <div id="prob_emp_container">
      <!-- probationary tab content-->
        <div class="table_wrap_3">
         <div class="table_fl_50">
            <table class="tstyle_1 tborder_2 tfonts_4 ac" id="prob_table">
               <colgroup>
                  <col width="145" />
                  <col />
               </colgroup>
               <thead>
                  <tr class="tbground_3">
                     <td colspan="3" class="al">Probationary Employees</td>
                  </tr>
                  <tr class="tbground_2 tfonts_2 tfonts_1">
                     <th>Department</th>
                     <th>TOTAL</th>
                  </tr>		
               </thead>
               <tbody>
                    <?php foreach($probationary as $kprob=>$vprob) { ?>
                    <tr class="tbground_3">
                        <td><?php echo $vprob->dept_name; ?></td>
                        <td><?php echo (empty($vprob->total_ids)) ? "0" : $vprob->total_ids; ?></td>
                    </tr>
                    <?php } ?>
               </tbody>
               <tfoot>
                  <tr class="tbground_2">
                     <th class="tfonts_2 tfonts_1">Total</th>
                     <td><?php echo $total_ids_prob; ?></td>
                  </tr>
               </tfoot>
            </table>
         </div>
         
         <div id="pie_probationary" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
      </div>
      
      <?php if($total_ids_prob!=0) {?>
      <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_probationary">View Graph</span></a> 
      <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_probationary"><span>Export</span></a>
      <?php } ?>
      <!-- end of probationary tab content-->
      </div>
    <?php } ?>
     
    <?php if(isset($menu) && $menu == 'contract_emp') { ?>
    <!--contractual-->
    <div id="contract_emp_container">
        <!-- contractual tab content-->
        <div class="table_wrap_3">
         <div class="table_fl_50">
            <table class="tstyle_1 tborder_2 tfonts_4 ac" id="prob_table">
               <colgroup>
                  <col width="145" />
                  <col />
               </colgroup>
               <thead>
                  <tr class="tbground_3">
                     <td colspan="3" class="al">Contractual Employees</td>
                  </tr>
                  <tr class="tbground_2 tfonts_2 tfonts_1">
                     <th>Department</th>
                     <th>TOTAL</th>
                  </tr>		
               </thead>
               <tbody>
                    <?php foreach($contractual as $kcont=>$vcont) { ?>
                    <tr class="tbground_3">
                        <td><?php echo $vcont->dept_name; ?></td>
                        <td><?php echo (empty($vcont->total_ids)) ? "0" : $vcont->total_ids; ?></td>
                    </tr>
                    <?php } ?>
               </tbody>
               <tfoot>
                  <tr class="tbground_2">
                     <th class="tfonts_2 tfonts_1">Total</th>
                     <td><?php echo $total_ids_contract; ?></td>
                  </tr>
               </tfoot>
            </table>
         </div>
         
         <div id="pie_contractual" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
        </div>

        <?php if($total_ids_contract!=0) {?>
        <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_contractual">View Graph</span></a> 
        <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_contractual"><span>Export</span></a>
        <?php } ?>
        
        <!-- end of probationary tab content-->
    </div>
    <?php } ?>
    
    <?php if(isset($menu) && $menu == 'total_emp') { ?>
    <!--all employees-->
    <div id="total_emp_container">
      <!-- total tab content-->
        <div class="table_wrap_3">
         <div class="table_fl_50">
            <table class="tstyle_1 tborder_2 tfonts_4 ac">
               <colgroup>
                  <col width="145" />
                  <col />
               </colgroup>
               <thead>
                  <tr class="tbground_3">
                     <td colspan="3" class="al">Number of Employees</td>
                  </tr>
                  <tr class="tbground_2 tfonts_2 tfonts_1">
                     <th>Department</th>
                     <th>TOTAL</th>
                  </tr>		
               </thead>
               <tbody>
                    <?php foreach($all_emp as $kall=>$vall) { ?>
                    <tr class="tbground_3">
                        <td><?php echo $vall->dept_name; ?></td>
                        <td><?php echo (empty($vall->total_ids)) ? "0" : $vall->total_ids; ?></td>
                    </tr>
                    <?php } ?>
               </tbody>
               <tfoot>
                  <tr class="tbground_2">
                     <th class="tfonts_2 tfonts_1">Total</th>
                     <td><?php echo $total_ids_all; ?></td>
                  </tr>
               </tfoot>
            </table>
         </div>
         <div id="pie_all" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
      </div>
      <!-- total tab content-->      
      <?php if($total_ids_all!=0) {?>
      <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_employees">View Graph</span></a> 
      <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_employees"><span>Export</span></a>
      <?php } ?>
      
    <div>
    <?php } ?>
    
    
    <?php if(isset($menu) && ($menu == 'vacation_leave' || $menu == 'sick_leave' || $menu == 'awol' || $menu == 'lwop' || $menu == 'tardiness')) { ?>
     <!--attendance-->
    <div id="attendance_container">
        <div class="table_wrap_3">
            <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_reg" style="color:#fff" id="attendance_per_month">View statistics per month</span></a>
            <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-left: 20px;margin-bottom : 20px;"><span class="view_reg" id="attendance_by_dept">View statistics by department</span></a> 
            
            <div id="attendance_per_month_container" style="margin-top:50px;">
                
                <div class="table_fl_50">
                    <table class="tstyle_1 tborder_2 tfonts_4 ac">
                       <colgroup>
                          <col width="85" />
                          <col width="85" />
                          <col width="85" />
                          <col />
                       </colgroup>
                       
                       <thead>
                          <tr class="tbground_3">
                             <td colspan="4" class="al" id="attend_month_title"><?php if($menu == 'awol' || $menu == 'lwop'){ echo strtoupper($menu); }else { echo ucwords((str_replace("_"," ",$menu))); } ?> per Month</td>
                          </tr>
                          <tr class="tbground_2 tfonts_2 tfonts_1">
                             <th>Month</th>
                             <th>Year</th>
                             <th>TOTAL</th>
                          </tr>		
                       </thead>
                       <tr>
                       </tr>
                       <tfoot>
                          <tr class="tbground_2">
                             <td></td>
                             <th class="tfonts_2 tfonts_1">Total</th>
                             <td><?php if(!empty($aRegEmp)){ echo $total_ids_reg; } else { echo "0";} ?></td>
                          </tr>			
                       </tfoot>
                       <tbody>
                                
                        <?php if(!empty($aRegEmp)){
                            for($yr=(int)min($years_reg);$yr<=(int)max($years_reg);$yr++) { ?>                                            
                        <?php for($mon=1;$mon<=12;$mon++) { ?>                                            
                            <tr class="tbground_3">                            
                                <td><?php echo $months[$mon]; ?></td>
                                <td><?php echo $yr;?></td> 
                                <td>
                                <?php $total_ids = "0";
                                foreach($aRegEmp as $kreg => $vreg) { ?>   
                                    <?php if($vreg->month == $months[$mon] && $vreg->year == $yr) { 
                                        $total_ids = $vreg->total_ids;
                                    } ?>  
                                <?php } 
                                echo $total_ids; ?>
                                </td>                          
                            </tr>	 
                        <?php } ?>
                        <?php } 
                        } else {?>
                            <tr class="tbground_3">                            
                                <td colspan=3>No results found.</td>
                            </tr>
                        
                        <?php } ?>
                        
                       </tbody>
                    </table> 
                    <?php 
                    if($to=="" || $from=="") {
                        echo $pager_reg_per_month; 
                    } ?>
                    
                    <br/>
                    
                    <?php if(!empty($aRegEmp)){ ?>
                    <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_per_month_<?php echo $menu;?>">View Graph</span></a> 
                    <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_per_month_<?php echo $menu;?>"><span>Export</span></a>
                    <?php }?>
                    
                </div>
                
                <div id="pie_per_month_<?php echo $menu;?>" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
            </div>
            
            <div id="attendance_by_dept_container" style="display:none;margin-top:50px;">
                
                <div class="table_fl_50">
                    <table class="tstyle_1 tborder_2 tfonts_4 ac">
                       <colgroup>
                          <col width="145" />
                          <col />
                       </colgroup>
                       <thead>
                          <tr class="tbground_3">
                             <td colspan="3" class="al" id="attend_title"><?php if($menu == "lwop" || $menu == "awol") { echo strtoupper($menu); } else { echo ucwords(str_replace("_"," ",$menu)); } ?> by Department</td>
                          </tr>
                          <tr class="tbground_2 tfonts_2 tfonts_1">
                             <th>Department</th>
                             <th>TOTAL</th>
                          </tr>		
                       </thead>
                       <tbody>                       
                            <?php foreach($attendance as $kreg_dept=>$vreg_dept) { ?>
                            <tr class="tbground_3">
                                <td><?php echo $vreg_dept->dept_name; ?></td>
                                <td><?php echo $vreg_dept->$menu; ?></td>
                            </tr>
                            <?php } ?>
                       </tbody>
                       <tfoot>
                          <tr class="tbground_2">
                             <th class="tfonts_2 tfonts_1">Total</th>
                             <td><?php echo $total_info; ?></td>
                          </tr>
                       </tfoot>
                    </table>
                    
                    <br/>
                    <?php if($total_info!=0) {?>
                    <a href="javascript:void(0)" class="btn_small btn_type_2s fl" style="margin-bottom : 20px;"><span class="view_graph" name="graph_by_dept_<?php echo $menu;?>">View Graph</span></a> 
                    <a href="javascript:void(0)" class="btn_export fr export_excel" title="Export to Excel" name="excel_by_dept_<?php echo $menu;?>"><span>Export</span></a>
                    <?php } ?>
                </div>
                
                <div id="pie_by_dept_<?php echo $menu;?>" style="float:left;width: 600px; height: 450px; margin:0px 0px 0px 20px; display :none"></div>
            </div>        
      </div>
            
    </div>
    
    <?php } ?>

    
    
      
    <input type="hidden" id="menu_tab" value="">   
    <input type="hidden" value="<?php if(isset($_GET['page'])) { echo $_GET['page']; } else { echo 1; } ?>" id="page_num"/>   
    <input type="hidden" value="<?php if(isset($_GET['menu'])) { echo $_GET['menu']; } else { echo "hired_emp"; } ?>" id="menu"/>  
    <div class="table_wrap_3" id="summary_graph" style="display:none;min-width: 400px; height: 400px; margin: 0 auto;margin-top:10px;"></div>
    	
   </div>