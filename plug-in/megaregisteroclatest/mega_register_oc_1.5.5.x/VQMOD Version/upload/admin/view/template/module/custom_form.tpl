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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/information.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">      
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_description[$language['language_id']]) ? $option_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td> <span class="required">*</span> <?php echo $entry_option_identifier; ?></td>
            <td>
              <input type="text" name="identifier" title="<?php echo $entry_identifier; ?>" value="<?php echo $identifier;?>" />
              <?php if (isset($error_identifier)) { ?>
              <span class="error"><?php echo $error_identifier; ?></span>
              <?php } ?>           
             </td>
          </tr>
          <tr>
            <td> <?php echo $entry_error_message; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" size="120" name="option_msg[<?php echo $language['language_id']; ?>][error]" value="<?php echo isset($option_msg[$language['language_id']]) ? $option_msg[$language['language_id']]['error'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_msg[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_msg[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td> <?php echo $entry_tips; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" size="120" name="tips_msg[<?php echo $language['language_id']; ?>][tips]" value="<?php echo isset($tips_msg[$language['language_id']]) ? $tips_msg[$language['language_id']]['tips'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />

              <?php } ?></td>
          </tr>           
        </table>        
        <table  class="list">
          <thead >
          	<tr >
              <td class="center" colspan="9" style="background-color: lightblue;"><?php echo $entry_more_attributes; ?></td>
              <td class="center" colspan="4" style="background-color: pink;"><?php echo $entry_visibility; ?></td>
            </tr>  
            <tr >
              <td class="left"><?php echo $entry_isenable; ?></td>
              <td class="left"><?php echo $entry_sort_order; ?></td>
              <td class="left"><?php echo $entry_type; ?></td>
              <td class="left"><?php echo $entry_section; ?></td>              
              <td class="left"><?php echo $entry_mask; ?></td>              
              <td class="left"><?php echo $entry_numeric; ?></td>
              <td class="left"><?php echo $entry_required; ?></td>
              <td class="left"><?php echo $entry_min_length; ?></td>
              <td class="left"><?php echo $entry_max_length; ?></td>
              <td class="left"><?php echo $entry_invoice; ?></td>
              <td class="left"><?php echo $entry_email_vis; ?></td>
              <td class="left"><?php echo $entry_order_info_vis; ?></td>              
              <td class="left"><?php echo $entry_address_list_vis; ?></td>
            </tr>
            </thead>
            <tr >
              <td class="center"><input type="checkbox" name="isenable" value="1"  <?php  if($isenable)   echo  'checked="checked"' ; ?> /></td>
              <td class="left"><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
              <td class="left"><select name="type">
                <optgroup label="<?php echo $text_choose; ?>">
                <?php if ($type == 'select') { ?>
                <option value="select" selected><?php echo $text_select; ?></option>
                <?php } else { ?>
                <option value="select"><?php echo $text_select; ?></option>
                <?php } ?>
                <?php if ($type == 'radio') { ?>
                <option value="radio" selected><?php echo $text_radio; ?></option>
                <?php } else { ?>
                <option value="radio"><?php echo $text_radio; ?></option>
                <?php } ?>
                <?php if ($type == 'checkbox') { ?>
                <option value="checkbox" selected><?php echo $text_checkbox; ?></option>
                <?php } else { ?>
                <option value="checkbox"><?php echo $text_checkbox; ?></option>
                <?php } ?>
                
                </optgroup>
                <optgroup label="<?php echo $text_input; ?>">
                <?php if ($type == 'text') { ?>
                <option value="text" selected><?php echo $text_text; ?></option>
                <?php } else { ?>
                <option value="text"><?php echo $text_text; ?></option>
                <?php } ?>
                <?php if ($type == 'textarea') { ?>
                <option value="textarea" selected><?php echo $text_textarea; ?></option>
                <?php } else { ?>
                <option value="textarea"><?php echo $text_textarea; ?></option>
                <?php } ?>
                </optgroup>                
                <optgroup label="<?php echo $text_date; ?>">
                <?php if ($type == 'date') { ?>
                <option value="date" selected><?php echo $text_date; ?></option>
                <?php } else { ?>
                <option value="date"><?php echo $text_date; ?></option>
                <?php } ?>
                
                </optgroup>
              </select></td>
              <td class="left"><select name="section">                
                <?php if ($section == '1') { ?>
                <option value="1" selected>Personal Detail Field</option>
                <?php } else { ?>
                <option value="1">Personal Detail Field</option>
                <?php } ?>
                <?php if ($section == '2') { ?>
                <option value="2" selected>Address Field</option>
                <?php } else { ?>
                <option value="2">Address Field</option>
                <?php } ?>                
              </select></td>              
              <td class="left"><select name="mask">
                
                <?php if ($mask == '') { ?>
                <option value="" selected>None</option>
                <?php } else { ?>
                <option value="">None</option>
                <?php } ?>
                <?php if ($mask == 'mask mobile') { ?>
                <option value="mask mobile" selected>Mobile</option>
                <?php } else { ?>
                <option value="mask mobile">Mobile</option>
                <?php } ?>
                <?php if ($mask == 'mask telephone') { ?>
                <option value="mask telephone" selected>Telephone</option>
                <?php } else { ?>
                <option value="mask telephone">Telephone</option>
                <?php } ?>
                 <?php if ($mask == 'mask cep') { ?>
                <option value="mask cep" selected>CEP</option>
                <?php } else { ?>
                <option value="mask cep">CEP</option>
                <?php } ?>            
                 <?php if ($mask == 'mask cpf') { ?>
                <option value="mask cpf" selected>CPF</option>
                <?php } else { ?>
                <option value="mask cpf">CPF</option>
                <?php } ?>
                <?php if ($mask == 'mask cnpj') { ?>
                <option value="mask cnpj" selected>CNPJ</option>
                <?php } else { ?>
                <option value="mask cnpj">CNPJ</option>
                <?php } ?>               
              </select></td>              
              <td class="left"><input type="checkbox" name="isnumeric" value="1"  <?php  if($isnumeric)   echo  'checked="checked"' ; ?> /></td>
              <td class="left"><input type="checkbox" name="required" value="1"  <?php  if($required)   echo  'checked="checked"' ; ?> /></td>
              <td class="left"><input type="text" name="min" size="3" value="<?php echo $min; ?>" /></td>
              <td class="left"><input type="text" name="max" size="3" value="<?php echo $max; ?>" /></td>
              
              <td class="center"><input type="checkbox" name="invoice" value="1" <?php  if($invoice)   echo  'checked="checked"' ; ?> /></td>
              <td class="center"><input type="checkbox" name="email_display" value="1" <?php  if($email_display)   echo  'checked="checked"' ; ?> /></td>
              <td class="center"><input type="checkbox" name="order_display" value="1" <?php  if($order_display)   echo  'checked="checked"' ; ?> /></td>              
              <td class="center"><input type="checkbox" name="list_display" value="1" <?php  if($list_display)   echo  'checked="checked"' ; ?> /></td>              
            </tr>
            </table>
        <table id="option-value" class="list">
          <thead>
            <tr>
              <td class="left"><span class="required">*</span> <?php echo $entry_option_value; ?></td>
              
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $option_value_row = 0; ?>
          <?php foreach ($option_values as $option_value) { ?>
          <tbody id="option-value-row<?php echo $option_value_row; ?>">
            <tr>
              <td class="left"><input type="hidden" name="option_value[<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $option_value['option_value_id']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_option_value[$option_value_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_option_value[$option_value_row][$language['language_id']]; ?></span>
                <?php } ?>
                <?php } ?></td>
             
              <td class="right"><input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $option_value_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="addOptionValue();" class="button"><?php echo $button_add_option_value; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'type\']').bind('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' ) {
		$('#option-value').show();
	} else {
		$('#option-value').hide();
	}
});

$(document).ready(function(){
	
	var value1 = $('select[name=\'type\']').val();	
	if (value1 == 'select' || value1 == 'radio' || value1 == 'checkbox' ) {
		$('#option-value').show();
		
	} else {
		$('#option-value').hide();
		
	}
});

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue() {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';	
    html += '    <td class="left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="option_value[' + option_value_row + '][option_value_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '    </td>';
    
	html += '    <td class="right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" size="1" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';	
    html += '</tbody>';
	
	$('#option-value tfoot').before(html);
	
	option_value_row++;
}
//--></script> 

<?php echo $footer; ?>