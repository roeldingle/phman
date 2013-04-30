<!--hidden values-->
<input type="hidden" id="main_category_id" value="<?php echo MAIN_CATEGORY_ID;?>" />

<!--end hidden values-->
<h2 class="title nm np fl"><strong class="fn"><?php echo $db_main_cat_data['tsmc_name'];?></strong></h2>
<a href="javascript:void(0)" class="add_hardware_stock addnew_btn btn btn_type_1 fl ml10">                                               
 <span>Add New <?php echo $db_main_cat_data['tsmc_name'];?></span>  
</a>                                        
<div class="content np category_management_main_container">
    <div class="category_container">
        <div class="message_wrap"><?php echo $this->common->get_message("message-container");?></div>
        <div class="category_mgmt_container">
        
            <div class="category_mgmt_inner">
           
            <div class="search_01 fl"> 
            
                <form id="search_form">
                    <label>Search by</label>
                    <span id="search-field-wrap">
                    <input type="hidden" id="search_option_hidtxt" name="search_field" />
                    <select id="search_option_select">
                        <option value="">--select--</option>
                        <option value="tsit_tssc_sscid">Category</option>
                        
                        <?php 
                        if(MAIN_CATEGORY_ID == "000011"){
                            echo '<option value="tsit_version">Version</option>';
                        }else{
                            echo '<option value="tsit_model">Model</option>';
                        }
                        ?>
                        
                        <option value="tsit_user_assigned">Employee</option>
                    </select>
                    <span>
                    <span id="search-item-wrap"></span>&nbsp;
                    <a class="link_1" href="<?php echo $module_path;?>category_management/<?php echo strtolower($db_main_cat_data['tsmc_name']);?>" >Refresh</a>
                </form>
                <div class="search_mess_wrap"><?php echo $search_breadcrumbs;  ?></div>
                    
            </div>
            <div class="fr">
                <label>Show Rows</label>
                    <?php $this->app->show_rows(10,array(10,20,30));?>
            </div>



<table id="tb_stock_list" border="0" cellspacing="0" cellpadding="0" class="table_01 table_hover" >
    <colgroup>
        <col width="10">
        <col width="50">
        <col width="150">
        <col width="200">
        <col width="200">
        <col width="*">
        <col width="120">
        <col width="120">
        <col width="120">
    </colgroup>
    <thead>
        <tr>
            <th><input type="checkbox" class="checkall" /></th>
            <th>No.</th>
            <th>Category</th>
            <th>
            <?php echo (MAIN_CATEGORY_ID == "000011") ? "Version" : "Model";?></th>
            <th>Employee</th>
            <th>History</th>
            <th>V/M/D</th>
            <th>Reg Date</th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody style=""><?php echo $tb_content_rows;?></tbody>
</table>
</div>
    
    <a href="#" class="btn_small btn_type_1s delete-btn fl" title="Delete"><span>Delete</span></a>
             
     <form method="post" id="export_form" action="<?php echo $exec_path;?>?mod=stock|main_category_excel|result_export_to_excel">
         <input type="hidden" name="category_page" value="<?php echo strtolower($db_main_cat_data['tsmc_name']);?>">                                                
         <button title="Export to Excel" type="submit" class="btn_export fr" style="border: 0;" name="export">
         <span>Export</span>                                                
         </button>                                            
     </form>
     
     
         <div class="pagination center"><?php echo $pagination;?></div>
        </div>
    </div>
</div>
        
        <!-- BEGIN inner content -->
        <div class="content np">
            <!--CATEGORY_CONTAINER-->
            
            <!--<div class="category_mgmt_bottom_btns">
                <a href="#" class="btn btn_type_3 mr10"><span>Save</span></a><a href="#" class="link_1">Undo Changes</a>
            </div>-->
        </div>
        <!--//CATEGORY_CONTAINER-->					
        <!-- END inner content -->
