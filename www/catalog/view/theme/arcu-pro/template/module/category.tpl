<div class="box">
	<div class="box-heading"><span><?php echo $heading_title; ?></span></div>
	<div class="box-content">
		<ul class="box-category treemenu">
			<?php foreach ($categories as $category) { ?>
			<li>
				<?php if ($category['category_id'] == $category_id) { ?>
				<a href="<?php echo $category['href']; ?>" class="active"><span><?php echo $category['name']; ?></span></a>
				<?php } else { ?>
				<a href="<?php echo $category['href']; ?>"><span><?php echo $category['name']; ?></span></a>
				<?php } ?>
				<?php if ($category['children']) { ?>
				<ul>
					<?php foreach ($category['children'] as $child) { ?>
					<li>
						<?php if ($child['category_id'] == $child_id) { ?>
						<a href="<?php echo $child['href']; ?>" class="active"><span><?php echo $child['name']; ?></span></a>
						<?php } else { ?>
						<a href="<?php echo $child['href']; ?>"><span><?php echo $child['name']; ?></span></a>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
