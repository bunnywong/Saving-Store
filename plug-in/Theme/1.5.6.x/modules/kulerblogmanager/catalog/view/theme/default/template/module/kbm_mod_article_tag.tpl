<div id="kuler-blog-manager-popular-article-tag-1" class="kuler-blog-manager-popular-article-tag">
    <div class="box kuler-module">
	    <?php if ($show_title) { ?>
        <div class="box-heading"><span><?php echo $title; ?></span></div>
	    <?php } ?>
        <div class="box-content">
	        <?php foreach ($tags as $tag) { ?>
            <a href="<?php echo $tag['link']; ?>" style="font-size: <?php echo $tag['size'] ?>px;" title="<?php echo $tag['text_tag']; ?>"><?php echo $tag['tag']; ?></a>
	        <?php } ?>
        </div>
    </div>
</div>