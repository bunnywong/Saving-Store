<div class="left">
  <h2><?php echo $text_your_details; ?></h2>
  <div class="xpersonal">
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_show']) { ?>
  <xdiv sort="a<?php echo $modData['f_name_sort']; ?>" >
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_firstname; ?><br />
  <input type="text" <?php if($title_firstname) echo "xtitle ='".$title_firstname."'";?> name="firstname" value="" class="large-field" />
  <br />
  <br />
  </xdiv>
  <?php } ?>
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_show']) { ?>
  <xdiv sort="a<?php echo $modData['l_name_sort']; ?>" >
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_lastname; ?><br />
  <input type="text" <?php if($title_lastname) echo "xtitle ='".$title_lastname."'";?> name="lastname" value="" class="large-field" />
  <br />
  <br />
  </xdiv>
  <?php } ?>
  <xdiv sort="a<?php echo $modData['email_sort']; ?>" >
  <span class="required">*</span> <?php echo $entry_email; ?><br />
  <input type="text" <?php if($title_email) echo "xtitle ='".$title_email."'";?> name="email" value="" class="large-field" />
  <br />
  <br />
  </xdiv>
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['mob_show']) { ?>
  <xdiv sort="a<?php echo $modData['mob_sort']; ?>" >
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['mob_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_telephone; ?><br />
  <input type="text" <?php if($title_telephone) echo "xtitle ='".$title_telephone."'";?> name="telephone" value="" class="large-field <?php echo ($modData['mob_numeric']?"numeric":""); ?> <?php echo ($modData['mob_masking']?"mask telephone":""); ?>" />
  <br />
  <br />
  </xdiv>
  <?php } ?>
  <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['fax_show']){ ?>
  <xdiv sort="a<?php echo $modData['fax_sort']; ?>" >
  <?php echo $entry_fax; ?><br />
  <input type="text" <?php if($title_fax) echo "xtitle ='".$title_fax."'";?> name="fax" value="" class="large-field <?php echo ($modData['fax_numeric']?"numeric":""); ?>" />
  <br />
  <br />
  </xdiv>
  <?php } ?>
   <?php if ($optionsP) { ?>


        <?php foreach ($optionsP as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
       	<xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <select <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'selected ="selected"' ; ?> value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>

            </option>
            <?php } ?>
          </select>
          <br/>
          <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
       <br />
       </xdiv>
        <?php } ?>

        <?php if ($option['type'] == 'radio') { ?>
       <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <?php foreach ($option['option_value'] as $option_value) { ?>

          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		  <input type="radio" name="option<?php echo $option['option_id']; ?>" <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>

          </label>
        	<br/>
          <?php } ?>
         <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
        <br />
         </xdiv>
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
          <input type="checkbox" <?php if( ${"optionV_O" . $option['option_id']."C".$option_value['option_value_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> name="optionV<?php echo $option['option_id']; ?>C<?php echo $option_value['option_value_id']; ?>" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
          <?php echo $option_value['name']; ?></label>
        	<br/>
          <?php } ?>
          <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
         <br />
         </xdiv>
        <?php } ?>

        <?php if ($option['type'] == 'text') { ?>
       <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <input type="text" <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> <?php if($option['max'])echo "maxlength='".$option['max']."'";?> class="large-field <?php echo $option['mask']; echo (($option['isnumeric'])?" numeric":"");?>" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>" />
       <br/>
        <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
       <br />
       </xdiv>
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
     	<xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <textarea <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>" cols="40" rows="5"><?php echo ${"optionV" . $option['option_id']} ; ?></textarea>
          <br />
        <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
       <br />
       </xdiv>
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
       <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <input type="text" <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> readonly="readonly" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>"  class="date" />
       <br/>
       <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
        <br />
         </xdiv>
        <?php } ?>

        <?php } ?>

      <?php } ?>
      </div>
   <div class="xpassword">
  <h2><?php echo $text_your_password; ?></h2>
  <xdiv sort="a<?php echo $modData['pass_sort']; ?>" >
  <span class="required">*</span> <?php echo $entry_password; ?><br />
  <input type="password" <?php if($title_password) echo "xtitle ='".$title_password."'";?> name="password" value="" class="large-field" />
  <br />
  <br />
  </xdiv>
   <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['passconf_show']) { ?>
   <xdiv sort="a<?php echo $modData['passconf_sort']; ?>" >
  <span class="required">*</span> <?php echo $entry_confirm; ?> <br />
  <input type="password" <?php if($title_confirm) echo "xtitle ='".$title_confirm."'";?>  name="confirm" value="" class="large-field" />
  </xdiv>
  <?php } ?>
  <br />
  <br />
  <br />
  </div>
