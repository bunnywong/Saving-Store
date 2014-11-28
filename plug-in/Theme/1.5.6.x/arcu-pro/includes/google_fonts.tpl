<?php $font = $this->config->get('font'); ?>
<?php if (is_array($font)) { ?>
<?php $heading_font = $font['heading']; $body_font = $font['body']; ?>
<?php if($heading_font['status'] || $body_font['status']) { // Process Google font when the heading font or the body font is enabled ?>
<?php
		// Prepare heading & body font
		$font_format = '';
		if ($heading_font['status']) {
			$font_format .= $heading_font['css-name'] . ':' . $heading_font['font-weight'];

			// Wrap double quote around font name if it has space
			if (strpos($heading_font['font-family'], ' ') !== false) {
				$heading_font['font-family'] = '"' . $heading_font['font-family'] . '"';
			}
		}

		if ($body_font['status']) {
			$font_format .= $font_format ? '|' : '';
			$font_format .= $body_font['css-name'] . ':' . $body_font['font-weight'];

			// Wrap double quote around font name if it has space
			if (strpos($body_font['font-family'], ' ') !== false) {
				$body_font['font-family'] = '"' . $body_font['font-family'] . '"';
			}
		}
		?>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo $font_format; ?>&subset=all" />
<style type="text/css">
<?php if ($heading_font['status']) {
?>  <?php echo $heading_font['font-selector'] ?> {
 font-family: <?php echo $heading_font['font-family'];
?>;
}
 <?php
}
?>  <?php if ($body_font['status']) {
?>  <?php echo $body_font['font-selector'] ?> {
 font-family: <?php echo $body_font['font-family'];
?>;
}
 <?php
}
?>
</style>
<?php } ?>
<?php } ?>