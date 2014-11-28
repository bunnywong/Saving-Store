<?php

/**
 * Class ModelModuleKBMModArticleTag
 * @property Cache $cache
 */
class ModelModuleKBMModArticleTag extends Model
{
    public function __construct($registry)
    {
        parent::__construct($registry);
    }

	public function getProductTags()
	{
		$config_language_id = intval($this->config->get('config_language_id'));

		if (!$results = $this->cache->get(ModelModuleKbm::CACHE_PRODUCT_TAGS . ".$config_language_id"))
		{
			$query = $this->db->query("
			SELECT ad.tags FROM ". BLOG_TABLE_PREFIX ."article_description ad
			INNER JOIN ". BLOG_TABLE_PREFIX ."article a
				ON      (ad.article_id = a.article_id)
			WHERE a.status = 1 AND ad.language_id = $config_language_id
		");

			$results = array();

			foreach ($query->rows as $description)
			{
				if ($description['tags'])
				{
					$tags = explode(',', $description['tags']);

					foreach ($tags as $tag)
					{
						$tag = trim($tag);

						$results[$tag] = $this->model_module_kbm->countArticles(array('tag' => $tag));
					}
				}
			}

			ksort($results);

			$this->cache->set(ModelModuleKbm::CACHE_PRODUCT_TAGS . ".$config_language_id", $results);
		}

		return $results;
	}

}