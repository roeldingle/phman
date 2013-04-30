<h2 id="menu_title" class="title nm np fl"><strong class="">User </strong>
   <span class="subtext">The curent list of the system users.</span></h2>
   
   <a href="javascript:void(0);" class="btn btn_type_1 fl ml5 add-expense-button" id="add_user_link"><span>Add User</span></a>
    
    <div class="table_wrap_3 mt10">
         				
         <div class="search_01 fr">
            
            <div class="ar mt5">
               <label class="mr5">Show Entries by </label>
               <?php
                  $this->app->show_rows(10,array(10,20,30));
               ?>							
            </div>
         </div>						
      </div>
   <!-- BEGIN inner content -->
<div class="content np">
<div id="sub_content">
    <div class="message_wrap"><?php echo $this->common->get_message("message-container");?></div>
    <div id="real_content">
    
    <!--start content-->
    <table id="tb_user_list" border="0" cellspacing="0" cellpadding="0" class="table_02">
        <colgroup>
            <col width="60">
            <col width="80">
            <col>
            <col width="250">
            <col width="250">
            <col width="250">
            <col width="150">
        </colgroup>
        <thead>
           <tr>
              <th><input type="checkbox" name="checkall" class="checkall" value="" /></th>
              <th>No.</th>
              <!--<th>Employee</th>-->
              <th>Username</th>
              <th>User grade</th>
              <th>Date created</th>
              <th>Modify</th>
           </tr>
        </thead>
        <tbody>
            <?php echo $tb_content_rows;?>
        </tbody>
    </table>
    
    <!--end content-->
    <?php echo $pagination;?>
    </div>
    <a href="javascript:void(0);" class="btn btn_type_1 fr ml5 add-expense-button" id="delete_btn"><span>Delete</span></a>

    <br />
    <div style="width:100%;display:inline-block">
        <div class="legend_box"></div>
    </div>
</div>



<!--container for dialogbox-->
<div class="d_box"></div>
</div>