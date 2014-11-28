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
                <a onclick="$('#op').val('close'); $('#form').submit();" class="button cancel-settings"><?php echo _t('button_close'); ?></a>
                <a href="<?php echo $cancel; ?>" class="button cancel-settings"><?php echo _t('button_cancel'); ?></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="op" id="op" />
                <ul class="vtabs">
                    <li><a href="<?php echo $home_url; ?>"><?php echo _t('text_home'); ?></a></li>
                    <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
                    <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
                    <li><a href="<?php echo $comment_url; ?>"><?php echo _t('text_comments'); ?></a></li>
                    <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                    <li><a href="<?php echo $setting_url; ?>" class="selected"><?php echo _t('text_settings'); ?></a></li>
                </ul>
                <div class="vtabs-content">
                    <div class="htabs">
                        <a href="#BlogHomeSetting" class="SettingTab" id="SettingTab_2" data-tab="2"><?php echo _t('text_blog_home'); ?></a>
                        <a href="#CategorySetting" class="SettingTab" id="SettingTab_1" data-tab="1"><?php echo _t('text_categories'); ?></a>
                        <a href="#ArticleSetting" class="SettingTab" id="SettingTab_3" data-tab="3"><?php echo _t('text_articles'); ?></a>
                        <a href="#CommentSetting" class="SettingTab" id="SettingTab_4" data-tab="4"><?php echo _t('text_comments'); ?></a>
                        <a href="#SearchAndFeedsSetting" class="SettingTab" id="SettingTab_5" data-tab="5"><?php echo _t('text_search_and_feeds'); ?></a>
                        <a href="#AdminSetting" class="SettingTab" id="SettingTab_6" data-tab="6"><?php echo _t('text_admin'); ?></a>
                    </div>

                    <div id="BlogHomeSetting">
                        <h2><?php echo _t('text_general'); ?></h2>
                        <div class="htabs">
                            <?php foreach ($languages as $language) { ?>
                                <a href="#BlogHomeGeneralSetting_<?php echo $language['language_id']; ?>" class="BlogHomeGeneralTab" id="BlogHomeGeneralTab_<?php echo $language['language_id']; ?>" data-tab="<?php echo $language['language_id']; ?>">
                                    <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
                                </a>
                            <?php } ?>
                        </div>

                        <?php foreach ($languages as $language) { ?>
                        <div id="BlogHomeGeneralSetting_<?php echo $language['language_id']; ?>">
                            <table class="form">
                                <tr>
                                    <td><?php echo _t('entry_blog_name'); ?></td>
                                    <td>
                                        <input type="text" name="setting[home][description][<?php echo $language['language_id']; ?>][blog_name]" value="<?php echo $setting['home']['description'][$language['language_id']]['blog_name']; ?>" size="60" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_blog_meta_keyword'); ?></td>
                                    <td>
                                        <textarea name="setting[home][description][<?php echo $language['language_id']; ?>][blog_keyword]" cols="60" rows="3"><?php echo $setting['home']['description'][$language['language_id']]['blog_keyword']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_blog_meta_description'); ?></td>
                                    <td>
                                        <textarea name="setting[home][description][<?php echo $language['language_id']; ?>][blog_meta_description]" cols="60" rows="3"><?php echo $setting['home']['description'][$language['language_id']]['blog_meta_description']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo _t('entry_blog_home_description'); ?></td>
                                    <td>
                                        <textarea name="setting[home][description][<?php echo $language['language_id']; ?>][blog_home_description]" class="Editor"><?php echo $setting['home']['description'][$language['language_id']]['blog_home_description']; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php } ?>

                        <h2><?php echo _t('text_setting'); ?></h2>
                        <table class="form">
	                        <tr>
		                        <td><?php echo _t('entry_show_title'); ?></td>
		                        <td>
			                        <div class="kuler-switch-btn">
				                        <input type="hidden" name="setting[home][show_title]" value="0" />
				                        <input type="checkbox" name="setting[home][show_title]" value="1"<?php if ($setting['home']['show_title']) echo ' checked="checked"'; ?> />
				                        <span class="kuler-switch-btn-holder"></span>
			                        </div>
		                        </td>
	                        </tr>
                            <tr>
                                <td><?php echo _t('entry_article_order'); ?></td>
                                <td>
                                    <select name="setting[home][article_order]">
                                        <?php foreach ($article_order_options as $article_order_option_value => $article_order_option_name) { ?>
                                        <option value="<?php echo $article_order_option_value; ?>"<?php if ($article_order_option_value == $setting['home']['article_order']) echo ' selected="selected"' ; ?>><?php echo $article_order_option_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_exclude_category'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $category_index = 0; ?>
                                        <?php foreach ($category_options as $category_id => $category_name) { ?>
                                            <div class="<?php echo $category_index % 2 ? 'odd' : 'even'; ?>">
                                                <input type="checkbox" name="setting[home][exclude_category][]" value="<?php echo $category_id; ?>" class="HomeSectionExcludeCategory"<?php if (in_array($category_id, $setting['home']['exclude_category'])) echo ' checked="checked"' ?> />
                                                <?php echo $category_name; ?>
                                            </div>
                                            <?php $category_index++; ?>
                                        <?php } ?>
                                    </div>
                                    <a class="CheckboxToggle" data-state="true" data-checkbox=".HomeSectionExcludeCategory"><?php echo _t('text_check_all'); ?></a> / <a class="CheckboxToggle" data-state="false" data-checkbox=".HomeSectionExcludeCategory"><?php echo _t('text_uncheck_all'); ?></a>
                                </td>
                            </tr>
                        </table>
                        
                        <h2><?php echo _t('text_layout'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_blog_column'); ?></td>
                                <td>
                                    <select name="setting[home][column]" style="width: 150px;">
                                        <?php foreach ($column_options as $column_option_value => $column_option_name) { ?>
                                        <option value="<?php echo $column_option_value; ?>"<?php if ($column_option_value == $setting['home']['column']) echo ' selected="selected"'; ?>><?php echo $column_option_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="CategorySetting">
                        <h2><?php echo _t('text_setting'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_articles_per_page'); ?></td>
                                <td>
                                    <input type="text" name="setting[category][articles_per_page]" value="<?php echo $setting['category']['articles_per_page']; ?>" size="5" />
                                    <?php if (isset($error_category_articles_per_page)) { ?>
                                    <p class="error"><?php echo $error_category_articles_per_page; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_article_characters'); ?></td>
                                <td>
                                    <input type="text" name="setting[category][article_characters]" value="<?php echo $setting['category']['article_characters']; ?>" size="5" />
                                    <?php if (isset($error_category_article_characters)) { ?>
                                    <p class="error"><?php echo $error_category_article_characters; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_featured_image_size'); ?></td>
                                <td>
                                    <input type="text" name="setting[category][featured_image_width]" value="<?php echo $setting['category']['featured_image_width']; ?>" size="5" /> x
                                    <input type="text" name="setting[category][featured_image_height]" value="<?php echo $setting['category']['featured_image_height']; ?>" size="5" />
                                    <?php if (isset($error_category_featured_image_size)) { ?>
                                        <p class="error"><?php echo $error_category_featured_image_size; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        
                        <h2><?php echo _t('text_virtual_directory'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_virtual_directory'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[category][virtual_directory]" value="0" />
                                        <input type="checkbox" name="setting[category][virtual_directory]" value="1"<?php if ($setting['category']['virtual_directory']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_virtual_directory_name'); ?></td>
                                <td>
                                    <input type="text" name="setting[category][virtual_directory_name]" value="<?php echo $setting['category']['virtual_directory_name']; ?>" size="10" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_virtual_exclude'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $category_index = 0; ?>
                                        <?php foreach ($category_options as $category_id => $category_name) { ?>
                                            <div class="<?php echo $category_index % 2 ? 'odd' : 'even'; ?>">
                                                <input type="checkbox" name="setting[category][virtual_exclude_category][]" value="<?php echo $category_id; ?>" class="CategorySectionExcludeCategory"<?php if (in_array($category_id, $setting['category']['virtual_exclude_category'])) echo ' checked="checked"'; ?> />
                                                <?php echo $category_name; ?>
                                            </div>
                                            <?php $category_index++; ?>
                                        <?php } ?>
                                    </div>
                                    <a class="CheckboxToggle" data-state="true" data-checkbox=".CategorySectionExcludeCategory"><?php echo _t('text_check_all'); ?></a> / <a class="CheckboxToggle" data-state="false" data-checkbox=".CategorySectionExcludeCategory"><?php echo _t('text_uncheck_all'); ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="ArticleSetting">
                        <h2><?php echo _t('text_setting'); ?></h2>
                        <table class="form">
	                        <tr>
		                        <td><?php echo _t('entry_seo_url_suffix'); ?></td>
		                        <td>
			                        <input type="text" name="setting[article][url_suffix]" value="<?php echo $setting['article']['url_suffix']; ?>" />
		                        </td>
	                        </tr>
                            <tr>
                                <td><?php echo _t('entry_article_info'); ?></td>
                                <td>
                                    <div class="checkbox-group">
                                        <label for="setting_article_author_name">
                                            <input type="hidden" name="setting[article][author_name]" value="0" />
                                            <input type="checkbox" id="setting_article_author_name" name="setting[article][author_name]" value="1"<?php if ($setting['article']['author_name']) echo ' checked="checked"'; ?> /> <?php echo _t('text_author_name'); ?>
                                        </label>
                                        <label for="setting_article_date">
                                            <input type="hidden" name="setting[article][date]" value="0" />
                                            <input type="checkbox" id="setting_article_date" name="setting[article][date]" value="1"<?php if ($setting['article']['date']) echo ' checked="checked"'; ?> /> <?php echo _t('text_date'); ?>
                                        </label>
                                        <label for="setting_article_last_update">
                                            <input type="hidden" name="setting[article][last_update]" value="0" />
                                            <input type="checkbox" id="setting_article_last_update" name="setting[article][last_update]" value="1"<?php if ($setting['article']['last_update']) echo ' checked="checked"'; ?> /> <?php echo _t('text_last_update'); ?>
                                        </label>
                                        <label for="setting_article_category">
                                            <input type="hidden" name="setting[article][category]" value="0" />
                                            <input type="checkbox" id="setting_article_category" name="setting[article][category]"<?php if ($setting['article']['category']) echo ' checked="checked"'; ?> /> <?php echo _t('text_category'); ?>
                                        </label>
                                        <label for="setting_article_comment">
                                            <input type="hidden" name="setting[article][comment]" value="0" />
                                            <input type="checkbox" id="setting_article_comment" name="setting[article][comment]"<?php if ($setting['article']['comment']) echo ' checked="checked"'; ?>/> <?php echo _t('text_comment'); ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_related_article_at_article_page'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[article][related_article]" value="0" />
                                        <input type="checkbox" name="setting[article][related_article]" value="1"<?php if ($setting['article']['related_article']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
	                        <tr>
		                        <td><?php echo _t('entry_featured_product_image'); ?></td>
		                        <td>
			                        <input type="text" name="setting[article][featured_image_width]" value="<?php echo $setting['article']['featured_image_width']; ?>" class="input-sm"/> x
			                        <input type="text" name="setting[article][featured_image_height]" value="<?php echo $setting['article']['featured_image_height']; ?>" class="input-sm"/>
		                        </td>
	                        </tr>
                        </table>
                        
                        <h2><?php echo _t('text_social_media'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_social_media'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[article][social_media]" value="0" />
                                        <input type="checkbox" name="setting[article][social_media]" value="1"<?php if ($setting['article']['social_media']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_addthis_analytic_code'); ?></td>
                                <td>
                                    <input type="text" name="setting[article][add_analytic_code]" value="<?php echo $setting['article']['add_analytic_code']; ?>" size="10" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_addthis_custom_code'); ?></td>
                                <td>
                                    <textarea name="setting[article][add_this_custom_code]" cols="60" rows="3"><?php echo $setting['article']['add_this_custom_code']; ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="CommentSetting">
                        <h2><?php echo _t('text_setting'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_comment_status'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[comment][status]" value="0" />
                                        <input type="checkbox" name="setting[comment][status]" value="1"<?php if ($setting['comment']['status']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_comment_per_page'); ?></td>
                                <td>
                                    <input type="text" name="setting[comment][comment_per_page]" value="<?php echo $setting['comment']['comment_per_page']; ?>" size="5" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_avatar_size'); ?></td>
                                <td>
                                    <input type="text" name="setting[comment][avatar_size]" value="<?php echo $setting['comment']['avatar_size']; ?>" size="5" />
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_min_max_character'); ?></td>
                                <td>
                                    <input type="text" name="setting[comment][min_character]" value="<?php echo $setting['comment']['min_character']; ?>" size="5" /> x
                                    <input type="text" name="setting[comment][max_character]" value="<?php echo $setting['comment']['max_character']; ?>" size="5" />
                                    <?php if (isset($error_comment_entry_min_max_character)) { ?>
                                    <p class="error"><?php echo $error_comment_entry_min_max_character; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_comment_order'); ?></td>
                                <td>
                                    <select name="setting[comment][comment_order]" style="width: 150px;">
                                        <?php foreach ($comment_order_options as $comment_order_option_value => $comment_order_option_name) { ?>
                                        <option value="<?php echo $comment_order_option_value; ?>"<?php if ($comment_order_option_value == $setting['comment']['comment_order']) echo ' selected="selected"'; ?>><?php echo $comment_order_option_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_email_notification'); ?></td>
                                <td>
                                    <input type="text" name="setting[comment][email_notification]" value="<?php echo $setting['comment']['email_notification']; ?>" size="60" />
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_capcha'); ?></td>
                                <td>
                                    <select name="setting[comment][captcha]" style="width: 150px;">
                                        <?php foreach ($capcha_options as $caption_option_value => $capcha_option_name) { ?>
                                        <option value="<?php echo $caption_option_value; ?>"<?php if ($caption_option_value == $setting['comment']['captcha']) echo ' selected="selected"'; ?>><?php echo $capcha_option_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                        <h2><?php echo _t('text_access_and_permission'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_guest_comment'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[comment][guest_comment]" value="0" />
                                        <input type="checkbox" name="setting[comment][guest_comment]" value="1"<?php if ($setting['comment']['guest_comment']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_disable_category'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $category_index = 0; ?>
                                        <?php foreach ($category_options as $category_id => $category_name) { ?>
                                            <div class="<?php echo $category_index % 2 ? 'odd' : 'even'; ?>">
                                                <input type="checkbox" name="setting[comment][disable_category][]" class="CommentSectionDisableCategory"<?php if (in_array($category_id, $setting['comment']['disable_category'])) echo ' checked="checked"'; ?> value="<?php echo $category_id; ?>" />
                                                <?php echo $category_name; ?>
                                            </div>
                                            <?php $category_index++; ?>
                                        <?php } ?>
                                    </div>
                                    <a class="CheckboxToggle" data-state="true" data-checkbox=".CommentSectionDisableCategory"><?php echo _t('text_check_all'); ?></a> / <a class="CheckboxToggle" data-state="false" data-checkbox=".CommentSectionDisableCategory"><?php echo _t('text_uncheck_all'); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_default_auto_approve'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[comment][auto_approve]" value="0" />
                                        <input type="checkbox" name="setting[comment][auto_approve]" value="1"<?php if ($setting['comment']['auto_approve']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_auto_approve_group'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $group_index = 0; ?>
                                        <?php foreach ($customer_group_options as $group_id => $group_name) { ?>
                                            <div class="<?php echo $group_index % 2 ? 'odd' : 'even'; ?>">
                                                <input type="checkbox" name="setting[comment][auto_approve_group][<?php echo $group_id; ?>]" value="<?php echo $group_id; ?>" class="CommentSectionAutoApproveGroup"<?php if (in_array($group_id, $setting['comment']['auto_approve_group'])) echo ' checked="checked"'; ?> />
                                                <?php echo $group_name; ?>
                                            </div>
                                            <?php $group_index++; ?>
                                        <?php } ?>
                                    </div>
                                    <a class="CheckboxToggle" data-state="true" data-checkbox=".CommentSectionAutoApproveGroup"><?php echo _t('text_check_all'); ?></a> / <a class="CheckboxToggle" data-state="false" data-checkbox=".CommentSectionAutoApproveGroup"><?php echo _t('text_uncheck_all'); ?></a>
                                </td>
                            </tr>
                        </table>
                        
                        <h2><?php echo _t('text_admin_badge_settings'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_admin_badge_group'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $group_index = 0; ?>
                                        <?php foreach ($admin_group_options as $group_id => $group_name) { ?>
                                            <div class="<?php echo $group_index % 2 ? 'odd' : 'even'; ?> BadgeSelectorList" data-tab="#CommentAdminBadgeTabs" data-container="#CommentAdminBadgeContainer">
                                                <input type="checkbox" name="setting[comment][admin_badge_group][]" value="<?php echo $group_id; ?>" class="CommentSectionAdminBadgeGroup"<?php if (in_array($group_id, $setting['comment']['admin_badge_group'])) echo ' checked="checked"'; ?> />
                                                <?php echo $group_name; ?>
                                            </div>
                                            <?php $group_index++; ?>
                                        <?php } ?>
                                    </div>
                                    <a class="CheckboxToggle" data-state="true" data-checkbox=".CommentSectionAdminBadgeGroup"><?php echo _t('text_check_all'); ?></a> / <a class="CheckboxToggle" data-state="false" data-checkbox=".CommentSectionAdminBadgeGroup"><?php echo _t('text_uncheck_all'); ?></a>
                                </td>
                            </tr>
                        </table>
                        <div class="htabs" id="CommentAdminBadgeTabs">
                            <?php foreach ($setting['comment']['admin_badge_group'] as $group_id) { ?>
                                <a href="#BadgeSetting_<?php echo $group_id ?>" class="CommentBadgeTab"><?php echo $admin_group_options[$group_id]; ?></a>
                            <?php } ?>
                        </div>

                        <div>
                            <?php foreach ($setting['comment']['admin_badge_group'] as $group_id) { ?>
                            <table id="BadgeSetting_<?php echo $group_id ?>" class="form">
                                <tr>
                                    <td><?php echo $admin_group_options[$group_id]; ?> <?php echo _t('text_badge_color'); ?></td>
                                    <td>
                                        <div class="color-picker-container">
                                            <div class="color-picker ColorPicker" data-background-color="#BadgeColor_<?php echo $group_id; ?>" data-default-color="<?php echo $setting['comment']['admin_badge_color'][$group_id]; ?>">
                                                <div style="background-color: <?php echo $setting['comment']['admin_badge_color'][$group_id]; ?>"></div>
                                            </div>
                                            <input type="text" id="BadgeColor_<?php echo $group_id; ?>" name="setting[comment][admin_badge_color][<?php echo $group_id; ?>]" value="<?php echo $setting['comment']['admin_badge_color'][$group_id]; ?>" size="7" />
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <?php } ?>
                        </div>

                        <h2><?php echo _t('text_customer_badge_settings'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_customer_badge_group'); ?></td>
                                <td>
                                    <div class="scrollbox">
                                        <?php $group_index = 0; ?>
                                        <?php foreach ($customer_group_options as $group_id => $group_name) { ?>
                                            <div class="<?php echo $group_index % 2 ? 'odd' : 'even'; ?>">
                                                <input type="checkbox" name="setting[comment][customer_badge_group][]" value="<?php echo $group_id; ?>" class="CommentSectionCustomerBadgeGroup"<?php if (in_array($group_id, $setting['comment']['customer_badge_group'])) echo ' checked="checked"'; ?> />
                                                <?php echo $group_name; ?>
                                            </div>
                                            <?php $group_index++; ?>
                                        <?php } ?>
                                    </div>
                                    <a class="CheckboxToggle" data-state="true" data-checkbox=".CommentSectionCustomerBadgeGroup"><?php echo _t('text_check_all'); ?></a> / <a class="CheckboxToggle" data-state="false" data-checkbox=".CommentSectionCustomerBadgeGroup"><?php echo _t('text_uncheck_all'); ?></a>
                                </td>
                            </tr>
                        </table>
                        <div class="htabs">
                            <?php foreach ($setting['comment']['customer_badge_group'] as $group_id) { ?>
                            <a href="#CustomerBadgeSetting_<?php echo $group_id; ?>" class="CustomerCommentBadgeTab"><?php echo $customer_group_options[$group_id]; ?></a>
                            <?php } ?>
                        </div>
                        <?php foreach ($setting['comment']['customer_badge_group'] as $group_id) { ?>
                        <table id="CustomerBadgeSetting_<?php echo $group_id; ?>" class="form">
                            <tr>
                                <td><?php echo $customer_group_options[$group_id]; ?> <?php echo _t('text_badge_color'); ?></td>
                                <td>
                                    <div class="color-picker-container">
                                        <div class="color-picker ColorPicker" data-background-color="#CustomerBadgeColor_<?php echo $group_id; ?>" data-default-color="<?php echo $setting['comment']['customer_badge_color'][$group_id]; ?>">
                                            <div style="background-color: <?php echo $setting['comment']['customer_badge_color'][$group_id]; ?>"></div>
                                        </div>
                                        <input type="text" id="CustomerBadgeColor_<?php echo $group_id; ?>" name="setting[comment][customer_badge_color][<?php echo $group_id; ?>]" value="<?php echo $setting['comment']['customer_badge_color'][$group_id]; ?>" size="7" />
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <?php } ?>
                    </div>

                    <div id="SearchAndFeedsSetting">
	                    <div style="display: none;">
                        <h2><?php echo _t('text_search_setting'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_search_status'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[search][status]" value="0" />
                                        <input type="checkbox" name="setting[search][status]" value="1"<?php if ($setting['search']['status']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_article_info'); ?></td>
                                <td>
                                    <div class="checkbox-group">
                                        <label for="setting_search_author_name">
                                            <input type="hidden" name="setting[search][author_name]" value="0" />
                                            <input type="checkbox" id="setting_search_author_name" name="setting[search][author_name]"<?php if ($setting['search']['author_name']) echo ' checked="checked"'; ?> /> <?php echo _t('text_author_name'); ?>
                                        </label>
                                        <label for="setting_search_date">
                                            <input type="hidden" name="setting[search][date]" value="0" />
                                            <input type="checkbox" id="setting_search_date" name="setting[search][date]"<?php if ($setting['search']['date']) echo ' checked="checked"'; ?> /> <?php echo _t('text_date'); ?>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_search_display'); ?></td>
                                <td>
                                    <select name="setting[search][search_display]" style="width: 150px;">
                                        <?php foreach ($search_display_options as $search_display_option_value => $search_display_option_name) { ?>
                                        <option value="<?php echo $search_display_option_value; ?>"<?php if ($search_display_option_value == $setting['search']['search_display']) echo ' selected="selected"'; ?>><?php echo $search_display_option_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_search_result'); ?></td>
                                <td>
                                    <input type="text" name="setting[search][search_result]" value="<?php echo $setting['search']['search_result']; ?>" size="5" />
                                    <?php if (isset($error_search_search_result)) { ?>
                                    <p class="error"><?php echo $error_search_search_result; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_result_per_row_grid'); ?></td>
                                <td>
                                    <input type="text" name="setting[search][result_per_row]" size="5" value="<?php echo $setting['search']['result_per_row']; ?>" />
                                    <?php if (isset($error_search_result_per_row)) { ?>
                                    <p class="error"><?php echo $error_search_result_per_row; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
	                    </div>
                        
                        <h2><?php echo _t('text_feeds_setting'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_blog_feed'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[feed][status]" value="0" />
                                        <input type="checkbox" name="setting[feed][status]" value="1"<?php if ($setting['feed']['status']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_blog_feed_limit'); ?></td>
                                <td>
                                    <input type="text" name="setting[feed][limit]" value="<?php echo $setting['feed']['limit']; ?>" size="5" />
                                </td>
                            </tr>
                        </table>

                        <h2><?php echo _t('text_sitemap_setting'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><?php echo _t('entry_blog_sitemap'); ?></td>
                                <td>
                                    <div class="kuler-switch-btn">
                                        <input type="hidden" name="setting[sitemap][status]" value="0" />
                                        <input type="checkbox" name="setting[sitemap][status]" value="1"<?php if ($setting['sitemap']['status']) echo ' checked="checked"'; ?> />
                                        <span class="kuler-switch-btn-holder"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo _t('entry_blog_sitemap_limit'); ?></td>
                                <td>
                                    <input type="text" name="setting[sitemap][limit]" value="<?php echo $setting['sitemap']['limit']; ?>" size="6" />
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div id="AdminSetting">
                        <h2><?php echo _t('text_setting'); ?></h2>
                        <table class="form">
                            <tr>
                                <td><span class="required">*</span> <?php echo _t('entry_articles_per_page'); ?></td>
                                <td>
                                    <input type="text" name="setting[admin][articles_per_page]" value="<?php echo $setting['admin']['articles_per_page']; ?>" size="5" />
                                    <?php if (isset($error_admin_articles_per_page)) { ?>
                                    <p class="error"><?php echo $error_admin_articles_per_page; ?></p>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
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
    })(g_token);

    var ColorPicker = (function () {
        return {
            init: function (selector, context) {
                var CP = this;
                CP.$el = $(selector, context || document);

                CP.$el.each(function () {
                    var $cpElement = $(this);
                    $(this).ColorPicker({
                        color: CP.$el.data('defaultColor'),
                        onShow: function (colpkr) {
                            // recalculate position of the color picker
                            var pos = $(this).offset();

                            $(colpkr)
                                .css({
                                    left: pos.left + 20,
                                    top: pos.top + 20
                                })
                                .fadeIn(500);

                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            $cpElement.find('div').css('backgroundColor', '#' + hex);
                            $($cpElement.data('backgroundColor')).val('#' + hex).trigger('change');
                        }
                    });
                });
            }
        };
    })();

    var CheckboxToggle = (function () {
        return {
            init: function (selector) {
                $(selector).on('click', function (evt) {
                    evt.preventDefault();

                    var $button = $(this),
                        $checkbox = $($button.data('checkbox'));

                    $checkbox.prop('checked', $button.data('state'));
                });
            }
        };
    })();

    $(function () {
        Tab.init('.SettingTab', 'kbm_', '');
        Tab.init('.BlogHomeGeneralTab', 'kbm_', '');
        Tab.init('.CommentBadgeTab', 'kbm_', '');
        Tab.init('.CustomerCommentBadgeTab', 'kbm_', '');

        Editor.init('.Editor');
        ColorPicker.init('.ColorPicker');

        CheckboxToggle.init('.CheckboxToggle');
    });
</script>
<?php echo $footer; ?>