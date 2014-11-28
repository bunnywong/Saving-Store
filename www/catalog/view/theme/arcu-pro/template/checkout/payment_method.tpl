<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<table class="radio">
	<?php foreach ($payment_methods as $payment_method) { ?>
	<tr class="highlight">
		<td><?php if ($payment_method['code'] == $code || !$code) { ?>
			<?php $code = $payment_method['code']; ?>
			<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
			<?php } else { ?>
			<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
			<?php } ?></td>
		<td><label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>
	</tr>
	<?php } ?>
</table>
<?php } ?>
<p><strong><?php echo $text_comments; ?></strong></p>
<p>
	<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
</p>
<?php if ($text_agree) { ?>
<div class="buttons clearafter">
	<div class="right">
		<button id="button-payment-method" class="button"><?php echo $button_continue; ?></button>
	</div>
	<p>
		<?php if ($agree) { ?>
		<input type="checkbox" name="agree" value="1" checked="checked" />
		<?php } else { ?>
		<input type="checkbox" name="agree" value="1" />
		<?php } ?>
		<?php echo $text_agree; ?> </p>
</div>
<?php } else { ?>
<div class="buttons clearafter">
	<div class="right">
		<button id="button-payment-method" class="button"><?php echo $button_continue; ?></button>
	</div>
</div>
<?php } ?>
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	width: 640,
	height: 480
});
//--></script> 