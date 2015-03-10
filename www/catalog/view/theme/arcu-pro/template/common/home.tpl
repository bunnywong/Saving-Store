<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
	<?php
	  $this->load->model('catalog/information');

	  $information_id 	= 16;	// Hardcode :D
		$information_info	= $this->model_catalog_information->getInformation($information_id);
//		$this->data['info_heading_title'] = $information_info['title'];
		if( count($information_info) > 0 ){
			$my_banner = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
			echo '<div class="home-notic">';
			echo '<span class="cross"></span>';
			echo $my_banner;
			echo '</div>';
		}
		?>

<div class="home-my_banner my_banner">
	<?php
		$this->language->load('information/information');

	    $this->load->model('catalog/information');

	  	$information_id 	= 8;	// Hardcode :D
		$information_info	= $this->model_catalog_information->getInformation($information_id);
//		$this->data['info_heading_title'] = $information_info['title'];
		if( count($information_info) > 0 ){
			$my_banner = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
			echo $my_banner;
		}
	?>
</div>

<div id="content" class="home-page"><?php echo $content_top; ?>
	<h1 style="display: none;"><?php echo $heading_title; ?></h1>
	<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
