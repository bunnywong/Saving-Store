<?php echo $header; ?><?php echo $kuler_column_left; ?><?php echo $kuler_column_right; ?>

<div id="content"><?php echo $kuler_content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<?php if ($show_title) { ?>
		<h1><?php echo $heading_title; ?></h1>
		<?php } ?>
		<?php if ($blog_description) { ?>
		<p class="blog-description"> <?php echo $blog_description; ?> </p>
		<?php } ?>
		<?php if ($feed) { ?>
		<p class="blog-feed"><a href="<?php echo $feed_url; ?>"><img src="catalog/view/kulercore/images/kbm_feed.png" /></a></p>
		<?php } ?>
		<?php if ($articles) { ?>
		<div class="article-list clearafter">
			<?php foreach ($articles as $article) { ?>
			<div class="article grid-<?php echo $column; ?>">
				<div class="article-header">
					<h2><a href="<?php echo $article['link']; ?>"><?php echo $article['name']; ?></a></h2>
                    <div class="article-extra-info">
                        <?php if ($article['display_author']) { ?>
                        <?php echo '<span class="author vcard">'; echo _t('text_by_x', '<a rel="author">'. $article['author_name'] .'</a></span>'); ?>
                        <?php } ?>

                        <?php if ($article['display_category'] && $article['categories']) { ?>
                        <?php echo '<span class="category">'; ?>
                        <?php echo _t('text_in'); ?>
                        <?php $article_links = array(); ?>
                        <?php foreach ($article['categories'] as $article_category) {
                            $article_links[] = sprintf('<a href="%s">%s</a>', $article_category['link'], $article_category['name']);
                        } ?>
                        <?php echo implode(', ', $article_links); ?>
                        <?php echo '</span>'; ?>
                        <?php } ?>

                        <?php if ($article['display_date']) { ?>
                        <?php echo '<span class="entry-date">' ; echo _t('text_on'); echo '<time>'; ?>  <?php echo $article['date_added_formatted']; echo '</time></span>'; ?>.
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
		<?php } else { ?>
		<p><?php echo _t('text_no_articles'); ?></p>
		<?php } ?>
	</div>
	<?php echo $kuler_content_bottom; ?></div>
<?php echo $footer; ?>