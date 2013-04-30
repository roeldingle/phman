
    <h2 class="title nm np fl"><strong class="fn">Statistic (Stocks)</strong></h2>
    <div class="category_container">
        <!-- BEGIN inner content -->
        <div class="content np">
            <!-- TABS-->
            <ul class="tabmenu">
                <li><a href="<?php echo $module_path;?>statistic/hardware" <?php echo ($category_name == "hardware") ? 'class="current"' : ''; ?> value="9">Hardware</a></li>
                <li><a href="<?php echo $module_path;?>statistic/accessories" <?php echo ($category_name == "accessories") ? 'class="current"' : ''; ?> value="10">Accessories</a></li>
                <li><a href="<?php echo $module_path;?>statistic/software" <?php echo ($category_name == "software") ? 'class="current"' : ''; ?> id="load_software" value="11">Software</a></li>
                <li><a href="<?php echo $module_path;?>statistic/furnitures" <?php echo ($category_name == "furnitures") ? 'class="current"' : ''; ?> id="load_furnitures" value="12">Furnitures/ Appliances</a></li>
            </ul>
            <!-- //TABS-->
            <div class="search_02">
                <form method="GET">
                    <div class="holder">
                        <label>Period :</label>
                        <input type="text" value="" class="input_type_3" id="from_date" name="from_date" readonly="readonly">
                       
                    </div>
                    <div class="holder">
                        <span class="mr10">–</span>
                        <input type="text" value="" class="input_type_3" id="to_date" name="to_date" readonly="readonly">
                    </div>
                    <input type="submit" value="Search" />
                    <!--
                    <a href="javascript:void(0);" class="btn_small btn_type_2s" id="search_timeframe"><span>View</span></a>
                    <a href="javascript:;" class="btn_small btn_type_2s" id="clear"><span>Clear</span></a>
                    -->
                </form>
            </div>
            
            <div id="listdiv" style="inline-block">
                    
                    <div id="category_data" class="fl mr10" style="margin-top:30px;"><span><p id="c_search_counter"></p></span>
                        <h2><strong class="title fn">By Category</strong></h2>
                        <table border="0" cellspacing="0" cellpadding="0" class="table_02 table_hover" id="by_category">
                            <colgroup>
                                <col width="80">
                                <col width="300">
                                <col width="100">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Category</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody><?php echo $tb_list_category;?></tbody>
                        </table>
                    </div>
                                     
                    <div id="model_data" class="fl"><span><p id="m_search_counter"></p></span>
                        <div class="show_rows fr">
                            <form>
                                <label>Show Rows</label>
                                
                                    <?php $this->app->show_rows(10,array(10,20,30));?>
                               
                            </form>
                        </div>
                        <h2><strong class="title fn">By Model</strong></h2>
                        <table border="0" cellspacing="0" cellpadding="0" class="table_02 table_hover" id="by_model">
                            <colgroup>
                            <col width="80">
                                <col width="300">
                                <col width="300">
                                <col width="100">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Category</th>
                                   <!-- <th>Brand</th> -->
                                    <th>Model</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody><?php echo $tb_list_model;?></tbody>
                        </table>
                         <a class="btn_export fr" href="javascript:;" style="margin-top: 20px;" id="open_export_dialog"><span>Export</span></a>
                         <div class="pagination center"><?php echo $pagination;?></div>
                    </div>
                
                </div>    
           
            
        </div>
<!-- END inner content -->
</div> <!-- END category container -->

<!-- EXPORT DIALOG -->