</div>

 <?php if (!$isActive['enablemod'] || ($isActive['enablemod'] && !$modData['show_address'])
         ) { ?>
<div class="right">
   <div class="xaddress" >
  <h2><?php echo $text_your_address; ?></h2>

 <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['company_show']) { ?>
 <xdiv sort="a<?php echo $modData['company_sort']; ?>" >
  <?php echo $entry_company; ?><br />
  <input type="text" <?php if($title_company) echo "xtitle ='".$title_company."'";?> name="company" value="" class="large-field" />
  <br />
  <br />
  </xdiv>
  <?php }  ?>
  <xdiv sort="a<?php echo $modData['cgroup_sort']; ?>" >
  <div style="display: <?php echo (count($customer_groups) > 1 ? 'table-row' : 'none'); ?>;">

  <?php echo $entry_customer_group; ?><br />
  <?php foreach ($customer_groups as $customer_group) { ?>
  <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
  <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
  <br />
  <?php } else { ?>
  <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" id="customer_group_id<?php echo $customer_group['customer_group_id']; ?>" />
  <label for="customer_group_id<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></label>
  <br />
  <?php } ?>
  <?php } ?>
  <br />

</div>
</xdiv>
  <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['companyId_show']){ ?>
  <xdiv sort="a<?php echo $modData['companyId_sort']; ?>" >
<div id="company-id-display"><span id="company-id-required" class="required">*</span> <?php echo $entry_company_id; ?><br />
  <input type="text" <?php if($title_company_id) echo "xtitle ='".$title_company_id."'";?> name="company_id" value="" class="large-field <?php echo ($modData['companyId_numeric']?"numeric":""); ?>" />
  <br />
  <br />
</div>
</xdiv>
  <?php }  ?>
  <xdiv sort="a<?php echo $modData['taxId_sort']; ?>" >
<div id="tax-id-display"><span id="tax-id-required" class="required">*</span> <?php echo $entry_tax_id; ?><br />
  <input type="text" name="tax_id" value="" class="large-field" />
  <br />
  <br />
</div>
</xdiv>
  <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_show']) { ?>
  <xdiv sort="a<?php echo $modData['address1_sort']; ?>" >
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_address_1; ?><br />
<input type="text" <?php if($title_address_1) echo "xtitle ='".$title_address_1."'";?> name="address_1" value="" class="large-field" />
<br />
<br />
</xdiv>
<?php } ?>
<?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address2_show']){ ?>
<xdiv sort="a<?php echo $modData['address2_sort']; ?>" >
<?php echo $entry_address_2; ?><br />
<input type="text" <?php if($title_address_2) echo "xtitle ='".$title_address_2."'";?> name="address_2" value="" class="large-field" />
<br />
<br />
</xdiv>
<?php }  ?>
<?php if($optionsA) {  ?>


        <?php foreach ($optionsA as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
       	<xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <select <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'selected ="selected"' ; ?> value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>

            </option>
            <?php } ?>
          </select>
          <br/>
          <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
       <br />
       </xdiv>
        <?php } ?>

        <?php if ($option['type'] == 'radio') { ?>
       <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <?php foreach ($option['option_value'] as $option_value) { ?>

          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		  <input type="radio" name="option<?php echo $option['option_id']; ?>" <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>

          </label>
        	<br/>
          <?php } ?>
         <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
        <br />
         </xdiv>
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <label <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
          <input type="checkbox" <?php if( ${"optionV_O" . $option['option_id']."C".$option_value['option_value_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> name="optionV<?php echo $option['option_id']; ?>C<?php echo $option_value['option_value_id']; ?>" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
          <?php echo $option_value['name']; ?></label>
        	<br/>
          <?php } ?>
          <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
         <br />
         </xdiv>
        <?php } ?>

        <?php if ($option['type'] == 'text') { ?>
       <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <input type="text" <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> <?php if($option['max'])echo "maxlength='".$option['max']."'";?> class="large-field <?php echo $option['mask']; echo (($option['isnumeric'])?" numeric":"");?>" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>" />
       <br/>
        <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
       <br />
       </xdiv>
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
     	<xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <textarea <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>" cols="40" rows="5"><?php echo ${"optionV" . $option['option_id']} ; ?></textarea>
          <br />
        <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
       <br />
       </xdiv>
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
       <xdiv sort="a<?php echo $option['sort_order']; ?>" >
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:<br />
          <input type="text" <?php if($option['tips'])echo "xtitle='".$option['tips']."'"?> readonly="readonly" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>"  class="date" />
       <br/>
       <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/>
        <br />
         </xdiv>
        <?php } ?>

        <?php } ?>

      <?php } ?>
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_show']) { ?>
<xdiv sort="a<?php echo $modData['city_sort']; ?>" >
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_city; ?><br />
<input type="text" <?php if($title_city) echo "xtitle ='".$title_city."'";?> name="city" value="" class="large-field" />
<br />
<br />
</xdiv>
<?php } ?>
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_show']) { ?>
<xdiv sort="a<?php echo $modData['pin_sort']; ?>" >
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_postcode; ?><br />
<input type="text" <?php if($title_postcode) echo "xtitle ='".$title_postcode."'";?> name="postcode" value="<?php echo $postcode; ?>" class="large-field <?php echo ($modData['pin_numeric']?"numeric":""); ?> <?php echo ($modData['pin_masking']?"mask postcode":""); ?>" />
<br />
<br />
</xdiv>
<?php } ?>
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show']) { ?>
<xdiv sort="a<?php echo $modData['country_sort']; ?>" >
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_country; ?><br />
<select  name="country_id" class="large-field">
  <option value=""><?php echo $text_select; ?></option>
  <?php foreach ($countries as $country) { ?>
  <?php if ($country['country_id'] == $country_id) { ?>
  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
  <?php } else { ?>
  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
  <?php } ?>
  <?php } ?>
