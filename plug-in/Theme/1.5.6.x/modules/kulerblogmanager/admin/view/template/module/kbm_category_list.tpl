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
                <a href="<?php echo $insert_url; ?>" class="button"><?php echo _t('button_insert'); ?></a>
                <a onclick="$('#form').attr('action', '<?php echo $copy_url; ?>'); $('#form').submit();" class="button"><?php echo _t('button_copy'); ?></a>
                <a onclick="$('#form').submit();" class="button"><?php echo _t('button_delete'); ?></a>
            </div>
        </div>
        <div class="content">
            <ul class="vtabs">
                <li><a href="<?php echo $home_url; ?>"><?php echo _t('text_home'); ?></a></li>
                <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
                <li><a href="<?php echo $category_url; ?>" class="selected"><?php echo _t('text_categories'); ?></a></li>
                <li><a href="<?php echo $comment_url; ?>"><?php echo _t('text_comments'); ?></a></li>
                <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
            </ul>
            <div class="vtabs-content">
                <div class="sub-heading">
                    <h3><?php echo _t('text_category_list'); ?></h3>
                </div>
                <form action="<?php echo $action; ?>" method="post" id="form">
                    <table class="list">
                        <thead>
                        <tr>
                            <td class="center"><input type="checkbox" class="CheckAll" data-checkbox=".CategoryCheckbox" /></td>
                            <td class="center" style="width: 50px"><?php echo _t('entry_id'); ?></td>
                            <td class="center"><?php echo _t('entry_category_name'); ?></td>
                            <td class="center"><?php echo _t('entry_seo_url'); ?></td>
                            <td class="center" style="width: 150px;"><?php echo _t('entry_status'); ?></td>
                            <td class="center" style="width: 150px;"><?php echo _t('entry_order'); ?></td>
                            <td class="center"><?php echo _t('entry_action'); ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($categories) { ?>
                        <?php foreach ($categories as $category) { ?>
                        <tr<?php if (!$category['status']) echo ' class="row-disabled"' ?>>
                            <td class="center"><input type="checkbox" name="category_ids[]" value="<?php echo $category['category_id']; ?>" class="CategoryCheckbox" /></td>
                            <td class="center"><?php echo $category['category_id']; ?></td>
                            <td class="left"><?php echo $category['name']; ?></td>
                            <td class="left"><?php echo $category['keyword']; ?></td>
                            <td class="center">
                                <select name="category[<?php echo $category['category_id']; ?>][status]" class="SubmitOnChange" data-url="<?php echo $fast_edit_url; ?>">
                                    <option value="1"<?php if ($category['status'] == 1) echo ' selected="selected"'; ?>><?php echo _t('text_enabled'); ?></option>
                                    <option value="0"<?php if ($category['status'] == 0) echo ' selected="selected"'; ?>><?php echo _t('text_disabled'); ?></option>
                                </select>
                            </td>
                            <td class="center">
                                <input type="text" size="5" name="category[<?php echo $category['category_id']; ?>][sort_order]" value="<?php echo $category['sort_order']; ?>" class="SubmitOnChange" data-url="<?php echo $fast_edit_url; ?>" />
                            </td>
                            <td class="center">
                                <?php foreach ($category['actions'] as $action) { ?>
                                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
						<?php } else { ?>
	                        <tr>
		                        <td class="left" colspan="100%"><?php echo _t('text_no_results'); ?></td>
	                        </tr>
						<?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
        CheckAll.init('.CheckAll');
        SubmitOnChange.init('.SubmitOnChange');
    });
</script>
<?php echo $footer; ?>