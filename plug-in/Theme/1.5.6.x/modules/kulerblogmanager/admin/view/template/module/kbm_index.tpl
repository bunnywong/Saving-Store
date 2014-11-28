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
        <div class="content" id="home-page">
            <ul class="vtabs">
                <li><a href="<?php echo $home_url; ?>" class="selected"><?php echo _t('text_home'); ?></a></li>
                <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
                <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
                <li><a href="<?php echo $comment_url; ?>"><?php echo _t('text_comments'); ?></a></li>
                <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
            </ul>
            <div class="vtabs-content">
                <h3><?php echo _t('heading_welcome'); ?></h3>
                <table class="main-layout">
                    <tr>
                        <td class="navs-container" valign="top">
                            <ul class="blog-navs">
                                <li><a href="<?php echo $article_insert_url; ?>"><img src="view/kulercore/images/blog-nav-icons/article-insert.png" alt=""/> <p><?php echo _t('text_add_article'); ?></p></a></li>
                                <li><a href="<?php echo $article_list_url; ?>"><img src="view/kulercore/images/blog-nav-icons/article-list.png" alt=""/> <p><?php echo _t('text_article_list'); ?></p></a></li>
                                <li><a href="<?php echo $category_insert_url; ?>"><img src="view/kulercore/images/blog-nav-icons/category-insert.png" alt=""/> <p><?php echo _t('text_add_category'); ?></p></a></li>
                                <li><a href="<?php echo $category_list_url; ?>"><img src="view/kulercore/images/blog-nav-icons/category-list.png" alt=""/> <p><?php echo _t('text_category_list'); ?></p></a></li>
                                <li><a href="<?php echo $comment_list_url; ?>"><img src="view/kulercore/images/blog-nav-icons/comment-list.png" alt=""/> <p><?php echo _t('text_comment_list'); ?></p></a></li>
                                <li><a href="<?php echo $author_url; ?>"><img src="view/kulercore/images/blog-nav-icons/author.png" alt=""/> <p><?php echo _t('text_manage_author'); ?></p></a></li>
                                <li><a href="<?php echo $setting_url; ?>"><img src="view/kulercore/images/blog-nav-icons/setting.png" alt=""/> <p><?php echo _t('text_settings'); ?></p></a></li>
                            </ul>
                        </td>
                        <td valign="top">
                            <table class="list">
                                <tr>
                                    <td class="left" style="width: 50%;"><?php echo _t('entry_articles'); ?></td>
                                    <td class="left"><?php echo $article_total; ?></td>
                                </tr>
                                <tr>
                                    <td class="left"><?php echo _t('entry_comments'); ?></td>
                                    <td class="left"><?php echo $comment_total; ?></td>
                                </tr>
                                <tr>
                                    <td class="left"><?php echo _t('entry_categories'); ?></td>
                                    <td class="left"><?php echo $category_total; ?></td>
                                </tr>
                                <tr>
                                    <td class="left"><?php echo _t('entry_authors'); ?></td>
                                    <td class="left"><?php echo $author_total; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>