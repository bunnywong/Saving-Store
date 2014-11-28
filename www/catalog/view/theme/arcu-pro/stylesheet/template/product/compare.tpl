<?php echo $header; ?>
<?php if ($success) { ?>

<div class="success"><?php echo $success; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
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
		<?php if ($products) { ?>
		<table class="list compare-info">
			<thead>
				<tr>
					<td class="compare-product" colspan="<?php echo count($products) + 1; ?>"><?php echo $text_product; ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $text_name; ?></td>
					<?php foreach ($products as $product) { ?>
					<td class="name"><a href="<?php echo $products[$product['product_id']]['href']; ?>"><?php echo $products[$product['product_id']]['name']; ?></a></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_image; ?></td>
					<?php foreach ($products as $product) { ?>
					<?php $products[$product['product_id']]['thumb'] = $products[$product['product_id']]['thumb'] ? $products[$product['product_id']]['thumb'] : $this->config->get('config_url') . 'image/no_image-' . $this->config->get('config_image_product_width') . 'x' . $this->config->get('config_image_category_height') . '.jpg' ?>
					<td><?php if ($product['thumb']) { ?>
						<?php if ($products[$product['product_id']]['thumb']) { ?>
						<img src="<?php echo $products[$product['product_id']]['thumb']; ?>" alt="<?php echo $products[$product['product_id']]['name']; ?>" />
						<?php } ?>
						<?php } else { ?>
						<span class="no-image" style="width: <?php echo $this->config->get('config_image_compare_width') ?>px; line-height: <?php echo $this->config->get('config_image_compare_width') ?>px"><img src="image/no_image.jpg" alt="<?php echo $products[$product['product_id']]['name']; ?>" /></span>
						<?php } ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_price; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><?php if ($products[$product['product_id']]['price']) { ?>
						<?php if (!$products[$product['product_id']]['special']) { ?>
						<?php echo $products[$product['product_id']]['price']; ?>
						<?php } else { ?>
						<span class="price-old"><?php echo $products[$product['product_id']]['price']; ?></span> <span class="price-fixed"><?php echo $products[$product['product_id']]['special']; ?></span>
						<?php } ?>
						<?php } ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_model; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><?php echo $products[$product['product_id']]['model']; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_manufacturer; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><?php echo $products[$product['product_id']]['manufacturer']; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_availability; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><?php echo $products[$product['product_id']]['availability']; ?></td>
					<?php } ?>
				</tr>
				<?php if ($review_status) { ?>
				<tr>
					<td><?php echo $text_rating; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><img src="catalog/view/theme/arcu-pro/image/icons/stars-<?php echo $products[$product['product_id']]['rating']; ?>.png" alt="<?php echo $products[$product['product_id']]['reviews']; ?>" /><br />
						<?php echo $products[$product['product_id']]['reviews']; ?></td>
					<?php } ?>
				</tr>
				<?php } ?>
				<tr>
					<td><?php echo $text_summary; ?></td>
					<?php foreach ($products as $product) { ?>
					<td class="description"><?php echo $products[$product['product_id']]['description']; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_weight; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><?php echo $products[$product['product_id']]['weight']; ?></td>
					<?php } ?>
				</tr>
				<tr>
					<td><?php echo $text_dimension; ?></td>
					<?php foreach ($products as $product) { ?>
					<td><?php echo $products[$product['product_id']]['length']; ?> x <?php echo $products[$product['product_id']]['width']; ?> x <?php echo $products[$product['product_id']]['height']; ?></td>
					<?php } ?>
				</tr>
			</tbody>
			<?php foreach ($attribute_groups as $attribute_group) { ?>
			<thead>
				<tr>
					<td class="compare-attribute" colspan="<?php echo count($products) + 1; ?>"><?php echo $attribute_group['name']; ?></td>
				</tr>
			</thead>
			<?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
			<tbody>
				<tr>
					<td><?php echo $attribute['name']; ?></td>
					<?php foreach ($products as $product) { ?>
					<?php if (isset($products[$product['product_id']]['attribute'][$key])) { ?>
					<td><?php echo $products[$product['product_id']]['attribute'][$key]; ?></td>
					<?php } else { ?>
					<td></td>
					<?php } ?>
					<?php } ?>
				</tr>
			</tbody>
			<?php } ?>
			<?php } ?>
			<tr>
				<td></td>
				<?php foreach ($products as $product) { ?>
				<td><a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><span class="icon-cart-grey"><?php echo $button_cart; ?></span></a></td>
				<?php } ?>
			</tr>
			<tr>
				<td></td>
				<?php foreach ($products as $product) { ?>
				<td class="remove"><a href="<?php echo $product['remove']; ?>"><img src="catalog/view/theme/arcu-pro/image/icons/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
				<?php } ?>
			</tr>
		</table>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>
		<?php } else { ?>
		<div class="content">
			<p><?php echo $text_empty; ?></p>
		</div>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
		</div>
		<?php } ?>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>