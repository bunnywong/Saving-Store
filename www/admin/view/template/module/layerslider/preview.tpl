<!DOCTYPE html>

<?php
$typoFile = HTTP_CATALOG . "catalog/view/theme/default/stylesheet/layerslider/css/typo.css";
if (file_exists(DIR_CATALOG . "view/theme/" . $this->config->get('config_template') . "/stylesheet/layerslider/css/typo.css")) {
	$typoFile = HTTP_CATALOG . "catalog/view/theme/" . $this->config->get('config_template') . "/stylesheet/layerslider/css/typo.css";
}

$slide_params = $slider['params'];

?>
<head>
	<!-- get jQuery from the google apis -->
	<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>

	<script type="text/javascript"
		src="<?php echo HTTP_CATALOG; ?>catalog/view/javascript/layerslider/jquery.themepunch.plugins.min.js"></script>
	<script type="text/javascript"
		src="<?php echo HTTP_CATALOG; ?>catalog/view/javascript/layerslider/jquery.themepunch.revolution.js"></script>

	<link rel="stylesheet" href="<?php echo $typoFile; ?>" type="text/css"/>

</head>
<style>
	.rev_slider {
		position: relative;
		overflow: hidden;

	}

	.bannercontainer {
		position: relative;
		margin: 18px auto
	}
</style>

<body class="body-dark">
	<div class="bannercontainer"
		style="width: <?php echo $sliderParams['width']; ?>px; height: <?php echo $sliderParams['height']; ?>px;">
		<div class="banner rev_slider" style="width:<?php echo $sliderParams['width']; ?>px; height:<?php echo $sliderParams['height']; ?>px; ">
			<ul>
				<li data-transition="<?php echo $slide_params['slider_transition']; ?>" data-slotamount="<?php echo $slide_params['slider_slot']; ?>">
					<?php if ($slider['image']) { ?>
						<img src="<?php echo HTTP_CATALOG . "image/" . $slider['image']; 	?>" >
					<?php } ?>

					<?php foreach ($slider['layers'] as $layer) { ?>
					<?php
						$type = $layer['layer_type'];

						$endeffect = '';

						if ($layer['layer_endanimation'] == 'auto')
						{
							$layer['layer_endanimation'] = '' ;
						}

						if((int)$layer['layer_endtime'])
						{
							$endeffect = ' data-end="' . (int)$layer['layer_endtime'] . '"';
							$endeffect .= ' data-endspeed="' . (int)$layer['layer_endspeed'] . '" ';

							if ($layer['layer_endeasing'] != 'nothing')
							{
								$endeffect .= ' data-endeasing="' . $layer['layer_endeasing'] .'" ';	
							}
						}
						else
						{
							$layer['layer_endanimation'] = '' ;
						}
					?>

						<div class="caption <?php echo $layer['layer_class']; ?> <?php echo $layer['layer_animation']; ?> <?php echo $layer['layer_easing']; ?> <?php echo $layer['layer_endanimation']; ?>"
							 data-x="<?php echo $layer['layer_left']; ?>"
							 data-y="<?php echo $layer['layer_top']; ?>"
							 data-speed="<?php echo $layer['layer_speed']; ?>"
							 data-start="<?php echo $layer['time_start']; ?>"
							 data-easing="<?php echo $layer['layer_easing']; ?>" <?php echo $endeffect; ?>>
							 	<?php if ($type=='image') { ?> 
							 		<img src="<?php echo HTTP_CATALOG . "image/" . $layer['layer_content']; ?>">
								<?php } else if($type == 'video') { ?>
									<?php if( $layer['layer_video_type'] == 'vimeo') { ?>
								 		<iframe src="http://player.vimeo.com/video/<?php echo $layer['layer_video_id']; ?>?title=0&amp;byline=0&amp;portrait=0;api=1" width="<?php echo $layer['layer_video_width']; ?>" height="<?php echo $layer['layer_video_height'];?>"></iframe>
								 	<?php } else { ?>
								 		<iframe width="<?php echo $layer['layer_video_width'];?>" height="<?php echo $layer['layer_video_height'];?>" src="http://www.youtube.com/embed/<?php echo $layer['layer_video_id'];?>" frameborder="0" allowfullscreen></iframe>
							 		<?php } ?>
							 	<?php } else { ?>
							 		<?php echo html_entity_decode( str_replace( "_ASM_", "&", $layer['layer_caption']), ENT_QUOTES, 'UTF-8');?>
							 	<?php } ?>
							</div>
					<?php } ?>
				</li>
			</ul>
		</div>
	</div>

	<script>
		jQuery(window).on('load', function () {
			if ($.fn.cssOriginal != undefined) {
				$.fn.css = $.fn.cssOriginal;
			}

			$('.banner').revolution({
				delay: <?php echo json_encode($sliderParams['delay']); ?>,
				startheight: <?php echo json_encode($sliderParams['height']); ?>,
				startwidth: <?php echo json_encode($sliderParams['width']); ?>,

				hideThumbs: 200,

				thumbWidth: <?php echo json_encode($sliderParams['thumbnail_width']); ?>,
				thumbHeight: <?php echo json_encode($sliderParams['thumbnail_height']); ?>,
				thumbAmount: <?php echo json_encode($sliderParams['thumbnail_amount']); ?>,

				navigationType: <?php echo json_encode($sliderParams['navigator_type']); ?>,
				navigationArrows: <?php echo json_encode($sliderParams['navigator_arrows']); ?>,
				navigationStyle: <?php echo json_encode($sliderParams['navigation_style']); ?>,

				navigationHAlign: "center",
				navigationVAlign: "bottom",
				navigationHOffset: 0,
				navigationVOffset: 20,

				soloArrowLeftHalign:"left",
				soloArrowLeftValign:"center",
				soloArrowLeftHOffset:20,
				soloArrowLeftVOffset:0,

				soloArrowRightHalign: "right",
				soloArrowRightValign: "center",
				soloArrowRightHOffset: 20,
				soloArrowRightVOffset: 0,
				touchenabled: <?php echo $sliderParams['touch_mobile'] ? json_encode('on') : json_encode('off'); ?>,
				onHoverStop: <?php echo $sliderParams['stop_on_hover'] ? json_encode('on') : json_encode('off'); ?>,

				navOffsetHorizontal: <?php echo json_encode($sliderParams['offset_horizontal']); ?>,
				navOffsetVertical: <?php echo json_encode($sliderParams['offset_vertical']); ?>,

				hideCaptionAtLimit:0,
				hideAllCaptionAtLilmit:0,
				hideSliderAtLimit: <?php echo json_encode($sliderParams['hide_screen_width']); ?>,

				stopAtSlide:-1,
				stopAfterLoops:-1,

				shadow: <?php echo json_encode($sliderParams['shadow_type']); ?>,
				fullWidth:"off"
			});
		});
	</script>
</body>
</html>	