<?php echo $header; ?><?php echo $kuler_column_left; ?><?php echo $kuler_column_right; ?>

<div id="content" class="<?php echo $kuler_content_top; ?>
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<h1><?php echo $category['name']; ?></h1>
	<div class="image"><img src="<?php echo $category['image_thumb']; ?>" alt="<?php echo $category['name']; ?>" /></div>
	<?php if ($category['description']) { ?>
	<p class="blog-description"> <?php echo $category['description']; ?> </p>
	<?php } ?>
	<?php if ($sub_categories) { ?>
	<div class="sub-categories">
		<h4><?php echo _t('text_sub_categories'); ?></h4>
		<ul>
			<?php foreach ($sub_categories as $sub_category) { ?>
			<li><a href="<?php echo $sub_category['link']; ?>"><?php echo $sub_category['name']; ?></a></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
	<div class="article-list clearafter">
		<?php foreach ($articles as $article) { ?>
		<div class="article grid-<?php echo $column; ?>">
			<div class="article-header">
				<h2><a href="<?php echo $article['link']; ?>"><?php echo $article['name']; ?></a></h2>
				<div class="article-extra-info">
					<?php if ($article['display_author'] || $article['display_category'] || $article['display_date']) { ?>
					<?php echo _t('text_posted'); ?>
					<?php } ?>
					<?php if ($article['display_author']) { ?>
					<?php echo _t('text_by_x', '<a>'. $article['author_name'] .'</a>'); ?>
					<?php } ?>
					<?php if ($article['display_category'] && $article['categories']) { ?>
					<?php echo _t('text_in'); ?>
					<?php $article_links = array(); ?>
					<?php foreach ($article['categories'] as $article_category) {
				                $article_links[] = sprintf('<a href="%s">%s</a>', $article_category['link'], $article_category['name']);
			                } ?>
					<?php echo implode(', ', $article_links); ?>
					<?php } ?>
					<?php if ($article['display_date']) { ?>
					<?php echo _t('text_on'); ?> <?php echo $article['date_added_formatted']; ?>.
					<?php } ?>
				</div>
			</div>
			<div class="article-content"> <a href="<?php echo $article['link']; ?>" class="article-image"><img src="<?php echo $article['featured_image_thumb']; ?>" /></a>
				<p> <?php echo $article['description']; ?> </p>
				<div class="article-read-more">
					<?php if ($article['comment_total']) { ?>
					<a href="<?php echo $article['link']; ?>#comments"><?php echo _t('text_x_comments', $article['comment_total']); ?></a>
					<?php } ?>
					<a href="<?php echo $article['link']; ?>"><?php echo _t('text_read_more'); ?></a> </div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="pagination"> <?php echo $pagination; ?> </div>
	<?php echo $kuler_content_bottom; ?></div>
<?php echo $footer; ?>