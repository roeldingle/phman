<img src="<?php echo $getfile_path.'/hr/uploads/map/'.$aresult[0]->tss_map_src;?>" class="map_preview map_size fl" id="sitplanimg" alt="Sitting Plan" />
<input type="hidden" id="usergradeid" value="<?php echo $this->session->userdata('usergradeid');?>" />
<?php
foreach($acoords as $arr){
   echo '<img src="'.$assets_path.$module_name.'/images/'.$arr->tsc_seat_usage.'.png" id="'.$arr->tsc_idx.'" class="img">';
}
?>

<div class="set_seatinfo" style="display:none">
   <table class="table_form al" border="0">
       <colgroup>
           <col width="140px">
               <col>
       </colgroup>
       <tbody>
           <tr>
               <th>
                   <label for="seat-number">Seat Number</label>
               </th>
               <td>
                   <input id="seat-number" class="input_type_3 nm" type="text" disabled value="00001">
               </td>
           </tr>
           <tr class="sitting_usage">
               <th>
                   <label for="map-employee-department">Availability</label>
               </th>
               <td>
                   <select id="seat_usage" class="select_type_1 nm np">
                     <option value="3">Used</option>
                     <option value="1">Vacant PC</option>
                     <option value=2>Vacant Table</option>
                     <option value=0>Not Available</option>'
                   </select>
               </td>
           </tr>
           <tr class="emp_sit_detail">
               <th>
                   <label for="map-employee-department">Department</label>
               </th>
               <td>
                   <select id="department_list" class="select_type_1 nm np">
                   </select>
               </td>
           </tr>
           <tr class="emp_sit_detail">
               <th>
                   <label for="map-employee-name">Employee Name</label>
               </th>
               <td>
                   <select id="employee_list" class="select_type_1 nm np">
                   </select>
               </td>
           </tr>
       </tbody>
   </table>
   <div class="btn_div">
      <a class="btn btn_type_3 btn_space" href="#" id="submitForm">
      <span>Save</span>
      </a>

      <a class="link_1 mt5" href="#">Cancel</a>

   </div>
</div>

<div class="seat_info" style="display:none">
<img id="loader" src="<?php  echo $assets_path.$module_name ?>/images/loader.gif" style="position:absoulte;top:100px"/>
   <table class="table_form al seat_form" border="0">
       <colgroup>
           <col width="140px">
               <col>
       </colgroup>
       <tbody>
           <tr>
               <th>
                   <label for="seat-number">Seat Number</label>
               </th>
               <td>
                   <span id="seat_no">1<span>
               </td>
           </tr>
           <tr>
               <th>
                   <label for="map-employee-availability">Availability</label>
               </th>
               <td>
                   <span id="availability">Vacant<span>
               </td>
           </tr>
           <tr>
               <th>
                   <label for="map-employee-department">Department</label>
               </th>
               <td>
                   <span id="department">Developer<span>
               </td>
           </tr>
           <tr>
               <th>
                   <label for="map-employee-name">Employee Name</label>
               </th>
               <td>
                  <span id="employee">Dingle, Roel<span>
               </td>
           </tr>
       </tbody>
   </table>
</div>