<div id="cart">
	<div class="heading">
		<h4><?php echo $heading_title; ?></h4>
		<a><span id="cart-total"><?php echo $text_items; ?></span></a></div>
	<div class="content">
		<div class="inner">
			<?php if ($products || $vouchers) { ?>
			<div class="mini-cart-info">
				<?php if ($products) { ?>
				<?php foreach ($products as $product) { ?>
				<div class="cart-item">
					<?php if ($product['thumb']) { ?>
					<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
					<?php } ?>
					<div class="cart-item-details"> <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
						<p><?php echo $product['quantity']; ?>&nbsp;x&nbsp;<?php echo $product['price']; ?></p>
						<span class="remove"><img src="catalog/view/theme/arcu-pro/image/icons/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');" /></span> </div>
				</div>
				<?php } ?>
				<?php } ?>
				<?php if ($vouchers) { ?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<?php foreach ($vouchers as $voucher) { ?>
					<tr>
						<td class="image"></td>
						<td class="name"><?php echo $voucher['description']; ?></td>
						<td class="quantity">x&nbsp;1</td>
						<td class="total"><?php echo $voucher['amount']; ?></td>
						<td class="remove"><img src="catalog/view/theme/arcu-pro/image/icons/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" /></td>
					</tr>
					<?php } ?>
				</table>
				<?php } ?>
			</div>
			<div class="mini-cart-total">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<?php foreach ($totals as $total) { ?>
					<tr>
						<td class="name"><?php echo $total['title']; ?>:</td>
						<td class="total"><?php echo $total['text']; ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="checkout"> <a class="button" href="<?php echo $cart; ?>"><span><?php echo $text_cart; ?></span></a>&nbsp;&nbsp; <a class="button" href="<?php echo $checkout; ?>"><span><?php echo $text_checkout; ?></span></a> </div>
			<?php } else { ?>
			<div class="empty"><?php echo $text_empty; ?></div>
			<?php } ?>
		</div>
	</div>
</div>
