<div class="comment-stats">
	<?php echo _t('text_x_comments', $comment_total); ?> (<?php echo _t('text_x_replies', $reply_total); ?>)
</div>

<ol class="comment-list">
	<?php for ($i = 0; $i < count($comments); $i++) { ?>
		<?php $comment = $comments[$i]; ?>
		<li id="comment-<?php echo $comment['comment_id']; ?>" class="comment <?php echo ($i % 2) ? 'odd' : 'even'; ?>" itemprop="comment" itemscope="" itemtype="http://schema.org/UserComments">
			<img src="<?php echo $comment['author']['avatar_url']; ?>" class="avatar" />
			<div class="author">
				<span class="author-name" itemprop="creator"><?php echo $comment['author']['name']; ?></span>
				<?php if ($comment['author']['group']) { ?>
					<span class="author-badge<?php if ($comment['author']['badge_color']) echo ' author-badge-color'; ?>"<?php if ($comment['author']['badge_color']) echo ' style="background: ' . $comment['author']['badge_color'] . '"' ?>><?php echo $comment['author']['group']; ?></span>
				<?php } ?>
			</div>
			<div class="date" itemprop="commentTime"><?php echo $comment['date_added_formatted']; ?></div>
			<p class="message" itemprop="commentText"><?php echo $comment['content']; ?></p>
			<div class="reply">
				<a data-comment-id="<?php echo $comment['comment_id']; ?>" class="button"><?php echo _t('button_reply'); ?></a>
			</div>
			<?php if ($comment['reply_total']) { ?>
			<ol class="comment-list reply-list">
				<?php for ($j = 0; $j < count($comment['replies']); $j++) { ?>
				<?php $reply = $comment['replies'][$j]; ?>
				<li class="comment <?php echo ($j % 2) ? 'odd' : 'even'; ?>">
					<img src="<?php echo $reply['author']['avatar_url']; ?>" class="avatar" />
					<div class="author">
						<span class="author-name" itemprop="creator"><?php echo $reply['author']['name']; ?></span>
						<?php if ($reply['author']['group']) { ?>
							<span class="author-badge<?php if ($reply['author']['badge_color']) echo ' author-badge-color'; ?>"<?php if ($reply['author']['badge_color']) echo ' style="background: ' . $reply['author']['badge_color'] . '"' ?>><?php echo $reply['author']['group']; ?></span>
						<?php } ?>
					</div>
					<div class="date" itemprop="commentTime"><?php echo $reply['date_added_formatted']; ?></div>
					<p class="message" itemprop="commentText"><?php echo $reply['content']; ?></p>
				</li>
				<?php } ?>
			</ol>
			<?php } ?>
		</li>
	<?php } ?>
</ol>

<?php if ($pagination) { ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
<?php } ?>

