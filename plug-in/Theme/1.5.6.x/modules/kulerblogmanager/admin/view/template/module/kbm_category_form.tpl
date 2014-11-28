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
                    <a onclick="$('#form').submit();" class="button save-settings"><?php echo _t('button_save'); ?></a>
                    <a href="<?php echo $cancel; ?>" class="button cancel-settings"><?php echo _t('button_cancel'); ?></a>
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
                        <h3><?php echo $sub_heading_title; ?></h3>
                    </div>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                        <input type="hidden" name="category[category][category_id]" value="<?php echo $category['category']['category_id']; ?>" />
                        <div class="htabs">
                            <a href="#CategoryGeneral" class="CategoryTab" id="CategoryTab_1" data-tab="1"><?php echo _t('text_general'); ?></a>
                            <a href="#CategoryData" class="CategoryTab" id="CategoryTab_2" data-tab="2"><?php echo _t('text_data'); ?></a>
                            <a href="#CategoryDesign" class="CategoryTab" id="CategoryTab_3" data-tab="3"><?php echo _t('text_design'); ?></a>
                        </div>

                        <div id="CategoryGeneral">
                            <div class="htabs">
                                <?php foreach ($languages as $language) { ?>
                                    <a href="#CategoryGeneralSetting_<?php echo $language['language_id']; ?>" class="CategoryGeneralTab" id="CategoryGeneralTab_<?php echo $language['language_id']; ?>" data-tab="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?>
                                        <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                                    </a>
                                <?php } ?>
                            </div>
                            
                            <?php foreach ($languages as $language) { ?>
                            <div id="CategoryGeneralSetting_<?php echo $language['language_id']; ?>">
                                <table class="form">
                                    <tr>
                                        <td><span class="required">*</span> <?php echo _t('entry_category_name'); ?></td>
                                        <td>
                                            <input type="text" name="category[description][<?php echo $language['language_id']; ?>][name]" value="<?php echo $category['description'][$language['language_id']]['name']; ?>" class="input-lg" />
                                            <?php if (isset($error_category_name[$language['language_id']])) { ?>
                                                <span class="error"><?php echo $error_category_name[$language['language_id']]; ?></span>
                                            <?php } ?></td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo _t('entry_meta_tag_keywords'); ?></td>
                                        <td>
                                            <textarea name="category[description][<?php echo $language['language_id']; ?>][meta_keyword]" class="text-lg"><?php echo $category['description'][$language['language_id']]['meta_keyword']; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo _t('entry_meta_tag_description'); ?></td>
                                        <td>
                                            <textarea  name="category[description][<?php echo $language['language_id']; ?>][meta_description]" class="text-lg"><?php echo $category['description'][$language['language_id']]['meta_description']; ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="100%"><?php echo _t('entry_description'); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="100%">
                                            <textarea name="category[description][<?php echo $language['language_id']; ?>][description]" class="Editor"><?php echo $category['description'][$language['language_id']]['description']; ?></textarea>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php } ?>
                        </div>

                        <div id="CategoryData">
                            <table class="form">
                                <tr>
                                    <td><?php echo _t('entry_status'); ?></td>
                                    <td>
                                        <div class="kuler-switch-btn">
                                            <input type="hidden" name="category[category][status]" value="0" />
                                            <input type="checkbox" name="category[category][status]" value="1"<?php if ($category['category']['status'] == 1) echo 'checked="checked"'; ?> />
                                            <span class="kuler-switch-btn-holder"></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_parent_category'); ?></td>
                                    <td>
                                        <select name="category[category][parent_id]">
                                            <option value="0"><?php echo _t('text_none'); ?></option>
                                            <?php foreach ($category_options as $category_id => $category_name) { ?>
                                                <option value="<?php echo $category_id; ?>"<?php if ($category['category']['parent_id'] == $category_id) echo ' selected="selected"'; ?>><?php echo $category_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_seo_keyword'); ?></td>
                                    <td>
                                        <input type="text" name="category[keyword]" value="<?php echo $category['keyword']; ?>" size="50" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_article_order'); ?></td>
                                    <td>
                                        <select name="category[category][article_order]">
                                            <?php foreach ($article_order_options as $article_order_option_value => $article_order_option) { ?>
                                            <option value="<?php echo $article_order_option_value; ?>"<?php if ($article_order_option_value == $category['category']['article_order']) echo ' selected="selected"'; ?>><?php echo $article_order_option; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_article_image_size'); ?></td>
                                    <td>
                                        <input type="text" name="category[category][article_image_width]" value="<?php echo $category['category']['article_image_width']; ?>" class="input-sm"/> x
                                        <input type="text" name="category[category][article_image_height]" value="<?php echo $category['category']['article_image_height']; ?>" class="input-sm"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_character_limit'); ?></td>
                                    <td>
                                        <input type="text" name="category[category][character_limit]" value="<?php echo $category['category']['character_limit']; ?>" class="input-sm" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_category_image'); ?></td>
                                    <td>
                                        <div id="CategoryImageContainer">
                                            <?php if ($category['category']['image_thumb']) { ?>
                                                <img src="<?php echo $category['category']['image_thumb']; ?>" />
                                            <?php } ?>
                                        </div>

                                        <input type="hidden" id="CategoryImage" name="category[category][image]" value="<?php echo $category['category']['image']; ?>" />
                                        <a href="#" class="ImageManager button" data-clear="#CategoryImageClear" data-field="CategoryImage" data-image="#CategoryImageContainer"><?php echo _t('button_choose_image'); ?></a> &nbsp;
                                        <a href="#" class="button" id="CategoryImageClear"><?php echo _t('button_clear'); ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_stores'); ?></td>
                                    <td>
                                        <div class="scrollbox">
                                            <?php $store_index = 0; ?>
                                            <?php foreach ($stores as $store_id => $store_name) { ?>
                                            <div class="<?php echo ($store_index % 2) ? 'odd' : 'even'; ?>">
                                                <input type="checkbox" name="category[store][]" value="<?php echo $store_id; ?>"<?php if (in_array($store_id, $category['store'])) echo ' checked="checked"'; ?> />
                                                <?php echo $store_name; ?>
                                            </div>
                                            <?php $store_index++; ?>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_sort_order'); ?></td>
                                    <td>
                                        <input type="text" name="category[category][sort_order]" value="<?php echo $category['category']['sort_order']; ?>" class="input-sm" />
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="CategoryDesign">
                            <table class="form">
                                <tr>
                                    <td><?php echo _t('entry_category_column'); ?></td>
                                    <td>
                                        <select name="category[category][column]" class="input-md">
                                            <?php foreach ($column_options as $column_option_value => $column_option_name) { ?>
                                            <option value="<?php echo $column_option_value; ?>"<?php if ($column_option_value == $category['category']['column']) echo ' selected="selected"'; ?>><?php echo $column_option_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            <table class="list">
                                <thead>
                                <tr>
                                    <td class="left"><?php echo _t('entry_stores'); ?></td>
                                    <td class="left"><?php echo _t('entry_layout_override'); ?></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($stores as $store_id => $store_name) { ?>
                                <tr>
                                    <td class="left"><?php echo $store_name; ?></td>
                                    <td class="left">
                                        <select name="category[layout][<?php echo $store_id; ?>]" class="input-md">
                                            <option value="0"></option>
                                            <?php foreach ($layout_options as $layout) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>"<?php if ($layout['layout_id'] == $category['layout'][$store_id]) echo ' selected="selected"'; ?>><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var Token = '<?php echo $token; ?>',
            CatalogBase = '<?php echo $catalog_base; ?>';

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

        var Editor = (function (Token) {
            CKEDITOR.config.autoParagraph = false;

            return {
                init: function (selector, context) {
                    $(selector, context || document).each(function () {
                        CKEDITOR.replace(this, {
                            filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=' + Token,
                            filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=' + Token,
                            filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=' + Token,
                            filebrowserUploadUrl: 'index.php?route=common/filemanager&token=' + Token,
                            filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=' + Token,
                            filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=' + Token
                        });
                    });
                }
            };
        })(Token);

        var ImageManager = (function (Token, CatalogBase) {
            return {
                init: function (selector, context) {
                    var self = this;
                    self.$el = $(selector, context || document);

                    self.$el.bind('click', function (evt) {
                        evt.preventDefault();

                        self.showDialog($(this).data('field'), $(this).data('image'));
                    });

                    self.$el.each(function () {
                        var $this = $(this);

                        $($this.data('clear')).bind('click', function (evt) {
                            evt.preventDefault();

                            $('#' + $this.data('field')).val('');
                            $($this.data('image')).html('');
                        });
                    });
                },
                showDialog: function (field, image) {
                    $('#dialog').remove();

                    $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token='+ Token +'&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

                    var val1 = $('#' + field).val();

                    $('#dialog').dialog({
                        title: 'Image Manager',
                        close: function (event, ui) {
                            var val2 = $('#' + field).val();

                            if (val1 != val2) {
                                $('#' + field).val(val2).trigger('change');

                                if (image !== undefined) {
                                    $(image).html($('<img />', {src: CatalogBase + 'image/' + val2}));
                                }
                            }
                        },
                        bgiframe: false,
                        width: 700,
                        height: 400,
                        resizable: false,
                        modal: false
                    });
                }
            };
        })(Token, CatalogBase);

        $(function () {
            Tab.init('.CategoryTab', 'kbm_', 'CategoryTab_');
            Tab.init('.CategoryGeneralTab', 'kbm_', 'CategoryGeneralTab_');

            Editor.init('.Editor');
            ImageManager.init('.ImageManager');
        });
    </script>
<?php echo $footer; ?>