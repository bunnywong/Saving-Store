<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
    <title><?php echo $title; ?></title>
    <link><?php echo $link; ?></link>
    <description><?php $description; ?></description>
    <image>
      <title><?php echo $title; ?></title>
      <url><?php echo $image_url; ?></url>
      <link><?php echo $link; ?></link>
    </image>
    <?php foreach ($items as $item) { ?>
    <item>
      <title><?php echo $item['title']; ?></title>
      <?php if ($item['category']) { ?>
      <category domain="<?php echo $item['category_url']; ?>"><?php echo $item['category_title']; ?></category>
      <?php } ?>
      <pubDate><?php echo $item['pub_date']; ?></pubDate>
      <link><?php echo $item['link']; ?></link>
      <description>
        &lt;img src="<?php echo $item['image_url']; ?>" alt="" align="left" /&gt;&lt;p&gt;
        <?php echo $item['description']; ?>
        &lt;a href="<?php echo $item['link']; ?>"&gt; Read more..&lt;/a&gt;
      </description>
    </item>
    <?php } ?>
	</channel>
</rss>