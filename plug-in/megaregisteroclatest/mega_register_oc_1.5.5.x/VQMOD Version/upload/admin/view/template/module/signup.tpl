<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if (isset($this->session->data['success'])) { ?>
  <div class="success"><?php echo $this->session->data['success']; ?></div>
  <?php unset($this->session->data['success']);} ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
         
          
          
                  <?php if ($isModActive['enablemod'] == 1) { ?>
                 <h2> Enable Module:  <input type="checkbox" value = "1"  name="mod_enable" checked  >
                  <?php } else { ?>
                  <h2>Enable Module:  <input type="checkbox" value = "1"  name="mod_enable"   > 
                  
                                 <?php } ?> 
           <?php if ($isModActive['single_box'] == 1) { ?>
                  All Fields in a single box:  <input type="checkbox" value = "1"  name="single_box" checked  ><br/><br/>
                  <?php } else { ?>
                 All Fields in a single box:  <input type="checkbox" value = "1"  name="single_box"   > <br/><br/>
                  
                                 <?php } ?>
                                  <?php if ($modData['show_address'] == 1) { ?>
                 Hide complete Address fields box(On Registration Only):  <input type="checkbox" value = "1"  name="show_address" checked  ></h2>
                  <?php } else { ?>
                 Hide complete Address fields box(On Registration Only):  <input type="checkbox" value = "1"  name="show_address"   > </h2>
                  
                                 <?php } ?> 
          <table  class="list">
          <thead >
            <tr >
              <td class="center" colspan="2">Attribute</td>
              <td class="center" colspan="2">Registration Area Main/Checkout includes Guest</td>              
              <td class="center" colspan="2">Edit Area Personal/Address</td>                   
              <td class="center" colspan="2">Payment/Shipping Area includes Guest Shipping</td>
              <td class="center" colspan="2">Set Field as Numeric/Masking</td>
            </tr>
            <tr >
              <td class="left">Field</td>
              <td class="left">Sort/Order</td>
              <td class="center">Show</td>
              <td class="left">Required</td>
              <td class="left">Show</td>
              <td class="left">Required</td>
              <td class="left">Show</td>
              <td class="left">Required</td>
              <td class="left" >Numeric</td>
              <td class="left" title="For Brasil Country">Masking</td>              
            </tr>
          </thead>          
             
               <tr>
                  <td class="left"> First Name </td>
                  <td class="left"> <input type="text" name="f_name_sort" size="1" value="<?php echo $modData['f_name_sort']; ?>"  maxlength="2" /> </td>
                  <td class="left"> <input type="checkbox" value ="1" name="f_name_show" <?php if ($modData['f_name_show'] >0)  echo "checked"; ?>   ></td>
                  <td class="left"> <input type="checkbox" value = "1"  name="f_name_req"<?php if ($modData['f_name_req'] >0)  echo "checked"; ?>   ></td>
					<td class="left"> <input type="checkbox" value ="1" name="f_name_show_edit" <?php if ($modData['f_name_show_edit'] >0)  echo "checked"; ?>   ></td>
                  <td class="left"> <input type="checkbox" value = "1"  name="f_name_req_edit"<?php if ($modData['f_name_req_edit'] >0)  echo "checked"; ?>   ></td>	                 
				<td class="left"> <input type="checkbox" value ="1" name="f_name_show_checkout" <?php if ($modData['f_name_show_checkout'] >0)  echo "checked"; ?>   ></td>
                  <td class="left"> <input type="checkbox" value = "1"  name="f_name_req_checkout"<?php if ($modData['f_name_req_checkout'] >0)  echo "checked"; ?>   ></td>
                  <td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>	
               </tr>
               <tr>
                  <td class="left"> Last Name </td>
                  <td class="left"> <input type="text" name="l_name_sort" size="1" value="<?php echo $modData['l_name_sort']; ?>"  maxlength="2" /> </td>
                  <td class="left"> <input type="checkbox" value = "1"  name="l_name_show"<?php if ($modData['l_name_show'] >0)  echo "checked"; ?>   ></td>
				<td class="left"> <input type="checkbox" value = "1"  name="l_name_req"<?php if ($modData['l_name_req'] >0)  echo "checked"; ?>   ></td>
				  <td class="left"> <input type="checkbox" value = "1"  name="l_name_show_edit"<?php if ($modData['l_name_show_edit'] >0)  echo "checked"; ?>   ></td>
				<td class="left"> <input type="checkbox" value = "1"  name="l_name_req_edit"<?php if ($modData['l_name_req_edit'] >0)  echo "checked"; ?>   ></td>
				  <td class="left"> <input type="checkbox" value = "1"  name="l_name_show_checkout"<?php if ($modData['l_name_show_checkout'] >0)  echo "checked"; ?>   ></td>
				<td class="left"> <input type="checkbox" value = "1"  name="l_name_req_checkout"<?php if ($modData['l_name_req_checkout'] >0)  echo "checked"; ?>   ></td>
				<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> Email </td>
                  <td class="left"> <input type="text" name="email_sort" size="1" value="<?php echo $modData['email_sort']; ?>"  maxlength="2" /> </td>
                  <td class="center" colspan="9" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> Telephone </td>
                  <td class="left"> <input type="text" name="mob_sort" size="1" value="<?php echo $modData['mob_sort']; ?>"  maxlength="2" /> </td>
                  <td class="left"> <input type="checkbox" value = "1"  name="mob_show"<?php if ($modData['mob_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="mob_req"<?php if ($modData['mob_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="mob_show_edit"<?php if ($modData['mob_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="mob_req_edit"<?php if ($modData['mob_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="mob_show_checkout"<?php if ($modData['mob_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="mob_req_checkout"<?php if ($modData['mob_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="mob_numeric"<?php if ($modData['mob_numeric'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  title="Telephone Brasil-(xx)xxxx-xxxx" name="mob_masking"<?php if ($modData['mob_masking'] >0)  echo "checked"; ?>   ></td>

               </tr>
               <tr>
                  <td class="left"> Fax </td>
                  <td class="left"> <input type="text" name="fax_sort" size="1" value="<?php echo $modData['fax_sort']; ?>"  maxlength="2" /> </td>
                  <td class="left"> <input type="checkbox" value = "1"  name="fax_show"<?php if ($modData['fax_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1" disabled name="fax_req"<?php if ($modData['fax_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="fax_show_edit"<?php if ($modData['fax_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1" disabled name="fax_req_edit"<?php if ($modData['fax_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="fax_show_checkout"<?php if ($modData['fax_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1" disabled name="fax_req_checkout"<?php if ($modData['fax_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="fax_numeric"<?php if ($modData['fax_numeric'] >0)  echo "checked"; ?>   ></td>
<td class="left" style="background-color: #CCC;">N/A </td>

               </tr>
               <tr>
                  <td class="left"> Company </td>
                   <td class="left"> <input type="text" name="company_sort" size="1" value="<?php echo $modData['company_sort']; ?>"  maxlength="2" /> </td>
                  <td class="left"> <input type="checkbox" value = "1"  name="company_show"<?php if ($modData['company_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="company_req"<?php if ($modData['company_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="company_show_edit"<?php if ($modData['company_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="company_req_edit"<?php if ($modData['company_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="company_show_checkout"<?php if ($modData['company_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="company_req_checkout"<?php if ($modData['company_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> Customer Group </td>
                  <td class="left"> <input type="text" name="cgroup_sort" size="1" value="<?php echo $modData['cgroup_sort']; ?>"  maxlength="2" /> </td>
                  <td class="center" colspan="9" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
                <tr>
                  <td class="left"> Company ID </td>
                   <td class="left"> <input type="text" name="companyId_sort" size="1" value="<?php echo $modData['companyId_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="companyId_show"<?php if ($modData['companyId_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="companyId_req"<?php if ($modData['companyId_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="companyId_show_edit"<?php if ($modData['companyId_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="companyId_req_edit"<?php if ($modData['companyId_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="companyId_show_checkout"<?php if ($modData['companyId_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="companyId_req_checkout"<?php if ($modData['companyId_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="companyId_numeric"<?php if ($modData['companyId_numeric'] >0)  echo "checked"; ?>   ></td>
<td class="left" style="background-color: #CCC;">N/A </td>
</tr>
 <tr>
                  <td class="left"> Tax ID </td>
                  <td class="left"> <input type="text" name="taxId_sort" size="1" value="<?php echo $modData['taxId_sort']; ?>"  maxlength="2" /> </td>
                  <td class="center" colspan="9" style="background-color: #CCC;"> Not Applicable</td>
               </tr>

                <tr>
                  <td class="left"> Address1 </td>
                   <td class="left"> <input type="text" name="address1_sort" size="1" value="<?php echo $modData['address1_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="address1_show"<?php if ($modData['address1_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address1_req"<?php if ($modData['address1_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address1_show_edit"<?php if ($modData['address1_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address1_req_edit"<?php if ($modData['address1_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address1_show_checkout"<?php if ($modData['address1_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address1_req_checkout"<?php if ($modData['address1_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> Address2 </td>
                   <td class="left"> <input type="text" name="address2_sort" size="1" value="<?php echo $modData['address2_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="address2_show"<?php if ($modData['address2_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="address2_req"<?php if ($modData['address2_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address2_show_edit"<?php if ($modData['address2_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="address2_req_edit"<?php if ($modData['address2_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="address2_show_checkout"<?php if ($modData['address2_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  disabled name="address2_req_checkout"<?php if ($modData['address2_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> City </td>
                   <td class="left"> <input type="text" name="city_sort" size="1" value="<?php echo $modData['city_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="city_show"<?php if ($modData['city_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="city_req"<?php if ($modData['city_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="city_show_edit"<?php if ($modData['city_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="city_req_edit"<?php if ($modData['city_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="city_show_checkout"<?php if ($modData['city_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="city_req_checkout"<?php if ($modData['city_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> Post Code </td>
                   <td class="left"> <input type="text" name="pin_sort" size="1" value="<?php echo $modData['pin_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_show"<?php if ($modData['pin_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_req"<?php if ($modData['pin_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_show_edit"<?php if ($modData['pin_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_req_edit"<?php if ($modData['pin_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_show_checkout"<?php if ($modData['pin_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_req_checkout"<?php if ($modData['pin_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="pin_numeric"<?php if ($modData['pin_numeric'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  title ="PostCode Brasil-xxxxx-xxx" name="pin_masking"<?php if ($modData['pin_masking'] >0)  echo "checked"; ?>   ></td>
</tr>
  <tr>
                  <td class="left"> State/Region </td>
                   <td class="left"> <input type="text" name="state_sort" size="1" value="<?php echo $modData['state_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="state_show"<?php if ($modData['state_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="state_req"<?php if ($modData['state_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="state_show_edit"<?php if ($modData['state_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="state_req_edit"<?php if ($modData['state_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="state_show_checkout"<?php if ($modData['state_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="state_req_checkout"<?php if ($modData['state_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>             
               <tr>
                  <td class="left"> Country </td>
                   <td class="left"> <input type="text" name="country_sort" size="1" value="<?php echo $modData['country_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="country_show"<?php if ($modData['country_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="country_req"<?php if ($modData['country_req'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="country_show_edit"<?php if ($modData['country_show_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="country_req_edit"<?php if ($modData['country_req_edit'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="country_show_checkout"<?php if ($modData['country_show_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1"  name="country_req_checkout"<?php if ($modData['country_req_checkout'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="2" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
  <tr>
                  <td class="left"> Password </td>
                  <td class="left"> <input type="text" name="pass_sort" size="1" value="<?php echo $modData['pass_sort']; ?>"  maxlength="2" /> </td>
                  <td class="center" colspan="9" style="background-color: #CCC;"> Not Applicable</td>
               </tr>                            
               <tr>
                  <td class="left"> Password Confirm </td>
                  <td class="left"> <input type="text" name="passconf_sort" size="1" value="<?php echo $modData['passconf_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="passconf_show"<?php if ($modData['passconf_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1" disabled name="passconf_req"<?php  echo "checked"; ?>   ></td>
<td class="center" colspan="6" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
                <tr>
                  <td class="left"> NewsLetter </td>
                  <td class="left"> <input type="text" name="subscribe_sort" size="1" value="<?php echo $modData['subscribe_sort']; ?>"  maxlength="2" /> </td>
<td class="left"> <input type="checkbox" value = "1"  name="subsribe_show"<?php if ($modData['subsribe_show'] >0)  echo "checked"; ?>   ></td>
<td class="left"> <input type="checkbox" value = "1" disabled name="subsribe_req"<?php if ($modData['subsribe_req'] >0)  echo "checked"; ?>   ></td>
<td class="center" colspan="6" style="background-color: #CCC;"> Not Applicable</td>
               </tr>
               <tr>
                  <td class="left"> Default Country if Hiding From all pages</td>
<td class="left" colspan="3"> 
<select name="country_id">
              <option value="0">--Please Select--</option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $modData['def_country']) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
</td>
<td class="left"> Default Region if Hiding From all pages</td>
<td class="left" colspan="4"> <select name="zone_id"></td>


               </tr>                        
             </table>
             <br/><br/>
             <table  class="list">
                  <thead>
                      <tr>
                        <td class="left"> Attribute Length </td>
                        <td class="left"> Minimum </td>
                        <td class="left"> Maximum </td>
                        <td class="left"> Or Fixed Length for Mobiles </td>
                      </tr>
                  </thead>
                  <tr>
                      <td class="left"> Mobile/Telephone  </td>
<td class="left"> <input type="text" name="mob_min" value="<?php echo $modData['mob_min']; ?>"  maxlength="20" /> </td>
<td class="left"> <input type="text" name="mob_max" value="<?php echo $modData['mob_max']; ?>"  maxlength="20" /> </td>
<td class="left"> <input type="text" name="mob_fix" value="<?php echo $modData['mob_fix']; ?>"  maxlength="20" /> </td>
                  </tr>
                  <tr>
                      <td class="left"> Password  </td>
<td class="left"> <input type="text" name="pass_min" value="<?php echo $modData['pass_min']; ?>"  maxlength="20" /> </td>
<td class="left"> <input type="text" name="pass_max" value="<?php echo $modData['pass_max']; ?>"  maxlength="20" /> </td>
<td class="left"> <input type="text" name="pass_fix" value="<?php echo $modData['pass_fix']; ?>"  maxlength="20" /> </td>
                  </tr>          
              </table>
             </form>
    </div>
  </div>
</div>
 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
					
		success: function(json) {
			
			
			
			html='';
			if (json['zone'] != '' && undefined !=json['zone']) {
				html = '<option value="999">--Please Select--</option>';
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $modData['def_state'];?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected">--None--</option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 

<?php echo $footer; ?>