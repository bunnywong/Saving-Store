<?php
function _t($text, $placeholder = '')
{
    return ControllerModuleKbm::__($text, $placeholder);
}

/**
 * Class ControllerModuleKbm
 * @property Config $config
 * @property Url $url
 * @property Request $request
 * @property Session $session
 * @property Document $document
 * @property Response $response
 * @property User $user
 * @property ModelModuleKbm $model
 */
class ControllerModuleKbm extends Controller
{
    const MODE                  = 'PRODUCTION';

    public static $__           = array();
    public static $lost_texts   = array();

    private $model;
    private $errors             = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('module/kbm');
        $this->model = $this->model_module_kbm;

        self::$__ = $this->getLanguages();

        // Resources
        $this->document->addStyle('view/kulercore/css/kulercore.css');
        $this->document->addStyle('view/kulercore/css/kbm.css');

        $this->document->addScript('view/kulercore/js/handlebars.js');

        // Vars
        $this->data['token']        = $this->session->data['token'];
        $this->data['catalog_base'] = $this->model->getCatalogBase();

        // Breadcrumb
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->helperLink('common/home'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->helperLink('extension/module'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_module_title'),
            'href'      => $this->helperLink('module/kbm'),
            'separator' => ' :: '
        );

        // Navigation
        $this->data['home_url']         = $this->helperLink('module/kbm');
        $this->data['article_url']      = $this->helperLink('module/kbm/article');
        $this->data['category_url']     = $this->helperLink('module/kbm/category');
        $this->data['comment_url']      = $this->helperLink('module/kbm/comment');
        $this->data['author_url']       = $this->helperLink('module/kbm/author');
        $this->data['setting_url']      = $this->helperLink('module/kbm/setting');
    }

    public function index()
    {
        $this->data['article_total']        = $this->model->countArticles();
        $this->data['category_total']       = $this->model->countCategories();
        $this->data['comment_total']        = $this->model->countComments();
        $this->data['author_total']         = $this->model->countAuthors();

        // Navigation
        $this->data['article_insert_url']   = $this->helperLink('module/kbm/article_insert');
        $this->data['article_list_url']     = $this->helperLink('module/kbm/article');
        $this->data['category_insert_url']  = $this->helperLink('module/kbm/category_insert');
        $this->data['category_list_url']    = $this->helperLink('module/kbm/category');
        $this->data['comment_list_url']     = $this->helperLink('module/kbm/comment');
        $this->data['author_url']           = $this->helperLink('module/kbm/author');
        $this->data['setting_url']          = $this->helperLink('module/kbm/setting');

        $this->renderView('module/kbm_index.tpl');
    }

    /* ARTICLE */
    public function article()
    {
        // Prepare filters
        $conditions = isset($this->request->get['filters']) ? $this->request->get['filters'] : array();
        $conditions = $this->article_prepareFilters($conditions);

        // Prepare sort order
        $order = isset($this->request->get['order']) ? $this->request->get['order'] : 'date_added';
        $order_direction = isset($this->request->get['order_direction']) ? $this->request->get['order_direction'] : 'desc';

        // Prepare pagination
        $page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $per_page = $this->model->getSetting('admin', 'articles_per_page');

        $pagination = new Pagination();
        $pagination->total = $this->model->countArticles($conditions);
        $pagination->page = $page;
        $pagination->limit = $per_page;
        $pagination->text = $this->language->get('text_pagination');

        $extra_page_params = array(
            'filters'   => $conditions,
            'page'      => '{page}'
        );
        if ($order)
        {
            $extra_page_params['order'] = $order;
            $extra_page_params['order_direction'] = $order_direction;
        }

        $pagination->url = $this->helperLink('module/kbm/article', $extra_page_params);
        $this->data['pagination'] = $pagination->render();
        
        // Prepare articles
        $articles = $this->model->getArticles($conditions, array(
            'page'              => $page,
            'per_page'          => $per_page,
            'order'             => $order,
            'order_direction'   => $order_direction
        ));

        // - Prepare articles Actions
        foreach ($articles as &$article)
        {
            $article['actions'] = array(
                array(
                    'text'  => _t('text_edit'),
                    'href'  => $this->helperLink('module/kbm/article_edit', array('article_id' => $article['article_id']))
                )
            );
        }

        $this->data['articles'] = $this->model->prepareArticles($articles);

        // Filters
        $this->data['filters']  = $conditions;
        $this->data['category_options'] = $this->model->getCategoryOptions();
        $this->data['author_options']   = $this->model->getAuthorOptions();
        $this->data['store_options']    = $this->model->getStoreOptions();

        $this->data['reset_url']        = $this->helperLink('module/kbm/article');

        $this->data['filter_action']    = $this->helperLink('module/kbm/article');
        $this->data['route']            = 'module/kbm/article';

        $this->data['order'] = $order;
        $this->data['order_direction'] = $order_direction;

        // Prepare order urls
        $inverse_order = $order_direction == 'desc' ? 'asc' : 'desc';

        $this->data['order_article_title_url']    = $this->helperLink('module/kbm/article', array('filters' => $conditions, 'order' => 'article_title', 'order_direction' => $inverse_order));
        $this->data['order_date_added_url']       = $this->helperLink('module/kbm/article', array('filters' => $conditions, 'order' => 'date_added', 'order_direction' => $inverse_order));
        $this->data['order_author_url']           = $this->helperLink('module/kbm/article', array('filters' => $conditions, 'order' => 'author_name', 'order_direction' => $inverse_order));
        $this->data['order_order_url']            = $this->helperLink('module/kbm/article', array('filters' => $conditions, 'order' => 'sort_order', 'order_direction' => $inverse_order));

        // Prepare main urls
        $this->data['insert_url']   = $this->helperLink('module/kbm/article_insert');
        $this->data['delete_url']   = $this->helperLink('module/kbm/article_delete');
        $this->data['copy_url']     = $this->helperLink('module/kbm/article_copy');
        $this->data['cancel']       = $this->helperLink('extension/module');

        $this->data['fast_edit_url'] = $this->helperLink('module/kbm/article_fastEdit');

        $this->renderView('module/kbm_article_list.tpl', _t('heading_article'));
    }