</select>
<br />
<br />
</xdiv>
<?php }else{ ?>
<input name="country_id" type="hidden" value="<?php echo $country_id;?>" />
<?php }?>
<?php if (!$isActive['enablemod'] || $isActive['enablemod']  && $modData['state_show']) { ?>
<xdiv sort="a<?php echo $modData['state_sort']; ?>" >
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_req'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_zone; ?><br />
<select  name="zone_id" class="large-field">
</select>
<br />
<br />
<br />
</xdiv>
<?php }else{ ?>
<input name="zone_id" type="hidden" value="<?php echo $zone_id;?>" />
<?php }?>

</div>
</div>
 <?php }  ?>



<div style="clear: both; padding-top: 15px; border-top: 1px solid #EEEEEE;">
<?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['subsribe_show'] ) { ?>
  <input type="checkbox" name="newsletter" value="1" id="newsletter" />
  <label for="newsletter"><?php echo $entry_newsletter; ?></label>
  <br />
  <?php }?>

  <?php if (!$isActive['enablemod'] || ($isActive['enablemod'] && !$modData['show_address'])
         ) { ?>

  <?php if ($shipping_required) { ?>
  <input type="checkbox" name="shipping_address" value="1" id="shipping" checked="checked" />
  <label for="shipping"><?php echo $entry_shipping; ?></label>
  <br />
  <?php } ?>
  <?php } ?>
  <?php if (!$isActive['enablemod'] || ($isActive['enablemod']  && $modData['subsribe_show']) || ($isActive['enablemod']
            && $modData['address1_show'] &&
          $modData['city_show'] && $modData['state_show'] && $modData['country_show'] && $shipping_required)
         ) { ?>
  <br />
  <br />
</div>
<?php }?>
<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="right"><?php echo $text_agree; ?>
    <input type="checkbox" name="agree" value="1" />
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="button" />
  </div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-register" class="button" />
  </div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$('#payment-address input[name=\'customer_group_id\']:checked').live('change', function() {
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

$('#payment-address input[name=\'customer_group_id\']:checked').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('#payment-address <?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show'])?'select':'input');?>[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#payment-postcode-required').show();
			} else {
				$('#payment-postcode-required').hide();
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

			$('#payment-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#payment-address <?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show'])?'select':'input');?>[name=\'country_id\']').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	width: 640,
	height: 480
});
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.timers.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.dropshadow.js"></script>
<!--
  <script type="text/javascript" src="catalog/view/javascript/mbTooltip.js"></script>
  <link rel="stylesheet" type="text/css" href="catalog/view/javascript/mbTooltip.css" media="screen">
 -->
 <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="catalog/view/javascript/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/xcustom.js"></script>