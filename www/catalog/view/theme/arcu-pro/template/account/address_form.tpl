<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
			<h2><?php echo $text_edit_address; ?></h2>
			<div class="content">
				<table class="form">
					<tr>
						<td><?php echo $entry_firstname; ?><span class="required">*</span></td>
						<td><input type="text" name="firstname" value="<?php echo $firstname; ?>" /></td>
					</tr>
					<?php if ($error_firstname) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_firstname; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_lastname; ?><span class="required">*</span></td>
						<td><input type="text" name="lastname" value="<?php echo $lastname; ?>" /></td>
					</tr>
					<?php if ($error_lastname) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_lastname; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_company; ?></td>
						<td><input type="text" name="company" value="<?php echo $company; ?>" /></td>
					</tr>
					<?php if ($company_id_display) { ?>
					<tr>
						<td><?php echo $entry_company_id; ?></td>
						<td><input type="text" name="company_id" value="<?php echo $company_id; ?>" /></td>
					</tr>
					<?php if ($error_company_id) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_company_id; ?></span></td>
					</tr>
					<?php } ?>
					<?php } ?>
					<?php if ($tax_id_display) { ?>
					<tr>
						<td><?php echo $entry_tax_id; ?></td>
						<td><input type="text" name="tax_id" value="<?php echo $tax_id; ?>" /></td>
					</tr>
					<?php if ($error_tax_id) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_tax_id; ?></span></td>
					</tr>
					<?php } ?>
					<?php } ?>
					<tr>
						<td><?php echo $entry_address_1; ?><span class="required">*</span></td>
						<td><input type="text" name="address_1" value="<?php echo $address_1; ?>" /></td>
					</tr>
					<?php if ($error_address_1) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_address_1; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_address_2; ?></td>
						<td><input type="text" name="address_2" value="<?php echo $address_2; ?>" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_city; ?><span class="required">*</span></td>
						<td><input type="text" name="city" value="<?php echo $city; ?>" /></td>
					</tr>
					<?php if ($error_city) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_city; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_postcode; ?><span id="postcode-required" class="required">*</span></td>
						<td><input type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
					</tr>
					<?php if ($error_postcode) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_postcode; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_country; ?><span class="required">*</span></td>
						<td><select name="country_id">
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
					<?php if ($error_country) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_country; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_zone; ?><span class="required">*</span></td>
						<td><select name="zone_id">
							</select></td>
					</tr>
					<?php if ($error_zone) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_zone; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_default; ?></td>
						<td><?php if ($default) { ?>
							<input type="radio" name="default" value="1" checked="checked" />
							<?php echo $text_yes; ?>&nbsp;&nbsp;&nbsp;
							<input type="radio" name="default" value="0" />
							<?php echo $text_no; ?>
							<?php } else { ?>
							<input type="radio" name="default" value="1" />
							<?php echo $text_yes; ?>&nbsp;&nbsp;&nbsp;
							<input type="radio" name="default" value="0" checked="checked" />
							<?php echo $text_no; ?>
							<?php } ?></td>
					</tr>
				</table>
			</div>
			<div class="buttons">
				<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
				<div class="right">
					<button type="submit" class="button"><?php echo $button_continue; ?></button>
				</div>
			</div>
		</form>
	</div>
	<?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/address/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').before('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
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

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<?php echo $footer; ?>