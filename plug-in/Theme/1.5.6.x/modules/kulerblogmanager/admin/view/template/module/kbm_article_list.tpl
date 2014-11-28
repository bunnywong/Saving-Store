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
            <h1><img src="./view/kulercore/images/logos/kbm.png" alt="<?php echo _t('sub_heading_edit_category'); ?>" /></h1>
            <div class="buttons">
                <a href="<?php echo $insert_url; ?>" class="button"><?php echo _t('button_insert'); ?></a>
                <a onclick="$('#form').attr('action', '<?php echo $copy_url; ?>').submit();" class="button"><?php echo _t('button_copy'); ?></a>
                <a onclick="$('#form').submit();" class="button"><?php echo _t('button_delete'); ?></a>
            </div>
        </div>
        <div class="content">
            <ul class="vtabs">
                <li><a href="<?php echo $home_url; ?>"><?php echo _t('text_home'); ?></a></li>
                <li><a href="<?php echo $article_url; ?>" class="selected"><?php echo _t('text_articles'); ?></a></li>
                <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
                <li><a href="<?php echo $comment_url; ?>"><?php echo _t('text_comments'); ?></a></li>
                <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
            </ul>
            <div class="vtabs-content article-page">
                <div class="sub-heading">
                    <h3><?php echo _t('text_article_list'); ?></h3>
                </div>
                <div class="filter-options">
                    <a href="<?php echo $reset_url; ?>"><?php echo _t('button_reset'); ?></a> -
                    <a href="#" id="ToggleFilter"><?php echo _t('button_show_filter'); ?></a>
                </div>
                <div class="filter">
                    <h4><?php echo _t('text_filter_options'); ?></h4>

                    <form action="<?php echo $filter_action; ?>">
                        <input type="hidden" name="route" value="<?php echo $route; ?>" />
                        <input type="hidden" name="token" value="<?php echo $token; ?>" />
                        <table class="form">
                            <tr>
                                <th><?php echo _t('entry_date_created_start'); ?></th>
                                <td>
                                    <input type="text" class="input-md DatePicker" name="filters[date_added_start]" value="<?php if (isset($filters['date_added_start'])) echo $filters['date_added_start']; ?>" />
                                </td>
                                <th><?php echo _t('entry_date_created_end'); ?></th>
                                <td>
                                    <input type="text" class="input-md DatePicker" name="filters[date_added_end]" value="<?php if (isset($filters['date_added_end'])) echo $filters['date_added_end']; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo _t('entry_category'); ?></th>
                                <td>
                                    <select name="filters[category_id]" class="input-lg">
                                        <option value=""><?php echo _t('text_all'); ?></option>
                                        <?php foreach ($category_options as $category_id => $category_name) { ?>
                                        <option value="<?php echo $category_id; ?>"<?php if (isset($filters['category_id']) && $filters['category_id'] == $category_id) echo ' selected="selected"'; ?>><?php echo $category_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <th><?php echo _t('entry_author'); ?></th>
                                <td>
                                    <select name="filters[author_id]" class="input-md">
                                        <option value=""><?php echo _t('text_all'); ?></option>
                                        <?php foreach ($author_options as $author_id => $author_name) { ?>
                                        <option value="<?php echo $author_id; ?>"<?php if (isset($filters['author_id']) && $filters['author_id'] == $author_id) echo ' selected="selected"'; ?>><?php echo $author_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo _t('entry_status'); ?></th>
                                <td>
                                    <select name="filters[status]" class="input-md">
                                        <option value=""><?php echo _t('text_all'); ?></option>
                                        <option value="1"<?php if (isset($filters['status']) && $filters['status']) echo ' selected="selected"'; ?>><?php echo _t('text_enabled'); ?></option>
                                        <option value="0"<?php if (isset($filters['status']) && !$filters['status']) echo ' selected="selected"'; ?>><?php echo _t('text_disabled'); ?></option>
                                    </select>
                                </td>
                                <th><?php echo _t('entry_store'); ?></th>
                                <td>
                                    <select name="filters[store_id]" class="input-md">
                                        <option value=""><?php echo _t('text_all'); ?></option>
                                        <?php foreach ($store_options as $store_id => $store_name) { ?>
                                        <option value="<?php echo $store_id; ?>"<?php if (isset($filters['store_id']) && $filters['store_id'] == $store_id) echo ' selected="selected"'; ?>><?php echo $store_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div class="filter-buttons">
                            <button class="button" type="submit"><?php echo _t('button_apply_filter'); ?></button>
                        </div>
                    </form>
                </div>
                <form action="<?php echo $delete_url; ?>" method="post" id="form">
                    <table class="list">
                        <thead>
                        <tr>
                            <td class="center" style="width: 30px"><input type="checkbox" class="CheckAll" data-checkbox=".ArticleSelector" /></td>
                            <td class="center"><a href="<?php echo $order_article_title_url; ?>"<?php if ($order == 'article_title') echo ' class="'. $order_direction .'"'; ?>><?php echo _t('entry_article_title'); ?></a></td>
                            <td class="center" style="width: 100px;"><?php echo _t('entry_seo_url'); ?></td>
                            <td class="center" style="width: 80px;"><a href="<?php echo $order_date_added_url; ?>"<?php if ($order == 'date_added') echo ' class="'. $order_direction .'"'; ?>><?php echo _t('entry_created'); ?></a></td>
                            <td class="center" style="width: 170px;"><?php echo _t('entry_category'); ?></td>
                            <td class="center" style="width: 100px;"><a href="<?php echo $order_author_url; ?>"<?php if ($order == 'author_name') echo ' class="'. $order_direction .'"'; ?>><?php echo _t('entry_author'); ?></a></td>
                            <td class="center" style="width: 120px;"><?php echo _t('entry_store'); ?></td>
                            <td class="center" style="width: 100px;"><?php echo _t('entry_status'); ?></td>
                            <td class="center" style="width: 100px;"><a href="<?php echo $order_order_url; ?>"<?php if ($order == 'sort_order') echo ' class="'. $order_direction .'"'; ?>><?php echo _t('entry_order'); ?></a></td>
                            <td class="center" style="width: 50px;"><?php echo _t('entry_action'); ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($articles) { ?>
                        <?php foreach ($articles as $article) { ?>
                        <tr<?php if (!$article['status']) echo ' class="row-disabled"'; ?>>
                            <td class="center"><input type="checkbox" name="article_ids[]" value="<?php echo $article['article_id']; ?>" class="ArticleSelector" /></td>
                            <td class="left"><?php echo $article['name']; ?></td>
                            <td class="left"><?php echo $article['keyword']; ?></td>
                            <td class="center"><?php echo $article['date_added_formatted']; ?></td>
                            <td class="left">
                                <?php echo implode('<br />', $article['categories']); ?>
                            </td>
                            <td class="left"><?php echo $article['author_name']; ?></td>
                            <td class="left"><?php echo implode('<br />', $article['stores']); ?></td>
                            <td class="center">
                                <select name="articles[<?php echo $article['article_id']; ?>][status]" class="SubmitOnChange" data-url="<?php echo $fast_edit_url; ?>">
                                    <option value="1"<?php if ($article['status']) echo ' selected="selected"'; ?>><?php echo _t('text_enabled'); ?></option>
                                    <option value="0"<?php if (!$article['status']) echo ' selected="selected"'; ?>><?php echo _t('text_disabled'); ?></option>
                                </select>
                            </td>
                            <td class="center">
                                <input type="text" size="5" name="articles[<?php echo $article['article_id']; ?>][sort_order]" class="SubmitOnChange" data-url="<?php echo $fast_edit_url; ?>" value="<?php echo $article['sort_order']; ?>" />
                            </td>
                            <td class="center">
                                <?php foreach ($article['actions'] as $action) { ?>
                                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="100%" class="left">
                                    <?php echo _t('text_no_results'); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php if ($articles) { ?>
                    <div class="pagination">
                        <?php echo $pagination; ?>
                    </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var DatePicker = (function () {
        return {
            init: function (selector) {
                $(selector).datepicker({
                    dateFormat: 'yy-m-dd'
                });
            }
        };
    })();

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

	                            if ($input.is('select'))
	                            {
		                            $input.parents('tr').toggleClass('row-disabled');
	                            }

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

    $(function () {
        DatePicker.init('.DatePicker');
        CheckAll.init('.CheckAll');
        SubmitOnChange.init('.SubmitOnChange');

        $('#ToggleFilter').on('click', function (evt) {
            evt.preventDefault();
            $('.filter').slideToggle();
        });
    });

</script>
<?php echo $footer; ?>