<h2 id="menu_title" class="title nm np fl"><strong class="">My Account </strong>
   <span class="subtext">View my account.</span></h2>
   <a href="javascript:void(0);" class="btn btn_type_1 fl ml5 add-expense-button" id="link_change_pass"><span>Update Account Info</span></a>
   
   
    <!-- BEGIN inner content -->
    <div class="content np">
        <div id="sub_content">
        
            <div class="message-container"></div>
            <div id="real_content" style="padding:20px;" >
            <!--
                <h1 ><strong><?php echo $user_info['employee_name'];?></strong></h1>
                <h2 style="color:#333;margin-top:5px;margin-bottom:20px;" ><?php echo $user_info['tp_position'];?></h2>-->
                    <input type="hidden" id="hidden_tu_idx" value="<?php echo $user_info['tu_idx'];?>" />
                <form class="rounded-corners fl" id="view_account" >
                    <h3>ACCOUNT INFORMATION</h3>
					<table class="table_form al" border="0" style="margin:10px 50px 30px 0px;" cellpadding="10px">
						<colgroup>
							<col width="140px" />
							<col />
						</colgroup>
                        <!--
                        <tr>
							<th><label for="input1">Employee ID</label></th>
							<td>
                            <span><?php echo $user_info['te_employee_id'];?></span>
                            </td>
						</tr>
                        -->
						<tr>
							<th><label for="input1">Account name</label></th>
							<td>
                            <span><?php echo $user_info['tu_username'];?></span>
                            
                            </td>
						</tr>
						<tr>
							<th><label for="select1">Account Type</label></th>
							<td>
                            <span><?php echo $user_info['tug_name'];?></span>
							</td>
						</tr>
					</table>
                </form>  
                <form  class="fl" style="width:500px;" id="update_form" >
                </form>
                
                
               
               
               
              
            
            </div>
        </div>
    </div>