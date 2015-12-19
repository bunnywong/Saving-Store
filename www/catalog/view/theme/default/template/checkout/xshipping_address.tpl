<?php if ($addresses) { ?>
 <div class="xleft">
     <div class="xten">
        <input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
        <label for="shipping-address-existing">
          <?php echo $text_address_existing; ?>
        </label>
     </div>
</div>
<div class="xright">
    <div class="xten">
        <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
        <label for="shipping-address-new">
          <?php echo $text_address_new; ?><br>
           (只接受香港地區用户訂購)
        </label>
    </div>
</div>
<div class="xleft"><div id="shipping-existing">
<table class="xten" width="100%">
  <?php foreach ($addresses as $address) { ?>
  <tr >
  <?php if ($address['address_id'] == $address_id) { ?>
  	<td>
  		<input id="s-<?php echo $address['address_id']; ?>" class="radio_shipping_address" type="radio" name="saddress_id" value="<?php echo $address['address_id']; ?>" checked="checked"/>
  		<label for="s-<?php echo $address['address_id']; ?>">
  			<?php echo $address['address']; ?>
  		</label>
  	</td>
  <?php }else{?>
  	<td>
  		<input id="s-<?php echo $address['address_id']; ?>" class="radio_shipping_address"  type="radio" name="saddress_id" value="<?php echo $address['address_id']; ?>" />
  		<label for="s-<?php echo $address['address_id']; ?>">
  			<?php echo $address['address']; ?>
  		</label>
  	</td>
  <?php }?>
  </tr>
<?php }?>
</table>
</div></div>
<?php } ?>
<div class="xleft" id="shipping-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
  <table class="form xaddress1">
     <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['f_name_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_req_checkout']) echo '<span class="required">*</span>';  ?>
    <?php echo $entry_firstname; ?></td>
    <td><input type="text" <?php if($title_firstname) echo "title ='".$title_firstname."'";?> name="firstname" value="<?php echo $cfname; ?>" class="large-field" /></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="firstname" value="<?php echo $cfname; ?>" class="large-field" />
    <?php } ?>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['l_name_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_req_checkout']) echo '<span class="required">*</span>';  ?>
    <?php echo $entry_lastname; ?></td>
    <td><input type="text" <?php if($title_lastname) echo "title ='".$title_lastname."'";?> name="lastname" value="<?php echo $clname; ?>" class="large-field" /></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="lastname" value="<?php echo $clname; ?>" class="large-field" />
    <?php } ?>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['company_show']){ ?>
    <tr sort="a<?php echo $modData['company_sort']; ?>">
      <td><?php echo $entry_company; ?></td>
      <td><input type="text" <?php if($title_company) echo "title ='".$title_company."'";?> name="company" value="" class="large-field" /></td>
    </tr>
    <?php } else { ?>
    <input type="hidden" name="company" value="" class="large-field" />
    <?php } ?>

    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['address1_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_req_checkout']) echo '<span class="required">*</span>';  ?>
    <?php echo $entry_address_1; ?></td>
    <td><input type="text" <?php if($title_address_1) echo "title ='".$title_address_1."'";?> name="address_1" value="" class="large-field" /></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="address_1" value="" class="large-field" />
    <?php } ?>
     <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address2_show']){ ?>
    <tr sort="a<?php echo $modData['address2_sort']; ?>">
      <td><?php echo $entry_address_2; ?></td>
      <td><input type="text" <?php if($title_address_2) echo "title ='".$title_address_2."'";?> name="address_2" value="" class="large-field" /></td>
    </tr>
    <?php } else { ?>
    <input type="hidden" name="address_2" value="" class="large-field" />
    <?php } ?>
     <?php if ($options) { ?>


        <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
       <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <select <?php if($option['tips'])echo "title='".$option['tips']."'"?> class="large-field" name="option<?php echo $option['option_id']; ?>">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'selected ="selected"' ; ?> value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?>

            </option>
            <?php } ?>
          </select>
         <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/></td></tr>

        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <label <?php if($option['tips'])echo "title='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		  <input type="radio" name="option<?php echo $option['option_id']; ?>" <?php if( ${"optionV" . $option['option_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>
          </label>
          <br/>
          <?php } ?>
        <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/></td></tr>

        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <?php foreach ($option['option_value'] as $option_value) { ?>

          <label <?php if($option['tips'])echo "title='".$option['tips']."'"?> for="option-value-<?php echo $option_value['option_value_id']; ?>">
		  <input type="checkbox" <?php if( ${"optionV_O" . $option['option_id']."C".$option_value['option_value_id']} ==$option_value['option_value_id']) echo 'checked ="checked"' ; ?> name="optionV<?php echo $option['option_id']; ?>C<?php echo $option_value['option_value_id']; ?>" value="<?php echo $option_value['option_value_id']; ?>" id="option-value-<?php echo $option_value['option_value_id']; ?>" />
		  <?php echo $option_value['name']; ?>

          </label>
        	<br/>
          <?php } ?>
         <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/></td></tr>
        <?php } ?>
       <?php if ($option['type'] == 'text') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <input type="text" <?php if($option['tips'])echo "title='".$option['tips']."'"?> <?php if($option['max'])echo "maxlength='".$option['max']."'";?> class="large-field <?php echo $option['mask']; echo (($option['isnumeric'])?" numeric":"");?>" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>" />
       <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/></td></tr>
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
       <tr sort="a<?php echo $option['sort_order']; ?>"> <td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <textarea <?php if($option['tips'])echo "title='".$option['tips']."'"?> name="option<?php echo $option['option_id']; ?>" cols="40" rows="5"><?php echo ${"optionV" . $option['option_id']} ; ?></textarea>
        <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/></td></tr>
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
        <tr sort="a<?php echo $option['sort_order']; ?>"><td>
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <?php echo $option['name']; ?>:</td><td>
          <input type="text" <?php if($option['tips'])echo "title='".$option['tips']."'"?> readonly="readonly" name="option<?php echo $option['option_id']; ?>"  value="<?php echo ${"optionV" . $option['option_id']} ; ?>"  class="date large-field" />
       <input type="hidden" name ="optionVE<?php echo $option['option_id']; ?>"/></td></tr>

        <?php } ?>

        <?php } ?>
        <?php } ?>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['city_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_req_checkout']) echo '<span class="required">*</span>';  ?>
    <?php echo $entry_city; ?></td>
    <td><input type="text" <?php if($title_city) echo "title ='".$title_city."'";?> name="city" value="" class="large-field" /></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="city" value="" class="large-field" />
    <?php } ?>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['pin_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_req_checkout']) echo '<span class="required">*</span>';  ?>
    <?php echo $entry_postcode; ?></td>
    <td><input type="text" <?php if($title_postcode) echo "title ='".$title_postcode."'";?> name="postcode" value="" class="large-field <?php echo ($modData['pin_numeric']?"numeric":""); ?> <?php echo ($modData['pin_masking']?"mask postcode":""); ?>" /></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="postcode" value="" class="large-field" />
    <?php } ?>

  <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['country_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_req_checkout']) echo '<span class="required">*</span>';  ?>
    <?php echo $entry_country; ?></td>
    <td><select  name="country_id" class="large-field" disabled>
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $country_id) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="country_id" value="<?php echo $country_id; ?>" class="large-field" />
    <?php } ?>
  <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_show_checkout']){ ?>
  <tr sort="a<?php echo $modData['state_sort']; ?>">
    <td>
    <?php if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_req_checkout']) echo '<span class="required">*</span>';  ?>
     <?php echo $entry_zone; ?></td>
    <td><select  name="zone_id" class="large-field">
      </select></td>
  </tr>
  <?php } else { ?>
    <input type="hidden" name="zone_id" value="<?php echo $zone_id; ?>" class="large-field" />
    <?php } ?>
  </table>
</div>
<br />
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-address" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('#shipping-address input[name=\'shipping_address\']').live('change', function() {
	if (this.value == 'new') {
		$('#shipping-existing').hide();
		$('#shipping-new').show();
	} else {
		$('#shipping-existing').show();
		$('#shipping-new').hide();
	}
});
//--></script>
<script type="text/javascript"><!--
$('#shipping-address <?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show_checkout'])?'select':'input');?>[name=\'country_id\']').bind('change', function() {
	if (this.value == '') return;
	$.ajax({
		url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#shipping-postcode-required').show();
			} else {
				$('#shipping-postcode-required').hide();
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
            var str = json['zone'][i]['name'];
	    			html += '>' + str.substr(5) + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#shipping-address select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#shipping-address <?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show_checkout'])?'select':'input');?>[name=\'country_id\']').trigger('change');
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