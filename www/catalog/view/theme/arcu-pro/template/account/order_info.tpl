<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<table class="list">
			<thead>
				<tr>
					<td class="left" colspan="2">訂單明細<?php //echo $text_order_detail; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="2" class="left" style="width: 50%;"><?php if ($invoice_no) { ?>
						<b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
						<?php } ?>
					<!--
						<b><?php echo $text_order_id; ?></b><?php echo ORDER_PREFIX.year_perfix($date_added).str_pad($order_id,ORDER_DIGI,'0',STR_PAD_LEFT); ?><br />
					-->
						<b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?>
					<!--
					</td>
					-->
					<!--
					<td class="left" style="width: 50%;">
					-->
						<?php if ($payment_method) { ?>
						<br>
						<b><?php echo $text_payment_method; ?></b>貨到付款<?php //echo $payment_method; ?>
						<?php } ?>
						<br><b>郵箱地址：</b><?= $this->customer->getEmail(); ?>
						<?php if ($shipping_method) { ?>
						<b><?php //echo $text_shipping_method; ?></b> <?php //echo $shipping_method; ?>
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="list">
			<thead>
				<tr>
				<!--
					<td class="left"><?php echo $text_payment_address; ?></td>
				-->
					<?php if ($shipping_address) { ?>
					<td colspan="2" class="left">送貨地址<?php //echo $text_shipping_address; ?></td>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<tr>
				<!--
					<td class="left"><?php echo $payment_address; ?></td>
				-->
					<?php if ($shipping_address) { ?>
					<td class="left"><?php echo $shipping_address; ?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
		<table class="list">
			<thead>
				<tr>
					<td class="left">產品名稱<?php //echo $column_name; ?></td>
					<td class="left model">產品編號<?php //echo $column_model; ?></td>
					<td class="right quantity"><?php echo $column_quantity; ?></td>
					<td class="right price">價錢<?php //echo $column_price; ?></td>
					<td class="right"><?php echo $column_total; ?></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $product) { ?>
				<tr>
					<td class="left"><?php echo $product['name']; ?>
						<?php foreach ($product['option'] as $option) { ?>
						<br />
						&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
						<?php } ?></td>
					<td class="left model"><?php echo $product['model']; ?></td>
					<td class="right quantity"><?php echo $product['quantity']; ?></td>
					<td class="right price"><?php echo $product['price']; ?></td>
					<td class="right"><?php echo $product['total']; ?></td>
				</tr>
				<?php } ?>
				<?php foreach ($vouchers as $voucher) { ?>
				<tr>
					<td class="left"><?php echo $voucher['description']; ?></td>
					<td class="left"></td>
					<td class="right">1</td>
					<td class="right"><?php echo $voucher['amount']; ?></td>
					<td class="right"><?php echo $voucher['amount']; ?></td>
					<?php if ($products) { ?>
					<td></td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<table class="list">
			<tbody>
				<?php foreach ($totals as $total) { ?>
				<tr>
					<td class="right"><b><?php echo $total['title']; ?>:</b></td>
					<td class="right">
						<?php if( $total['code'] != 'reward' ): ?>
							<?php echo $total['text']; ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php if ($comment) { ?>
		<table class="list">
			<thead>
				<tr>
					<td class="left"><?php echo $text_comment; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="left"><?php echo $comment; ?></td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<?php if ($histories) { ?>
		<h2><?php echo $text_history; ?></h2>
		<table class="list">
			<thead>
				<tr>
					<td class="left"><?php echo $column_date_added; ?></td>
					<td class="left"><?php echo $column_status; ?></td>
					<td class="left"><?php echo $column_comment; ?></td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($histories as $history) { ?>
				<tr>
					<td class="left"><?php echo $history['date_added']; ?></td>
					<td class="left"><?php echo zh_order_status($history['status']); ?></td>
					<td class="left"><?php echo $history['comment']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>