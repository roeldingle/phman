<h2 id="menu_title" class="title nm np fl"><strong class="">My Profile </strong>
   <span class="subtext">View my profile.</span></h2>
   <a href="javascript:void(0);" class="btn btn_type_1 fl ml5 add-expense-button" id="add_user_link"><span>Update My Profile</span></a>
   
   
    <!-- BEGIN inner content -->
    <div class="content np">
        <div id="sub_content">
        
            <div class="message_wrap"><?php echo $this->common->get_message("message-container");?></div>
            <div id="real_content" class="">
                    <!--
                <table>
                    <th>
                        <td></td>
                    </th>
                </table>
                    -->
			   <form>
					<table class="table_form al" border="0">
						<colgroup>
							<col width="140px" />
							<col />
						</colgroup>
						<tr>
							<th><label for="input1">Account Name</label></th>
							<td><input type="text" class="input_type_1 nm" id="input1" /><span class="message_type1 np">Required</span></td>
						</tr>
						<tr>
							<th><label for="input1">Employee Name</label></th>
							<td><input type="text" class="input_type_1 nm" id="input1" /><span class="message_type1 np">Required</span></td>
						</tr>
						<tr>
							<th><label for="input1">Employee Number</label></th>
							<td><input type="text" class="input_type_1 nm" id="input1" /><span class="message_type1 np">Required</span></td>
						</tr>
						<tr>
							<th><label for="input1">Password</label></th>
							<td><input type="password" class="input_type_1 nm" id="input1" /></td>
						</tr>
						<tr>
							<th><label for="input1">Confirm Password</label></th>
							<td><input type="password" class="input_type_1 nm" id="input1" /></td>
						</tr>
						<tr>
							<th><label for="select1">Account Type</label></th>
							<td>
								<select class="select_type_1 nm np" id="select1">
									<option>Super Admin</option>
									<option>Admin</option>
									<option>Employee</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>Gender</th>
							<td>
								<ul class="opt_radio nl nm np">
									<li><label for="option1">Male</label><input type="radio" class="radio_type_1 np" name="option" id="option1" checked="checked" /></li>
									<li><label for="option2">Female</label><input type="radio" class="radio_type_1 np" name="option" id="option2" /></li>
								</ul>
							</td>
						</tr>
					</table>
				</form>
				<div class="category_mgmt_bottom_btns">
					<a href="#" class="btn btn_type_3 mr10" id="btn_save_menu"><span>Save</span></a><a href="javascript:void(0)" id="return_default"class="link_1">Return to Default</a>
				</div>	
            </div>
        </div>
    </div>