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
    </div>
    <div class="content">
    <input type="hidden" name="op" id="op" />
    <ul class="vtabs">
        <li><a href="<?php echo $home_url; ?>"><?php echo _t('text_home'); ?></a></li>
        <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
        <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
        <li><a href="<?php echo $comment_url; ?>"><?php echo _t('text_comments'); ?></a></li>
        <li><a href="<?php echo $author_url; ?>" class="selected"><?php echo _t('text_authors'); ?></a></li>
        <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
    </ul>
    <div class="vtabs-content">
        <div class="htabs">
            <a href="#AuthorListPanel" id="AuthorTab_1" class="AuthorTab" data-tab="1"><?php echo _t('text_authors'); ?></a>
            <a href="#AuthorPermssionPanel" id="AuthorTab_2" class="AuthorTab" data-tab="2"><?php echo _t('text_permission'); ?></a>
        </div>
        <div id="AuthorListPanel">
            <table class="author-layout">
                <tr>
                    <td class="author-list-container">
                        <form action="<?php echo $action; ?>" method="post">
                            <table class="list">
                                <thead>
                                <tr>
                                    <td class="left"><input type="checkbox" class="CheckAll" data-checkbox=".AuthorSelector" /></td>
                                    <td class="left"><?php echo _t('entry_author_name'); ?></td>
                                    <td class="left"><?php echo _t('entry_group'); ?></td>
                                    <td class="left"><?php echo _t('entry_user_name'); ?></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($authors as $author) { ?>
                                    <tr>
                                        <td class="left"><input type="checkbox" name="author_ids[]" value="<?php echo $author['author_id']; ?>" class="AuthorSelector" /></td>
                                        <td class="left"><?php echo $author['name']; ?></td>
                                        <td class="left"><?php echo $author['group_name']; ?></td>
                                        <td class="left"><?php echo $author['username']; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <div class="author-actions">
                                <button type="submit" name="act" value="remove_author" class="button"><?php echo _t('button_delete'); ?></button>
                            </div>
                        </form>
                    </td>
                    <td class="author-form-container">
                        <form action="<?php echo $action; ?>" method="post">
                            <table class="list">
                                <thead>
                                <tr>
                                    <td class="left" colspan="100%"><?php echo _t('entry_add_author'); ?></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="left"><?php echo _t('entry_user'); ?></td>
                                    <td class="left">
                                        <select name="user_id" class="input-md">
                                            <?php foreach ($user_options as $user_id => $user_name) { ?>
                                                <option value="<?php echo $user_id; ?>"<?php if ($user_id == $new_author['user_id']) echo ' selected="selected"'; ?>><?php echo $user_name; ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php if (isset($error_add_user)) { ?>
                                            <p class="error"><?php echo $error_add_user; ?></p>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left"><?php echo _t('entry_author_name'); ?></td>
                                    <td class="left">
                                        <input type="text" name="name" class="input-md" value="<?php echo $new_author['name']; ?>" />
                                        <?php if (isset($error_add_author_name)) { ?>
                                            <p class="error"><?php echo $error_add_author_name; ?></p>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left"><?php echo _t('entry_set_author_group'); ?></td>
                                    <td class="left">
                                        <select name="group" class="input-md">
                                            <?php foreach ($author_group_options as $group_id => $group_name) { ?>
                                                <option value="<?php echo $group_id ?>"<?php if ($group_id == $new_author['group']) echo ' selected="selected"'; ?>><?php echo $group_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="100%" class="right author-actions">
                                        <button type="submit" name="act" value="add_author" class="button"><?php echo _t('button_submit'); ?></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>

                        <form action="<?php echo $action; ?>" method="post">
                            <table class="list">
                                <thead>
                                <tr>
                                    <td colspan="2" class="left"><?php echo _t('entry_rename_author'); ?></td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="left"><?php echo _t('entry_author_name'); ?></td>
                                    <td class="left">
                                        <select name="author_id" class="input-md">
                                            <?php foreach ($authors as $author) { ?>
                                                <option value="<?php echo $author['author_id']; ?>"<?php if ($author['author_id'] == $rename['author_id']) echo ' selected="selected"'; ?>><?php echo $author['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left"><?php echo _t('entry_rename'); ?></td>
                                    <td class="left">
                                        <input type="text" name="new_name" value="<?php echo $rename['new_name']; ?>" class="input-md" />
                                        <?php if (isset($error_edit_rename)) { ?>
                                        <p class="error"><?php echo $error_edit_rename; ?></p>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="author-actions right">
                                        <button type="submit" name="act" value="edit_author" class="button"><?php echo _t('button_submit'); ?></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </div>

        <div id="AuthorPermssionPanel">
            <form action="<?php echo $action; ?>" method="post">
                <table class="list perm-list">
                    <thead>
                    <tr>
                        <td class="center"><?php echo _t('text_author'); ?></td>
                        <td class="center"><?php echo _t('text_editor'); ?></td>
                        <td class="center"><?php echo _t('text_admin'); ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="left">
                            <?php foreach ($group_author_permissions as $section_permission_name => $group_permissions) { ?>
                                <fieldset class="perm-group">
                                    <legend>
                                        <label for="perm-group-author-<?php echo $section_permission_name; ?>"><input type="checkbox" id="perm-group-author-<?php echo $section_permission_name; ?>" class="CheckAll" data-checkbox=".AuthorPermissionCheckbox_<?php echo $section_permission_name; ?>" /> <?php echo _t('permission_' . $section_permission_name); ?></label>
                                    </legend>
                                    <div class="perm-container">
                                        <?php foreach ($group_permissions as $group_permission_value => $group_permission_name) { ?>
                                            <label for="perm-author-<?php echo $group_permission_value; ?>">
                                                <input type="checkbox" id="perm-author-<?php echo $group_permission_value; ?>" class="AuthorPermissionCheckbox_<?php echo $section_permission_name; ?>" name="perms[5][]" value="<?php echo $group_permission_value; ?>"<?php if (in_array($group_permission_value, $author_permissions)) echo ' checked="checked"'; ?> /> <?php echo $group_permission_name; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </fieldset>
                            <?php } ?>
                        </td>
                        <td class="left">
                            <?php foreach ($group_editor_permissions as $section_permission_name => $group_permissions) { ?>
                                <fieldset class="perm-group">
                                    <legend>
                                        <label for="perm-group-editor-<?php echo $section_permission_name; ?>"><input type="checkbox" id="perm-group-editor-<?php echo $section_permission_name; ?>" class="CheckAll" data-checkbox=".EditorPermissionCheckbox_<?php echo $section_permission_name; ?>" /> <?php echo _t('permission_' . $section_permission_name); ?></label>
                                    </legend>
                                    <div class="perm-container">
                                        <?php foreach ($group_permissions as $group_permission_value => $group_permission_name) { ?>
                                            <label for="perm-editor-<?php echo $group_permission_value; ?>">
                                                <input type="checkbox" id="perm-editor-<?php echo $group_permission_value; ?>" class="EditorPermissionCheckbox_<?php echo $section_permission_name; ?>" name="perms[10][]" value="<?php echo $group_permission_value; ?>"<?php if (in_array($group_permission_value, $editor_permissions)) echo ' checked="checked"'; ?> /> <?php echo $group_permission_name; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </fieldset>
                            <?php } ?>
                        </td>
                        <td class="left">
                            <?php foreach ($group_admin_permissions as $section_permission_name => $group_permissions) { ?>
                                <fieldset class="perm-group">
                                    <legend>
                                        <label for="perm-group-admin-<?php echo $section_permission_name; ?>"><input type="checkbox" id="perm-group-admin-<?php echo $section_permission_name; ?>" class="CheckAll" data-checkbox=".AdminPermissionCheckbox_<?php echo $section_permission_name; ?>" /> <?php echo _t('permission_' . $section_permission_name); ?></label>
                                    </legend>
                                    <div class="perm-container">
                                        <?php foreach ($group_permissions as $group_permission_value => $group_permission_name) { ?>
                                            <label for="perm-admin-<?php echo $group_permission_value; ?>">
                                                <input type="checkbox" id="perm-admin-<?php echo $group_permission_value; ?>" class="AdminPermissionCheckbox_<?php echo $section_permission_name; ?>" name="perms[15][]" value="<?php echo $group_permission_value; ?>"<?php if (in_array($group_permission_value, $admin_permissions)) echo ' checked="checked"'; ?> /> <?php echo $group_permission_name; ?>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </fieldset>
                            <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="author-actions">
                    <button type="submit" name="act" value="edit_group_permission" class="button"><?php echo _t('button_save'); ?></button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    </div>
    <script>
        var g_token = '<?php echo $token; ?>';

        var Tab = (function () {
            $.fn.tabs = function (options) {
                var defaults = {
                        prefix: '',
                        key: ''
                    },
                    selector = this.selector,
                    activeKey;

                options = $.extend(defaults, options);

                activeKey = options.prefix + options.key;

                var matches = document.cookie.match(new RegExp(activeKey + '=([^;]+);')), activeValue = 0;

                if (matches) {
                    activeValue = matches[1];
                }

                $('body').on('click', selector, function (evt) {
                    evt.preventDefault();

                    $(selector)
                        .removeClass('selected')
                        .each(function () {
                            $($(this).attr('href')).hide();
                        });

                    var $this = $(this);
                    $this.addClass('selected');
                    $($this.attr('href')).show();

                    document.cookie = activeKey + '=' + $this.data('tab');
                });

                this.show();

                if (!$('#' + options.key + '_' + activeValue).trigger('click').length) {
                    $(selector).eq(0).trigger('click');
                }
            };

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

            return {
                init: function (selector, tabPrefix, tabKey) {
                    var context = $.isPlainObject(selector) ? selector.context : document,
                        selector = $.isPlainObject(selector) ? selector.selector : selector;

                    $(selector, context).tabs({
                        prefix: tabPrefix,
                        key: tabKey
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

        $(function () {
            Tab.init('.AuthorTab', 'kbm_', 'AuthorTab');
            CheckAll.init('.CheckAll');

            $('#perm-admin-edit_setting, #perm-admin-edit_group_permission').prop('disabled', true);
        });
    </script>
<?php echo $footer; ?>