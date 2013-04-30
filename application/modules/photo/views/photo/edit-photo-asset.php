<h2 class="title nm np fl">
   <strong class="">Edit Photo Asset </strong><span class="subtext fn"><!-- nothing here --></span>
</h2>
<a href="<?php echo $module_path;?>photo_assets" class="btn btn_type_1 fl"><span>Back To List</span></a>
<!-- BEGIN inner content -->
<div class="content np">
   <?php echo $this->common->get_message("photo-message");?>
   <?php echo $this->common->form_submit(array("module" => "photo", 
                                               "controller" => "exec_photo_asset", 
                                               "method" => "edit_photo",
                                               "method_type" => "post",
                                               "name" => "photo-asset-form"));?>
      <input type="hidden" value="<?php echo $iidx;?>" name="id"/>
      <table class="table_form al" border="0">
         <colgroup>
            <col width="140px" />
            <col />
         </colgroup>
         <tr>
            <th><label for="select1">Category: </label></th>
            <td>
               <select class="select_type_3 nm np" id="select1" name="category">
                  <option value="">-select-</option>
                  <?php if ( $acategory ) {?>
                  <?php foreach ( $acategory as $rows ) { ?>
                  <option value="<?php echo $rows->tpac_idx;?>" <?php echo ( $aasset->tpal_tpac_idx == $rows->tpac_idx ) ? 'selected="selected"' : "";?>><?php echo $rows->tpac_category;?></option>
                  <?php }?>
                  <?php }?>
               </select>
            </td>
         </tr>
         <tr>
            <th>Item Name: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <input type="text" value="<?php echo $aasset->tpal_item_name;?>" name="item" class="input_type_2 nm" />
               </div>
            </td>
         </tr>
         <tr>
            <th>Description: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <textarea class="tarea" name="description" rows="5" cols="57"><?php echo $aasset->tpal_description;?></textarea>
               </div>
            </td>
         </tr>
         <tr>
            <th>Remarks: </th>
            <td>
               <div class="fl nm np" style="display:inline-block">
                  <textarea class="tarea" name="remarks" rows="5" cols="57"><?php echo $aasset->tpal_remarks;?></textarea>
               </div>
            </td>
         </tr>         
         <tr>
            <th><label for="select1">Status: </label></th>
            <td>
               <select class="select_type_3 nm np" name="status">
                  <option value="">-select-</option>               
                  <?php if ( $astatus ) {?>
                  <?php foreach ( $astatus as $rows ) { ?>
                  <option value="<?php echo $rows->tpas_idx;?>" <?php echo ( $aasset->tpal_tpas_idx == $rows->tpas_idx ) ? 'selected="selected"' : "";?>><?php echo $rows->tpas_status;?></option>
                  <?php }?>
                  <?php }?>
               </select>
            </td>
         </tr>
      </table>
   </form>
   <div class="btn_div" style="width:500px;">
      <a href="#" class="btn_small btn_type_3s btn_space save-photo-btn"><span>Save</span></a>
      <a href="<?php echo $module_path;?>photo_assets" class="link_1 mt5 btn_space">Return to List</a>
   </div>
</div>
<!-- END inner content -->