<div id="comment-form-container">
	<div id="comment-form">
		<h4 id="comment-form-title" data-text-reply="<?php echo _t('text_leave_a_reply'); ?>" data-text-comment="<?php echo _t('text_leave_a_comment'); ?>"><?php echo _t('text_leave_a_comment'); ?></h4>
		<a id="reply-cancel" class="button"><?php echo _t('button_cancel_reply'); ?></a>
		<form action="?" id="form-comment">
			<input type="hidden" name="kbm_article_id" value="<?php echo $article_id; ?>" />
			<input type="hidden" name="parent_comment_id" />
			<table>
				<tr>
					<td><span class="required">*</span> <?php echo _t('entry_name'); ?></td>
					<td class="validator" id="validator-name"><input type="text" name="name" value="<?php echo $comment_author['name']; ?>" /></td>
				</tr>
				<tr>
					<td><span class="required">*</span> <?php echo _t('entry_email'); ?></td>
					<td class="validator" id="validator-email"><input type="text" name="email" value="<?php echo $comment_author['email']; ?>" /></td>
				</tr>
				<tr>
					<td><?php echo _t('entry_website'); ?></td>
					<td><input type="text" name="website" value="<?php echo $comment_author['website']; ?>" /></td>
				</tr>
				<tr>
					<td><span class="required">*</span> <?php echo _t('entry_comment'); ?></td>
					<td class="validator" id="validator-content">
						<textarea name="content" cols="60" rows="5"></textarea>
					</td>
				</tr>
				<?php if (isset($captcha_url)) { ?>
					<tr>
						<td><span class="required">*</span> <?php echo _t('entry_captcha'); ?></td>
						<td class="validator" id="validator-captcha">
							<input type="text" name="captcha" />
							<img src="<?php echo $captcha_url; ?>" id="captcha" />
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td></td>
					<td>
						<input type="submit" id="comment-submit" class="button" />
					</td>
				</tr>
			</table>
	</div>
</div>

<script>
	var CommentWritingUrl = '<?php echo $comment_writing_url; ?>';

	var CommentForm = (function () {
		return {
			init: function () {
				var commentForm = this;

				commentForm.$commentFormContainer = $('#comment-form-container');
				commentForm.$commentForm = $('#comment-form');
				commentForm.$form = commentForm.$commentForm.find('#form-comment');
				commentForm.$commentFormTitle = commentForm.$commentForm.find('#comment-form-title');
				commentForm.$parentCommentId = commentForm.$commentForm.find('input[name="parent_comment_id"]');
				commentForm.$replyCancel = commentForm.$commentForm.find('#reply-cancel');
				commentForm.$submit = commentForm.$commentForm.find('#comment-submit');
				commentForm.$validator = commentForm.$commentForm.find('.validator');

				commentForm.commentId = 0;

				commentForm.bindEvents();
				commentForm.$replyCancel.hide();
			},
			bindEvents: function () {
				var commentForm = this;

				commentForm.$replyCancel.on('click', function (evt) {
					evt.preventDefault();
					commentForm.renderCommentForm();
				});

				commentForm.$form.on('submit', function (evt) {
					evt.preventDefault();

					$.ajax({
						url: CommentWritingUrl,
						type: 'POST',
						dataType: 'json',
						data: commentForm.$form.serialize(),
						beforeSend: function () {
							commentForm.$validator.find('.blog-field-error').remove();
							commentForm.$submit.before('<span class="waiting"><img src="catalog/view/theme/default/image/loading.gif"/></span>');
							commentForm.$commentForm.find('.warning').remove();
						},
						complete: function () {
							$('.waiting').remove();
						},
						success: function (response) {
							if (response.status) {
								CommentList.renderPage(1);
							} else {
								commentForm.$commentForm.prepend('<div class="warning">'+ response.message +'</div>');

								if (response.errors) {
									for (var field in response.errors) {
										commentForm.$validator.filter('#validator-' + field).append('<p class="blog-field-error">'+ response.errors[field] +'</p>');
									}
								}
							}
						}
					});
				});
			},
			renderReplyForm: function (commentId, $reply) {
				Comment.showReplyButton(this.commentId);

				this.commentId = commentId;

				this.$commentFormTitle.text(this.$commentFormTitle.data('textReply'));

				this.$replyCancel.show();
				this.$parentCommentId.val(commentId);

				$reply.after(this.$commentForm);
			},
			renderCommentForm: function () {
				Comment.showReplyButton(this.commentId);
				this.commentId = 0;
				this.$parentCommentId.val(0);

				this.$commentFormTitle.text(this.$commentFormTitle.data('textComment'));

				this.$replyCancel.hide();
				this.$commentFormContainer.append(this.$commentForm);
			}
		};
	})();

	var Comment = (function () {
		return {
			init: function () {
				$('.reply a').on('click', function (evt) {
					evt.preventDefault();

					var $replyBtn = $(this),
						$parent = $replyBtn.parent();

					$parent.hide();
					CommentForm.renderReplyForm($replyBtn.data('commentId'), $parent);
				});
			},
			showReplyButton: function (commentId) {
				$('#comment-' + commentId + ' .reply').show();
			}
		};
	})();

	var Pagination = (function () {
		return {
			init: function () {
				$('.pagination a').on('click', function (evt) {
					evt.preventDefault();

					var match = this.href.match(/page=(\d+)/);

					if (match) {
						CommentList.renderPage(match[1]);
					}
				});
			}
		};
	})();

	Comment.init();
	CommentForm.init();
	Pagination.init();
</script>