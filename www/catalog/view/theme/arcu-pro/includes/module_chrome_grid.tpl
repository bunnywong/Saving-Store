<a href="<?php echo $product['href']; ?>">
	<div data-product-id="<?php echo $product['product_id']; ?>" style="width: <?php echo $setting['image_width'] + 52; ?>px">
		<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
		<?php if ($product['thumb']) { ?>
		<div class="image"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></div>
		<?php } else { ?>
		<div class="image"><span class="no-image"><img src="image/no_image.jpg" alt="<?php echo $product['name']; ?>" /></span></div>
		<?php } ?>
		<?php if(isset($setting['name']) && $setting['name']) { ?>
		<div class="name"><?php echo $product['name']; ?></div>
		<?php } ?>
		<?php if(isset($setting['price']) && $setting['price']) { ?>
		<div class="price">
			<?php if (!$product['special']) { ?>
			<div><span class="price-fixed"><?php echo $product['price']; ?></span></div>
			<?php } else { ?>
			<div class="special-price"><span class="price-fixed"><?php echo $product['special']; ?></span><span class="price-old"><?php echo $product['price']; ?></span></div>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if(isset($setting['rating']) && $setting['rating']) { ?>
		<div class="rating"><img src="catalog/view/theme/arcu-pro/image/icons/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
		<?php } ?>
		<?php if (isset($setting['description']) && $setting['description']) { ?>
		<div class="description"><?php echo $product['description']; ?></div>
		<?php } ?>
		<?php if (isset($setting['display']) && $setting['display'] == 'product_list' && isset($setting['product_options']) && $setting['product_options'] && count($product['options'])) { ?>
		<div class="options" id="product-options-<?php echo $product['product_id']; ?>">
			<?php foreach ($product['options'] as $option) { ?>
			<?php if ($option['type'] == 'select') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<select name="option[<?php echo $option['product_option_id']; ?>]">
					<option value=""><?php echo $text_select; ?></option>
					<?php foreach ($option['option_value'] as $option_value) { ?>
					<option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
					<?php if ($option_value['price']) { ?>
					(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
					<?php } ?>
					</option>
					<?php } ?>
				</select>
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'radio') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option multi">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<div>
					<?php foreach ($option['option_value'] as $option_value) { ?>
					<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
					<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
						<?php if ($option_value['price']) { ?>
						(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
						<?php } ?>
					</label>
					<br />
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'checkbox') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option multi">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<div>
					<?php foreach ($option['option_value'] as $option_value) { ?>
					<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
					<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
						<?php if ($option_value['price']) { ?>
						(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
						<?php } ?>
					</label>
					<br />
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'image') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<table class="option-image">
					<?php foreach ($option['option_value'] as $option_value) { ?>
					<tr>
						<td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
						<td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
						<td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
								<?php if ($option_value['price']) { ?>
								(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
								<?php } ?>
							</label></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'text') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'textarea') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option multi">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'file') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<input type="button" value="<?php echo $button_upload; ?>" class="button button-product-upload-file" data-option="#option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" data-input="#product-file-input-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>">
				<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" id="product-file-input-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="file" value="" />
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'date') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'datetime') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
			</div>
			<?php } ?>
			<?php if ($option['type'] == 'time') { ?>
			<div id="option-<?php echo $setting['module']; ?>-<?php echo $product['product_id']; ?>-<?php echo $option['product_option_id']; ?>" class="option">
				<p>
					<?php if ($option['required']) { ?>
					<span class="required">*</span>
					<?php } ?>
					<strong><?php echo $option['name']; ?>:</strong> </p>
				<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
			</div>
			<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if((isset($setting['add']) && $setting['add']) ||(isset($setting['wishlist']) && $setting['wishlist']) || (isset($setting['compare']) && $setting['compare'])) { ?>
		<div class="details">
			<?php if(isset($setting['add']) && $setting['add']) { ?>
			<div class="cart">
				<?php if (isset($setting['display']) && $setting['display'] == 'product_list' && isset($setting['product_options']) && $setting['product_options']) { ?>
				<a class="button button-cart" data-hover="<?php echo $button_cart; ?>"><span class="icon-basket-light"><?php echo $button_cart; ?></span></a>
				<?php } else { ?>
				<a class="button button-cart" data-hover="<?php echo $button_cart; ?>" onclick="addToCart('<?php echo $product['product_id']; ?>');"><span class="icon-basket-light"><?php echo $button_cart; ?></span></a>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if(isset($setting['wishlist']) && $setting['wishlist']) { ?>
			<div class="wishlist"><a class="button" onclick="addToWishList('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>"><span class="icon-wishlist-grey"></span></a></div>
			<?php } ?>
			<?php if(isset($setting['compare']) && $setting['compare']) { ?>
			<div class="compare"><a class="button" onclick="addToCompare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>"><span class="icon-compare-grey"></span></a></div>
			<?php } ?>
			<div class="more-info"><a class="button" href="<?php echo $product['href']; ?>" title="<?php echo $product['name']; ?>"><span class="icon-info-grey"></span></a></div>
		</div>
		<?php } ?>
	</div>
</a>