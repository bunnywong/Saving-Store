<?php echo $header; ?>
<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<?php echo $text_description; ?>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
			<h2><?php echo $text_order; ?></h2>
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
						<td><?php echo $entry_email; ?><span class="required">*</span></td>
						<td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
					</tr>
					<?php if ($error_email) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_email; ?></span></td>
					</tr>
					<?php } ?>
				</table>
				<table class="form">
					<tr>
						<td><?php echo $entry_telephone; ?><span class="required">*</span></td>
						<td><input type="text" name="telephone" value="<?php echo $telephone; ?>" /></td>
					</tr>
					<?php if ($error_telephone) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_telephone; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_order_id; ?><span class="required">*</span></td>
						<td><input type="text" name="order_id" value="<?php echo $order_id; ?>" /></td>
					</tr>
					<?php if ($error_order_id) { ?>
					<tr>
						<td></td>
						<td><span class="error"><?php echo $error_order_id; ?></span></td>
					</tr>
					<?php } ?>
					<tr>
						<td><?php echo $entry_date_ordered; ?></td>
						<td><input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" class="date" /></td>
					</tr>
				</table>
			</div>
			<h2><?php echo $text_product; ?></h2>
			<div id="return-product">
				<div class="content">
					<table class="form">
						<tr>
							<td><?php echo $entry_product; ?><span class="required">*</span></td>
							<td><input type="text" name="product" value="<?php echo $product; ?>" /></td>
						</tr>
						<?php if ($error_product) { ?>
						<tr>
							<td></td>
							<td><span class="error"><?php echo $error_product; ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $entry_model; ?><span class="required">*</span></td>
							<td><input type="text" name="model" value="<?php echo $model; ?>" /></td>
						</tr>
						<?php if ($error_model) { ?>
						<tr>
							<td></td>
							<td><span class="error"><?php echo $error_model; ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $entry_quantity; ?></td>
							<td><input type="text" name="quantity" value="<?php echo $quantity; ?>" /></td>
						</tr>
						<tr>
							<td valign="top"><p><?php echo $entry_reason; ?><span class="required">*</span></p></td>
							<td><?php foreach ($return_reasons as $return_reason) { ?>
								<?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
								<p>
									<input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
									<label for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></label>
								</p>
								<?php } else { ?>
								<p>
									<input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" />
									<label for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></label>
								</p>
								<?php  } ?>
								<?php  } ?></td>
						</tr>
						<?php if ($error_reason) { ?>
						<tr>
							<td></td>
							<td><span class="error"><?php echo $error_reason; ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><?php echo $entry_opened; ?></td>
							<td><?php if ($opened) { ?>
								<input type="radio" name="opened" value="1" id="opened" checked="checked" />
								<?php } else { ?>
								<input type="radio" name="opened" value="1" id="opened" />
								<?php } ?>
								<label for="opened"><?php echo $text_yes; ?></label>
								<?php if (!$opened) { ?>
								<input type="radio" name="opened" value="0" id="unopened" checked="checked" />
								<?php } else { ?>
								<input type="radio" name="opened" value="0" id="unopened" />
								<?php } ?>
								<label for="unopened"><?php echo $text_no; ?></label></td>
						</tr>
					</table>
				</div>
			</div>
			<p><strong><?php echo $entry_fault_detail; ?></strong></p>
			<p>
				<textarea name="comment" cols="95" rows="6"><?php echo $comment; ?></textarea>
			</p>
			<div class="return-captcha">
				<div class="left"><strong><?php echo $entry_captcha; ?></strong></div>
				<div class="right">
					<input type="text" name="captcha" value="<?php echo $captcha; ?>" />
					<p><img src="index.php?route=account/return/captcha" alt="" /></p>
					<?php if ($error_captcha) { ?>
					<span class="error"><?php echo $error_captcha; ?></span>
					<?php } ?>
				</div>
			</div>
			<?php if ($text_agree) { ?>
			<div class="buttons clearafter">
				<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
				<div class="right"><?php echo $text_agree; ?>
					<?php if ($agree) { ?>
					<input type="checkbox" name="agree" value="1" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" name="agree" value="1" />
					<?php } ?>
					<button type="submit" class="button"><?php echo $button_continue; ?></button>
				</div>
			</div>
			<?php } else { ?>
			<div class="buttons clearafter">
				<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
				<div class="right">
					<button type="submit" class="button"><?php echo $button_continue; ?></button>
				</div>
			</div>
			<?php } ?>
		</form>
	</div>
	<?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});
//--></script> 
<?php echo $footer; ?>