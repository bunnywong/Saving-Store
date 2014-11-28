<?php echo $header; ?><?php echo $kuler_column_left; ?><?php echo $kuler_column_right; ?>

<div id="content"><?php echo $kuler_content_top; ?>
	<div class="box">
		<div class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
			<?php } ?>
		</div>
		<h1><?php echo $article['name']; ?></h1>
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
		<div class="article">
			<div class="article-content"> <?php echo $article['description']; ?> </div>
			<?php if ($article['display_last_update']) { ?>
			<div class="last-update"> <?php echo _t('text_latest_update'); ?> <?php echo $article['date_modified_formatted']; ?> </div>
			<?php } ?>
		</div>
		<?php if ($addthis_code) { ?>
		<div class="blog-socials"> <?php echo $addthis_code; ?> </div>
		<?php } ?>
		<?php if ($article['display_related_article'] && $related_articles) { ?>
		<div class="related-articles">
			<h4><?php echo _t('text_related_articles'); ?></h4>
			<?php $items_per_column = 2; ?>
			<?php for ($i = 0; $i < ceil(count($related_articles) / $items_per_column); $i++) { ?>
			<ul>
				<?php for ($j = 0; $j < $items_per_column; $j++) { ?>
				<?php if (isset($related_articles[$j + $i * $items_per_column])) { ?>
				<?php $related_article = $related_articles[$j + $i * $items_per_column]; ?>
				<li><a href="<?php echo $related_article['link']; ?>"><?php echo $related_article['name']; ?></a></li>
				<?php } ?>
				<?php } ?>
			</ul>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if ($article['display_comment'] && $comment_status) { ?>
		<div class="comment-container" name="comments" data-comment-loader=".comment-loader"> </div>
		<?php } ?>
	</div>
	<?php echo $kuler_content_bottom; ?> </div>
<?php if ($article['display_comment'] && $comment_status)  { ?>
<script>
	    var ArticleId = <?php echo $article['article_id']; ?>,
		    CommentUrl = '<?php echo $comment_url; ?>',
		    TextPleaseWait = '<?php echo _t('text_please_wait'); ?>';

	    function kbm_onLoadingComment($commentContainer) {
		    $commentContainer.slideUp(1000);
	    }

	    function kbm_onLoadedComment($commentContainer, html) {
		    $commentContainer.html(html).stop(true, true).slideDown(1000);
	    }

        (function () {
			var CommentList = (function () {
				return {
					init: function ($listContainer) {
						this.$listContainer = $listContainer;
						this.renderPage(1);
					},
					renderPage: function (page) {
						var commentList = this;

						kbm_onLoadingComment(commentList.$listContainer);

						$.ajax({
							url: CommentUrl,
							type: 'GET',
							dataType: 'html',
							data: {
								kbm_article_id: ArticleId,
								page: page
							},
							beforeSend: function () {
								commentList.$listContainer
									.after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif"/> '+ TextPleaseWait +'</div>');
							},
							complete: function () {
								$('.attention').fadeOut('fast').remove();
							},
							success: function (html) {
								kbm_onLoadedComment(commentList.$listContainer, html);
							}
						});
					}
				};
			})();

	        CommentList.init($('.comment-container'));

	        window.CommentList = CommentList;
        })();
    </script>
<?php } ?>
<?php echo $footer; ?>