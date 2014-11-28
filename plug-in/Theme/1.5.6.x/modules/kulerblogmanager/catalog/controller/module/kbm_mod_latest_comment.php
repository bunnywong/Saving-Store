<?php
/**
 * Class ControllerModuleKBMModLatestComment
 * @property ModelModuleKBMModRecentArticle $model
 * @property ModelKulerBlogManager $kbm_model
 */
class ControllerModuleKBMModLatestComment extends Controller
{
    private $model;
	private $kbm_model;

    public function __construct($registry)
    {
        parent::__construct($registry);

//        $this->load->model('module/kbm_latest_comment');
//        $this->model = $this->model_module_kbm_latest_comment;

	    $this->load->model('module/kbm');
	    $this->kbm_model = $this->model_module_kbm;
    }

    public function index($setting)
    {
        static $module = 0;

	    // Prepare conditions
	    $conditions = array();

	    if (!empty($setting['exclude_categories']))
	    {
		    $conditions['exclude_category'] = $setting['exclude_categories'];
	    }

	    // Prepare fetch options
	    $fetch_options = array(
		    'page'      => 1,
		    'per_page'  => intval($setting['limit']) ? intval($setting['limit']) : 5,

		    'sort'              => 'date_added',
		    'sort_direction'    => 'DESC'
	    );

	    // Comments
	    $comments = $this->kbm_model->getComments($conditions, $fetch_options);
	    $comments = $this->kbm_model->prepareComments($comments);
	    foreach ($comments as &$comment)
	    {
			$comment['author']['avatar_url'] = $this->kbm_model->prepareCommentAvatarUrl($comment['author']['email'], intval($setting['avatar_size']));

		    $article = $this->kbm_model->getArticleByArticleId($comment['article_id']);
		    $article = $this->kbm_model->prepareArticle($article);

		    $comment['article'] = array(
			    'link'  => $article['link'],
			    'name'  => $article['name']
		    );
	    }

	    $this->data['comments'] = $comments;

	    // Module Index
	    $this->data['module']   = $module++;

	    // Module title
	    $this->data['show_title']   = $setting['show_title'];
	    $this->data['title']        = $this->translate($setting['title'], $this->config->get('config_language_id'));

	    // Languages
	    $this->language->load('module/kbm_mod_latest_comment');
	    $this->data['text_on'] = $this->language->get('text_on');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_latest_comment.tpl'))
        {
            $this->template = $this->config->get('config_template') . '/template/module/kbm_mod_latest_comment.tpl';
        }
        else
        {
            $this->template = 'default/template/module/kbm_mod_latest_comment.tpl';
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