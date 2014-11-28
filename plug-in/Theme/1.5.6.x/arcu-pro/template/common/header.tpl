<?php include( DIR_TEMPLATE . '/arcu-pro/includes/megamenu.tpl' );?>
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php if($this->config->get('kuler_compress_styles')) { ?>
<?php echo $this->config->get('kuler_compress_styles') ?>
<?php } else { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/arcu-pro/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/arcu-pro/stylesheet/modules.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/arcu-pro/stylesheet/responsive.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/arcu-pro/stylesheet/chosen.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/arcu-pro/stylesheet/custom-scrollbar.css" />
<?php if ($this->config->get('kuler_cp_settings') && $preset = $this->getChild('module/kulercp/usePreset')) { ?>
<link rel="stylesheet" href="<?php echo $preset; ?>" />
<?php } ?>
<?php if ($this->config->get('kuler_cp_custom_scheme') && $color_scheme = $this->getChild('module/kulercp/colorScheme')) { ?>
<?php echo $color_scheme ?>
<?php } ?>
<?php if ($this->config->get('kuler_cp_settings') && $file_custom_css = $this->getChild('module/kulercp/useCustomCSS')) { ?>
<link rel="stylesheet" href="<?php echo $file_custom_css; ?>" />
<?php } ?>
<?php } ?>
<!-- google fonts -->
<?php include( DIR_TEMPLATE . '/arcu-pro/includes/google_fonts.tpl' );?>
<!-- google fonts /-->
<?php if(!$this->config->get('kuler_compress_styles') || $this->config->get('kuler_compress_style_type') == 'theme') { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<?php } ?>
<?php foreach ($styles as $style) { ?>
<?php if($this->config->get('kuler_compress_style_type') == 'all' && strpos($style['href'], 'catalog/view') === 0) { continue; } ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php if(!$this->config->get('kuler_compress_scripts') || $this->config->get('kuler_compress_script_type') == 'theme') { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php } ?>
<?php if($this->config->get('kuler_compress_scripts')) { ?>
<?php echo $this->config->get('kuler_compress_scripts') ?>
<?php } else { ?>
<script type="text/javascript" src="catalog/view/theme/arcu-pro/js/utils.js"></script>
<script type="text/javascript" src="catalog/view/theme/arcu-pro/js/masonry.js"></script>
<script type="text/javascript" src="catalog/view/theme/arcu-pro/js/chosen.js"></script>
<script type="text/javascript" src="catalog/view/theme/arcu-pro/js/custom-scrollbar.js"></script>
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<?php if($this->config->get('kuler_compress_script_type') == 'all' && strpos($script, 'catalog/view') === 0) { continue; } ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/arcu-pro/stylesheet/ie.css" />
<![endif]-->
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php if($this->config->get('kuler_analytics_position') == 'head') echo $this->config->get('kuler_analytics_code'); ?>
<script type="text/javascript">
$(document).ready(function () {
	// Active the home item if there is no active item
	if ( ! $('.mainmenu').find('.active').length) {
		$('.mainmenu > li:eq(0)').addClass('active');
	}

	var config = {
	  '.product-info select'	: {disable_search_threshold:1000},
	  '.box-product select'		: {disable_search_threshold:1000}
	}
	
	for (var selector in config) {
	  $(selector).chosen(config[selector]).data('chosen');
	}

	if($('.kuler-finder select').length > 0) {
		
		var chosen = $(".kuler-finder select").chosen({disable_search_threshold: 100}).data('chosen');

		if (chosen) {
			chosen.winnow_results();
		}

		$(".kuler-finder .chosen-drop").mCustomScrollbar({
			contentTouchScroll: true
		});

	}

	// adjust scrollbar
	$("#header .wrapper, #bottom .wrapper, #footer .wrapper").mCustomScrollbar({
		autoHideScrollbar: true,
		contentTouchScroll: true,
		mouseWheelPixels: 200,
		advanced:{
			autoScrollOnFocus: false
		}
	});

});
</script>
</head>
<body class="primary-define color-<?php echo $this->config->get('kuler_theme_color') ? $this->config->get('kuler_theme_color') : 'yellow'; ?><?php if (preg_match('#MSIE (.+?);#', $this->request->server['HTTP_USER_AGENT'], $matches) && intval($matches[1]) < 9) echo ' is-ie'; ?><?php echo ((empty($_GET['_route_']) && empty($_GET['route'])) || (isset($_GET['route']) && $_GET['route'] == 'common/home')) ? ' home' : '' ?>">
<div id="header">
	<div id="toppanel">
		<?php if ($logo) { ?>
		<div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
		<?php } ?>
	</div>
	<div class="wrapper"> <?php echo $cart; ?>
		<div id="topbar"> <?php echo $language; ?> <?php echo $currency; ?>
			<div class="links clearafter"> <a href="<?php echo $wishlist; ?>" id="wishlist-total"><span class="icon-wishlist"><?php echo $text_wishlist; ?></span></a> <a href="<?php echo $checkout; ?>" id="link-checkout"><span class="icon-checkout"><?php echo $text_checkout; ?></span></a> <a href="<?php echo $account; ?>" id="link-account"><span class="icon-user"><?php echo $text_account; ?></span></a> <a href="<?php echo $shopping_cart; ?>" id="link-cart"><span class="icon-cart"><?php echo $text_shopping_cart; ?></span></a> </div>
			<div id="welcome">
				<?php if (!$logged) { ?>
				<?php echo $text_welcome; ?>
				<?php } else { ?>
				<?php echo $text_logged; ?>
				<?php } ?>
			</div>
		</div>
		<?php if (($kuler_finder = $this->config->get('kuler_finder')) && $kuler_finder['status']) { ?>
		<?php echo $this->getChild('module/kuler_finder', $kuler_finder); ?>
		<?php } else { ?>
		<div id="search">
			<div id="search-inner">
				<div class="button-search"></div>
				<input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
			</div>
		</div>
		<?php } ?>
		<?php if ($categories) { ?>
		<div id="menu">
			<div id="menu-inner"> <span id="btn-mobile-toggle">Menu</span>
				<?php if ($this->config->get('kuler_menu_status')) { ?>
				<?php echo $this->getChild('module/kuler_menu'); ?>
				<?php } else { ?>
				<ul class="mainmenu clearafter">
					<li class="item"><a href="index.php" title="Home">Home</a></li>
					<?php $path = isset($this->request->get['path']) ? $this->request->get['path'] : ''; ?>
					<?php foreach ($categories as $category) { ?>
					<?php $category_id = (int) substr($category['href'], strpos($category['href'], 'path=') + 5); ?>
					<li class="item<?php echo count($category['children']) ? ' parent' : '' ?><?php echo !empty($category['active']) ? ' active' : '' ?>"> <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
						<?php if (count($category['children'])) { ?>
						<span class="btn-expand-menu"></span>
						<?php } ?>
						<?php if ($category['children']) { ?>
						<div class="dropdown-container">
							<div class="dropdown clearafter">
								<?php for ($i = 0; $i < count($category['children']);) { ?>
								<ul class="sublevel">
									<?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
									<?php for (; $i < $j; $i++) { ?>
									<?php if (isset($category['children'][$i])) { ?>
									<?php $children_id = substr($category['children'][$i]['href'], strpos($category['children'][$i]['href'], 'path=') + 5); ?>
									<li class="item<?php echo !empty($category['children'][$i]['active']) ? ' active' : '' ?><?php if (!empty($category['children'][$i]['children'])) { echo ' parent'; } ?>"> <a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a>
										<?php if (!empty($category['children'][$i]['children'])) { ?>
										<span class="btn-expand-menu"></span>
										<?php } ?>
										<?php if (!empty($category['children'][$i]['children'])) { ?>
										<?php echo renderSubMenuRecursive($category['children'][$i]['children']); ?>
										<?php } ?>
									</li>
									<?php } ?>
									<?php } ?>
								</ul>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
				<?php } ?>
			</div>
		</div>
		<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
		<div id="powered" class="clearafter">
			<?php if ($this->config->get('color') && $custom_copyright = $this->getChild('module/kulercp/getcustomcopyright')) { ?>
			<?php echo $custom_copyright; ?>
			<?php } else { ?>
			<!-- <?php echo $powered; ?> --> 
			<!-- Please do not remove following code or we can not support you with this product ! -->
			<p>Powered by <a href="http://www.opencart.com" title="Opencart" target="_blank">Opencart</a><br />
				Opencart theme designed by <a href="http://www.kulerthemes.com" title="KulerThemes">KulerThemes.com</a></p>
			<?php } ?>
			<!-- block: Payment Icons -->
			<?php if($this->config->get('kuler_payment_status') && $this->config->get('kuler_payment_items')) { ?>
			<?php $payments = $this->config->get('kuler_payment_items'); ?>
			<ul id="footer-payments" class="clearafter">
				<?php foreach($payments as $k => $item) { ?>
				<?php if($item['status'] == 0) continue; ?>
				<li class="<?php echo $k ?>"><?php echo $item['link'] ?></li>
				<?php } ?>
			</ul>
			<?php } ?>
		</div>
		<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//--> 
	</div>
</div>
<div id="container">
<div id="container-inner" class="wrapper clearafter">
<div id="notification">
	<?php if ($error) { ?>
	<div class="warning"><?php echo $error ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
	<?php } ?>
</div>
