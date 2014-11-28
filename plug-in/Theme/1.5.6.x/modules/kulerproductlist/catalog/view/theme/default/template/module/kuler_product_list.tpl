<script type="text/javascript">
$(document).ready(function () {
	$('.box-product').masonry({
		columnWidth: <?php echo $setting['image_width'] + 72; ?>,
		isFitWidth: true,
		itemSelector: '.product-grid > div'
	});
});
</script>

<div id="kuler-product-list-<?php echo $module; ?>" class="kuler-product-list">
	<div class="box kuler-module">
		<?php if ($show_title) { ?>
		<div class="box-heading"><span><?php echo $module_title ?></span></div>
		<?php } ?>
		<div class="box-content horizontal">
			<div class="box-product product-grid">
				<?php foreach ($products as $product) { ?>
				<?php echo $this->loadChromeTemplate($setting, $product); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php if (isset($setting['display']) && $setting['display'] == 'product_list') { ?>
	<script>
		$(function () {
			var module = <?php echo $setting['module']; ?>,
				$products = $('#kuler-product-list-<?php echo $module; ?> .product-grid > div');

			function bindEvent($products) {
				$products.each(function () {
					var $product = $(this),
						productId = $product.data('product-id'),
						cartTimer;

					$product.find('.button-cart').bind('click', function() {
						clearTimeout(cartTimer);

						$.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: $product.find('input[type=\'text\'], input[type=\'hidden\'], input[type=\'radio\']:checked, input[type=\'checkbox\']:checked, select, textarea'),
							dataType: 'json',
							success: function(json) {
								$('.success, .warning, .attention, information, .error').remove();

								if (json['error']) {
									if (json['error']['option']) {
										for (i in json['error']['option']) {
											$('#option-'+ module +'-'+ productId +'-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
										}
									}
								}

								if (json['success']) {
									$('#notification')
										.html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>')
										.addClass('active');

									$('.success').fadeIn('slow', function () {
										cartTimer = setTimeout(function () {
											$('#notification').removeClass('active');
										}, 2500);
									});

									$('#cart-total').html(json['total']);
								}
							}
						});
					});
				});

				<?php if (isset($setting['product_options']) && $setting['product_options']) { ?>
				// Upload
				$products.find('.button-product-upload-file').each(function () {
					new AjaxUpload(this, {
						action: 'index.php?route=product/product/upload',
						name: 'file',
						autoSubmit: true,
						responseType: 'json',
						onSubmit: function(file, extension) {
							var $button = $(this._button);

							$button
								.attr('disabled', true)
								.after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
						},
						onComplete: function(file, json) {
							var $button = $(this._button),
								$option = $($button.data('option')),
								$fileInput = $($button.data('input'));

							$button.attr('disabled', false);

							$('.error').remove();

							if (json['success']) {
								alert(json['success']);

								$fileInput.attr('value', json['file']);
							}

							if (json['error']) {
								$option.after('<span class="error">' + json['error'] + '</span>');
							}

							$('.loading').remove();
						}
					});
				});

				// Date-time
				if ($.browser.msie && $.browser.version == 6) {
					$products.find('.date, .datetime, .time').bgIframe();
				}

				$products.find('.date').datepicker({dateFormat: 'yy-mm-dd'});
				$products.find('.datetime').datetimepicker({
					dateFormat: 'yy-mm-dd',
					timeFormat: 'h:m'
				});
				$products.find('.time').timepicker({timeFormat: 'h:m'});
				<?php } ?>
			}

			bindEvent($products);

			var setting = '<?php echo json_encode($setting); ?>',
				productUrl = '<?php echo $product_url; ?>',
				totalPage = <?php echo $total_page; ?>,
				$module = $('#kuler-product-list-' + module),
				currentPage = 0;

			$(window).on('scroll', function () {
				var page = Math.floor($(document).scrollTop() / 300);

				if (page > totalPage && currentPage < totalPage) {
					page = totalPage;
				}

				if (page > currentPage && page <= totalPage) {
					$.post(productUrl, {
						setting: setting,
						current_page: currentPage + 1,
						page: page
					}, function (data) {
						var $product = $(data),
							masonry = $module.find('.box-product').append($product).data('masonry');

						masonry.appended($product);
						bindEvent($product.filter('div'));
					}, 'html');

					currentPage = page;
				}
			});
		});
	</script>
<?php } ?>