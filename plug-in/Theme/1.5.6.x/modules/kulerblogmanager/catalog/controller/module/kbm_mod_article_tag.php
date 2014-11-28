<?php
/**
 * Class ControllerModuleKBMModArticleTag
 * @property ModelModuleKBMModArticleTag $model
 * @property ModelKulerBlogManager $kbm_model
 */
class ControllerModuleKBMModArticleTag extends Controller
{
    private $model;
	private $kbm_model;

    public function __construct($registry)
    {
        parent::__construct($registry);

	    $this->load->model('module/kbm');
	    $this->kbm_model = $this->model_module_kbm;

        $this->load->model('module/kbm_mod_article_tag');
        $this->model = $this->model_module_kbm_mod_article_tag;
    }

    public function index($setting)
    {
        static $module = 0;
	    $this->language->load('module/kbm_mod_article_tag');

	    // Get product tags
	    $tags = $this->model->getProductTags();

	    // Calculate font size for each tag
	    $size_tags = array();

	    if ($tags)
	    {
		    $min_size = 10;
		    $max_size = 30;

		    $min = min($tags);
		    $max = max($tags);

			$spread = $max - $min;

		    if ($spread <= 0)
		    {
			    $spread = 1;
		    }

			$step = ($max_size - $min_size) / $spread;

		    foreach ($tags as $tag => $count)
		    {
			    $size_tags[] = array(
				    'tag'   => $tag,
				    'size'  => round($min_size + ($count - $min) * $step),
				    'count' => $count,
				    'link'  => $this->kbm_model->link('module/kbm/search', array('tag' => $tag)),
				    'text_tag' => sprintf($this->language->get('text_x_items_tagged_with_y'), $count, $tag)
		    );
		    }
	    }

	    $this->data['tags'] = $size_tags;

	    // Module Index
	    $this->data['module']   = $module++;

	    // Module title
	    $this->data['show_title']   = $setting['show_title'];
	    $this->data['title']        = $this->translate($setting['title'], $this->config->get('config_language_id'));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_mod_article_tag.tpl'))
        {
            $this->template = $this->config->get('config_template') . '/template/module/kbm_mod_article_tag.tpl';
        }
        else
        {
            $this->template = 'default/template/module/kbm_mod_article_tag.tpl';
        }

        $this->render();
    }

	private function translate($texts, $language_id)
	{
		if (is_array($texts))
		{
			$first = current($texts);

			if (is_string($first))
			{
				$texts = empty($texts[$language_id]) ? $first : $texts[$language_id];
			}
			else if (is_array($texts))
			{
				if (!isset($texts[$language_id]))
				{
					$texts[$language_id] = array();
				}

				foreach ($first as $key => $value)
				{
					if (empty($texts[$language_id][$key]))
					{
						$texts[$language_id][$key] = $value;
					}
				}
			}
		}

		return $texts;
	}
}