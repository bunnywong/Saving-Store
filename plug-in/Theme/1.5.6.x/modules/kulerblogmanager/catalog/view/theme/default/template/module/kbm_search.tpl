<?php echo $header; ?><?php echo $kuler_column_left; ?><?php echo $kuler_column_right; ?>
    <div id="content" class="blog-column-<?php echo $column; ?>"><?php echo $kuler_content_top; ?>
        <div class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php } ?>
        </div>
        <h1><?php echo $heading_title; ?></h1>

	    <?php if ($articles) { ?>
        <div class="article-list">
            <?php foreach ($articles as $article) { ?>
            <div class="article">
                <div class="article-header">
                    <h3><a href="<?php echo $article['link']; ?>"><?php echo $article['name']; ?></a></h3>
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
                <div class="article-content">
                    <a href="<?php echo $article['link']; ?>" class="article-image"><img src="<?php echo $article['featured_image_thumb']; ?>" /></a>
                    <p>
                        <?php echo $article['description']; ?>
                    </p>
                    <div class="article-read-more">
                        <a href="<?php echo $article['link']; ?>"><?php echo _t('text_read_more'); ?></a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="pagination">
            <?php echo $pagination; ?>
        </div>
	    <?php } else { ?>
		    <p><?php echo _t('text_there_is_no_article_that_match_the_search_criteria'); ?></p>
	    <?php } ?>
        <?php echo $kuler_content_bottom; ?></div>
<?php echo $footer; ?>