<style>
    <?php if ($data['transition'] == 5) { ?>
    .kuler-css3-slideshow {
        height: <?php echo $data['height']; ?>px;
    }
    <?php } ?>

    <?php if ($data['slideshow_type'] == 'split') { ?>
	.kuler-css3-slideshow .slidenav {
		margin-bottom: -<?php echo $data['height'] - 30; ?>px;
	}

	.kuler-css3-slideshow .slide_container {
        <?php if ($data['dimension'] == 'fixed') { ?>
			width: <?php echo $data['width']; ?>px;
		<?php } ?>
		height: <?php echo $data['height']; ?>px;
	}

	.kuler-css3-slideshow .slide_container section {
		width: <?php echo 100 / $data['split']; ?>%;
	}

	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.kuler-css3-slideshow #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?>,
	<?php } ?>
	.kuler-css3-slideshow #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?> {
		z-index: 0;
		transition: none;
		-moz-transition: none;
		-webkit-transition: none;
	}

	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.kuler-css3-slideshow #pag_<?php echo $i; ?>:checked ~ #button_<?php echo $i; ?>,
	<?php } ?>
	.kuler-css3-slideshow #pag_<?php echo $i; ?>:checked ~ #button_<?php echo $i; ?> {
		background-color: #999;
		box-shadow: 0 0 2px #ccc;
		-moz-box-shadow: 0 0 2px #ccc;
		-webkit-box-shadow: 0 0 2px #ccc;
	}

	/* STYLE 1 */
	.slide.style-1 .slide_container span {
		opacity: 1;
		transition: top 0.8s ease-out, opacity 1s ease-in, box-shadow 1s ease;	
		-moz-transition: top 0.8s ease-out, opacity 1s ease-in, box-shadow 1s ease;	
		-webkit-transition: top 0.8s ease-out, opacity 1s ease-in, box-shadow 1s ease;
	}

	.slide.style-1 .input:checked ~ .slide_container span {
		z-index: 1; 
		opacity: 0.5;
		box-shadow: 0 0 1px rgba(0,0,0,0.5) inset;
	}

	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.slide.style-1 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?>,
	<?php } ?>
	.slide.style-1 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?> {
		top: 0;
		opacity: 1;
		box-shadow: none;
	}

	<?php for ($i = 0; $i < $data['split']; $i++) { ?>
		.slide.style-1 .split-<?php echo ($i + 1); ?> span {
			-transition-delay: <?php echo $i * 0.05; ?>s;
			-moz-transition-delay: <?php echo $i * 0.05; ?>s;
			-webkit-transition-delay: <?php echo $i * 0.05; ?>s;
		}
	<?php } ?>

	/* STYLE 2 */
	.slide.style-2 .input:checked ~ .slide_container span {
		left: <?php echo $data['width']; ?>px;
	}
	
	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.slide.style-2 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?>,
	<?php } ?>
	.slide.style-2 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?> {
		left: 0;
		opacity: 1;
	}

	/* STYLE 3 */
	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.slide.style-3 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?>,
	<?php } ?>
	.slide.style-3 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?> {
		top: 0;
		opacity: 1;
	}

	.slide.style-3 .input:checked ~ .slide_container section:nth-child(2n) span {
		top: -<?php echo $data['height']; ?>px;
	}	
	.slide.style-3 .input:checked ~ .slide_container section:nth-child(2n+1) span {
		top: <?php echo $data['height']; ?>px;
	}

	<?php for ($i = 0; $i < $data['split']; $i++) { ?>
		.slide.style-3 .split-<?php echo ($i + 1); ?> span {
			-transition-delay: <?php echo $i * 0.05; ?>s;
			-moz-transition-delay: <?php echo $i * 0.05; ?>s;
			-webkit-transition-delay: <?php echo $i * 0.05; ?>s;
		}
	<?php } ?>

	/* STYLE 4 */
	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.slide.style-4 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?>,
	<?php } ?>
	.slide.style-4 #pag_<?php echo $i; ?>:checked ~ .slide_container .picture-<?php echo $i; ?> {
		opacity: 1;
		transform: scale(1);
		-moz-transform: scale(1);
		-webkit-transform: scale(1);
	}

	<?php for ($i = 0; $i < $data['split']; $i++) { ?>
		.slide.style-4 .split-<?php echo ($i + 1); ?> span {
			-transition-delay: <?php echo $i * 0.05; ?>s;
			-moz-transition-delay: <?php echo $i * 0.05; ?>s;
			-webkit-transition-delay: <?php echo $i * 0.05; ?>s;
		}
	<?php } ?>
    <?php } ?>

	 /* COVER LINK */
	.slide .cover-link {
		position: absolute;
		z-index: 1;
		display: none;
		width: 100%;
		height: 100%;
		left: 0;
		top: 0;
	}

	<?php for ($i = 1; $i < count($data['images']); $i++) { ?>
		.slide #pag_<?php echo $i; ?>:checked ~ .slide_container #cover-link-<?php echo $i ?>,
	<?php } ?>
	.slide #pag_<?php echo $i; ?>:checked ~ .slide_container #cover-link-<?php echo $i ?> {
		display: block;
	}

	/* STYLE 5 */
	.slide.style-5 .jcarousel-clip {
		height: <?php echo $data['height']; ?>px;
	}

	<?php if ($data['slideshow_type'] == 'full-screen') { ?>
        <?php for ($i = 0; $i < count($data['images']); $i++) { ?>
            <?php $image = $data['images'][$i]; ?>

			.slide_container ul li:nth-child(<?php echo $i + 1; ?>) span {
				background: url(<?php echo $image['image'] ?>);
			}
        <?php } ?>
	<?php } ?>
