<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<script>
	jQuery(document).ready(function(){
		$('body').addClass('success');
	});
</script>

<div id="content"><?php echo $content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $heading_title; ?></h1>
		<?php echo $text_message; ?>
		<div class="buttons">
			<div class="right"><a href="<?php echo $continue; ?>" class="button btn_continue"><?php echo $button_continue; ?></a></div>
		</div>
	</div>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>