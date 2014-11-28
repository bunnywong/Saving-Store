<div id="kbm-archive-<?php echo $module; ?>" class="kuler-archive">
	<div class="box kuler-module">
		<?php if ($show_title) { ?>
		<div class="box-heading"><span><?php echo $title; ?></span></div>
		<?php } ?>
		<div class="box-content horizontal">
			<ul class="treemenu">
				<?php foreach ($year_articles as $year => $month_articles) { ?>
				<li><a href="javascript:void(0);"><?php echo $year; ?><?php if ($year_counter[$year]) echo " ({$year_counter[$year]})"; ?></a>
					<ul>
						<?php foreach ($month_articles as $month => $articles) { ?>
						<li><a href="javascript:void(0);"><?php echo $month; ?><?php if ($month_counter[$year][$month]) echo " ({$month_counter[$year][$month]})"; ?></a>
							<ul>
								<?php foreach ($articles as $article) { ?>
								<li><a href="<?php echo $article['link']; ?>"><?php echo $article['name']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<script>
	$(function () {
		var $container = $('#kbm-archive-<?php echo $module; ?>');

		$container.find('ul ul').css('display', 'none');

		$container.find('a')
			.data('close', 1)
			.on('click', function (evt) {
				var $this = $(this),
					$parentLi = $(this).parent('li');

				if (this.href.indexOf('#') !== -1) {
					evt.preventDefault();
				}

				if ($this.data('close')) {
					$parentLi.children('ul').slideDown();
					$this.data('close', 0);
				} else {
					$parentLi.children('ul').slideUp();
					$this.data('close', 1);
				}
			});
	});
</script>