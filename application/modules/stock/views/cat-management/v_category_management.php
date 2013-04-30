<!--end hidden values-->
<h2 class="title nm np fl"><strong class="fn">Category Management</strong></h2>
<a  href="javascript:void(0)" class="btn btn_type_1 fl ml10" id="add_subcategory_btn" ><span>Add Sub Category</span></a>
<div class="category_container">
    <div class="message_wrap"><?php echo $this->common->get_message("message-container");?></div>
    <div class="category_mgmt_container">
        <div class="category_mgmt_inner">
            <div class="accordion atype1 fl" style="display:inline-block;width:510px">
                <div class="menu_listing fl ml10">
                            
                <!--loop starts-->        
                    <?php echo $main_category;?>
               <!--loop ends-->
                
                </div>
            </div>
            <div class="box_1 fl ml10 category_management_box_info">
                <p class="box_1_title">Category Information </p>
                <form>
                    
                    <input type="hidden" id="category_id_textbox" name="category_id_textbox" class="input_type_4" />
                    <!--<input type="text" id="category_name_textbox" name="category_name_textbox" class="input_type_4" disabled />-->
                   
                    <p>
                    <label class="mr5">
                    Sub category</label>
                    <input type="text" id="category_name_textbox" name="category_name_textbox" class="input_type_4 cat_hide" disabled  />
                    </p>
                </form>
                <div style="margin:20px 0 20px 0;display:none;" class="ac action-box" >
                    <a href="javascript:void(0)" id="edit_subcategory" class="link_1 mr10">Edit this category</a>
                    <a href="javascript:void(0)" id="delete_subcategory" class="link_1">Delete this category</a>
                </div>
            </div>
        </div>
        <div class="pagination center"></div>
    </div>
</div>