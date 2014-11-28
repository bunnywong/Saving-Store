<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content" class="search-page"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<?php
	  		$thumb_width = $this->config->get('config_image_product_width');
	  		$thumb_height = $this->config->get('config_image_product_height');
		?>
		<div class="box-product product-grid grid-layout">
			<div class="search-info" style="width: <?php echo ($thumb_width + 52) * 2 + 20; ?>px">
				<h1><?php echo $heading_title; ?></h1>
				<b><?php echo $text_critea; ?></b>
				<div class="content">
					<p><?php echo $entry_search; ?>
						<?php if ($search) { ?>
						<input type="text" name="search" value="<?php echo $search; ?>" />
						<?php } else { ?>
						<input type="text" name="search" value="<?php echo $search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
						<?php } ?>
						<select name="category_id">
							<option value="0"><?php echo $text_category; ?></option>
							<?php foreach ($categories as $category_1) { ?>
							<?php if ($category_1['category_id'] == $category_id) { ?>
							<option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
							<?php } ?>
							<?php foreach ($category_1['children'] as $category_2) { ?>
							<?php if ($category_2['category_id'] == $category_id) { ?>
							<option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
							<?php } ?>
							<?php foreach ($category_2['children'] as $category_3) { ?>
							<?php if ($category_3['category_id'] == $category_id) { ?>
							<option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
							<?php } ?>
							<?php } ?>
							<?php } ?>
							<?php } ?>
						</select>
					</p>
					<p>
						<?php if ($sub_category) { ?>
						<input type="checkbox" name="sub_category" value="1" id="sub_category" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="sub_category" value="1" id="sub_category" />
						<?php } ?>
						<label for="sub_category"><?php echo $text_sub_category; ?></label>
					</p>
					<p>
						<?php if ($description) { ?>
						<input type="checkbox" name="description" value="1" id="description" checked="checked" />
						<?php } else { ?>
						<input type="checkbox" name="description" value="1" id="description" />
						<?php } ?>
						<label for="description"><?php echo $entry_description; ?></label>
					</p>
				</div>
				<div class="buttons">
					<div class="right">
						<button id="button-search" class="button"><?php echo $button_search; ?></button>
					</div>
				</div>
				<h2><?php echo $text_search; ?></h2>
				<?php if ($products) { ?>
				<div class="product-filter clearafter">
					<div class="limit"><?php echo $text_limit; ?>
						<select onchange="location = this.value;">
							<?php foreach ($limits as $limits) { ?>
							<?php if ($limits['value'] == $limit) { ?>
							<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="sort"><?php echo $text_sort; ?>
						<select onchange="location = this.value;">
							<?php foreach ($sorts as $sorts) { ?>
							<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
							<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<?php foreach ($products as $product) { ?>
			<div style="width: <?php echo $thumb_width + 52; ?>px">
				<?php if ($product['thumb']) { ?>
				<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
				<?php } else { ?>
				<div class="image"><span class="no-image"><img src="image/no_image.jpg" alt="<?php echo $product['name']; ?>" /></span></div>
				<?php } ?>
				<div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
				<?php if ($product['price']) { ?>
				<div class="price">
					<?php if (!$product['special']) { ?>
					<div><span class="price-fixed"><?php echo $product['price']; ?></span></div>
					<?php } else { ?>
					<div class="special-price"><span class="price-fixed"><?php echo $product['special']; ?></span><span class="price-old"><?php echo $product['price']; ?></span></div>
					<?php } ?>
				</div>
				<?php } ?>
				<?php if ($product['rating']) { ?>
				<div class="rating"><img src="catalog/view/theme/arcu-pro/image/icons/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
				<?php } ?>
				<div class="description"><?php echo $product['description']; ?></div>
				<div class="cart"><a class="button" onclick="addToCart('<?php echo $product['product_id']; ?>');" data-hover="<?php echo $button_cart; ?>"><span class="icon-basket-light"><?php echo $button_cart; ?></span></a></div>
				<div class="wishlist"><a class="button" onclick="addToWishList('<?php echo $product['product_id']; ?>');"><span class="icon-wishlist-grey"><?php echo $button_wishlist; ?></span></a></div>
				<div class="compare"><a class="button" onclick="addToCompare('<?php echo $product['product_id']; ?>');"><span class="icon-compare-grey"><?php echo $button_compare; ?></span></a></div>
				<div class="more-info"><a class="button" href="<?php echo $product['href']; ?>"><span class="icon-info-grey"><?php echo $button_compare; ?></span></a></div>
			</div>
			<?php } ?>
			<div class="pagination"><?php echo $pagination; ?></div>
			<?php } else { ?>
			<div class="content">
				<p><?php echo $text_empty; ?></p>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#content input[name=\'search\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'category_id\']').bind('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').attr('disabled', 'disabled');
		$('input[name=\'sub_category\']').removeAttr('checked');
	} else {
		$('input[name=\'sub_category\']').removeAttr('disabled');
	}
});

$('select[name=\'category_id\']').trigger('change');

$('#button-search').bind('click', function() {
	url = 'index.php?route=product/search';
	
	var search = $('#content input[name=\'search\']').attr('value');
	
	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var category_id = $('#content select[name=\'category_id\']').attr('value');
	
	if (category_id > 0) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}
	
	var sub_category = $('#content input[name=\'sub_category\']:checked').attr('value');
	
	if (sub_category) {
		url += '&sub_category=true';
	}
		
	var filter_description = $('#content input[name=\'description\']:checked').attr('value');
	
	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

//--></script> 
<?php echo $footer; ?>
<script type="text/javascript">
$(document).ready(function () {
	$('.box-product').masonry({
		columnWidth: <?php echo $thumb_width + 72; ?>,
		isFitWidth: true,
		itemSelector: '.product-grid > div'
	});
});
</script>