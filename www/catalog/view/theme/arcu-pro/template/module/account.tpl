<div class="box">
	<div class="box-heading"><span><?php echo $heading_title; ?></span></div>
	<div class="box-content">
		<ul class="treemenu">
			<?php if (!$logged) { ?>
			<li><a href="<?php echo $login; ?>"><span><?php echo $text_login; ?></span></a></li>
			<li><a href="<?php echo $register; ?>"><span><?php echo $text_register; ?></span></a></li>
			<li><a href="<?php echo $forgotten; ?>"><span><?php echo $text_forgotten; ?></span></a></li>
			<?php } ?>
			<li><a href="<?php echo $account; ?>"><span><?php echo $text_account; ?></span></a></li>
			<?php if ($logged) { ?>
			<li><a href="<?php echo $edit; ?>"><span><?php echo $text_edit; ?></span></a></li>
			<li><a href="<?php echo $password; ?>"><span><?php echo $text_password; ?></span></a></li>
			<?php } ?>
			<li><a href="<?php echo $address; ?>"><span><?php echo $text_address; ?></span></a></li>
			<li><a href="<?php echo $wishlist; ?>"><span><?php echo $text_wishlist; ?></span></a></li>
			<li><a href="<?php echo $order; ?>"><span><?php echo $text_order; ?></span></a></li>
			<li><a href="<?php echo $download; ?>"><span><?php echo $text_download; ?></span></a></li>
			<li><a href="<?php echo $return; ?>"><span><?php echo $text_return; ?></span></a></li>
			<li><a href="<?php echo $transaction; ?>"><span><?php echo $text_transaction; ?></span></a></li>
			<li><a href="<?php echo $newsletter; ?>"><span><?php echo $text_newsletter; ?></span></a></li>
			<li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
			<?php if ($logged) { ?>
			<li><a href="<?php echo $logout; ?>"><span><?php echo $text_logout; ?></span></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
