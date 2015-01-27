<?php if (!isset($redirect)) { ?>

<div class="checkout-product last-step">
	<table>
		<thead>
			<tr>
				<td class="name"><?php echo $column_name; ?></td>
				<td class="model"><?php echo $column_model; ?></td>
				<td class="quantity"><?php echo $column_quantity; ?></td>
				<td class="price"><?php echo $column_price; ?></td>
				<td class="total"><?php echo $column_total; ?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($products as $product) { ?>
			<?php if($product['recurring']): ?>
			<tr>
				<td colspan="6" style="border:none;"><image src="catalog/view/theme/arcu-pro/image/icons/reorder.png" alt="" title="" style="float:left;" />
					<span style="float:left;line-height:18px; margin-left:10px;"> <strong><?php echo $text_recurring_item ?></strong> <?php echo $product['profile_description'] ?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
					<?php foreach ($product['option'] as $option) { ?>
					<br />
					&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
					<?php } ?>
					<?php if($product['recurring']): ?>
					<br />
					&nbsp;<small><?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
					<?php endif; ?></td>
				<td class="model"><?php echo $product['model']; ?></td>
				<td class="quantity"><?php echo $product['quantity']; ?></td>
				<td class="price">
					<?php
						//echo '<pre>'.var_dump($product).'</pre>';
/*						if( strtolower($product['category_name']) == 'redeem' && $product['price'] != '$0.00' )
							// point + $
							echo $this->currency->format($product['price_digi'] / 2);
						else*/
							//	Regular
							echo $product['price'];
					?>
				</td>
				<td class="total">
					<?php
						//echo '<pre>'.var_dump($product).'</pre>';
/*						if( strtolower($product['category_name']) == 'redeem' && $product['total'] != '$0.00' )
							// point + $
							echo $this->currency->format($product['price_digi'] / 2);
						else*/
							//	Regular
							echo $product['total'];
					?>
				</td>
			</tr>
			<?php } ?>
			<?php foreach ($vouchers as $voucher) { ?>
			<tr>
				<td class="name"><?php echo $voucher['description']; ?></td>
				<td class="model"></td>
				<td class="quantity">1</td>
				<td class="price"><?php echo $voucher['amount']; ?></td>
				<td class="total"><?php echo $voucher['amount']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<?php
				// DEBUG
				echo '<pre>';
				echo  var_dump($totals[1]);	// reward
				//echo  var_dump($totals);
				echo '</pre>';
			?>
			<?php foreach ($totals as $total) { ?>
				<?php if( $total['code'] != 'reward' ): ?>
					<tr>
						<td colspan="2" class="collapse"></td>
						<td colspan="2" class="price"><?php echo $total['title']; ?>:</td>
						<td class="total">
							<?php echo $total['text']; ?></td>
					</tr>
				<?php endif; ?>
			<?php } ?>
		</tfoot>
	</table>
</div>
<div class="payment"><?php echo $payment; ?></div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
