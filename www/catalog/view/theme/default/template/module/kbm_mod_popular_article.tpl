<div id="kbm-popular-article-<?php echo $module; ?>" class="kbm-popular-article">
    <div class="box kuler-module">
	    <?php if ($show_title) { ?>
        <div class="box-heading"><span><?php echo $title; ?></span></div>
	    <?php } ?>
        <div class="box-content">
            <ul class="articles">
	            <?php foreach ($articles as $article) { ?>
                <li>
	                <?php if ($product_featured_image) { ?>
                        <div class="image"><img src="<?php echo $article['featured_image_thumb']; ?>" class="avatar" /></div>
	                <?php } ?>
                    <a href="<?php echo $article['link']; ?>" class="article-title"><?php echo $article['name']; ?></a>
	                <?php if ($product_description) { ?>
	                    <p><?php echo $article['description']; ?></p>
	                <?php } ?>
                    <span class="date"><?php echo $article['date_added_formatted']; ?></span>
                </li>
	            <?php } ?>
            </ul>
        </div>
    </div>
</div>