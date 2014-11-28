<?php echo $header; ?>
<div id="content" class="kuler-module">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning" style="margin-top: 15px;"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="success" style="margin-top: 15px;"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading clearafter">
            <h1><img src="./view/kulercore/images/logos/kbm.png" alt="<?php echo _t('heading_module_title'); ?>" /></h1>
            <div class="buttons">
                <a onclick="approve();" class="button"><?php echo _t('button_approve'); ?></a>
                <a onclick="unapprove();" class="button"><?php echo _t('button_unapprove'); ?></a>
                <a onclick="$('#form').submit();" class="button"><?php echo _t('button_delete'); ?></a>
            </div>
        </div>
        <div class="content">
            <ul class="vtabs">
                <li><a href="<?php echo $home_url; ?>"><?php echo _t('text_home'); ?></a></li>
                <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
                <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
                <li><a href="<?php echo $comment_url; ?>" class="selected"><?php echo _t('text_comments'); ?></a></li>
                <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
            </ul>
            <div class="vtabs-content">
                <form action="<?php echo $action; ?>" method="post" id="form">
                    <input type="hidden" name="status" value="" />

                    <div class="sub-heading clearafter">
                        <h3><?php echo $sub_heading; ?></h3>
                    </div>
                    <div class="filter">
                        <a href="<?php echo $filter_reset_url; ?>"><?php echo _t('button_reset_filter'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo _t('entry_filter'); ?>
                        <a href="<?php echo $filter_all_url; ?>"><?php echo _t('button_filter_all'); ?></a> |
                        <a href="<?php echo $filter_approve_url; ?>"><?php echo _t('button_filter_approve'); ?></a> |
                        <a href="<?php echo $filter_unapprove_url; ?>"><?php echo _t('button_filter_unapprove'); ?></a>
                    </div>
                    <table class="list">
                        <thead>
                        <tr>
                            <td class="center"><input type="checkbox" class="CheckAll" data-checkbox=".CommentSelector" /></td>
                            <td class="center"><?php echo _t('entry_article'); ?></td>
                            <td class="center"><?php echo _t('entry_author'); ?></td>
                            <td class="center"><?php echo _t('entry_comment'); ?></td>
                            <td class="center" style="width: 150px;"><?php echo _t('entry_status'); ?></td>
                            <td class="center" style="width: 150px;"><?php echo _t('entry_action'); ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($comments) { ?>
                        <?php foreach ($comments as $comment) { ?>
                        <tr<?php if (!$comment['status']) echo ' class="row-disabled"'; ?>>
                            <td class="center"><input type="checkbox" name="comment_ids[]" value="<?php echo $comment['comment_id']; ?>" class="CommentSelector" /></td>
                            <td class="left"><a href="<?php echo $comment['article_url']; ?>"><?php echo $comment['article_name']; ?></a></td>
                            <td class="left">
                                <div class="author-name"><?php echo $comment['author']['name']; ?></div>
                                <?php if ($comment['author']['email']) { ?>
                                <div class="blog-meta"><?php echo $comment['author']['email']; ?></div>
                                <?php } ?>
                            </td>
                            <td class="left">
                                <div class="blog-meta"><?php echo $comment['date_added_formatted']; ?><?php  if ($comment['parent_comment_id']) {?> | <?php echo _t('text_in_reply_to'); ?> <?php echo $comment['parent_comment']['author']['name']; } ?></div>
                                <div><?php echo $comment['content']; ?></div>
                            </td>
                            <td class="center">
                                <select name="comment[<?php echo $comment['comment_id']; ?>][status]" class="SubmitOnChange" data-url="<?php echo $fast_edit_url; ?>">
                                    <option value="0"<?php if (!$comment['status']) echo ' selected="selected"'; ?>><?php echo _t('text_unapprove'); ?></option>
                                    <option value="1"<?php if ($comment['status']) echo ' selected="selected"'; ?>><?php echo _t('text_approve'); ?></option>
                                </select>
                            </td>
                            <td class="center">
                                <?php foreach ($comment['actions'] as $action) { ?>
                                    [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="100%" class="left"><?php echo _t('text_no_results'); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <?php echo $pagination; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var CheckAll = (function () {
        return {
            init: function (selector) {
                var $selector = $(selector);

                $selector.on('click', function () {
                    var $this = $(this);

                    $($this.data('checkbox')).prop('checked', $this.prop('checked'));
                });
            }
        };
    })();

    var SubmitOnChange = (function () {
        return {
            init: function (selector) {
                var $selector = $(selector);

                $selector.on('change', function () {
                    var $input = $(this), $loading;

                    clearTimeout(this.submitTimeout);

                    this.submitTimeout = setTimeout(function () {
                        $.ajax({
                            url: $input.data('url'),
                            type: 'POST',
                            data: $input.serialize(),
                            dataType: 'json',
                            beforeSend: function () {
                                $loading = $('<span class="loading"></span>').insertAfter($input);
                            },
                            success: function (response) {
                                $loading.remove();

	                            $input.parents('tr').toggleClass('row-disabled');

                                if (response.status) {
                                } else {
                                    console.log(response);
                                    alert(response.message);
                                }
                            }
                        });
                    }, 200);
                });
            }
        };
    })();

    var toggleUrl = '<?php echo $toggle_url; ?>';

    function approve() {
        document.querySelector('input[name=status]').value = 1;

        var form = document.getElementById('form');

        form.action = toggleUrl;
        form.submit();
    }

    function unapprove() {
        document.querySelector('input[name=status]').value = 0;

        var form = document.getElementById('form');

        form.action = toggleUrl;
        form.submit();
    }

    $(function () {
        CheckAll.init('.CheckAll');
        SubmitOnChange.init('.SubmitOnChange');
    });
</script>
<?php echo $footer; ?>