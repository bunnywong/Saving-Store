<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_edit_address; ?></h2>
    <div class="content">
      <table class="form xaddress">
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_show_edit']) { ?>
        <tr sort="a<?php echo $modData['f_name_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['f_name_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_firstname; ?></td>
          <td><input type="text" <?php if($title_firstname) echo "xtitle ='".$title_firstname."'";?> name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="firstname" value="<?php echo $firstname; ?>" />
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_show_edit']) { ?>
        <tr sort="a<?php echo $modData['l_name_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['l_name_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_lastname; ?></td>
          <td><input type="text" <?php if($title_lastname) echo "xtitle ='".$title_lastname."'";?> name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="lastname" value="<?php echo $lastname; ?>" />
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['company_show_edit']) { ?>
        <tr sort="a<?php echo $modData['company_sort']; ?>">
          <td><?php echo $entry_company; ?></td>
          <td><input type="text" <?php if($title_company) echo "xtitle ='".$title_company."'";?> name="company" value="<?php echo $company; ?>" /></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="company" value="<?php echo $company; ?>" />
        <?php }?>
         
         <?php if ($company_id_display) { ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['companyId_show_edit']) { ?>
        <tr sort="a<?php echo $modData['companyId_sort']; ?>">
          <td><?php echo $entry_company_id; ?></td>
          <td><input type="text" <?php if($title_company_id) echo "xtitle ='".$title_company_id."'";?> name="company_id" value="<?php echo $company_id; ?>" class="<?php echo ($modData['companyId_numeric']?"numeric":""); ?>" autocomplete="off"/>
            <?php if ($error_company_id) { ?>
            <span class="error"><?php echo $error_company_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="company_id" value="<?php echo $company_id; ?>" />
        <?php }?>
        <?php } ?>
        <?php if ($tax_id_display) { ?>
        <tr sort="a<?php echo $modData['taxId_sort']; ?>">
          <td><?php echo $entry_tax_id; ?></td>
          <td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
            <?php if ($error_tax_id) { ?>
            <span class="error"><?php echo $error_tax_id; ?></span>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_show_edit']) { ?>
        <tr sort="a<?php echo $modData['address1_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address1_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_address_1; ?></td>
          <td><input type="text" <?php if($title_address_1) echo "xtitle ='".$title_address_1."'";?> name="address_1" value="<?php echo $address_1; ?>" />
            <?php if ($error_address_1) { ?>
            <span class="error"><?php echo $error_address_1; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="address_1" value="<?php echo $address_1; ?>" />
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['address2_show_edit']) { ?>
        <tr sort="a<?php echo $modData['address2_sort']; ?>">
          <td><?php  echo $entry_address_2; ?></td>
          <td><input type="text"  <?php if($title_address_2) echo "xtitle ='".$title_address_2."'";?> name="address_2" value="<?php echo $address_2; ?>" /></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="address_2" value="<?php echo $address_2; ?>" />
        <?php }?>
         <?php if ($options) { ?>
         
        
        <?php foreach ($options as $option) { ?>
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
       <tr sort="a<?php echo $option['sort_order']; ?>"><td>
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
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_show_edit']) { ?>
        <tr sort="a<?php echo $modData['city_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['city_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_city; ?></td>
          <td><input type="text" <?php if($title_city) echo "xtitle ='".$title_city."'";?> name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="city" value="<?php echo $city; ?>" />
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_show_edit']) { ?>
        <tr sort="a<?php echo $modData['pin_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['pin_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_postcode; ?></td>
          <td><input type="text" <?php if($title_postcode) echo "xtitle ='".$title_postcode."'";?> name="postcode" value="<?php echo $postcode; ?>" class="<?php echo ($modData['pin_numeric']?"numeric":""); ?> <?php echo ($modData['pin_masking']?"mask postcode":""); ?>"/>
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="postcode" value="<?php echo $postcode; ?>" />
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show_edit']) { ?>
        <tr sort="a<?php echo $modData['country_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_country; ?></td>
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
        <?php }else{?>
        <input type="hidden" name="country_id" value="<?php echo $country_id; ?>" />
        <?php }?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_show_edit']) { ?>
        <tr sort="a<?php echo $modData['state_sort']; ?>">
          <td><?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['state_req_edit'])  echo "<span class = required >*</span>" ; ?> <?php echo $entry_zone; ?></td>
          <td><select  name="zone_id">
            </select>
            <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?></td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="zone_id" value="<?php echo $zone_id; ?>" />
        <?php }?>
        <tr sort="zzz99">
          <td><?php echo $entry_default; ?></td>
          <td><?php if ($default) { ?>
            <input type="radio" name="default" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="default" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="default" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('<?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show_edit'])?'select':'input');?>[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/address/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			
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

$('<?php ((!$isActive['enablemod'] || $isActive['enablemod'] && $modData['country_show_edit'])?'select':'input');?>[name=\'country_id\']').trigger('change');
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/xcustom.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.timers.js"></script> 
<script type="text/javascript" src="catalog/view/javascript/jquery.dropshadow.js"></script> 
  <script type="text/javascript" src="catalog/view/javascript/mbTooltip.js"></script> 
  <link rel="stylesheet" type="text/css" href="catalog/view/javascript/mbTooltip.css" media="screen">
<?php echo $footer; ?>