<?php
/**
 * Class ControllerModuleKBMModPopularArticle
 * @property ModelModuleKBMModRecentArticle $model
 * @property ModelKulerBlogManager $kbm_model
 */
class ControllerModuleKBMModPopularArticle extends Controller
{
    private $model;
	private $kbm_model;

    public function __construct($registry)
    {
        parent::__construct($registry);

//        $this->load->model('module/kbm_popular_article');
//        $this->model = $this->model_module_kbm_popular_article;

	    $this->load->model('module/kbm');
	    $this->kbm_model = $this->model_module_kbm;
    }

    public function index($setting)
    {
        static $module = 0;

	    // Preset settings
	    $setting['product_featured_image']  = isset($setting['product_featured_image']) ? $setting['product_featured_image'] : 0;
	    $setting['product_description']     = isset($setting['product_description']) ? $setting['product_description'] : 0;
	    $setting['description_limit']       = intval($setting['description_limit']) ? intval($setting['description_limit']) : 100;

	    // Prepare conditions
	    $conditions = array();

	    if (!empty($setting['specific_categories']))
	    {
		    $conditions['category_id'] = $setting['specific_categories'];
	    }

	    if (!empty($setting['exclude_categories']))
	    {
		    $conditions['exclude_category'] = $setting['exclude_categories'];
	    }

	    // Prepare fetch options
	    $fetch_options = array(
		    'page'      => 1,
		    'per_page'  => intval($setting['article_limit']) ? intval($setting['article_limit']) : 5,

		    'sort'              => 'viewed',
		    'sort_direction'    => 'DESC'
	    );

	    // Articles
	    $this->data['product_featured_image']   = $setting['product_featured_image'];
	    $this->data['product_description']      = $setting['product_description'];

	    $articles = $this->kbm_model->getArticles($conditions, $fetch_options);
	    $articles = $this->kbm_model->prepareArticles($articles);

	    foreach ($articles as &$article)
	    {
		    $article['featured_image_thumb']    = $this->kbm_model->prepareImage($article['featured_image'], $setting['featured_image_width'], $setting['featured_image_height']);
		    $article['description']             = $this->kbm_model->cutText($article['description'], $setting['description_limit']);
	    }

	    $this->data['articles'] = $articles;
	    $this->data['module']   = $module;

	    $module++;

	    // Module title
	    $this->data['show_title']   = $setting['show_title'];
	    $this->data['title']        = $this->translate($setting['title'], $this->config->get('config_language_id'));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_mod_popular_article.tpl'))
        {
            $this->template = $this->config->get('config_template') . '/template/module/kbm_mod_popular_article.tpl';
        }
        else
        {
            $this->template = 'default/template/module/kbm_mod_popular_article.tpl';
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