    public function article_insert()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateArticleForm())
        {
            if (!$this->model->hasPermission('create_article'))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            $article = $this->request->post['article'];

	        $article['description'] = $this->model->translate($article['description']);

            $article_id = $this->model->insertArticle($article);

            if ($this->request->post['op'] == 'apply')
            {
                // @todo: Test apply
                $this->redirect($this->helperLink('module/kbm/article_edit', array('article_id' => $article_id)));
            }
            else
            {
                $this->redirect($this->helperLink('module/kbm/article'));
            }
        }

        $article = array(
            'article'               => array(
                'article_id'        => 0,
                'author_id'         => 0,
                'featured_image'    => '',
                'date_added'        => ModelModuleKbm::$time,
                'date_modified'     => ModelModuleKbm::$time,
                'date_published'    => ModelModuleKbm::$time,
                'status'            => 1,
                'sort_order'        => '',
            ),
            'description'       => array(),
            'category'          => array(),
            'store'             => array(),
            'layout'            => array(),
            'keyword'           => ''
        );

        $stores = $this->model->getStoreOptions();
        foreach ($stores as $store_id => $store_name)
        {
            $article['store'][] = $store_id;
            $article['layout'][$store_id] = 0;
        }

        $this->data['action']   = $this->helperLink('module/kbm/article_insert');
        $this->data['apply']    = $this->helperLink('module/kbm/article_insert');

        $this->data['sub_heading'] = _t('sub_heading_insert_article');

        $this->article_getForm($article);
    }

    public function article_edit()
    {
        $article_id = $this->request->get['article_id'];

        $article = $this->model->getArticleByArticleId($article_id);

        if (empty($article))
        {
            return $this->forward('module/kbm/error', array('message' => _t('error_article_not_found')));
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateArticleForm())
        {
            if (($this->user->getId() == $article['author_id'] && !$this->model->hasPermission('edit_own_article'))         // Can edit own article
                || ($this->user->getId() != $article['author_id'] && !$this->model->hasPermission('edit_other_article'))    // Can edit other article
            )
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            $article        = $this->request->post['article'];
            $article_id     = $article['article']['article_id'];

            $this->model->editArticle($article);

            $this->session->data['success'] = _t('text_you_have_modified_article');

            if ($this->request->post['op'] == 'apply')
            {
                // @todo: Test apply
                $this->redirect($this->helperLink('module/kbm/article_edit', array('article_id' => $article_id)));
            }
            else
            {
                $this->redirect($this->helperLink('module/kbm/article'));
            }
        }

        $article = array(
            'article'       => $article,
            'description'   => $this->model->getArticleDescriptionsByArticleId($article_id),
            'category'      => $this->model->getArticleCategoryIdsByArticleId($article_id),
            'store'         => $this->model->getArticleStoresByArticleId($article_id),
            'layout'        => $this->model->getArticleLayoutsByArticleId($article_id),
            'keyword'       => $this->model->getUrlAlias('article', $article_id),
        );

        // Edit modified time
        $article['article']['date_modified'] = ModelModuleKbm::$time;

        $this->data['action'] = $this->helperLink('module/kbm/article_edit', array('article_id' => $article_id));
        $this->data['apply']    = $this->helperLink('module/kbm/article_edit');

        $this->data['sub_heading'] = _t('sub_heading_edit_article');

        $this->article_getForm($article);
    }

    public function article_delete()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('remove_article'))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            $article_ids = $this->request->post['article_ids'];

            foreach ($article_ids as $article_id)
            {
                $this->model->deleteArticle($article_id);
            }

            $this->session->data['success'] = _t('text_you_have_deleted_articles');
        }

        $this->redirect($this->helperLink('module/kbm/article'));
    }

    public function article_copy()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('copy_article'))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            $article_ids = $this->request->post['article_ids'];

            foreach ($article_ids as $article_id)
            {
                $this->model->copyArticle($article_id);
            }

            $this->session->data['success'] = _t('text_you_have_copied_articles');
        }

        $this->redirect($this->helperLink('module/kbm/article'));
    }

    public function article_fastEdit()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            $articles = $this->request->post['articles'];

            foreach ($articles as $article_id => $new_article)
            {
                $article = $this->model->getArticleByArticleId($article_id);

                if (($this->user->getId() == $article['author_id'] && !$this->model->hasPermission('edit_own_article'))         // Can edit own article
                    || ($this->user->getId() != $article['author_id'] && !$this->model->hasPermission('edit_other_article'))    // Can edit other article
                )
                {
                    $this->response->setOutput(json_encode(array(
                        'status' => 0,
                        'message' => _t('error_blog_permission')
                    )));

                    return false;
                }

                $this->model->editArticleStatusOrder($article_id, $new_article);
            }
        }

        $this->response->setOutput(json_encode(array('status' => 1)));
    }

    public function article_getForm(array $article)
    {
        // Prepare article

        if (isset($this->request->post['article']))
        {
            $article = $this->request->post['article'];

            if (empty($article['related_articles']))
            {
                $article['related_articles'] = array();
            }

            $article['article']['date_added_formatted']     = $article['article']['date_added'];
            $article['article']['date_modified_formatted']  = $article['article']['date_modified'];
            $article['article']['date_published_formatted'] = $article['article']['date_published'];
        }
        else
        {
            // - Description
            $languages = $this->model->getLanguages();
            $first_description = current($article['description']);

            if (!$first_description)
            {
                $first_description = array(
                    'name'              => '',
                    'meta_keyword'      => '',
                    'meta_description'  => '',
                    'description'       => '',
                    'tags'              => ''
                );
            }

            $descriptions = $article['description'];

            foreach ($languages as $language)
            {
                if (!isset($descriptions[$language['language_id']]))
                {
                    $descriptions[$language['language_id']] = array();
                }

                foreach ($first_description as $key => $value)
                {
                    if (empty($descriptions[$language['language_id']][$key]))
                    {
                        $descriptions[$language['language_id']][$key] = $value;
                    }
                }
            }

            $article['description'] = $descriptions;

            // - Date
            $article['article']['date_added_formatted']     = $this->model->formatDateTime($article['article']['date_added']);
            $article['article']['date_modified_formatted']  = $this->model->formatDateTime($article['article']['date_modified']);
            $article['article']['date_published_formatted'] = $this->model->formatDate($article['article']['date_published']);

            // - Related Article
            $article['related_articles']    = $this->model->getRelatedArticleIds($article['article']['article_id']);
        }

        // Related
        $article['related_articles'] = $this->model->prepareRelatedArticles($article['related_articles']);

        // - Featured Image
        $this->load->model('tool/image');

        if ($article['article']['featured_image'])
        {
            $article['article']['featured_image_thumb'] = $this->model_tool_image->resize($article['article']['featured_image'], $this->model->getSetting('article', 'featured_image_width'), $this->model->getSetting('article', 'featured_image_height'));
        }
        else
        {
            $article['article']['featured_image_thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->model->getSetting('article', 'featured_image_width'), $this->model->getSetting('article', 'featured_image_height'));
        }

        $this->data['article'] = $article;

        // Prepare options
        $this->data['author_options']               = $this->model->getAuthorOptions();
        $this->data['category_options']             = $this->model->getCategoryOptions();
        $this->data['store_options']                = $this->model->getStoreOptions();
        $this->data['layout_options']               = $this->model->getLayoutOptions(true);
        $this->data['languages']                    = $this->model->getLanguages();

        $this->data['related_article_url']  = $this->helperLink('module/kbm/article_getArticlesInCategory');

        $this->data['cancel'] = $this->helperLink('module/kbm/article');

        $this->data['breadcrumbs'][] = array(
            'text'      => _t('heading_article'),
            'href'      => $this->helperLink('module/kbm/article'),
            'separator' => ' :: '
        );

        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
        $this->document->addScript('view/javascript/jquery/ui/jquery-ui-timepicker-addon.js');

        $this->renderView('module/kbm_article_form.tpl', _t('heading_article'));
    }

    public function article_getArticlesInCategory()
    {
        $category_id = $this->request->get['category_id'];

        $articles = $this->model->getArticles(array('category_id' => $category_id));

        $json = array(
            'status'    => 1,
            'items'     => array()
        );

        foreach ($articles as $article)
        {
            $json['items'][$article['article_id']] = $article['name'];
        }

        $this->response->setOutput(json_encode($json));
    }

    public function article_prepareFilters(array $filters)
    {
        foreach ($filters as $key => $val)
        {
            if ($val === '')
            {
                unset($filters[$key]);
            }
        }

        return $filters;
    }

    private function validateArticleForm()
    {
        // Description
        if (isset($this->request->post['article']['description']))
        {
            $languages      = $this->model->getLanguages();
            $descriptions   = $this->request->post['article']['description'];

            foreach ($descriptions as $language_id => $description)
            {
                if (empty($description['name']))
                {
                    $this->errors['article_name'][$language_id] = _t('error_field_required');
                }
	            break;
            }
        }

        return $this->errors ? false : true;
    }
    /* end ARTICLE */

    /* CATEGORY */
    public function category()
    {
        // Category List
        $categories = $this->model->getCategories();

        foreach ($categories as &$category)
        {
            $category['actions'] = array(
                array(
                    'text' => _t('text_edit'),
                    'href' => $this->helperLink('module/kbm/category_edit', array('category_id' => $category['category_id']))
                )
            );

            $category['keyword'] = $this->model->getUrlAlias('category', $category['category_id']);
        }

        $this->data['categories'] = $categories;

        // Breadcrumbs
        $this->data['breadcrumbs'][] = array(
            'text'      => _t('heading_category'),
            'href'      => $this->helperLink('module/kbm/category'),
            'separator' => ' :: '
        );

        // Urls
        $this->data['insert_url']   = $this->helperLink('module/kbm/category_insert');
        $this->data['copy_url']     = $this->helperLink('module/kbm/category_copy');
        $this->data['delete_url']   = $this->helperLink('module/kbm/category_delete');

        $this->data['fast_edit_url'] = $this->helperLink('module/kbm/category_fast_edit');

        $this->data['action']       = $this->helperLink('module/kbm/category_delete');
        $this->data['cancel']       = $this->helperLink('extension/module');

        $this->renderView('module/kbm_category_list.tpl', _t('heading_category'));
    }

    public function category_insert()
    {
        $this->data['sub_heading_title'] = _t('text_insert_category');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission() && $this->validateCategoryForm())
        {
            if (!$this->model->hasPermission('create_category'))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

	        $this->request->post['category']['description'] = $this->model->translate($this->request->post['category']['description']);

            $this->model->insertCategory($this->request->post['category']);

            $this->session->data['success'] = _t('text_you_have_inserted_a_new_category');

            $this->redirect($this->helperLink('module/kbm/category'));
        }

        $category = array(
            'category' => array(
                'category_id' => 0,
                'status' => 1,
                'parent_id' => 0,
                'keyword' => '',
                'article_order' => 1,
                'article_image_width' => '',
                'article_image_height' => '',
                'character_limit' => '',
                'image' => '',
                'sort_order' => '',

                'class_suffix' => '',
                'column' => 1
            ),
            'store' => array(),
            'layout' => array(),
            'description' => array(),
            'keyword' => ''
        );

        // Set default value for store, layout
        $stores = $this->model->getStoreOptions();
        foreach ($stores as $store_id => $store_name)
        {
            $category['store'][] = $store_id;

            $category['layout'][] = array(
                'store_id' => $store_id,
                'layout_id' => 0
            );
        }

        // Set default value for description
        foreach ($this->getLanguageOptions() as $language)
        {
            if (empty($category['description'][$language['language_id']]))
            {
                $category['description'][$language['language_id']] = array(
                    'name' => '',
                    'meta_keyword' => '',
                    'meta_description' => '',
                    'description' => ''
                );
            }
        }

        $this->data['action'] = $this->helperLink('module/kbm/category_insert');

        $this->category_getForm($category);
    }

    public function category_edit()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateCategoryForm())
        {
            if (!$this->model->hasPermission('edit_category'))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            $this->model->editCategory($this->request->post['category']);

            $this->session->data['success'] = _t('text_you_have_modified_category');

            $this->redirect($this->helperLink('module/kbm/category'));
        }

        // Category
        $category_id = isset($this->request->get['category_id']) ? $this->request->get['category_id'] : 0;

        $category = $this->model->getCategoryById($category_id);

        if (empty($category))
        {
            return $this->forward('module/kbm/error', array('message' => _t('error_category_not_found')));
        }

        $category_data = array(
            'category'      => $category,
            'description'   => $this->model->getCategoryDescriptionsByCategoryId($category_id),
            'keyword'       => $this->model->getUrlAlias('category', $category_id),
            'store'         => $this->model->getCategoryStoresByCategoryId($category_id),
            'layout'        => $this->model->getCategoryLayoutsByCategoryId($category_id)
        );

        $this->data['sub_heading_title'] = _t('sub_heading_edit_category');

        $this->data['action'] = $this->helperLink('module/kbm/category_edit', array('category_id' => $category_id));

        $this->category_getForm($category_data);
    }

    public function category_delete()
    {
        if (!$this->model->hasPermission('remove_category'))
        {
            return $this->forward('module/kbm/error', array('message' => 'error_blog_permission'));
        }

        $category_ids = $this->request->post['category_ids'];

        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            foreach ($category_ids as $category_id)
            {
                $this->model->deleteCategory($category_id);
            }
        }

        $this->session->data['success'] = _t('text_you_have_deleted_categories');

        $this->redirect($this->helperLink('module/kbm/category'));
    }

    public function category_copy()
    {
        $category_ids = $this->request->post['category_ids'];

        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('copy_category'))
            {
                return $this->forward('module/kbm/error', array('message' => 'error_blog_permission'));
            }

            foreach ($category_ids as $category_id)
            {
                $this->model->copyCategory($category_id);
            }
        }

        $this->session->data['success'] = _t('text_you_have_copied_categories');
        $this->redirect($this->helperLink('module/kbm/category'));
    }

    public function category_fast_edit()
    {
        if (!$this->model->hasPermission('edit_category'))
        {
            $this->response->setOutput(json_encode(array(
                'status' => 0,
                'message' => _t('error_blog_permission')
            )));

            return false;
        }

        $json = array(
            'status' => 1
        );

        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            $categories = $this->request->post['category'];

            foreach ($categories as $category_id => $category)
            {
                $this->model->editCategoryStatusOrder($category_id, $category);
            }
        }

        $this->response->setOutput(json_encode($json));
    }

    private function category_getForm(array $category)
    {
        $this->load->model('tool/image');

        $this->data['languages']        = $this->getLanguageOptions();
        $this->data['stores']           = $this->model->getStoreOptions();
        $this->data['layout_options']   = $this->getLayoutOptions();
        $this->data['column_options']   = $this->model->getCategoryColumnOptions();

        if (isset($this->request->post['category']))
        {
            $category = $this->request->post['category'];
        }
	    else
	    {
		    $descriptions = $category['description'];
		    $first_description = current($category['description']);

		    foreach ($this->model->getLanguages() as $language)
		    {
			    if (empty($descriptions[$language['language_id']]))
			    {
				    $descriptions[$language['language_id']] = array();
			    }

			    foreach ($first_description as $key => $value)
			    {
				    if (empty($descriptions[$language['language_id']][$key]))
				    {
					    $descriptions[$language['language_id']][$key] = $value;
				    }
			    }
		    }

		    $category['description'] = $descriptions;
	    }

        // Prepare images
        if ($category['category']['image'])
        {
            $category['category']['image_thumb'] = $this->model_tool_image->resize($category['category']['image'], $this->model->getSetting('category','featured_image_width'), $this->model->getSetting('category', 'featured_image_height'));
        }
        else
        {
            $category['category']['image_thumb'] = $this->model_tool_image->resize('no_image.jpg', $this->model->getSetting('category', 'featured_image_width'), $this->model->getSetting('category', 'featured_image_height'));
        }

        $this->data['category']                 = $category;
        $this->data['article_order_options']    = $this->model->getArticleOrderOptions();
        $this->data['category_options']         = $this->model->getCategoryOptions($category['category']['category_id']);

        $this->data['cancel'] = $this->helperLink('module/kbm/category');

        // Breadcrumbs
        $this->data['breadcrumbs'][] = array(
            'text'      => _t('heading_category'),
            'href'      => $this->helperLink('module/kbm/category'),
            'separator' => ' :: '
        );

        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');

        $this->renderView('module/kbm_category_form.tpl', _t('heading_category'));
    }

    private function validateCategoryForm()
    {
        $category = $this->request->post['category'];

        foreach ($category['description'] as $language_id => $category_description)
        {
            if (empty($category_description['name']))
            {
                $this->errors['category_name'][$language_id] = _t('error_category_name');
            }

	        break;
        }

        return !$this->errors ? true : false;
    }
    /* end CATEGORY */

    /* COMMENT */
    public function comment()
    {
        // Prepare conditions
        $conditions = array();

        if (isset($this->request->get['filters']))
        {
            $conditions = $this->request->get['filters'];
        }

        // Prepare pagination
        $page       = isset($this->request->get['page']) ? $this->request->get['page'] : 1;
        $per_page   = 40;

        $pagination = new Pagination();
        $pagination->total = $this->model->countComments($conditions);
        $pagination->page = $page;
        $pagination->limit = $per_page;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->helperLink('module/kbm/article', array('filters' => $conditions, 'page' => '{page}'));

        $this->data['pagination'] = $pagination->render();

        // Prepare Comments
        $comments = $this->model->getComments($conditions, array('page' => $page, 'per_page' => $per_page));
        $comments = $this->model->prepareComments($comments);

        foreach ($comments as &$comment)
        {
            $comment['article_url'] = $this->helperLink('module/kbm/article_edit', array('article_id' => $comment['article_id']));

            $comment['actions'] = array(
                array(
                    'text' => _t('text_edit'),
                    'href' => $this->helperLink('module/kbm/comment_edit', array('comment_id' => $comment['comment_id']))
                )
            );

            if (!$comment['parent_comment_id'])
            {
                $comment['actions'][] = array(
                    'text' => _t('text_reply'),
                    'href' => $this->helperLink('module/kbm/comment_reply', array('comment_id' => $comment['comment_id']))
                );
            }
        }

        $this->data['comments'] = $comments;

        // Filter urls
        $this->data['filter_reset_url']     = $this->helperLink('module/kbm/comment');
        $this->data['filter_all_url']       = $this->helperLink('module/kbm/comment');
        $this->data['filter_approve_url']   = $this->helperLink('module/kbm/comment', array('filters' => array('status' => 1)));
        $this->data['filter_unapprove_url'] = $this->helperLink('module/kbm/comment', array('filters' => array('status' => 0)));

        $this->data['action']       = $this->helperLink('module/kbm/comment_delete');
        $this->data['toggle_url']   = $this->helperLink('module/kbm/comment_toggle');
        $this->data['cancel']       = $this->helperLink('extension/module');

        $this->data['fast_edit_url'] = $this->helperLink('module/kbm/comment_fastEdit');

        $this->data['sub_heading'] = _t('sub_heading_comment_list');

        $this->renderView('module/kbm_comment_list.tpl', _t('heading_comment'));
    }

    public function comment_reply()
    {
        // @toto: validate reply
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('reply_comment'))
            {
                return $this->forward('module/kbm/error', array('message' => 'error_blog_permission'));
            }

            // @todo: Check where the comment is reply or not

            $comment_id = $this->request->post['comment_id'];
            $reply = $this->request->post['reply'];

            $reply['author_id']     = $this->user->getId();
            $reply['author_type']   = ModelModuleKbm::AUTHOR_TYPE_ADMIN;

            $reply_id = $this->model->replyComment($comment_id, $reply);

            $this->data['success'] = _t('text_you_have_replied_comment');

            if ($this->request->post['op'] == 'apply')
            {
                // @todo: Test apply
                $this->redirect($this->helperLink('module/kbm/comment_edit', array('comment_id' => $reply_id)));
            }
            else
            {
                $this->redirect($this->helperLink('module/kbm/comment'));
            }
        }

        // Prepare comment
        $comment_id = $this->request->get['comment_id'];

        $comment = $this->model->getCommentByCommentId($comment_id);

        $comment['article_url'] = $this->helperLink('module/kbm/article_edit', array('article_id' => $comment['article_id']));

        $this->data['comment'] = $this->model->prepareComment($comment);

        // Prepare reply
        if (isset($this->request->post['reply']))
        {
            $reply = $this->request->post['reply'];
        }
        else
        {
            $reply = array(
                'content' => ''
            );

            $current_author = $this->model->getCurrentAuthor();
            $reply['author'] = $this->model->getCommentAuthor(array(
                'author_id'     => $current_author['author_id'],
                'author_type'   => ModelModuleKbm::AUTHOR_TYPE_ADMIN,
                'data'          => ''
            ));
        }

        $this->data['reply'] = $reply;

        $this->data['sub_heading'] = _t('sub_heading_reply_comment');

        $this->data['action'] = $this->helperLink('module/kbm/comment_reply', array('comment_id' => $comment_id));
        $this->data['cancel'] = $this->helperLink('extension/module');

        $this->data['sub_heading'] = _t('sub_heading_reply_comment');

        $this->renderView('module/kbm_comment_reply.tpl', _t('heading_comment'));
    }

    public function comment_edit()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('edit_comment'))
            {
                return $this->forward('module/kbm/error', array('message' => 'error_blog_permission'));
            }

            $comment_id = $this->request->post['comment']['comment_id'];

            $this->model->editComment($this->request->post['comment']);

            $this->data['success'] = _t('text_you_have_edited_comment');

            if ($this->request->post['op'] == 'apply')
            {
                // @todo: Test apply
                $this->redirect($this->helperLink('module/kbm/comment_edit', array('comment_id' => $comment_id)));
            }
            else
            {
                $this->redirect($this->helperLink('module/kbm/comment'));
            }
        }

        // Prepare comment
        $comment_id = $this->request->get['comment_id'];

        $comment = $this->model->getCommentByCommentId($comment_id);

        $comment['article_url'] = $this->helperLink('module/kbm/article_edit', array('article_id' => $comment['article_id']));

        $this->data['comment'] = $this->model->prepareComment($comment);

        $this->data['sub_heading'] = _t('sub_heading_edit_comment');

        $this->data['action'] = $this->helperLink('module/kbm/comment_edit');
        $this->data['cancel'] = $this->helperLink('module/kbm/comment');

        $this->renderView('module/kbm_comment_edit.tpl', _t('heading_comment'));
    }

    public function comment_fastEdit()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('edit_comment'))
            {
                $this->response->setOutput(json_encode(array(
                    'status' => 0,
                    'message' => _t('error_blog_permission')
                )));

                return false;
            }

            $comments = $this->request->post['comment'];

            foreach ($comments as $comment_id => $comment)
            {
                $this->model->editCommentStatus($comment_id, $comment);
            }
        }

        $this->response->setOutput(json_encode(array('status' => 1)));
    }

    public function comment_toggle()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('edit_comment'))
            {
                return $this->forward('module/kbm/error', array('message' => 'error_blog_permission'));
            }

            $comment_ids = isset($this->request->post['comment_ids']) ? $this->request->post['comment_ids'] : array();
            $status = $this->request->post['status'];

            foreach ($comment_ids as $comment_id)
            {
                $this->model->editCommentStatus($comment_id, array('status' => $status));
            }

            $this->session->data['success'] = _t('text_you_have_modified_comments');

            $this->redirect($this->helperLink('module/kbm/comment'));
        }
    }

    public function comment_delete()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            if (!$this->model->hasPermission('remove_comment'))
            {
                return $this->forward('module/kbm/error', array('message' => 'error_blog_permission'));
            }

            $comment_ids = $this->request->post['comment_ids'];

            foreach ($comment_ids as $comment_id)
            {
                $this->model->deleteComment($comment_id);
            }

            $this->session->data['success'] = _t('text_you_have_deleted_comments');

            $this->redirect($this->helperLink('module/kbm/comment'));
        }
    }
    /* end COMMENT */

    /* AUTHOR */
    public function author()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST')
        {
            $act = $this->request->post['act'];

            // Check permission
            if (!$this->model->hasPermission($act))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            if ($act == 'add_author')
            {
                if ($this->validateAddAuthorForm())
                {
                    $this->model->insertAuthor(array(
                        'user_id'   => $this->request->post['user_id'],
                        'name'      => $this->request->post['name'],
                        'role_id'   => $this->request->post['group']
                    ));

                    $this->session->data['success'] = _t('text_you_have_inserted_new_author');
                }
            }
            else if ($act == 'remove_author')
            {
                $author_ids = isset($this->request->post['author_ids']) ? $this->request->post['author_ids'] : array();

                foreach ($author_ids as $author_id)
                {
                    $this->model->deleteAuthor($author_id);
                }

                $this->session->data['success'] = _t('text_you_have_deleted_authors');
            }
            else if ($act == 'edit_author')
            {
                if ($this->validateEditAuthorForm())
                {
                    $author_id = $this->request->post['author_id'];
                    $new_name = $this->request->post['new_name'];

                    $this->model->renameAuthor($author_id, $new_name);

                    $this->session->data['success'] = _t('text_you_have_modified_author');
                }
            }
            else if ($act == 'edit_group_permission')
            {
                $permissions = $this->request->post['perms'];

                if (!isset($permissions[ModelModuleKbm::ROLE_AUTHOR]))
                {
                    $permissions[ModelModuleKbm::ROLE_AUTHOR] = array();
                }

                if (!isset($permissions[ModelModuleKbm::ROLE_EDITOR]))
                {
                    $permissions[ModelModuleKbm::ROLE_EDITOR] = array();
                }

                $permissions[ModelModuleKbm::ROLE_ADMIN][] = 'edit_group_permission';
                $permissions[ModelModuleKbm::ROLE_ADMIN][] = 'edit_setting';

                $this->model->setPermission($permissions);

                $this->session->data['success'] = _t('text_you_have_modified_permissions');
            }

            if (empty($this->errors))
            {
                $this->redirect($this->helperLink('module/kbm/author'));
            }
        }

        // Author List
        $this->data['authors']      = $this->model->prepareAuthors($this->model->getAuthors());

        // Add Author
        $this->data['user_options']             = $this->model->getUserOptions($this->model->getUserIdsAsAuthor());
        $this->data['author_group_options']     = $this->model->getAuthorGroupOptions();

        if (isset($this->request->post['user_id']) && $this->request->post['act'] == 'add_author')
        {
            $this->data['new_author'] = array(
                'user_id'   => $this->request->post['user_id'],
                'name'      => $this->request->post['name'],
                'group'     => $this->request->post['group']
            );
        }
        else
        {
            $this->data['new_author'] = array(
                'user_id'   => '',
                'name'      => '',
                'group'     => ModelModuleKbm::OPTION_AUTHOR_GROUP_AUTHOR
            );
        }

        // Rename Author
        if (isset($this->request->post['author_id']) && $this->request->post['act'] == 'edit_author')
        {
            $this->data['rename'] = array(
                'author_id' => $this->request->post['author_id'],
                'new_name'  => $this->request->post['new_name']
            );
        }
        else
        {
            $this->data['rename'] = array(
                'author_id'     => '',
                'new_name'      => ''
            );
        }

        // Prepare permissions
        $this->data['group_author_permissions']     = $this->model->getPermissionOptionsByRole(ModelModuleKbm::ROLE_AUTHOR);
        $this->data['group_editor_permissions']     = $this->model->getPermissionOptionsByRole(ModelModuleKbm::ROLE_EDITOR);
        $this->data['group_admin_permissions']      = $this->model->getPermissionOptionsByRole(ModelModuleKbm::ROLE_ADMIN);

        $this->data['author_permissions']   = $this->model->getPermissionsByRole(ModelModuleKbm::ROLE_AUTHOR);
        $this->data['editor_permissions']   = $this->model->getPermissionsByRole(ModelModuleKbm::ROLE_EDITOR);
        $this->data['admin_permissions']    = $this->model->getPermissionsByRole(ModelModuleKbm::ROLE_ADMIN);

        $this->data['action'] = $this->helperLink('module/kbm/author');
        $this->data['cancel'] = $this->helperLink('extension/module');

        $this->renderView('module/kbm_author.tpl', _t('heading_author'));
    }

    public function validateAddAuthorForm()
    {
        $name = $this->request->post['name'];

        if (empty($name))
        {
            $this->errors['add_author_name'] = _t('error_field_required');
        }

        if (empty($this->request->post['user_id']))
        {
            $this->errors['add_user']       = _t('error_field_required');
        }

        return $this->errors ? false : true;
    }

    public function validateEditAuthorForm()
    {
        $new_name = $this->request->post['new_name'];

        if (empty($new_name))
        {
            $this->errors['edit_rename'] = _t('error_field_required');
        }

        return $this->errors ? false : true;
    }
    /* end AUTHOR */

    /* SETTING */
    public function setting()
    {
        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateSettings())
        {
            if (!$this->model->hasPermission('edit_setting'))
            {
                return $this->forward('module/kbm/error', array('message' => _t('error_blog_permission')));
            }

            $this->model->editSetting($this->request->post['setting']);

	        // Hack the file /index.php to enable SEO for blog
	        $this->model->hackEnableSeoInIndexFile($this->request->post['setting']['category']['virtual_directory']);

            $this->session->data['success'] = _t('text_you_have_modified_settings');

            $this->redirect($this->helperLink('module/kbm/setting'));
        }

        if (isset($this->request->post['setting']))
        {
            $this->data['setting'] = $this->model->mapMerge($this->model->getDefaultSettings(), $this->request->post['setting']);
        }
        else
        {
            $this->data['setting'] = $this->model->getSettings();
        }

        $this->data['customer_group_options']   = $this->model->getCustomerGroupOptions();
        $this->data['admin_group_options']      = $this->model->getAdminGroupOptions();
        $this->data['article_order_options']    = $this->model->getArticleOrderOptions();
        $this->data['column_options']           = $this->model->getCategoryColumnOptions();
        $this->data['category_options']         = $this->model->getCategoryOptions();
        $this->data['capcha_options']           = $this->model->getCapchaOptions();
        $this->data['search_display_options']   = $this->model->getSearchDisplayOptions();
        $this->data['comment_order_options']    = $this->model->getCommentOrderOptions();

        $this->data['languages']                = $this->getLanguageOptions();

        $this->data['action'] = $this->helperLink('module/kbm/setting');
        $this->data['cancel'] = $this->helperLink('extension/module');

        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');

        $this->document->addStyle('view/kulercore/colorpicker/css/colorpicker.css');
        $this->document->addScript('view/kulercore/colorpicker/js/colorpicker.js');

        $this->renderView('module/kbm_setting.tpl', _t('heading_setting'));
    }

    public function validateSettings()
    {
        $settings = $this->request->post['setting'];

        // Categories
        $category = $settings['category'];

        if (!$this->is_number($category['articles_per_page']))
        {
            $this->errors['category_articles_per_page'] = _t('error_field_required');
        }

        if (!$this->is_number($category['article_characters']))
        {
            $this->errors['category_article_characters'] = _t('error_field_required');
        }

        if (!$this->is_number($category['featured_image_width']) || !$this->is_number($category['featured_image_height']))
        {
            $this->errors['category_featured_image_size'] = _t('error_field_required');
        }

        // Articles
        $article = $settings['article'];

        // Comment
        $comment = $settings['comment'];

        if (!$this->is_number($comment['min_character']) || !$this->is_number($comment['max_character']))
        {
            $this->errors['comment_entry_min_max_character'] = _t('error_field_required');
        }

        // Search
        $search = $settings['search'];

        if (!$this->is_number($search['search_result']))
        {
            $this->errors['search_search_result'] = _t('error_field_required');
        }

        if (!$this->is_number($search['result_per_row']))
        {
            $this->errors['search_result_per_row'] = _t('error_field_required');
         }

        // Admin
        $admin = $settings['admin'];

        if (!$this->is_number($admin['articles_per_page']))
        {
            $this->errors['admin_articles_per_page'] = _t('error_field_required');
        }

        return $this->errors ? false : true;
    }
    /* end SETTING */

    public function error($message = '')
    {
        $this->data['message'] = $message;

        $this->renderView('module/kbm_error.tpl', _t('heading_error'));
    }

    private function validatePermission()
    {
        if (!$this->user->hasPermission('modify', 'module/kbm'))
        {
            $this->errors['warning'] = _t('error_permission');
        }

        return !$this->errors ? true : false;
    }

    public static function __($text, $placeholder = '')
    {
        if (isset(self::$__[$text]))
        {
            return self::$__[$text];
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

            return $placeholder ? $placeholder : $text;
        }
    }

    private function getSelectedStore()
    {
        $selected_store_id = 0;
        if (isset($this->request->post['store_id']))
        {
            $selected_store_id = $this->request->post['store_id'];
        }
        else if (isset($this->request->get['store_id']))
        {
            $selected_store_id = $this->request->get['store_id'];
        }

        return $selected_store_id;
    }

    private function getLanguages()
    {
        $__ = $this->language->load('module/kbm');

        return $__;
    }

    private function prepareAlerts()
    {
        // Errors
        $this->data['error_warning'] = '';

        foreach ($this->errors as $error_key => $error_message)
        {
            $this->data['error_' . $error_key] = $error_message;
        }

        if ($this->errors && empty($this->data['error_warning']))
        {
            $this->data['error_warning'] = _t('error_warning');
        }

        // Success
        $this->data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';

        unset($this->session->data['success']);
    }

    private function getLanguageOptions()
    {
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $config_language = $this->config->get('config_language');

        $results = array();
        $default_language = $languages[$config_language];
        unset($languages[$config_language]);

        $results[$config_language] = $default_language;
        foreach ($languages as $code => $language)
        {
            $results[$code] = $language;
        }

        return $results;
    }

    private function getLayoutOptions($include_empty = true)
    {
        $this->load->model('design/layout');
        $result = $this->model_design_layout->getLayouts();

        return $result;
    }

    private function renderView($template, $title = '')
    {
        $this->prepareAlerts();

        $this->document->setTitle(($title ? $title . ' | ' : '') . _t('heading_module_title'));

        $this->template = $template;
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    /* INSTALL */
    public function install()
    {
	    // Create tables
		$this->model->createTables();

	    // Setting
	    $this->model->editSetting($this->model->getDefaultSettings());

        // Role
        $this->model->setupDefaultPermission();

        // Create admin
        $this->model->setupAdmin();

	    // Add blog layout
	    $this->model->addBlogLayout();
    }

    public function uninstall()
    {
	    // Drop tables
	    $this->model->dropTables();

	    // Uninstall related modules
	    $this->model->uninstallRelatedModules();

	    // Delete layout
	    $this->model->deleteBlogLayout();
    }
    /* end INSTALL */

    /* HELPER */
    private function is_number($input)
    {
        return preg_match('/^[123456789]\d*$/', $input);
    }

    private function helperLink($route, array $params = array())
    {
        $params['token'] = $this->data['token'];

        return urldecode(str_replace('&amp;', '&', $this->url->link($route, http_build_query($params), 'SSL')));
    }
    /* end HELPER */
}