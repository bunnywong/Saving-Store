<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <p><?php echo $text_account_already; ?></p>
   <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_your_details; ?></h2>
    <div class="content">
      <table class="form xpersonal">
       <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_show']) { ?>
        <tr sort="a<?php echo $modData['f_name_sort']; ?>">
          <td> <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" <?php if($title_firstname) echo "xtitle ='".$title_firstname."'";?>/>
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
         <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_show']) { ?>
        <tr sort="a<?php echo $modData['l_name_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" <?php if($title_lastname) echo "xtitle ='".$title_lastname."'";?> />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        
        <tr sort="a<?php echo $modData['email_sort']; ?>">
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" <?php if($title_email) echo "xtitle ='".$title_email."'";?> />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        
         <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['mob_show']) { ?>
        <tr sort="a<?php echo $modData['mob_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['mob_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_telephone; ?></td>
          <td><input type="text" <?php if($title_telephone) echo "xtitle ='".$title_telephone."'";?> class="<?php echo ($modData['mob_numeric']?"numeric":"")." ".($modData['mob_masking']?"mask telephone":""); ?>" name="telephone" value="<?php echo $telephone; ?>" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['fax_show']) { ?>
        <tr sort="a<?php echo $modData['fax_sort']; ?>">
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" <?php if($title_fax) echo "xtitle ='".$title_fax."'";?> class="<?php echo ($modData['fax_numeric']?"numeric":""); ?>" name="fax" value="<?php echo $fax; ?>" /></td>
        </tr>
        <?php } ?>
         <?php if ($optionsP) { ?>
         
        
        <?php foreach ($optionsP as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
       <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <select <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'selected ="selected"' ; ?> value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>
            
            </option>
            <?php } ?>
          </select>
         <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
        
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <?php foreach ($option['option_value'] as $option_value) { ?>
          
          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		  <input type="radio" name="option<?php echo $option['option_id']; ?>" <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>
            
          </label>
        	<br/>
          <?php } ?>
          <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td>
        </tr>        
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <?php foreach ($option['option_value'] as $option_value) { ?>
         
          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		   <input type="checkbox" <?php if( ${"optionV_O" . $option['option_id']."C".$option_value['option_value_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> name="optionV<?php echo $option['option_id']; ?>C<?php echo $option_value['option_value_id']; ?>" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>
            
          </label>
        	<br/>
          <?php } ?>
         <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        <?php } ?>
        
        <?php if ($option['type'] == 'text') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <input type="text" <?php if($option['max'])echo "maxlength='".$option['max']."'";?> name="option<?php echo $option['option_id']; ?>"  <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> class="<?php echo $option['mask']; echo (($option['isnumeric'])?" numeric":"");?>" value="<?php echo ${"optionV" . $option['option_id']} ; ?>" />
        <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
       <tr sort="a<?php echo $option['sort_order']; ?>"> <td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <textarea <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>" cols="40" rows="5"><?php echo ${"optionV" . $option['option_id']} ; ?></textarea>
        <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        <?php } ?>        
        <?php if ($option['type'] == 'date') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <input type="text" <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> readonly="readonly" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>"  class="date" />
        <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        
        <?php } ?>
       
        <?php } ?>
      
      <?php } ?>
         <?php if (($isActive['enablemod'] && $isActive['single_box']) || ($isActive['enablemod'] && $modData['show_address']))
          { }else{?>
      </table>
    </div>
    <h2><?php echo $text_your_address; ?></h2>
    <div class="content">
      <table class="form xaddress">
        <?php } ?>
        <?php if(!$modData['show_address']){?> 
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['company_show']) { ?>
        <tr sort="a<?php echo $modData['company_sort']; ?>">
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" <?php if($title_company) echo "xtitle ='".$title_company."'";?> name="company" value="<?php echo $company; ?>" /></td>
        </tr>
        <?php } ?>
        
        <tr sort="a<?php echo $modData['cgroup_sort']; ?>" style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">
          <td><?php echo $entry_customer_group; ?></td>
          <td><?php foreach ($customer_groups as $customer_group) { ?>
            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
            <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
            <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
            <br />
            <?php } else { ?>
            <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
            <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
            <br />
            <?php } ?>
            <?php } ?></td>
        </tr> 
       
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['companyId_show']) { ?>
        <tr sort="a<?php echo $modData['companyId_sort']; ?>" id="company-id-display">
          <td><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?></td>
          <td><input type="text" <?php if($title_company_id) echo "xtitle ='".$title_company_id."'";?> class="<?php echo ($modData['companyId_numeric']?"numeric":""); ?>" name="company_id" value="<?php echo $company_id; ?>" />
            <?php if ($error_company_id) { ?>
            <span class="error"><?php echo $error_company_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>

        <tr sort="a<?php echo $modData['taxId_sort']; ?>" id="tax-id-display">
          <td><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?></td>
          <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
            <?php if ($error_tax_id) { ?>
            <span class="error"><?php echo $error_tax_id; ?></span>
            <?php } ?></td>
        </tr>
        
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_show']) { ?>
        <tr sort="a<?php echo $modData['address1_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_address_1; ?></td>
          <td><input type="text" <?php if($title_address_1) echo "xtitle ='".$title_address_1."'";?> name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address2_show']) { ?>
        <tr sort="a<?php echo $modData['address2_sort']; ?>">
          <td><?php echo $entry_address_2; ?></td>
          <td><input type="text" <?php if($title_address_2) echo "xtitle ='".$title_address_2."'";?> name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <?php } ?>
        <?php if($optionsA){  ?>
         
        
        <?php foreach ($optionsA as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
       <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <select <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'selected ="selected"' ; ?> value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>
            
            </option>
            <?php } ?>
          </select>
         <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
        
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <?php foreach ($option['option_value'] as $option_value) { ?>
          
          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		  <input type="radio" name="option<?php echo $option['option_id']; ?>" <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>
            
          </label>
        	<br/>
          <?php } ?>
          <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td>
        </tr>        
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <?php foreach ($option['option_value'] as $option_value) { ?>
         
          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		   <input type="checkbox" <?php if( ${"optionV_O" . $option['option_id']."C".$option_value['option_value_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> name="optionV<?php echo $option['option_id']; ?>C<?php echo $option_value['option_value_id']; ?>" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>
            
          </label>
        	<br/>
          <?php } ?>
         <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        <?php } ?>
        
        <?php if ($option['type'] == 'text') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <input type="text" <?php if($option['max'])echo "maxlength='".$option['max']."'";?> name="option<?php echo $option['option_id']; ?>"  <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> class="<?php echo $option['mask']; echo (($option['isnumeric'])?" numeric":"");?>" value="<?php echo ${"optionV" . $option['option_id']} ; ?>" />
        <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
       <tr sort="a<?php echo $option['sort_order']; ?>"> <td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <textarea <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>" cols="40" rows="5"><?php echo ${"optionV" . $option['option_id']} ; ?></textarea>
        <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        <?php } ?>        
        <?php if ($option['type'] == 'date') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <input type="text" <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> readonly="readonly" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>"  class="date" />
        <?php if (${"optionVE" . $option['option_id']}) { ?>
            <span class="error"><?php echo ${"optionVE" . $option['option_id']}; ?></span>
            <?php } ?></td></tr>
        
        <?php } ?>
       
        <?php } ?>
      
      <?php } ?> 
        
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_show']) { ?>
        <tr sort="a<?php echo $modData['city_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_city; ?></td>
          <td><input type="text" <?php if($title_city) echo "xtitle ='".$title_city."'";?>  name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_show']) { ?>
        <tr sort="a<?php echo $modData['pin_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_req'])  echo "<span id=postcode-required class=required>*</span>" ; ?> <?php echo $entry_postcode; ?></td>
          <td><input type="text" <?php if($title_postcode) echo "xtitle ='".$title_postcode."'";?> class="<?php echo ($modData['pin_numeric']?"numeric":"")." ".($modData['pin_masking']?"mask postcode":""); ?>" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show']) { ?>
        <tr sort="a<?php echo $modData['country_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_country; ?></td>
          <td><select  name="country_id">
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <?php } else {?>
        <input type ="hidden" name="country_id" value ="<?php echo $country_id;?>">
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_show'] ) { ?>
        <tr sort="a<?php echo $modData['state_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_zone; ?></td>
          <td><select  name="zone_id">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>
       <?php } else {?>
        <input type ="hidden" name="zone_id" value ="<?php echo $zone_id;?>">
        <?php }?>
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && !$isActive['single_box']) { ?>
      </table>
    </div>    
    <h2><?php echo $text_your_password; ?></h2>    
    <div class="content">        
      <table class="form xpassword">
         <?php } ?>
        <tr sort="a<?php echo $modData['pass_sort']; ?>">
          <td><span class = required >*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" <?php if($title_password) echo "xtitle ='".$title_password."'";?> name="password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        
         <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['passconf_show']) { ?>
        <tr sort="a<?php echo $modData['passconf_sort']; ?>">
          <td><span class = required >*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" <?php if($title_confirm) echo "xtitle ='".$title_confirm."'";?> name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
    <?php if (!$isActive['enablemod'] || (!$isActive['single_box'] && $modData['subsribe_show'] && $isActive['enablemod'] )) { ?>
    
      </table>
    </div>
    
    
    <h2><?php echo $text_newsletter; ?></h2>
    <div class="content">
      <table class="form">
        
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['subsribe_show']) { ?>
        <tr sort="a<?php echo $modData['subscribe_sort']; ?>">
          <td><?php echo $entry_newsletter; ?></td>
          <td><?php if ($newsletter) { ?>
            <input type="radio" name="newsletter" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="newsletter" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="newsletter" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <?php if ($text_agree) { ?>
    <div class="buttons">
      <div class="right"><?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
    <?php } else { ?>
    <div class="buttons">
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
    <?php } ?>
  </form>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('input[name=\'customer_group_id\']:checked').live('change', function() {
	var customer_group = [];
	
<?php foreach ($customer_groups as $customer_group) { ?>
	customer_group[<?php echo $customer_group['customer_group_id']; ?>] = [];
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_display'] = '<?php echo $customer_group['company_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['company_id_required'] = '<?php echo $customer_group['company_id_required']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_display'] = '<?php echo $customer_group['tax_id_display']; ?>';
	customer_group[<?php echo $customer_group['customer_group_id']; ?>]['tax_id_required'] = '<?php echo $customer_group['tax_id_required']; ?>';
<?php } ?>	

	if (customer_group[this.value]) {
		if (customer_group[this.value]['company_id_display'] == '1') {
			$('#company-id-display').show();
		} else {
			$('#company-id-display').hide();
		}
		
		if (customer_group[this.value]['company_id_required'] == '1') {
			$('#company-id-required').show();
		} else {
			$('#company-id-required').hide();
		}
		
		if (customer_group[this.value]['tax_id_display'] == '1') {
			$('#tax-id-display').show();
		} else {
			$('#tax-id-display').hide();
		}
		
		if (customer_group[this.value]['tax_id_required'] == '1') {
			$('#tax-id-required').show();
		} else {
			$('#tax-id-required').hide();
		}	
	}
});

$('input[name=\'customer_group_id\']:checked').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('<?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show'])?'select':'input');?>[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/register/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('<?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show'])?'select':'input');?>[name=\'country_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});



//--></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/xcustom.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.timers.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.dropshadow.js"></script>     
  <script type="text/javascript" src="catalog/view/javascript/mbTooltip.js"></script> 
  <link rel="stylesheet" type="text/css" href="catalog/view/javascript/mbTooltip.css" media="screen">
<?php echo $footer; ?>