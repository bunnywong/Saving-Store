<?php echo $header; ?>

<script>
  jQuery(document).ready(function(){
    $('body').addClass('editaccount');
  });
</script>

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
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_your_details; ?></h2>
    <div class="content">
      <table class="form xpersonal">
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
        <tr sort="a<?php echo $modData['email_sort']; ?>">
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><?= $email; ?><input type="hidden" <?php if($title_email) echo "xtitle ='".$title_email."'";?> name="email" value="<?php echo $email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <?php  if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['mob_show_edit']) {?>
        <tr sort="a<?php echo $modData['mob_sort']; ?>">
          <td>
           <?php  if(!$isActive['enablemod'] || $isActive['enablemod'] && $modData['mob_req_edit']) echo '<span class="required">*</span>'; ?>
          <?php echo $entry_telephone; ?></td>
          <td><input type="text" <?php if($title_telephone) echo "xtitle ='".$title_telephone."'";?> name="telephone" value="<?php echo $telephone; ?>" class="<?php echo ($modData['mob_numeric']?"numeric":""); ?> <?php echo ($modData['mob_masking']?"mask telephone":""); ?>" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <?php } else { ?>
       	 <input type="hidden" name="telephone" value="<?php echo $telephone; ?>" />
        <?php } ?>
        <?php if (!$isActive['enablemod'] || $isActive['enablemod'] && $modData['fax_show_edit']) { ?>
        <tr sort="a<?php echo $modData['fax_sort']; ?>">
          <td><?php echo $entry_fax; ?></td>
          <td><input type="text" <?php if($title_fax) echo "xtitle ='".$title_fax."'";?> name="fax" value="<?php echo $fax; ?>" class="<?php echo ($modData['fax_numeric']?"numeric":""); ?>"/>
           </td>
        </tr>
        <?php }else{?>
        <input type="hidden" name="fax" value="<?php echo $fax; ?>" />
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
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
      <div class="right">
        <input type="submit" value="<?php echo $button_save; ?>" class="button" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
  <script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/xcustom.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.timers.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery.dropshadow.js"></script>
  <!--
  <script type="text/javascript" src="catalog/view/javascript/mbTooltip.js"></script>
  <link rel="stylesheet" type="text/css" href="catalog/view/javascript/mbTooltip.css" media="screen">
-->
<?php echo $footer; ?>