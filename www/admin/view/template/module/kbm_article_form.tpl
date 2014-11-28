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
                <a onclick="save();" class="button"><?php echo _t('button_save'); ?></a>
                <a onclick="apply();" class="button"><?php echo _t('button_apply'); ?></a>
                <a href="<?php echo $cancel; ?>" class="button"><?php echo _t('button_cancel'); ?></a>
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
            <div class="vtabs-content">
                <div class="sub-heading">
                    <h3><?php echo $sub_heading; ?></h3>
                </div>
                <form action="<?php echo $action; ?>" method="post" id="form">
                    <input type="hidden" name="op" value="" />

                    <input type="hidden" name="article[article][article_id]" value="<?php echo $article['article']['article_id']; ?>" />
                    <div class="htabs">
                        <a href="#ProductGeneral" class="ProductTab" id="ProductTab_1" data-tab="1"><?php echo _t('text_general'); ?></a>
                        <a href="#ProductData" class="ProductTab" id="ProductTab_2" data-tab="2"><?php echo _t('text_data'); ?></a>
                        <a href="#ProductLinks" class="ProductTab" id="ProductTab_3" data-tab="3"><?php echo _t('text_links'); ?></a>
                        <a href="#ProductDesign" class="ProductTab" id="ProductTab_4" data-tab="4"><?php echo _t('text_design'); ?></a>
                    </div>

                    <div id="ProductGeneral">
                        <div class="htabs">
                            <?php foreach ($languages as $language) { ?>
                            <a href="#ProductGeneral_<?php echo $language['language_id']; ?>" class="ProductGeneralTab" id="ProductGeneralTab_<?php echo $language['language_id'] ?>" data-tab="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?>
                                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                            </a>
                            <?php } ?>
                        </div>

                        <?php foreach ($languages as $language) { ?>
                        <div id="ProductGeneral_<?php echo $language['language_id']; ?>">
                                    <table class="form">
                                        <tr>
                                            <td><span class="required">*</span> <?php echo _t('entry_article_title'); ?></td>
                                            <td>
                                                <input type="text" name="article[description][<?php echo $language['language_id']; ?>][name]" value="<?php echo $article['description'][$language['language_id']]['name']; ?>" class="input-lg" />
                                                <?php if (isset($error_article_name) && isset($error_article_name[$language['language_id']])) { ?>
                                                <p class="error"><?php echo $error_article_name[$language['language_id']]; ?></p>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo _t('entry_meta_tag_keywords'); ?></td>
                                            <td>
                                                <textarea name="article[description][<?php echo $language['language_id']; ?>][meta_keyword]" class="text-lg"><?php echo $article['description'][$language['language_id']]['meta_keyword']; ?></textarea>
                                            </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_meta_tag_description'); ?></td>
                                    <td>
                                        <textarea name="article[description][<?php echo $language['language_id']; ?>][meta_description]" class="text-lg"><?php echo $article['description'][$language['language_id']]['meta_description']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="100%"><?php echo _t('entry_description'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="100%">
                                        <?php // @todo: Fixed bug no active when click  ?>
                                        <textarea name="article[description][<?php echo $language['language_id']; ?>][description]" class="Editor"><?php echo $article['description'][$language['language_id']]['description']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_article_tags'); ?></td>
                                    <td>
                                        <input type="text" name="article[description][<?php echo $language['language_id']; ?>][tags]" value="<?php echo $article['description'][$language['language_id']]['tags']; ?>" class="input-lg" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                    <div id="ProductData">
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_status'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="article[article][status]" value="0" />
                                        <input type="checkbox" name="article[article][status]" value="1"<?php if ($article['article']['status']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_seo_keyword'); ?></td>
                                <td>
                                    <input type="text" name="article[keyword]" value="<?php echo $article['keyword']; ?>" class="input-lg" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_author'); ?></td>
                                <td>
                                    <select name="article[article][author_id]" class="input-md">
                                        <?php foreach ($author_options as $author_id => $author_name) { ?>
                                        <option value="<?php echo $author_id; ?>"<?php if ($author_id == $article['article']['author_id']) echo ' selected="selected"'; ?>><?php echo $author_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_created_date'); ?></td>
                                <td>
                                    <input type="text" name="article[article][date_added]" value="<?php echo $article['article']['date_added_formatted']; ?>" class="input-md DateTimePicker" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_modified_date'); ?></td>
                                <td>
                                    <input type="text" name="article[article][date_modified]" value="<?php echo $article['article']['date_modified_formatted']; ?>" class="input-md DateTimePicker" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_start_publishing'); ?></td>
                                <td>
                                    <input type="text" name="article[article][date_published]" value="<?php echo $article['article']['date_published_formatted']; ?>" class="input-md DatePicker" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_featured_image'); ?></td>
                                <td>
                                    <div id="FeaturedImageContainer">
                                        <?php if ($article['article']['featured_image_thumb']) { ?>
                                            <img src="<?php echo $article['article']['featured_image_thumb']; ?>" />
                                        <?php } ?>
                                    </div>

                                    <input type="hidden" id="FeaturedImage" name="article[article][featured_image]" value="<?php echo $article['article']['featured_image']; ?>" />
                                    <a href="#" class="ImageManager button" data-clear="#FeaturedImageClear" data-field="FeaturedImage" data-image="#FeaturedImageContainer"><?php echo _t('button_browse'); ?></a> &nbsp;
                                    <a href="#" class="button" id="FeaturedImageClear"><?php echo _t('button_clear'); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_sort_order'); ?></td>
                                <td>
                                    <input type="text" name="article[article][sort_order]" value="<?php echo $article['article']['sort_order']; ?>" class="input-sm" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="ProductLinks">
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_categories'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $category_index = 0; ?>
                                        <?php foreach ($category_options as $category_id => $category_name) { ?>
                                        <div class="<?php echo $category_index % 2 ? 'odd' : 'even'; ?>">
                                            <input type="checkbox" name="article[category][]" value="<?php echo $category_id; ?>"<?php if (in_array($category_id, $article['category'])) echo ' checked="checked"'; ?> />
                                            <?php echo $category_name; ?>
                                        </div>
                                        <?php $category_index++; ?>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_stores'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $store_index = 0; ?>
                                        <?php foreach ($store_options as $store_id => $store_name) { ?>
                                        <div class="<?php echo $store_index % 2 ? 'odd' : 'even'; ?>">
                                            <input type="checkbox" name="article[store][]" value="<?php echo $store_id; ?>"<?php if (in_array($store_id, $article['store'])) echo ' checked="checked"'; ?> />
                                            <?php echo $store_name; ?>
                                        </div>
                                        <?php $store_index++; ?>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_related_articles'); ?></td>
                                <td>
                                    <select class="SwitchSelector" data-url="<?php echo $related_article_url; ?>" data-source="#ArticleSource" data-dist="#ArticleDist" data-add="#RelatedArticleAdder" data-remove="#RelatedArticleRemover" data-selected-container="#SelectedArticleContainer" data-input-name="article[related_articles][]">
                                        <?php foreach ($category_options as $category_id => $category_name) { ?>
                                            <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>
                                        <?php } ?>
                                    </select>

                                    <table class="switch-selector-container">
                                        <tr>
                                            <td>
                                                <select multiple="multiple" id="ArticleSource">
                                                </select>
                                            </td>
                                            <td>
                                                <button id="RelatedArticleAdder">--></button>
                                                <br/>
                                                <button id="RelatedArticleRemover"><--</button>
                                            </td>
                                            <td>
                                                <select multiple="multiple" id="ArticleDist">
                                                    <?php foreach ($article['related_articles'] as $related_article_id => $related_article_name) { ?>
                                                    <option value="<?php echo $related_article_id; ?>"><?php echo $related_article_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div id="SelectedArticleContainer">
                                                    <?php foreach ($article['related_articles'] as $related_article_id => $related_article_name) { ?>
                                                    <input name="article[related_articles][]" type="hidden" value="<?php echo $related_article_id; ?>" />
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="ProductDesign">
                        <table class="list">
                            <thead>
                            <tr>
                                <td class="left"><?php echo _t('entry_stores'); ?></td>
                                <td class="left"><?php echo _t('entry_layout_override'); ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($store_options as $store_id => $store_name) { ?>
                            <tr>
                                <td class="left"><?php echo $store_name; ?></td>
                                <td class="left">
                                    <select name="article[layout][<?php echo $store_id; ?>]" class="input-md">
                                    <?php foreach ($layout_options as $layout_id => $layout_name) { ?>
                                    <option value="<?php echo $layout_id; ?>"<?php if (isset($article['layout'][$store_id]) && $layout_id == $article['layout'][$store_id]) echo ' selected="selected"'; ?>><?php echo $layout_name; ?></option>
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
<script type="text/javascript">
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

    var Editor = (function (token) {
        CKEDITOR.config.autoParagraph = false;

        return {
            init: function (selector, context) {
                $(selector, context || document).each(function () {
                    CKEDITOR.replace(this, {
                        filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=' + token,
                        filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=' + token,
                        filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=' + token,
                        filebrowserUploadUrl: 'index.php?route=common/filemanager&token=' + token,
                        filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=' + token,
                        filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=' + token
                    });
                });
            }
        };
    })(Token);

    var DatePicker = (function () {
        return {
            init: function (selector) {
                $(selector).datepicker({
                    dateFormat: 'yy-m-dd'
                });
            }
        };
    })();

    var DateTimePicker = (function () {
        return {
            init: function (selector) {
                $(selector).datetimepicker({
                    dateFormat: 'yy-mm-dd',
                    timeFormat: 'h:m'
                });
            }
        };
    })();

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

    var SwitchSelector = (function () {
        return {
            init: function (selector) {
                $(selector).each(function () {
                    var $selector = $(this),
                        data = $selector.data(),
                        $source = $(data.source),
                        $dist = $(data.dist),
                        $add = $(data.add),
                        $remove = $(data.remove),
                        $selectedContainer = $(data.selectedContainer),
                        inputName = data.inputName,
                        $loading;

                    // Load new product source when the category changed
                    $selector.on('change', function () {
                        $.ajax({
                            url: data.url,
                            type: 'GET',
                            dataType: 'json',
                            data: {category_id: this.value},
                            beforeSend: function () {
                                $loading = $('<span class="loading" />').insertAfter($selector);
                            },
                            success: function (response) {
                                var distOptionEls = $dist[0].options, distOptions = [];

                                $loading.remove();

                                for (var i = 0; i < distOptionEls.length; i++) {
                                    distOptions.push(distOptionEls[i].value);
                                }

                                if (response.status) {
                                    $source.empty();

                                    var optionHtml = '';

                                    for (var itemId in response.items) {
                                        if (distOptions.indexOf(itemId) === -1) {
                                            optionHtml += '<option value="'+ itemId +'">'+ response.items[itemId] +'</option>'
                                        }
                                    }

                                    $source.append(optionHtml);
                                }
                            }
                        });
                    });

                    // Init add button
                    $add.on('click', function (evt) {
                        evt.preventDefault();

                        var options = $source[0].selectedOptions;

                        for (var i = 0; i < options.length; i++) {
                            $selectedContainer.append('<input type="hidden" name="'+ inputName +'" value="'+ options[i].value +'" />');
                            $dist.append(options[i]);
                        }
                    });

                    // Init Remove button
                    $remove.on('click', function (evt) {
                        evt.preventDefault();

                        var options = $dist[0].selectedOptions;

                        for (var i = 0; i < options.length; i++) {
                            $selectedContainer.find('input[type="hidden"][value="'+ options[i].value +'"]').remove();
                            $source.append(options[i]);
                        }
                    });
                })
                .trigger('change');
            }
        };
    })();

    function save() {
        $('#form')
            .find('input[name="op"]')
            .val('save')
            .end()
            .submit();
    }

    function apply() {
        $('#form')
            .find('input[name="op"]')
            .val('apply')
            .end()
            .submit();
    }

    $(function () {
        Tab.init('.ProductTab', 'kbm_', '');
        Tab.init('.ProductGeneralTab', 'kbm_', '');

        Editor.init('.Editor');
        DatePicker.init('.DatePicker');
        DateTimePicker.init('.DateTimePicker');
        ImageManager.init('.ImageManager');

        SwitchSelector.init('.SwitchSelector');
    });

</script>
<?php echo $footer; ?>