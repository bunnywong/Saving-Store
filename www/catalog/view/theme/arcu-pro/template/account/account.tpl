<?php echo $header; ?>

<script src="http://savingstore.com.hk/catalog/view/theme/arcu-pro/js/jquery_cookie/src/jquery.cookie.js"></script>
<script>
	if($.cookie("iframe_url") == undefined ){
	  $('body').addClass('account_account');
	}else{
		$('body').addClass('account_account iframe');
	}
</script>

<?php if ($success) { ?>

<div class="success"><?php echo $success; ?></div>
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
<!--
		<h2><?php echo $text_my_account; ?></h2>
-->
		<div class="content">
			<ul>
				<li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
				<li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
				<li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
				<li class="my_hide"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
			</ul>
		</div>
		<h2><?php echo $text_my_orders; ?></h2>
		<div class="content">
			<ul>
				<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
				<li class="my_hide"><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
				<?php if ($reward) { ?>
				<li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
				<?php } ?>
				<li class="my_hide"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
				<li class="my_hide"><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
				<li class="my_hide"><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
			</ul>
		</div>
		<h2 class="my_hide"><?php echo $text_my_newsletter; ?></h2>
		<div class="content my_hide">
			<ul>
				<li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
			</ul>
		</div>
	</div>
	<?php echo $content_bottom; ?></div>
<script>
// My Script
// --------------------------------------------------

  $('body').addClass('my_account');
</script>
<?php echo $footer; ?>