</style>
<div class="kuler-css3-slideshow <?php echo $data['dimension']; ?> <?php echo $data['bullet'] ? 'bullet' : ''; ?> <?php echo $data['slideshow_type']; ?>">
	<div class="box kuler-module">
		<?php if($show_title) { ?>
		<div class="box-heading"><span><?php echo $moduleTitle ?></span></div>
		<?php } ?>
		<div class="box-content slide style-<?php echo $data['transition']; ?><?php if ($data['transition'] == 5) echo ' jcarousel' ?>">
			<?php if ($data['transition'] != 5) { ?>
				<?php $imageRow = 1; ?>
				<?php foreach ($data['images'] as $image) { ?>
					<input type="radio" name="slide[<?php echo $module; ?>]" id="pag_<?php echo $imageRow; ?>" class="input"<?php if ($data['start_slide'] == $imageRow) echo ' checked="checked"'; ?> data-index="<?php echo $imageRow; ?>" />
					<?php if (!isset($data['bullet']) || $data['bullet']) { ?>
						<label for="pag_<?php echo $imageRow; ?>" class="slidenav" id="button_<?php echo $imageRow; ?>"></label>
					<?php } ?>
					<?php $imageRow++; ?>
				<?php } ?>
			<?php } ?>

			<div class="slide_container<?php if ($data['transition'] == 5) echo ' jcarousel-inner' ?><?php if (!isset($data['navigation']) || $data['navigation']) echo ' jcarousel-container' ?>">
				<?php if ($data['transition'] == 5) { ?>
					<ul class="jcarousel-skin-opencart">
						<?php foreach ($data['images'] as $image) { ?>
						<li>
							<?php if (!empty($image['link'])) { ?>
							<a href="<?php echo $image['link']; ?>" target="_blank">
							<?php } ?>
	
								<img src="<?php echo $image['image']; ?>" />
	
							<?php if (!empty($image['link'])) { ?>
							</a>
							<?php } ?>
						</li>
						<?php } ?>
					</ul>
				<?php } else { ?>
				<?php if ($data['slideshow_type'] == 'full-screen') { ?>
					<ul>
						<?php foreach ($data['images'] as $image) { ?>
							<li><span></span></li>
						<?php } ?>
					</ul>
				<?php } else { ?>
				<?php for ($i = 1; $i <= $data['split']; $i++) { ?>
				<section class="split split-<?php echo $i; ?>">
					<?php $imageRow = 1; ?>
					<?php foreach ($data['images'] as $image) { ?>
						<span class="picture-<?php echo $imageRow; ?>" data-image-index="<?php echo $imageRow; ?>"><img src="<?php echo $image['image']; ?>" data-index="<?php echo ($i - 1); ?>" /></span>
					<?php $imageRow++; ?>
					<?php } ?>
				</section>
				<?php } ?>
				<?php } ?>

				<?php $imageRow = 1; ?>
				<?php foreach ($data['images'] as $image) { ?>
					<?php if (!empty($image['link'])) { ?>
						<a href="<?php echo $image['link']; ?>" id="cover-link-<?php echo $imageRow; ?>" class="cover-link" target="_blank"></a>
					<?php } ?>
					<?php $imageRow++; ?>
				<?php } ?>

                <?php if (!isset($data['navigation']) || $data['navigation']) { ?>
                    <a href="#" class="jcarousel-prev-horizontal"></a>
                    <a href="#" class="jcarousel-next-horizontal"></a>
                <?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script>
    $(function () {
        <?php if ($data['transition'] != 5) { ?>
        var type = '<?php echo $data['dimension']; ?>',
	        defaultWidth = <?php echo $data['default_width']; ?>,
	        width = <?php echo $data['width']; ?>,
            height = <?php echo $data['height']; ?>,
            autoplay = <?php echo $data['autostart']; ?>,
            split = <?php echo $data['split']; ?>,
            navigation = <?php echo !isset($data['navigation']) || $data['navigation'] ? 'true' : 'false'; ?>,
            bullet = <?php echo !isset($data['bullet']) || $data['bullet'] ? 'true' : 'false'; ?>;

        var $window = $(window),
            $moduleContainer = $('.kuler-css3-slideshow'),
			$slideWrapper = $('.kuler-css3-slideshow .slide'),
            $slideContainer = $moduleContainer.find('.slide_container'),
            $section = $moduleContainer.find('section'),
            $nav = $moduleContainer.find('.jcarousel-next-horizontal, .jcarousel-prev-horizontal'),
            sectionWidth = $section.width();

        $window.on('resize', function () {
            var windowWidth = $window.width(),
                rWidth = $slideWrapper.width(),
                rHeight, widthUnit;

	        if (type == 'fluid') {
		        rWidth = $window.width();
		        rWidth += split - (rWidth % split);
	        } else if (type == 'fixed') {
		        if ($moduleContainer.width() >= defaultWidth) {
			        $slideWrapper.width(defaultWidth);
			        rWidth = width;
		        } else {
			        rWidth = $moduleContainer.width();
			        $slideWrapper.width(rWidth);

			        rWidth = rWidth + (split - (rWidth % split));
		        }
	        }

	        rHeight = (rWidth * height) / width;

	        widthUnit = rWidth / split;

            $moduleContainer.css({
                height: rHeight
            });

            $slideWrapper.css({
                height: rHeight
            });

            $slideContainer.css({
                width: rWidth,
                height: rHeight
            });

            $section.each(function (index) {
                $(this).css({
                    width: widthUnit,
                    left: index * widthUnit
                });
            });

            $section.find('span img').each(function () {
	            var $this = $(this), imgWidth, imgHeight, offsetX = 0, offsetY = 0,
		            parentWidth = $this.parent().width(),
		            parentHeight = $this.parent().height();

	            if (rWidth / this.width >= rHeight / this.height) {
		            imgWidth = rWidth;
		            imgHeight = 'auto';

		            var rImgHeight = imgWidth * this.height / this.width;
		            offsetY = parseInt((rImgHeight - rHeight) / 2);
	            } else {
		            imgWidth = 'auto';
		            imgHeight = $this.parent().height();

		            var rImgWidth = imgHeight * this.width / this.height;
		            offsetX = parseInt((rImgWidth - rWidth) / 2);
	            }

				$this
					.width(imgWidth)
					.height(imgHeight)
					.css({
		                marginLeft: -1 * parseInt($this.data('index')) * widthUnit - offsetX,
						marginTop: -1 * offsetY
	                });
            });

            $moduleContainer.find('.slidenav').css({
                marginBottom: -1 * (rHeight - 30)
            });
        });

        $window.trigger('resize');

	    // Fix bug: Slide image load after resizing window
	    $section.eq(0).find('span img').on('load', function () {
		    $window.trigger('resize');
	    });

        var timer, current = <?php echo $data['start_slide']; ?>,
            slideCount = <?php echo count($data['images']) ?>,
            duration = <?php echo $data['duration']; ?>;

        function play(current) {
            clearTimeout(timer);

            $('#pag_' + current).prop('checked', true);

            if (autoplay) {
                timer = setTimeout(playLoop, duration);
            }
        }

        <?php if (!isset($data['navigation']) || $data['navigation']) { ?>
            $nav.on('click', function (evt) {
                evt.preventDefault();

                var $this = $(this);

                if ($this.hasClass('jcarousel-next-horizontal')) {
                    current = current + 1 > slideCount ? 1 : current + 1
                } else {
                    current = current - 1 < 1 ? slideCount : current - 1;
                }

                play(current);
            });
        <?php } ?>

        <?php if ($data['autostart']) { ?>
            function playLoop() {
                current = current + 1 > slideCount ? 1 : current + 1;
                play(current);
            }

            timer = setTimeout(playLoop, duration);
        <?php } ?>

        <?php if (!isset($data['bullet']) || $data['bullet']) { ?>
        $moduleContainer.find('.input').on('click', function () {
            current = $(this).data('index');
            play(current);
        });
        <?php } ?>

        <?php } else { ?>
            $('.slide_container ul').jcarousel({
                <?php if ($data['autostart']) { ?>
                auto: <?php echo round($data['duration'] / 1000) ?>,
                <?php } ?>
                wrap: 'circular',
                vertical: false,
                visible: 1,
                scroll: 1
            });
        <?php } ?>
    });
</script>