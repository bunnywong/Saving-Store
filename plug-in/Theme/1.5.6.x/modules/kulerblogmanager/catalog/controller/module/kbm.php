<?php
function _t()
{
    return call_user_func_array('ControllerModuleKbm::__', func_get_args());
}

/**
 * Class ControllerModuleKbm
 * @property Document $document
 * @property Url $url
 * @property Customer $customer
 * @property Response $response
 * @property Request $request
 * @property Session $session
 */
class ControllerModuleKbm extends Controller
{
    const MODE                  = 'PRODUCTION';

    public static $__           = array();
    public static $lost_texts   = array();

    /* @var ModelModuleKbm $model */
    protected $model;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('module/kbm');
        $this->model = $this->model_module_kbm;

        // Language
        self::$__ = $this->language->load('module/kbm');
        
        // Breadcrumb
        $home_title = $this->model->getSetting('home', 'blog_name');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->model->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $home_title,
            'href'      => $this->model->link('module/kbm'),
            'separator' => false
        );
    }

    public function index()
    {
        // Conditions
        $exclude_category_ids = $this->model->getSetting('home', 'exclude_category');
        $exclude_category_ids += $this->model->getDisabledCategoryIds();

        $conditions = array(
            'exclude_category' => $exclude_category_ids
        );

        // Pagination
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $per_page = $this->model->getSetting('category', 'articles_per_page');

        $pagination = new Pagination();
        $pagination->total = $this->model->countArticles($conditions);
        $pagination->page = $page;
        $pagination->limit = $per_page;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->model->link('module/kbm', array('page' => '{page}'));

        $this->data['pagination'] = $pagination->render();

        // Prepare Articles
        $articles = $this->model->getArticles($conditions, array('page' => $page, 'per_page' => $per_page));
        $articles = $this->model->prepareArticles($articles);

        foreach ($articles as &$article)
        {
            $article['description'] = $this->model->cutText($article['description'], 600);
            $article['comment_total'] = $this->model->countComments(array('article_id' => $article['article_id'], 'parent_comment_id' => 0));
        }

        $this->data['articles'] = $articles;

	    // Feed
	    $this->data['feed']     = $this->model->getSetting('feed', 'status');
	    $this->data['feed_url'] = $this->model->link('module/kbm/feed');

        // Page
        $home_title = $this->model->getSetting('home', 'blog_name');

        $this->data['heading_title']    = $home_title;
        $this->data['blog_description'] = html_entity_decode($this->model->getSetting('home', 'blog_home_description'), ENT_QUOTES, 'UTF-8');

	    $this->data['show_title']       = $this->model->getSetting('home', 'show_title');

        $this->document->setTitle($home_title);
        $this->document->setKeywords($this->model->getSetting('home', 'blog_keyword'));
        $this->document->setDescription($this->model->getSetting('home', 'blog_meta_description'));

	    $this->data['column'] = $this->model->getSetting('home', 'column');

        $this->renderView('module/kbm_home.tpl');
    }

    public function category()
    {
        $category_id = 0;

        // Prepare path
        $path = '';
        $parts = explode('_', isset($this->request->get['kbm_path']) ? $this->request->get['kbm_path'] : '0');

        foreach ($parts as $part)
        {
            if (!$path)
            {
                $path .= $part;
            }
            else
            {
                $path .= '_' . $part;
            }

            $category_id = $part;

            $category = $this->model->getCategoryByCategoryId($category_id);

            if (!$category || !$category['status'])
            {
                return $this->renderNotFound();
            }

            $category = $this->model->prepareCategory($category);

            $this->data['breadcrumbs'][] = array(
                'text'      => $category['name'],
                'href'      => $this->model->link('module/kbm/category', array('kbm_path' => $path)),
                'separator' => ' :: '
            );
        }

        // Override setting
        $article_order = $category['article_order'];
        $article_character_limit = $category['character_limit'] ? intval($category['character_limit']) : $this->model->getSetting('category', 'article_characters');
        $article_image_width = $category['article_image_width'] ? intval($category['article_image_width']) : $this->model->getSetting('category', 'featured_image_width');
        $article_image_height = $category['article_image_height'] ? intval($category['article_image_height']) : $this->model->getSetting('category', 'featured_image_height');

        $category['image_thumb'] = $this->model->prepareImage($category['image'], $article_image_width, $article_image_height);

        $this->data['category'] = $category;

        // Conditions
        $conditions = array(
            'category_id' => $category_id
        );

        // Sub categories
        $sub_categories = $this->model->getCategories(array('parent_id' => $category_id));

        foreach ($sub_categories as &$sub_category)
        {
            $sub_category = $this->model->prepareCategory($sub_category);

            $sub_category['link'] = $this->model->link('module/kbm/category', array('kbm_path' => $path . '_' . $sub_category['category_id']));
        }

        $this->data['sub_categories'] = $sub_categories;

        // Pagination
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $per_page = $this->model->getSetting('category', 'articles_per_page');

        $pagination = new Pagination();
        $pagination->total = $this->model->countArticles($conditions);
        $pagination->page = $page;
        $pagination->limit = $per_page;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->model->link('module/kbm', array('page' => '{page}'));

        $this->data['pagination'] = $pagination->render();

        // Prepare Articles
        $articles = $this->model->getArticles($conditions, array('page' => $page, 'per_page' => $per_page));
        $articles = $this->model->prepareArticles($articles);

        foreach ($articles as &$article)
        {
            $article['description'] = $this->model->cutText($article['description'], $article_character_limit);
            $article['comment_total'] = $this->model->countComments(array('article_id' => $article['article_id'], 'parent_comment_id' => 0));
            $article['featured_image_thumb'] = $this->model->prepareImage($article['featured_image'], $article_image_width, $article_image_height);
	        $article['link'] = $this->model->link('module/kbm/article', array('kbm_path' => $path, 'kbm_article_id' => $article['article_id']));
        }

        $this->data['articles'] = $articles;

	    // Column & Class Suffix
	    $this->data['column'] = $category['column'];

        // Page
        $this->document->setTitle($category['name']);
        $this->document->setKeywords($category['meta_keyword']);
        $this->document->setDescription($category['meta_description']);

        $this->renderView('module/kbm_category.tpl');
    }

    public function article()
    {
        $article_id = $this->request->get['kbm_article_id'];

        $article = $this->model->getArticleByArticleId($article_id);

        if (!$article || !$article['status'])
        {
            return $this->renderNotFound();
        }

        $this->model->updateArticleViewed($article_id);

        // Related Articles
	    $article['display_related_article'] = $this->model->getSetting('article', 'related_article');
	    if ($article['display_related_article'])
	    {
		    $related_articles = $this->model->getRelatedArticles($article['article_id']);
		    $this->data['related_articles'] = $related_articles;
	    }

	    // Comment
	    $this->data['comment_status'] = $this->model->getCommentStatusByArticleId($article_id);

	    $this->data['comment_url'] = $this->model->link('module/kbm/comment');

	    // Prepare article
	    $article = $this->model->prepareArticle($article);

	    $article['date_modified_formatted'] = $this->model->formatDate($article['date_modified']);

	    $this->data['article'] = $article;

	    // Addthis code
	    if ($this->model->getSetting('article', 'social_media'))
	    {
		    $addthis_id = $this->model->getSetting('article', 'add_analytic_code');
		    $addthis_code = $this->model->getSetting('article', 'add_this_custom_code');

		    $addthis_code = preg_replace('/pubid=([^"]+)/i', "pubid=$addthis_id", html_entity_decode($addthis_code, ENT_QUOTES, 'UTF-8'));

		    $this->data['addthis_code'] = $addthis_code;
	    }
	    else
	    {
		    $this->data['addthis_code'] = '';
	    }

        // Page
        $this->document->setTitle($article['name']);
        $this->document->setKeywords($article['meta_keyword']);
        $this->document->setDescription($article['meta_description']);

	    // Breadcrumbs
	    if (!empty($this->request->get['kbm_path']))
	    {
		    $paths = explode('_', $this->request->get['kbm_path']);

		    foreach ($paths as $category_id)
		    {
			    $category = $this->model->getCategoryByCategoryId($category_id);
			    $category = $this->model->prepareCategory($category);

			    if ($category)
			    {
				    $this->data['breadcrumbs'][] = array(
					    'text'          => $category['name'],
					    'href'          => $category['link'],
					    'separator'     => ' :: '
				    );
			    }
		    }
	    }

        $this->data['breadcrumbs'][] = array(
            'text'          => $article['name'],
            'href'          => $article['link'],
            'separator'     => ' :: '
        );

        $this->renderView('module/kbm_article.tpl');
    }

	public function comment()
	{
		$article_id = $this->request->get['kbm_article_id'];

		if (!$this->model->getCommentStatusByArticleId($article_id))
		{
			$this->response->setOutput('');
			return;
		}

		// Comment stats
		$this->data['reply_total'] = $this->model->countComments(array(
			'article_id'        => $article_id,
			'parent_comment_id' => array('>', 0)
		));

		// Prepare can Comment
		$customer_id = $this->customer->getId();

		$this->data['can_comment'] = $this->model->canComment($customer_id);

		// Comment Pagination
		$comment_total = $this->model->countComments(array(
			'article_id'        => $article_id,
			'parent_comment_id' => 0
		));

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$per_page = $this->model->getSetting('comment', 'comment_per_page');

		$pagination = new Pagination();
		$pagination->total = $comment_total;
		$pagination->page = $page;
		$pagination->limit = $per_page;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->model->link('module/kbm', array('page' => '{page}'));

		$this->data['comment_total'] = $comment_total;
		$this->data['pagination'] = $pagination->render();

		$sort_options = $this->model->prepareCommentSortOrder($this->model->getSetting('comment', 'comment_order'));

		$this->data['comments'] = $this->model->getComments(array(
			'article_id'            => $article_id,
			'parent_comment_id'     => 0,
		), array(
			'page'              => $page,
			'per_page'          => $per_page,
			'sort'              => $sort_options['sort'],
			'sort_direction'    => $sort_options['sort_direction']
		));

		// Prepare Comments
		$reply_total = 0;

		$comments = $this->model->prepareComments($this->data['comments']);
		foreach ($comments as &$comment)
		{
			$reply_conditions = array(
				'article_id'        => $article_id,
				'parent_comment_id' => $comment['comment_id']
			);

			$comment['replies'] = $this->model->getComments($reply_conditions);
			$comment['replies'] = $this->model->prepareComments($comment['replies']);
			$comment['reply_total'] = $this->model->countComments($reply_conditions);
		}

		$this->data['comments'] = $comments;

		// Captcha
		$customer_group_id = $this->customer->getCustomerGroupId();
		$enable_captcha = $this->model->enableCommentCaptcha($customer_id, $customer_group_id);

		if ($enable_captcha)
		{
			$this->data['captcha_url'] = $this->model->link('module/kbm/captcha');
		}

		// Comment Form

		// Comment Form - Article
		$this->data['article_id'] = $article_id;

		// Comment Form - Author
		$comment_author = array(
			'name' => '',
			'email' => '',
			'website' => ''
		);

		if ($customer_id)
		{
			$comment_author = array(
				'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
				'email' => $this->customer->getEmail(),
				'website' => ''
			);
		}

		$this->data['comment_author'] = $comment_author;

		// Writing Url
		$this->data['comment_writing_url'] = $this->model->link('module/kbm/writeComment');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_comment.tpl'))
		{
			$this->template = $this->config->get('config_template') . '/template/module/kbm_comment.tpl';
		}
		else
		{
			$this->template = 'default/template/module/kbm_comment.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function writeComment()
	{
		// Check post method
		if ($this->request->server['REQUEST_METHOD'] != 'POST')
		{
			$this->response->setOutput(json_encode(array(
				'status' => 0,
				'message' => ''
			)));

			return;
		}

		// Check comment can be replied
		$input = $this->request->post;

		$json = array();
		$errors = array();

		try
		{
			// Validate inputs
			$customer_id = $this->customer->getId();
			$customer_group_id = $this->customer->getCustomerGroupId();

			if (!$this->model->canComment($customer_id))
			{
				throw new Exception(_t('error_comment_permission'));
			}

			// Validate inputs - Parent Comment
			if ($input['parent_comment_id'])
			{
				$parent_comment = $this->model->getCommentByCommentId($input['parent_comment_id']);

				if (!$parent_comment || $parent_comment['parent_comment_id'])
				{
					throw new Exception('');
				}
			}

			// Validate inputs - Name
			if (empty($input['name']))
			{
				$errors['name'] = _t('error_comment_name');
			}

			// Email
			if (!$this->model->validateEmail($input['email']))
			{
				$errors['email'] = _t('error_comment_email');
			}

			// Validate inputs - Website

			// Validate inputs - Comment
			$comment_length = utf8_strlen($input['content']);
			if ($comment_length < $this->model->getSetting('comment', 'min_character') || $comment_length > $this->model->getSetting('comment', 'max_character'))
			{
				$errors['content'] = _t('error_comment_comment', $this->model->getSetting('comment', 'min_character'), $this->model->getSetting('comment', 'max_character'));
			}

			// Validate inputs - Captcha
			if ($this->model->enableCommentCaptcha($customer_id, $customer_group_id) && $input['captcha'] != $this->session->data['blog_captcha'])
			{
				$errors['captcha'] = _t('error_comment_captcha');
			}

			if ($errors)
			{
				throw new Exception(_t('error_comment_fields'));
			}

			// Prepare comment
			// Prepare comment - data
			$comment_data = array(
				'website' => $input['website']
			);

			$customer_name = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();

			if ($input['name'] != $customer_name)
			{
				$comment_data['name'] = $input['name'];
			}

			if ($input['email'] != $this->customer->getEmail())
			{
				$comment_data['email'] = $input['email'];
			}

			$comment = array(
				'parent_comment_id' => $input['parent_comment_id'],
				'author_id'         => $customer_id ? $customer_id : 0,
				'author_type'       => $customer_id ? ModelModuleKbm::AUTHOR_TYPE_CUSTOMER : ModelModuleKbm::AUTHOR_TYPE_GUEST,
				'article_id'        => $input['kbm_article_id'],
				'content'           => $input['content'],
				'data'              => $comment_data,
				'status'            => $this->model->getSetting('comment', 'auto_approve') && in_array($customer_group_id, array_keys($this->model->getSetting('comment', 'auto_approve_group'))) ? 1 : 0
			);

			// If passed, store comment in db
			$comment_id = $this->model->insertComment($comment);

			// Send mail
			if ($comment_id && $this->model->getSetting('comment', 'email_notification'))
			{
				// Get article info
				$article = $this->model->getArticleByArticleId($input['article_id']);
				$article = $this->model->prepareArticle($article);

				if (!$article)
				{
					throw new Exception(_t('error_article_does_not_exist'));
				}

				$post_date = $this->model->formatDateTime(ModelModuleKbm::$time);

				$mail_title = _t('text_success_email_title');
				$mail_content = _t('text_success_email_content', $input['name'], $article['name'], $post_date, $input['content']);

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');

				$mail->setTo($this->request->post['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));

				$mail->setSubject(html_entity_decode($mail_title, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($mail_content, ENT_QUOTES, 'UTF-8'));

				$mail->send();
			}

			if ($comment['status'])
			{
				$json['status'] = 1;
			}
			else
			{
				$json['status'] = 0;
				$json['message'] = _t('error_wait_approved');
			}
		}
		catch (Exception $e)
		{
			$json['status'] = 0;
			$json['errors'] = $errors;
			$json['message'] = $e->getMessage();
		}

		// Return response
		$this->response->setOutput(json_encode($json));
	}

    public function captcha()
    {
        $this->load->library('captcha');

        $captcha = new Captcha();

        $this->session->data['blog_captcha'] = $captcha->getCode();

        $captcha->showImage();
    }

	public function feed()
	{
		if (!$this->model->getSetting('feed', 'status'))
		{
			return $this->renderNotFound();
		}

		// Prepare blog info
		$this->data['title'] = $this->model->getSetting('home', 'blog_name');
		$this->data['link'] = $this->model->link('module/kbm');
		$this->data['description'] = $this->model->cutText($this->model->getSetting('home', 'blog_home_description'), 100);

		$this->data['image_url'] = $this->config->get('config_logo');

		// Prepare items
		$categories = $this->model->getCategories(array());
		$categories = array_map(array($this->model, 'prepareCategory'), $categories);

		$keyed_categories = array();

		foreach ($categories as $category)
		{
			$keyed_categories[$category['category_id']] = $category;
		}

		$articles = $this->model->getArticles(array(), array('limit' => $this->model->getSetting('feed', 'limit')));
		$articles = $this->model->prepareArticles($articles);

		$items = array();

		foreach ($articles as $article)
		{
			$item = array(
				'title'         => $article['name'],
				'pub_date'      => date('D, d M Y', $article['date_published']),
				'link'          => $article['link'],
				'image_url'     => $article['featured_image_thumb'],
				'description'   => $this->model->cutText($article['description'], 100),
				'category'      => false
			);

			$first_category = current($article['categories']);
			if ($first_category)
			{
				$item['category']           = true;

				$item['category_url']       = $first_category['link'];
				$item['category_title']    = $first_category['name'];
			}

			$items[] = $item;
		}

		$this->data['items'] = $items;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_feed.tpl'))
		{
			$this->template = $this->config->get('config_template') . '/template/module/kbm_feed.tpl';
		}
		else
		{
			$this->template = 'default/template/module/kbm_feed.tpl';
		}

		$this->response->addHeader('Content-Type: text/xml;');
		$this->response->setOutput($this->render());
	}

	public function sitemap()
	{
		if (!$this->model->getSetting('sitemap', 'status'))
		{
			return $this->renderNotFound();
		}

		$articles = $this->model->getArticles(array(), array('limit' => $this->model->getSetting('sitemap', 'limit')));
		$articles = $this->model->prepareArticles($articles);

		$urls = array();
		foreach ($articles as $article)
		{
			$urls[] = array(
				'loc'           => urlencode($article['link']),
				'lastmod'       => date('Y-m-d', $article['date_modified']),
				'changefreq'    => 'weekly',
				'priority'      => 1
			);
		}

		$this->data['urls'] = $urls;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kbm_sitemap.tpl'))
		{
			$this->template = $this->config->get('config_template') . '/template/module/kbm_sitemap.tpl';
		}
		else
		{
			$this->template = 'default/template/module/kbm_sitemap.tpl';
		}

		$this->response->addHeader('Content-Type: text/xml;');
		$this->response->setOutput($this->render());
	}

	public function search()
	{
		// Get search input
		$tag = '';

		if (isset($this->request->get['tag']))
		{
			$tag = htmlspecialchars_decode($this->request->get['tag']);
		}

		// Prepare page limit
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
		$per_page = isset($this->request->get['limit']) ? $this->request->get['limit'] : 15;

		// Prepare criteria
		$conditions = array(
			'tag' => $tag
		);

		$fetch_options = array(
			'page'      => $page,
			'per_page'  => $per_page
		);

		// Get articles that match criteria
		$this->load->model('module/kbm');

		$articles = $this->model->getArticles($conditions, $fetch_options);

		// Get total of matched get articles
		$article_total = $this->model->countArticles($conditions, $fetch_options);

		// Prepare pagination
		$pagination         = new Pagination();
		$pagination->total  = $article_total;
		$pagination->page   = $page;
		$pagination->limit  = $per_page;
		$pagination->text   = $this->language->get('text_pagination');
		$pagination->url    = $this->model->link('module/kbm/search', array(
			'tag'       => $tag,
			'page'      => '{page}',
			'limit'     => $per_page
		));

		$this->data['pagination'] = $pagination->render();

		// Prepare articles
		$articles = $this->model->prepareArticles($articles);

		$this->data['articles'] = $articles;

		// Page
		$this->data['heading_title'] = _t('text_search') . ' - ' . $tag;

		$this->document->setTitle($this->data['heading_title']);

		// Render matched articles
		$this->renderView('module/kbm_search.tpl');
	}

    private function renderView($template)
    {
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $template))
        {
            $this->template = $this->config->get('config_template') . '/template/' . $template;
        }
        else
        {
            $this->template = 'default/template/' . $template;
        }

	    $this->document->addStyle('catalog/view/kulercore/css/kbm.css');

        $this->children = array(
            'common/kuler_column_left',
            'common/kuler_column_right',
            'common/kuler_content_top',
            'common/kuler_content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

    public static function __()
    {
        $args = func_get_args();
        $text = $args[0];
        array_shift($args);

        if (isset(self::$__[$text]))
        {
            array_unshift($args, self::$__[$text]);

            return call_user_func_array('sprintf', $args);
        }
        else
        {
            if (self::MODE == 'DEVELOPMENT')
            {
                if (!in_array($text, self::$lost_texts))
                {
                    $cache[] = $text;

                    // todo: remove logger
                    Logger::log($text);
                }
            }

            return $text;
        }
    }

    private function renderNotFound()
    {
        $this->language->load('error/not_found');

        $this->document->setTitle($this->language->get('text_error'));

        $this->data['heading_title'] = $this->language->get('text_error');
        $this->data['text_error'] = $this->language->get('text_error');
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['continue'] = $this->model->link('module/kbm');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl'))
        {
            $this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
        }
        else
        {
            $this->template = 'default/template/error/not_found.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }
}