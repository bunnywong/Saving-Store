<div id="kbm-latest-comment-<?php echo $module; ?>" class="kbm-latest-comment">
    <div class="box kuler-module">
	    <?php if ($show_title) { ?>
        <div class="box-heading"><span><?php echo $title; ?></span></div>
	    <?php } ?>
        <div class="box-content">
            <ul class="comments">
	            <?php foreach ($comments as $comment) { ?>
                <li>
                    <a<?php if ($comment['author']['website'])  echo ' href="' . $comment['author']['website'] . '"'; ?> rel="nofollow" target="_blank" class="avatar">
	                    <img src="<?php echo $comment['author']['avatar_url']; ?>" alt="<?php echo $comment['author']['name']; ?>" />
                    </a>
                    <a<?php if ($comment['author']['website'])  echo ' href="' . $comment['author']['website'] . '"'; ?> class="author" target="_blank" rel="nofollow"><?php echo $comment['author']['name']; ?></a>
                    <i><?php echo $text_on; ?></i>
                    <a href="<?php echo $comment['article']['link']; ?>#comments" class="content"><?php echo $comment['article']['name']; ?></a>
                </li>
	            <?php } ?>
            </ul>
        </div>
    </div>
</div>