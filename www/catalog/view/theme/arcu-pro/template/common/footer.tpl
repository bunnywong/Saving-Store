</div>
</div>
<?php if ($this->config->get('color') && $bottom = $this->getChild('module/kulercp')) { ?>
<?php echo $bottom; ?>
<?php } ?>

<div id="footer"> <span class="toggle"></span>
	<div class="wrapper">
		<?php if ($informations) { ?>
		<h3><span><?php echo $text_information; ?></span></h3>
		<ul>
			<?php foreach ($informations as $information) { ?>
			<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
			<?php } ?>
		</ul>
		<?php } ?>
		<h3><span><?php echo $text_service; ?></span></h3>
		<ul>
			<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
			<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
			<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
		</ul>
		<h3><span><?php echo $text_extra; ?></span></h3>
		<ul>
			<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
			<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
			<li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
			<li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
		</ul>
		<h3><span><?php echo $text_account; ?></span></h3>
		<ul>
			<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
			<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
			<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
			<li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
		</ul>
	</div>
</div>
<?php if($this->config->get('kuler_analytics_position') == 'bottom') echo $this->config->get('kuler_analytics_code'); ?>
</body></html>