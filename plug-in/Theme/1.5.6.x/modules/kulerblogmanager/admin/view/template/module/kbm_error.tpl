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
            <ul class="vtabs">
                <li><a href="<?php echo $home_url; ?>" class="selected"><?php echo _t('text_home'); ?></a></li>
                <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
                <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
                <li><a href="<?php echo $comment_url; ?>"><?php echo _t('text_comments'); ?></a></li>
                <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
            </ul>
            <div class="vtabs-content">
                <div class="sub-heading">
                    <h3><?php echo _t('heading_error'); ?></h3>
                </div>
                <div style="border: 1px solid #DDDDDD; background: #F7F7F7; text-align: center; padding: 15px;"><?php echo $message; ?></div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>