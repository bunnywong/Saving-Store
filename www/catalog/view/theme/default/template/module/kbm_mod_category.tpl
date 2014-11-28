<div id="kbm-category-<?php echo $module; ?>" class="kbm-category">
    <div class="box kuler-module">
	    <?php if ($show_title) { ?>
        <div class="box-heading"><span><?php echo $title; ?></span></div>
	    <?php } ?>
        <div class="box-content horizontal">
            <ul class="treemenu">
	            <?php foreach ($categories as $category) { ?>
	            <li><a href="<?php echo $category['link']; ?>"<?php if ($category['category_id'] == $category_id) echo ' class="active"'; ?>><?php echo $category['name']; ?></a>
		            <?php if ($category_id == $category['category_id'] && !empty($category['children'])) { ?>
                    <ul>
	                    <?php foreach ($category['children'] as $child) { ?>
                        <li><a href="<?php echo $child['link']; ?>"<?php if ($child['category_id'] == $child_id) echo ' class="active"'; ?>><?php echo $child['name']; ?></a> </li>
	                    <?php } ?>
                    </ul>
			        <?php } ?>
                </li>
	            <?php } ?>
            </ul>
        </div>
    </div>
</div>