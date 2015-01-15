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
	<style type="text/css">
	.box > .content h2 { border-bottom: 1px dotted #000000; color: #FF802B; font-size: 15px; font-weight: bold; padding-bottom: 3px; text-transform: uppercase; }
	.btn-advanced { -moz-box-shadow:inset 0px 1px 0px 0px #ffffff; -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff; box-shadow:inset 0px 1px 0px 0px #ffffff; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) ); background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf'); background-color:#ededed; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; border:1px solid #dcdcdc; display:inline-block; color:#777777; font-family:arial; font-size:11px; font-weight:bold; padding:6px 12px; text-decoration:none; text-shadow:1px 1px 0px #ffffff; }
	.btn-advanced:hover { cursor: pointer; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) ); background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed'); background-color:#dfdfdf; }
	.btn-advanced:active { cursor: pointer; position:relative; top:1px; }
	</style>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a onclick="location = '<?php echo $email; ?>';" class="button"><?php echo $button_email; ?></a>
				<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
			<tr>
				<td><?php echo $entry_auto_checkout; ?></td>
				<td>
					<select name="rewardpoints_auto_checkout">
						<option value="0"><?php echo $text_no; ?></option>
						<option value="1" <?php echo ($rewardpoints_auto_checkout) ? 'selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_purchase_url; ?></td>
				<td>
					<input type="text" name="rewardpoints_purchase_url" value= "<?php echo $rewardpoints_purchase_url; ?>" size="60" />
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_completed_orders; ?></td>
				<td><div class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div class="<?php echo $class; ?>">
							<input type="checkbox" name="rewardpoints_completed_orders[]" value="<?php echo $order_status['order_status_id']; ?>" <?php echo (in_array($order_status['order_status_id'], $rewardpoints_completed_orders)) ? 'checked="checked"' : ''; ?> />
							<?php echo $order_status['name']; ?>
						</div>
					<?php } ?>
				</div></td>
			</tr>
			<tr>
				<td><?php echo $entry_cancelled_orders; ?></td>
				<td><div class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach ($order_statuses as $order_status) { ?>
						<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div class="<?php echo $class; ?>">
							<input type="checkbox" name="rewardpoints_cancelled_orders[]" value="<?php echo $order_status['order_status_id']; ?>" <?php echo (in_array($order_status['order_status_id'], $rewardpoints_cancelled_orders)) ? 'checked="checked"' : ''; ?> />
							<?php echo $order_status['name']; ?>
						</div>
					<?php } ?>
				</div></td>
			</tr>
		</table>
		<h2><?php echo $heading_currency; ?></h2>
		<table class="form">
			<tr>
				<td><?php echo $entry_currency_mode; ?></td>
				<td>
					<select name="rewardpoints_currency_mode">
						<option value="0"><?php echo $text_integer; ?></option>
						<option value="1" <?php echo ($rewardpoints_currency_mode) ? 'selected="selected"' : ''; ?>><?php echo $text_float; ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_hidden_zero; ?></td>
				<td>
					<input type="radio" name="rewardpoints_hidden_zero" value="0" <?php echo (!$rewardpoints_hidden_zero) ? 'checked="checked"' : ''; ?> /><?php echo $text_zero_display; ?>
					<input type="radio" name="rewardpoints_hidden_zero" value="1" <?php echo ($rewardpoints_hidden_zero) ? 'checked="checked"' : ''; ?> /><?php echo $text_zero_hide; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_subtext_display; ?></td>
				<td>
					<input type="radio" name="rewardpoints_subtext_display" value="0" <?php echo (!$rewardpoints_subtext_display) ? 'checked="checked"' : ''; ?> /><?php echo $text_display_attribute; ?>
					<input type="radio" name="rewardpoints_subtext_display" value="1" <?php echo ($rewardpoints_subtext_display) ? 'checked="checked"' : ''; ?> /><?php echo $text_display_subtext; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_pop_notification; ?></td>
				<td>
					<input type="radio" name="rewardpoints_pop_notification" value="1" <?php echo ($rewardpoints_pop_notification) ? 'checked="checked"' : ''; ?> /><?php echo $text_yes; ?>
					<input type="radio" name="rewardpoints_pop_notification" value="0" <?php echo (!$rewardpoints_pop_notification) ? 'checked="checked"' : ''; ?> /><?php echo $text_no; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_currency_prefix; ?></td>
				<td>
					<input type="text" name="rewardpoints_currency_prefix" value= "<?php echo $rewardpoints_currency_prefix; ?>" size="4" />
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_currency_suffix; ?></td>
				<td>
					<input type="text" name="rewardpoints_currency_suffix" value= "<?php echo $rewardpoints_currency_suffix; ?>" size="4" />
				</td>
			</tr>
		</table>
		<h2><?php echo $heading_bonuses; ?></h2>
		<table class="form">
			<tr>
				<td><?php echo $entry_registration_bonus; ?></td>
				<td>
					<input type="text" name="rewardpoints_registration_bonus" value= "<?php echo $rewardpoints_registration_bonus; ?>" size="4" />
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_newsletter_bonus; ?></td>
				<td>
					<input type="text" name="rewardpoints_newsletter_bonus" value= "<?php echo $rewardpoints_newsletter_bonus; ?>" size="4" />
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_newsletter_unsubscribe; ?></td>
				<td>
					<input type="radio" name="rewardpoints_newsletter_unsubscribe" value="1" <?php echo ($rewardpoints_newsletter_unsubscribe) ? 'checked="checked"' : ''; ?> /><?php echo $text_yes; ?>
					<input type="radio" name="rewardpoints_newsletter_unsubscribe" value="0" <?php echo (!$rewardpoints_newsletter_unsubscribe) ? 'checked="checked"' : ''; ?> /><?php echo $text_no; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_order_bonus; ?></td>
				<td>
					<input type="text" name="rewardpoints_order_bonus" value= "<?php echo $rewardpoints_order_bonus; ?>" size="4" />
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_review_bonus; ?></td>
				<td>
					<input type="text" name="rewardpoints_review_bonus" value= "<?php echo $rewardpoints_review_bonus; ?>" size="4" />
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_review_limit; ?></td>
				<td>
					<select name="rewardpoints_review_limit">
						<option value="0"><?php echo $text_unlimited; ?></option>
						<?php for ($i=1;$i<=100;$i++) { ?>
						<option value="<?php echo $i; ?>"<?php echo ($rewardpoints_review_limit == $i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_review_auto_approve; ?></td>
				<td>
					<select name="rewardpoints_review_auto_approve">
						<option value="0"<?php echo ($rewardpoints_review_auto_approve == 0) ? ' selected="selected"' : ''; ?>><?php echo $text_none; ?></option>
						<?php for ($i=1;$i<=5;$i++) { ?>
						<option value="<?php echo $i; ?>"<?php echo ($rewardpoints_review_auto_approve == $i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</table>
		
			<div style="height:25px;">&nbsp;</div>
			<h2><?php echo $heading_email_reminder; ?></h2>
			<table class="form">
				<tr>
					<td><?php echo $entry_email_reminder_enabled; ?></td>
					<td>
						<input type="radio" name="rewardpoints_email_reminder_enabled" value="1" <?php echo ((int)$rewardpoints_email_reminder_enabled) ? 'checked="checked"' : ''; ?> /><?php echo $text_yes; ?>
						<input type="radio" name="rewardpoints_email_reminder_enabled" value="0" <?php echo (!(int)$rewardpoints_email_reminder_enabled) ? 'checked="checked"' : ''; ?> /><?php echo $text_no; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_email_status; ?></td>
					<td><div class="scrollbox">
						<?php $class = 'odd'; ?>
						<?php foreach ($order_statuses as $order_status) { ?>
							<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
							<div class="<?php echo $class; ?>">
								<input type="checkbox" name="rewardpoints_email_status[]" value="<?php echo $order_status['order_status_id']; ?>" <?php echo (in_array($order_status['order_status_id'], $rewardpoints_email_status)) ? 'checked="checked"' : ''; ?> />
								<?php echo $order_status['name']; ?>
							</div>
						<?php } ?>
					</div></td>
				</tr>
				
				<tr>
					<td><?php echo $entry_email_date; ?></td>
					<td>
						<input type="radio" name="rewardpoints_email_date" value="0" <?php echo (!(int)$rewardpoints_email_date) ? 'checked="checked"' : ''; ?> /><?php echo $text_date_created; ?>
						<input type="radio" name="rewardpoints_email_date" value="1" <?php echo ((int)$rewardpoints_email_date) ? 'checked="checked"' : ''; ?> /><?php echo $text_date_modified; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_email_days; ?></td>
					<td>
						<select name="rewardpoints_email_days">
						<?php for ($i=1; $i<=60; $i++) { ?>
							<option value="<?php echo $i; ?>"<?php echo ($i == $rewardpoints_email_days) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
			</table>
				<div id="langemail" class="htabs">
					<?php foreach ($languages as $language) { ?>
						<a href="#langemail<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
					<?php } ?>
				</div>
				<?php foreach ($languages as $language) { ?>
					<div id="langemail<?php echo $language['language_id']; ?>">
						<table class="form">
							<tr>
								<td><?php echo $entry_email_subject; ?></td>
								<td><input type="text" name="rewardpoints_email_content[<?php echo $language['language_id']; ?>][subject]" value="<?php echo isset($rewardpoints_email_content[$language['language_id']]) ? $rewardpoints_email_content[$language['language_id']]['subject'] : ''; ?>" size="80" /></td>
							</tr>
							<tr>
								<td valign="top"><?php echo $entry_email_content; ?></td>
								<td><textarea name="rewardpoints_email_content[<?php echo $language['language_id']; ?>][content]" id="rewardpoints_email_content<?php echo $language['language_id']; ?>"  cols="90" rows="8"><?php echo isset($rewardpoints_email_content[$language['language_id']]) ? $rewardpoints_email_content[$language['language_id']]['content'] : ''; ?></textarea><?php echo $text_email_variables; ?></td>
							</tr>
						</table>
					</div>
				<?php } ?>
				<table class="form">
					<tr>
						<td><?php echo $entry_email_test; ?></td>
						<td>
							<input type="text" name="rewardpoints_email_test" value="" size="40" />
							<button class="btn-advanced" onclick="this.form.action='index.php?route=module/rewardpoints/sendtestemail&token=<?php echo $this->session->data['token']; ?>';"><?php echo $text_email_send_test; ?></button>
						</td>
					</tr>				
					<tr>
						<td><?php echo $entry_email_cron; ?></td>
						<td><?php echo html_entity_decode($rewardpoints_email_cron); ?><textarea style="visibility:hidden;display:none;" name="rewardpoints_email_cron"><?php echo $rewardpoints_email_cron; ?></textarea></td>
					</tr>				
				</table>
			</div>
		</form>
		</div>
	</div>
<script type="text/javascript"><!--
$('#langemail a').tabs(); 
//--></script> 
<?php echo $footer; ?>