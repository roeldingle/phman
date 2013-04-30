<h2 class="title nm np fl" ><strong class="fn">Assign IP Management</strong><span class="subtext"></span></h2>
<a href="#" class="btn_small btn_type_1s fl ml10 ip-form-btn" title="Assign New IP" ><span>Assign New IP</span></a>
<div class="category_container">
      <!-- BEGIN inner content -->
      <div class="content np">
         <div class="search_02">
            <form>
               <div class="holder_2">
                  <label >First Name / Last Name:</label>
                  <input type="text" value="<?php echo $search;?>" id="search-txtbox" class="input_type_1" title="First Name/Last Name Search" />
               </div>
               <div class="holder">
                  <label>Period :</label>
                  <input type="text" value="<?php echo $start_date;?>" readonly="true" class="input_type_3" id="calendar-from"  title="Start Date" />
                  <label class="calendar_icon" for="calendar-from"><img src="<?php echo $assets_path;?>core/images/calendar-day.png"  title="Start Date" ></label>
               </div>
               <div class="holder">
                  <span class="mr10">&ndash;</span>
                  <input type="text" value="<?php echo $end_date;?>" readonly="true" class="input_type_3" id="calendar-to" title="End Date" />
                  <label class="calendar_icon" for="calendar-to"><img src="<?php echo $assets_path;?>core/images/calendar-day.png" title="End Date" ></label>                  
               </div>
               <br /> <br />
               <div class="holder_2">
                  <label>Department</label>
                  <select title="Department" class="select_type_1 nm np" id="department">
                     <option value="">- Department-</option>
                     <?php if($adepartment){?>
                     <?php foreach($adepartment as $rows){?>
                     <option value="<?php echo $rows->td_idx;?>" <?php echo ($department == $rows->td_idx) ? 'selected="selected"' : "";?>><?php echo $rows->td_dept_name;?></option>
                     <?php }?>
                     <?php }?>
                  </select>
                  <a href="#" class="btn_small btn_type_2s ml10 search-btn" title="Search" ><span>Search</span></a>
                  <a href="#" class="btn_small btn_type_2s ml10 reset-search-btn" title="Reset" ><span>Reset</span></a>
               </div>                              
            </form>
         </div>
         <a href="#" class="btn_small btn_type_1s delete-btn" title="Delete" ><span>Delete</span></a>
         <div class="show_rows fr">
            <form>
               <label title="Rows Per Page" >Show Rows</label>
               <?php echo $this->app->show_rows($ilimit,array(10, 20, 30, 50, 100));?>
            </form>
         </div>
         <div class="js-message" style="width:99%"></div>
         <div class="php-message" style="width:99%"><?php $this->common->get_message("php-message");?></div>
         <table border="0" cellspacing="0" cellpadding="0" class="table_02" id="ip-list-table">
            <colgroup>
               <col width="35" />
               <col width="75"/>
               <col />
               <col />
               <col />
               <col />
               <col />
               <col />
               <col width="105"/>
            </colgroup>
            <thead>
               <tr>
                  <th><input type="checkbox" class="check-all" value="" title="Check All" /></th>
                  <th title="Row No." >No.</th>
                  <th title="Seat No." >Seat No.</th>
                  <th title="First Name" >First Name</th>
                  <th title="Last Name" >Last Name</th>
                  <th title="Department" >Department</th>
                  <th title="Assign IP" >Assign IP</th>
                  <th title="Gateway" >External IP</th>
                  <th title="Options" class="last">Options</th>                  
               </tr>
            </thead>
            <tbody>
               <?php if( $aip_list ) {?>
               <?php foreach( $aip_list as $rows ) {?>
               <tr>
                  <td><input type="checkbox" class="row-list" value="<?php echo $rows['idx'];?>" /></td>
                  <td><?php echo $rows['row'];?></td>
                  <td><?php echo ($rows['seat_no']) ? $rows['seat_no'] : '-';?></td>
                  <td><?php echo $rows['fname'];?></td>
                  <td><?php echo $rows['lname'];?></td>
                  <td><?php echo $rows['department'];?></td>
                  <td><?php echo $rows['assign_ip'];?></td>
                  <td><?php echo $rows['external_ip'];?></td>
                  <td class="last ip-management-list-option">
                     <a href="#" class="btn_vmd btn_vmd_1 view-btn" title="View">V</a>
                     <a href="#" class="btn_vmd btn_vmd_2 modify-btn" data-info='<?php echo $rows['sinfo'];?>' title="Modify">M</a>
                     <a href="#" class="btn_vmd btn_vmd_3 delete-single-btn" data-delete-id="<?php echo $rows['idx'];?>" title="Delete">D</a>
                  </td>
               </tr>
               <?php }?>
               <?php }else{?>
               <tr><td colspan="9"> No Record. </td></tr>
               <?php }?>
            </tbody>
         </table>
         <a href="#" class="btn_small btn_type_1s delete-btn" title="Delete" ><span>Delete</span></a>
         <a title="Export to Excel" class="btn_export fr" href="<?php echo $exec_path;?>?mod=stock|exec_ip_management|export_ip<?php echo $qry_param;?>"><span>Export</span></a>
         <div class="center">
            <?php echo $pager;?>
         </div>
      </div>
<!-- END inner content -->
</div> <!-- END category container -->