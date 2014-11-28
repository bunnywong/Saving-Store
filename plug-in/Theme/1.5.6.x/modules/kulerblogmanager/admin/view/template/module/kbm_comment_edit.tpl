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
                    <li><a href="<?php echo $article_url; ?>"><?php echo _t('text_articles'); ?></a></li>
                    <li><a href="<?php echo $category_url; ?>"><?php echo _t('text_categories'); ?></a></li>
                    <li><a href="<?php echo $comment_url; ?>" class="selected"><?php echo _t('text_comments'); ?></a></li>
                    <li><a href="<?php echo $author_url; ?>"><?php echo _t('text_authors'); ?></a></li>
                    <li><a href="<?php echo $setting_url; ?>"><?php echo _t('text_settings'); ?></a></li>
                </ul>
                <div class="vtabs-content">
                    <div class="sub-heading">
                        <h3><?php echo $sub_heading; ?></h3>
                    </div>
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                        <input type="hidden" name="comment[comment_id]" value="<?php echo $comment['comment_id']; ?>" />
                        <input type="hidden" name="op" />
                        <table>
                            <tr>
                                <td valign="top" style="width: 50%;">
                                    <div class="comment">
                                        <table class="form">
                                            <tr>
                                                <td><?php echo _t('entry_article'); ?></td>
                                                <td>
                                                    <a href="<?php echo $comment['article_url']; ?>"><?php echo $comment['article_name']; ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _t('entry_created'); ?></td>
                                                <td><?php echo $comment['date_added_formatted']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo _t('entry_status'); ?></td>
                                                <td>
                                                    <select name="comment[status]" class="input-md">
                                                        <option value="0"<?php if (!$comment['status']) echo ' selected="selected"'; ?>><?php echo _t('text_unapprove'); ?></option>
                                                        <option value="1"<?php if ($comment['status']) echo ' selected="selected"'; ?>><?php echo _t('text_approve'); ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td valign="top">
                                    <table class="form">
                                        <tr>
                                            <td><?php echo _t('entry_name'); ?></td>
                                            <td>
                                                <input type="text" name="comment[author][name]" class="input-md" value="<?php echo $comment['author']['name']; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo _t('entry_email'); ?></td>
                                            <td>
                                                <input type="text" name="comment[author][email]" class="input-md" value="<?php echo $comment['author']['email']; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php echo _t('entry_website'); ?></td>
                                            <td>
                                                <input type="text" name="comment[author][website]" class="input-md" value="<?php echo $comment['author']['website']; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="100%">
                                                <textarea name="comment[content]" cols="60" rows="5"><?php echo $comment['content']; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
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
    </script>
<?php echo $footer; ?>