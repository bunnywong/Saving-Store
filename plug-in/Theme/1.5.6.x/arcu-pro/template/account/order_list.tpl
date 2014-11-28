<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<?php if ($orders) { ?>
		<div class="order-list">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td><?php echo $text_order_id; ?></td>
						<td><?php echo $text_customer; ?></td>
						<td><?php echo $text_date_added; ?></td>
						<td><?php echo $text_products; ?></td>
						<td><?php echo $text_status; ?></td>
						<td><?php echo $text_total; ?></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($orders as $order) { ?>
					<tr>
						<td>#<?php echo $order['order_id']; ?></td>
						<td><?php echo $order['name']; ?></td>
						<td><?php echo $order['date_added']; ?></td>
						<td><?php echo $order['products']; ?></td>
						<td class="order-status"><?php echo $order['status']; ?> <a href="<?php echo $order['href']; ?>"><img src="catalog/view/theme/arcu-pro/image/icons/info.png" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" /></a> <a href="<?php echo $order['reorder']; ?>"><img src="catalog/view/theme/arcu-pro/image/icons/reorder.png" alt="<?php echo $button_reorder; ?>" title="<?php echo $button_reorder; ?>" /></a></td>
						<td class="order-total"><?php echo $order['total']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="pagination"><?php echo $pagination; ?></div>
		<?php } else { ?>
		<div class="content">
			<p><?php echo $text_empty; ?></p>
		</div>
		<?php } ?>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>