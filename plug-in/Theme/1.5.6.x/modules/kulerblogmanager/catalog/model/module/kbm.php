<?php

define('BLOG_TABLE_PREFIX', DB_PREFIX . 'kbm_');

/**
 * Class ModelModuleKbm
 * @property Db $db
 */
class ModelModuleKbm extends Model
{
    const ROLE_AUTHOR   = 5;
    const ROLE_EDITOR   = 10;
    const ROLE_ADMIN    = 15;

    const OPTION_ARTICLE_ORDER_SORT_ORDER_ASC   = 1;
    const OPTION_ARTICLE_ORDER_SORT_ORDER_DESC  = 2;
    const OPTION_ARTICLE_ORDER_DATE_CREATED_ASC = 3;
    const OPTION_ARTICLE_ORDER_DATE_CREATED_DESC= 4;

    const OPTION_CAPCHA_DISABLED                = 0;
    const OPTION_CAPCHA_GUEST_ONLY              = 1;
    const OPTION_CAPCHA_ALL_VISITOR_AND_CLIENT  = 2;
    const OPTION_CAPCHA_ALL_EXCEPT_NON_DEFAULT  = 3;

    const OPTION_SEARCH_DISPLAY_PLAIN_LIST      = 'plain_list';
    const OPTION_SEARCH_DISPLAY_COMPACT_LIST    = 'compact_list';
    const OPTION_SEARCH_DISPLAY_GRID            = 'grid';

    const OPTION_COMMENT_ORDER_DATE_CREATED_ASC     = 'date_created_asc';
    const OPTION_COMMENT_ORDER_DATE_CREATED_DESC    = 'date_created_desc';

    const OPTION_AUTHOR_GROUP_AUTHOR              = 5;
    const OPTION_AUTHOR_GROUP_EDITOR              = 10;
    const OPTION_AUTHOR_GROUP_ADMIN               = 15;

    const AUTHOR_TYPE_ADMIN     = 'admin';
    const AUTHOR_TYPE_CUSTOMER  = 'customer';
    const AUTHOR_TYPE_GUEST     = 'guest';

	const CACHE_PRODUCT_TAGS    = 'kbm_product_tags';

	public static $time;

    private $settings = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

	    self::$time = time();
        $this->settings = $this->prepareSettings($this->mapMerge($this->getDefaultSettings(), $this->getSettings()));
    }

	/* CRUD */
	public function insert($table, array $data)
	{
		$fields = array();

		foreach ($data as $column_key => $column_value)
		{
			$fields[] = "`$column_key` = $column_value";
		}

		if (!$fields)
		{
			throw new Exception('Kuler Blog Manager: No data to insert');
		}

		$table = BLOG_TABLE_PREFIX . $table;
		$field_clause = implode(', ', $fields);

		$this->db->query("
                INSERT INTO `$table`
                SET $field_clause
            ");

		return $this->db->getLastId();
	}
	/* end CRUD */

    /* SETTINGS */
    public function getSettings()
    {
        return $this->config->get('kbm_settings');
    }

    public function prepareSettings($settings)
    {
        $first_home_description = current($settings['home']['description']);
        $description = array();
        $config_language_id = $this->config->get('config_language_id');

        if (!isset($settings['home']['description'][$config_language_id]))
        {
            $description = array();
        }
        else
        {
            $description = $settings['home']['description'][$config_language_id];
        }

        foreach ($first_home_description as $key => $value)
        {
            if (empty($description[$key]))
            {
                $description[$key] = $value;
            }
        }

        $settings['home'] = array_merge($settings['home'], $description);

        return $settings;
    }

    public function getSetting($group, $name)
    {
        return isset($this->settings[$group]) && isset($this->settings[$group][$name]) ? $this->settings[$group][$name] : null;
    }

    public function getDefaultSettings()
    {
        return array(
            // Home
            'home' => array(
                'description'               => array(),
	            'show_title'                => 1,
                'article_order'             => self::OPTION_ARTICLE_ORDER_DATE_CREATED_DESC,
                'exclude_category'          => array(),
                'column'                    => 1,
            ),
            // Category
            'category' => array(
                'articles_per_page'         => 8,
                'article_characters'        => 600,
                'featured_image_width'      => 150,
                'featured_image_height'     => 150,

                'virtual_directory'         => 1,
                'virtual_directory_name'    => 'blog',
                'virtual_exclude_category'  => array(),
            ),
            // Article
            'article' => array(
	            'url_suffix'                => '.html',
	            'author_name'               => 1,
                'date'                      => 1,
                'last_update'               => 1,
                'category'                  => 1,
                'comment'                   => 1,
                'related_article'           => 1,
                'related_product'           => 1,
                'related_product_per_row'   => 5,
                'related_product_image_width' => 100,
                'related_product_image_height'=> 100,

                'social_media'              => 1,
                'add_analytic_code'         => '',
                'add_this_custom_code'      => '',

                'featured_image_width'      => 100,
                'featured_image_height'     => 100,
            ),
            // Comment
            'comment' => array(
                'status'                    => 1,
                'comment_per_page'          => 6,
                'min_character'             => 25,
                'max_character'             => 1500,
                'comment_order'             => self::OPTION_COMMENT_ORDER_DATE_CREATED_DESC,
                'email_notification'        => '',
                'captcha'                   => self::OPTION_CAPCHA_ALL_EXCEPT_NON_DEFAULT,
                'avatar_size'               => 60,
                'guest_comment'             => 1,
                'auto_approve'              => 0,
                'disable_category'          => array(),
                'auto_approve_group'        => array(),
                'admin_badge_group'         => array(),
                'admin_badge_color'         => array(),
                'customer_badge_group'      => array(),
                'customer_badge_color'      => array()
            ),
            // Search & Feeds
            'search' => array(
                'status'                    => 1,
                'search_display'            => self::OPTION_SEARCH_DISPLAY_COMPACT_LIST,
                'author_name'               => 1,
                'date'                      => 1,
                'search_result'             => 6,
                'result_per_row'            => 6
            ),
            'feed' => array(
                'status'                    => 1,
                'limit'                     => 500
            ),
            'sitemap' => array(
                'status'                    => 1,
                'limit'                     => 1000
            ),
            'admin' => array(
                'articles_per_page'         => 40
            )
        );
    }

    /* end SETTINGS */

    /* CATEGORY */
    public function getCategories(array $conditions, array $fetch_options = array())
    {
        $config_language_id = $this->config->get('config_language_id');
        $config_store_id = $this->config->get('config_store_id');

        $where = $this->prepareCategoryConditions($conditions);

        $query = $this->db->query("
            SELECT c.category_id, c.image, c.status, c.article_order, c.article_image_width, c.article_image_height, c.character_limit, c.column,
                cd.name, cd.description, cd.meta_keyword, cd.meta_description
            FROM ". BLOG_TABLE_PREFIX ."category c
            LEFT JOIN ". BLOG_TABLE_PREFIX ."category_description cd
                ON (c.category_id = cd.category_id AND cd.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."category_to_store c2s
                ON (c.category_id = c2s.category_id)
            WHERE c2s.store_id = $config_store_id AND $where
            ORDER BY c.sort_order ASC
        ");

        return $query->rows;
    }

    public function getCategoryByCategoryId($category_id)
    {
        $category_id = intval($category_id);
        $config_language_id = $this->config->get('config_language_id');
        $config_store_id = $this->config->get('config_store_id');

        $query = $this->db->query("
            SELECT c.category_id, c.image, c.status, c.article_order, c.article_image_width, c.article_image_height, c.character_limit, c.column,
                cd.name, cd.description, cd.meta_keyword, cd.meta_description
            FROM ". BLOG_TABLE_PREFIX ."category c
            LEFT JOIN ". BLOG_TABLE_PREFIX ."category_description cd
                ON (c.category_id = cd.category_id AND cd.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."category_to_store c2s
                ON (c.category_id = c2s.category_id)
            WHERE c.category_id = $category_id
                AND c2s.store_id = $config_store_id
        ");

        return $query->row;
    }

    public function getDisabledCategoryIds()
    {
        $query = $this->db->query('SELECT category_id FROM '. BLOG_TABLE_PREFIX .'category WHERE status = 0');

        $disabled = array();

        foreach ($query->rows as $category)
        {
            $disabled[] = $category['category_id'];
        }

        return $disabled;
    }

    public function prepareCategoryConditions(array $conditions)
    {
        $sql_conditions = array(
            'c.status = 1'
        );

        if (!empty($conditions['category_id']))
        {
            if (is_array($conditions['category_id']))
            {
                $conditions['category_id'] = $this->escapeIds($conditions['category_id']);

                $sql_conditions[] = 'c.category_id IN (' . implode(', ', $conditions['category_id']) . ')';
            }
            else
            {
                $sql_conditions[] = 'c.category_id = ' . intval($conditions['category_id']);
            }
        }

	    if (!empty($conditions['exclude_category']))
	    {
		    if (is_array($conditions['exclude_category']))
		    {
			    $conditions['exclude_category'] = $this->escapeIds($conditions['exclude_category']);

			    $sql_conditions[] = 'c.category_id NOT IN (' . implode(', ', $conditions['exclude_category']) . ')';
		    }
	    }

        if (isset($conditions['parent_id']))
        {
            $sql_conditions[] = 'c.parent_id = ' . intval($conditions['parent_id']);
        }

        if (isset($conditions['status']))
        {
            $sql_conditions[] = 'c.status = ' . intval($conditions['status']);
        }

        return $sql_conditions ? implode(' AND ', $sql_conditions) : '1 = 1';
    }

    public function prepareCategory(array $category)
    {
        $category_id = intval($category['category_id']);

        // @todo: test when insert new language
        // Description
        if (empty($category['name']))
        {
            $query = $this->db->query("SELECT * FROM ". BLOG_TABLE_PREFIX ."category_description WHERE category_id =  $category_id LIMIT 0, 1");

            if ($query->row)
            {
                $category = array_merge($category, $query->row);
            }
        }

	    $category['description'] = html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8');

	    // Link
        $category['link'] = $this->link('module/kbm/category', array('kbm_path' => $category_id));



        return $category;
    }

	public function getCategoryLayoutId($category_id)
	{
		$category_id = intval($category_id);
		$config_store_id = $this->config->get('config_store_id');

		$query = $this->db->query("SELECT * FROM ". BLOG_TABLE_PREFIX ."category_to_layout WHERE category_id = $category_id AND store_id = $config_store_id LIMIT 0, 1");

		return $query->row ? $query->row['layout_id'] : 0;
	}
    /* end CATEGORY */

    /* ARTICLE */
    public function getArticles(array $conditions, array $fetch_options = array())
    {
        $where = $this->prepareArticleConditions($conditions, $fetch_options);
        $join_options = $this->prepareArticleJoinOptions($conditions, $fetch_options);
        $order_clause = $this->prepareArticleSortOrderOptions($fetch_options);
        $limit_clause = $this->prepareArticleLimitOptions($fetch_options);

        $config_language_id = intval($this->config->get('config_language_id'));

        $query = $this->db->query("
            SELECT a.article_id, a.featured_image, a.date_added, a.date_modified, a.date_published, ad.name, ad.description, ad.meta_keyword, ad.meta_description, au.name AS author_name, au.author_id
            {$join_options['selected_fields']}
            FROM ". BLOG_TABLE_PREFIX ."article a
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (a.article_id = ad.article_id AND ad.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."author au
                ON (a.author_id = au.author_id)
            {$join_options['join_tables']}
            WHERE $where
            $order_clause
            $limit_clause
        ");

        return $query->rows;
    }

    public function getArticleByArticleId($article_id)
    {
        $article_id = intval($article_id);
        $config_language_id = $this->config->get('config_language_id');
        $config_store_id = $this->config->get('config_store_id');
	    $current_time = self::$time;

        $query = $this->db->query("
            SELECT a.article_id, a.status, a.featured_image, a.date_added, a.date_modified, ad.name, ad.description, ad.meta_keyword, ad.meta_description, au.name AS author_name, au.author_id
            FROM ". BLOG_TABLE_PREFIX ."article a
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (a.article_id = ad.article_id AND ad.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."author au
                ON (a.author_id = au.author_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."article_to_store a2s
                ON (a.article_id = a2s.article_id)
            WHERE a.article_id = $article_id
                AND a2s.store_id = $config_store_id
                AND a.date_published <= $current_time
        ");

        return $query->row;
    }

    public function countArticles(array $conditions, array $fetch_options = array())
    {
        $where = $this->prepareArticleConditions($conditions, $fetch_options);
        $join_options = $this->prepareArticleJoinOptions($conditions, $fetch_options);

        $config_language_id = intval($this->config->get('config_language_id'));

        $query = $this->db->query("
            SELECT COUNT(a.article_id) AS article_total
            {$join_options['selected_fields']}
            FROM ". BLOG_TABLE_PREFIX ."article a
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (a.article_id = ad.article_id AND ad.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."author au
                ON (a.author_id = au.author_id)
            {$join_options['join_tables']}
            WHERE $where
        ");

        return $query->row ? $query->row['article_total'] : 0;
    }

    public function getRelatedArticles($article_id)
    {
        $article_id = intval($article_id);

        $config_language_id = $this->config->get('config_language_id');

        $query = $this->db->query("
            SELECT r.related_id AS article_id, ad.name, ad.description
            FROM ". BLOG_TABLE_PREFIX ."article_related r
            INNER JOIN ". BLOG_TABLE_PREFIX ."article a
                ON (r.related_id = a.article_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (r.related_id = ad.article_id AND ad.language_id = $config_language_id)
            WHERE r.article_id = $article_id
                AND a.status = 1
        ");

        foreach ($query->rows as &$article)
        {
            $article = $this->prepareArticleLink($article);
        }

        return array_map(array($this, 'prepareArticleDescription'), $query->rows);
    }

    public function prepareArticles(array $articles)
    {
        return array_map(array($this, 'prepareArticle'), $articles);
    }

    public function prepareArticle(array $article)
    {
        $article_id = intval($article['article_id']);

        // Link
        $article = $this->prepareArticleLink($article);

        // Description
        $article = $this->prepareArticleDescription($article);

        // Featured image
        $article['featured_image_thumb'] = $this->prepareImage($article['featured_image'], $this->getSetting('article', 'featured_image_width'), $this->getSetting('article', 'featured_image_height'));

        // Date
        $article['date_added_formatted'] = $this->formatDate($article['date_added']);

        // Category
        $article['categories'] = array();

        $config_language_id = $this->config->get('config_language_id');
        $config_store_id = intval($this->config->get('config_store_id'));

        $query = $this->db->query("
            SELECT c.category_id, cd.name, cd.description
            FROM ". BLOG_TABLE_PREFIX ."article_to_category a2c
            INNER JOIN ". BLOG_TABLE_PREFIX ."category c
                ON (a2c.category_id = c.category_id)
            LEFT JOIN ". BLOG_TABLE_PREFIX ."category_description cd
                ON (c.category_id = cd.category_id AND cd.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."category_to_store c2s
                ON (c.category_id = c2s.category_id)
            WHERE a2c.article_id = $article_id
                AND c.status = 1
                AND c2s.store_id = $config_store_id
        ");

        foreach ($query->rows as $article_category)
        {
            $article['categories'][] = $this->prepareCategory($article_category);
        }

	    // Filter display
	    $article['display_author']      = $this->getSetting('article', 'author_name');
		$article['display_date']        = $this->getSetting('article', 'date');
	    $article['display_last_update'] = $this->getSetting('article', 'last_update');
	    $article['display_category']    = $this->getSetting('article', 'category');
	    $article['display_comment']     = $this->getSetting('article', 'comment');

        return $article;
    }

    public function prepareArticleLink(array $article)
    {
        $article['link'] = $this->link('module/kbm/article', array('kbm_article_id' => $article['article_id']));

        return $article;
    }

    public function prepareArticleDescription(array $article)
    {
        $article_id = intval($article['article_id']);

        // @todo: test when insert new language
        if (empty($article['name']))
        {
            $query = $this->db->query("
                SELECT ad.name, ad.description, ad.meta_keyword, ad.meta_description
                FROM ". BLOG_TABLE_PREFIX ."article_description ad
                WHERE ad.article_id = $article_id
            ");

            if ($query->row)
            {
                $article = array_merge($article, $query->row);
            }
        }

        $article['description'] = html_entity_decode($article['description'], ENT_QUOTES, 'UTF-8');

        return $article;
    }

    public function prepareArticleJoinOptions(array $conditions, array $fetch_options = array())
    {
        $selected_fields = '';
        $join_tables = '
            INNER JOIN '. BLOG_TABLE_PREFIX .'article_to_store a2s
                ON (a.article_id = a2s.article_id)
        ';

        if (!empty($conditions['category_id']) || !empty($conditions['exclude_category']))
        {
	        $join_tables .= '
                 INNER JOIN '. BLOG_TABLE_PREFIX .'article_to_category a2c
                    ON (a.article_id = a2c.article_id)
            ';

	        if (!empty($fetch_options['subcategory']))
	        {
		        $join_tables .= '
					INNER JOIN '. BLOG_TABLE_PREFIX .'category_path c2p
						ON (c2p.category_id = a2c.category_id)
				';
	        }
        }

        return array(
            'selected_fields' => $selected_fields,
            'join_tables' => $join_tables
        );
    }

    public function prepareArticleConditions(array $conditions, array $fetch_options = array())
    {
        $config_store_id = $this->config->get('config_store_id');

        $sql_conditions = array(
            'a.status = 1',
            'a2s.store_id = ' . intval($config_store_id),
	        'a.date_published <= ' . self::$time
        );

        if (!empty($conditions['category_id']))
        {
	        if (empty($fetch_options['subcategory']))
	        {
		        if (is_array($conditions['category_id']))
		        {
			        $conditions['category_id'] = $this->escapeIds($conditions['category_id']);

			        $sql_conditions[] = 'a2c.category_id IN (' . implode(', ', $conditions['category_id']) . ')';
		        }
		        else
		        {
			        $sql_conditions[] = 'a2c.category_id = ' . intval($conditions['category_id']);
		        }
	        }
			else
			{
				$sql_conditions[] = 'c2p.path_id = ' . intval($conditions['category_id']);
			}
        }

        if (!empty($conditions['exclude_category']))
        {
	        if (is_array($conditions['exclude_category']))
	        {
		        $conditions['exclude_category'] = $this->escapeIds($conditions['exclude_category']);

		        $sql_conditions[] = 'a2c.category_id NOT IN (' . implode(', ', $conditions['exclude_category']) . ')';
	        }
        }

	    if (!empty($conditions['tag']))
	    {
		    // todo: match correctly
		    $conditions['tag'] = '%' . $conditions['tag'] . '%';

		    $sql_conditions[] = "ad.tags LIKE " . $this->escapeString($conditions['tag']);
	    }

        return $sql_conditions ? implode(' AND ', $sql_conditions) : '1 = 1';
    }

    public function prepareArticleSortOrderOptions(array $fetch_options)
    {
        $sort_by = 'a.sort_order ASC';

        if (isset($fetch_options['sort']))
        {
            switch ($fetch_options['sort'])
            {
                case 'viewed':
                    $sort_by = 'a.viewed';
                    break;
	            case 'date_added':
		            $sort_by = 'a.date_added';
		            break;
	            case 'sort_order':
                default:
	                $sort_by = 'a.sort_order';
	            break;
            }

            if (isset($fetch_options['sort_direction']))
            {
                $sort_by .= ' ' . $fetch_options['sort_direction'];
            }
        }

        return $sort_by ? 'ORDER BY ' . $sort_by : '';
    }

    public function prepareArticleSortOrder($order_option)
    {
        $sort = array();

        switch ($order_option)
        {
            case self::OPTION_ARTICLE_ORDER_DATE_CREATED_ASC:
                $sort['sort'] = 'date_published';
                $sort['sort_direction'] = 'ASC';
                break;
            case self::OPTION_ARTICLE_ORDER_DATE_CREATED_DESC:
                $sort['sort'] = 'date_published';
                $sort['sort_direction'] = 'DESC';
                break;
            case self::OPTION_ARTICLE_ORDER_SORT_ORDER_ASC:
                $sort['sort'] = 'sort_order';
                $sort['sort_direction'] = 'ASC';
                break;
            case self::OPTION_ARTICLE_ORDER_SORT_ORDER_DESC:
                $sort['sort'] = 'sort_order';
                $sort['sort_direction'] = 'DESC';
                break;
        }

        return $sort;
    }

    public function prepareArticleLimitOptions(array $fetch_options)
    {
        $clause = '';

	    if (isset($fetch_options['limit']))
	    {
		    $clause = 'LIMIT 0, ' . intval($fetch_options['limit']);
	    }

        if (isset($fetch_options['page']))
        {
            $clause = 'LIMIT ' . (($fetch_options['page'] - 1) * $fetch_options['per_page']) . ', ' . $fetch_options['per_page'];
        }

        return $clause;
    }

    public function updateArticleViewed($article_id)
    {
        $article_id = intval($article_id);

        $this->db->query("UPDATE ". BLOG_TABLE_PREFIX ."article SET viewed = (viewed + 1) WHERE article_id = $article_id");

        return true;
    }

    public function getRelatedProducts($article_id)
    {
        $article_id = intval($article_id);

        $products = array();
        $config_store_id = $this->config->get('config_store_id');

        $query = $this->db->query("
            SELECT * FROM " . BLOG_TABLE_PREFIX . "article_related_product ar
            LEFT JOIN " . DB_PREFIX . "product p
                ON (ar.product_id = p.product_id)
            LEFT JOIN " . DB_PREFIX . "product_to_store p2s
                ON (p.product_id = p2s.product_id)
            WHERE ar.article_id = '$article_id'
                AND p.status = '1'
                AND p.date_available <= NOW()
                AND p2s.store_id = $config_store_id
        ");

        $this->load->model('catalog/product');

        foreach ($query->rows as $result)
        {
            $products[] = $this->model_catalog_product->getProduct($result['product_id']);
        }

        return $products;
    }

    public function prepareProducts(array $products, array $options)
    {
        foreach ($products as &$product)
        {
            $product = $this->prepareProduct($product, $options);
        }

        return $products;
    }

    public function prepareProduct(array $product, array $options)
    {
        $thumb = $this->prepareImage($product['image'], $options['image_width'], $options['image_height']);

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))
        {
            $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
        }
        else
        {
            $price = false;
        }

        if ((float)$product['special'])
        {
            $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
        }
        else
        {
            $special = false;
        }

        if ($this->config->get('config_review_status'))
        {
            $rating = $product['rating'];
        }
        else
        {
            $rating = false;
        }

        $product_categories = $this->model_catalog_product->getCategories($product['product_id']);
        $first_category_id = !empty($product_categories) ? $product_categories[0]['category_id'] : 0;

        $result = array(
            'product_id' => $product['product_id'],
            'image'      => $product['image'],
            'thumb'   	 => $thumb,
            'name'    	 => $product['name'],
            'description' => utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $options['description_text']) . '..',
            'price'   	 => $price,
            'special' 	 => $special,
            'rating'     => $rating,
            'reviews'    => sprintf($this->language->get('text_reviews'), (int)$product['reviews']),
            'href'    	 => $this->url->link('product/product', 'path=' . $this->getRecursivePath($first_category_id) .'&product_id=' . $product['product_id']),
        );

        return $result;
    }

    public function getRecursivePath($category_id, $cats = array())
    {
        static $categories;

        if (empty($categories))
        {
            if (!empty($cats))
            {
                $raw_categories = $cats;
            }
            else
            {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

                $raw_categories = $query->rows;
            }

            $categories = array();
            foreach ($raw_categories as $raw_category)
            {
                $categories[$raw_category['category_id']] = $raw_category['parent_id'];
            }
        }

        if (!isset($categories[$category_id]))
        {
            return '';
        }

        $path = $category_id;
        $parent_id = $categories[$category_id];

        while (true)
        {
            if (!$parent_id)
            {
                break;
            }

            $path = $parent_id . '_' . $path;
            $parent_id = $categories[$parent_id];
        }

        return $path;
    }

	public function getArticleLayoutId($article_id)
	{
		$article_id = intval($article_id);
		$config_store_id = $this->config->get('config_store_id');

		$query = $this->db->query("SELECT * FROM ". BLOG_TABLE_PREFIX ."article_to_layout WHERE article_id = $article_id AND store_id = $config_store_id LIMIT 0, 1");

		return $query->row ? $query->row['layout_id'] : 0;
	}
    /* end ARTICLE */

    /* COMMENT */
    public function getComments(array $conditions, array $fetch_options = array())
    {
	    $join_options = $this->prepareCommentJoinOptions($conditions);
        $where = $this->prepareCommentConditions($conditions);
	    $sort_clause = $this->prepareCommentSortOrderOptions($fetch_options);
	    $limit_clause = $this->prepareCommentLimitOptions($fetch_options);

        $query = $this->db->query("
            SELECT c.*
            {$join_options['selected_fields']}
            FROM ". BLOG_TABLE_PREFIX ."comment c
            {$join_options['join_tables']}
            WHERE $where
            $sort_clause
            $limit_clause
        ");

        return $query->rows;
    }

    public function countComments(array $conditions, array $fetch_options = array())
    {
        $where = $this->prepareCommentConditions($conditions);

        $query = $this->db->query("
            SELECT COUNT(c.comment_id) AS comment_total
            FROM ". BLOG_TABLE_PREFIX ."comment c
            WHERE $where
        ");

        return $query->row ? $query->row['comment_total'] : 0;
    }

	public function getCommentByCommentId($comment_id)
	{
		$comment_id = intval($comment_id);

		$query = $this->db->query("SELECT * FROM ". BLOG_TABLE_PREFIX ."comment WHERE comment_id = $comment_id");

		return $query->row;
	}

	public function insertComment(array $comment)
	{
		$data = array(
			'parent_comment_id' => intval($comment['parent_comment_id']),
			'article_id'        => intval($comment['article_id']),
			'author_id'         => intval($comment['author_id']),
			'author_type'       => $this->escapeString($comment['author_type']),
			'data'              => $this->escapeString(serialize($comment['data'])),
			'content'           => $this->escapeString($comment['content']),
			'date_added'        => self::$time,
			'date_modified'     => self::$time,
			'status'            => $comment['status']
		);

		return $this->insert('comment', $data);
	}

	public function canComment($customer_id)
	{
		return !$customer_id && !$this->getSetting('comment', 'guest_comment') ? false : true;
	}

	public function prepareComments(array $comments)
	{
		return array_map(array($this, 'prepareComment'), $comments);
	}

	public function prepareComment(array $comment)
	{
		// Author
		$author_id = intval($comment['author_id']);

		$author = array(
			'name'          => '',
			'email'         => '',
			'website'       => '',
			'badge_color'   => '',
			'group'         => ''
		);

		if ($comment['author_type'] == self::AUTHOR_TYPE_ADMIN)
		{
			$query = $this->db->query("
                SELECT a.author_id, a.name, u.email, u.user_group_id, ug.name AS `group`
                FROM ". BLOG_TABLE_PREFIX ."author a
                LEFT JOIN ". DB_PREFIX ."user u
                    ON (a.user_id = u.user_id)
                LEFT JOIN ". DB_PREFIX ."user_group ug
                    ON (u.user_group_id = ug.user_group_id)
                WHERE a.author_id = $author_id
                LIMIT 0, 1
            ");

			if ($query->row)
			{
				$author = array_merge($author, $query->row);

				$user_group_id = $query->row['user_group_id'];
				$admin_badge_colors = $this->getSetting('comment', 'admin_badge_color');

				if (isset($admin_badge_colors[$user_group_id]))
				{
					$author['badge_color'] = $admin_badge_colors[$user_group_id];
				}
			}
		}
		else if ($comment['author_type'] == self::AUTHOR_TYPE_CUSTOMER)
		{
			$config_language_id = intval($this->config->get('config_language_id'));

			$query = $this->db->query("
                SELECT c.customer_id, c.firstname, c.lastname, c.email, c.customer_group_id, cgd.name AS `group`
                FROM ". DB_PREFIX ."customer c
                LEFT JOIN ". DB_PREFIX ."customer_group_description cgd
                    ON (c.customer_group_id = cgd.customer_group_id AND cgd.language_id = $config_language_id)
                WHERE c.customer_id = $author_id
            ");

			if ($query->row)
			{
				$author = array_merge($author, array(
					'customer_id'   => $query->row['customer_id'],
					'name'          => $query->row['firstname'] . ' ' . $query->row['lastname'],
					'email'         => $query->row['email'],
					'group'         => $query->row['group']
				));

				$customer_group_id = $query->row['customer_group_id'];
				$customer_badge_colors = $this->getSetting('comment', 'customer_badge_color');

				if (isset($customer_badge_colors[$customer_group_id]))
				{
					$author['badge_color'] = $customer_badge_colors[$customer_group_id];
				}
			}
		}

		if ($comment['data'])
		{
			$author = array_merge($author, unserialize($comment['data']));
		}

		// Avatar
		$author['avatar_url'] = $this->prepareCommentAvatarUrl($author['email'], $this->getSetting('comment', 'avatar_size'));

		$comment['author'] = $author;

		// Date created
		$comment['date_added_formatted'] = $this->formatDateTime($comment['date_added']);

		// Content
		$comment['content'] = html_entity_decode($comment['content'], ENT_QUOTES, 'UTF-8');

		return $comment;
	}

	public function prepareCommentAvatarUrl($email, $avatar_size)
	{
		$email = trim($email);

		if ($email)
		{
			$hash_email = md5(strtolower($email));

			$url = "http://www.gravatar.com/avatar/$hash_email?s=$avatar_size";
		}
		else
		{
			$url = $this->prepareImage('no_image.jpg', $avatar_size, $avatar_size);
		}

		return $url;
	}

	public function prepareCommentJoinOptions(array $conditions)
	{
		$selected_fields = '';
		$join_tables = '';

		return array(
			'selected_fields' => $selected_fields,
			'join_tables' => $join_tables
		);
	}

    public function prepareCommentConditions(array $conditions)
    {
        $sql_conditions = array(
            'c.status = 1'
        );

        if (isset($conditions['parent_comment_id']))
        {
	        if (is_array($conditions['parent_comment_id']))
	        {
		        list($operator, $cut_off) = $conditions['parent_comment_id'];

		        $sql_conditions[] = "c.parent_comment_id $operator $cut_off";
	        }
	        else
	        {
		        $sql_conditions[] = 'c.parent_comment_id = ' . intval($conditions['parent_comment_id']);
	        }
        }

	    if (!empty($conditions['exclude_category']))
	    {
		    if (is_array($conditions['exclude_category']))
		    {
			    $conditions['exclude_category'] = $this->escapeIds($conditions['exclude_category']);

			    $sql_conditions[] = 'c.article_id NOT IN (
			        SELECT a2c.article_id
			        FROM '. BLOG_TABLE_PREFIX .'article_to_category a2c
			        WHERE a2c.category_id IN ('. implode(', ', $conditions['exclude_category']) .')
			    )';
		    }
	    }

        if (!empty($conditions['article_id']))
        {
            $sql_conditions[] = 'c.article_id = ' . intval($conditions['article_id']);
        }

        return $sql_conditions ? implode(' AND ', $sql_conditions) : '';
    }

	public function prepareCommentLimitOptions(array $fetch_options)
	{
		$limit = '';

		if (isset($fetch_options['page']))
		{
			$limit .= ($fetch_options['page'] - 1) * $fetch_options['per_page'] . ', ' . $fetch_options['per_page'];
		}

		return $limit ? 'LIMIT ' . $limit : '';
	}

	public function prepareCommentSortOrderOptions(array $fetch_options)
	{
		$order_by = '';

		if (isset($fetch_options['sort']))
		{
			switch ($fetch_options['sort'])
			{
				case 'date_added':
					$order_by = 'c.date_added';
					break;
			}

			if ($fetch_options['sort_direction'])
			{
				$order_by .= ' ' . $fetch_options['sort_direction'];
			}
			else
			{
				$order_by .= ' ASC';
			}
		}

		return $order_by ? 'ORDER BY ' . $order_by : '';
	}

	public function getCommentStatusByArticleId($article_id)
	{
		$article_id = intval($article_id);
		$query = $this->db->query("
			SELECT * FROM ". BLOG_TABLE_PREFIX ."article_to_category a2c WHERE a2c.article_id = $article_id
		");

		$category_ids = array();
		foreach ($query->rows as $article2category)
		{
			$category_ids[] = $article2category['category_id'];
		}

		$disabled_category_ids = $this->getSetting('comment', 'disable_category');

		$status = $this->getSetting('comment', 'status');

		if ($status)
		{
			$category_comment_status = true;

			foreach ($category_ids as $category_id)
			{
				if (in_array($category_id, $disabled_category_ids))
				{
					$category_comment_status = false;
					break;
				}
			}

			$status = $category_comment_status;
		}

		return $status;
	}

	public function prepareCommentSortOrder($order_option)
	{
		$sort = array();

		switch ($order_option)
		{
			case self::OPTION_COMMENT_ORDER_DATE_CREATED_ASC:
				$sort['sort'] = 'date_added';
				$sort['sort_direction'] = 'ASC';
				break;
			case self::OPTION_COMMENT_ORDER_DATE_CREATED_DESC:
				$sort['sort'] = 'date_added';
				$sort['sort_direction'] = 'DESC';
				break;
		}

		return $sort;
	}

	public function enableCommentCaptcha($customer_id, $customer_group_id)
	{
		$captcha_setting = $this->getSetting('comment', 'captcha');

		return ($captcha_setting == ModelModuleKbm::OPTION_CAPCHA_DISABLED
			|| ($captcha_setting == ModelModuleKbm::OPTION_CAPCHA_GUEST_ONLY && $customer_id)
			|| ($captcha_setting == ModelModuleKbm::OPTION_CAPCHA_ALL_EXCEPT_NON_DEFAULT && $customer_id && $customer_group_id != $this->config->get('config_customer_group_id'))
		) ? false : true;
	}
    /* end COMMENT */

    /* HELPER */
    public function link($route, array $params = array())
    {
        $query = http_build_query($params);

        $url = $this->url->link($route, $query);

	    $url = str_replace('%7B', '{', $url);
	    $url = str_replace('%7D', '}', $url);

	    return $url;
    }

    public function formatDateTime($time)
    {
        return date('M d, Y H:i', $time);
    }

    public function formatDate($time)
    {
        return date('M d, Y', $time);
    }

    public function convertDateTimeToTimestamp($date_formatted)
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i', $date_formatted);

        return $dt->getTimestamp();
    }

    public function convertDateToTimestamp($date_formatted)
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date_formatted);

        return $dt->getTimestamp();
    }

    public function escapeString($str)
    {
        return "'" . $this->db->escape($str) . "'";
    }

    public function escapeIds($ids)
    {
        if (!is_array($ids))
        {
            $ids = array($ids);
        }

        foreach ($ids as &$id)
        {
            $id = intval($id);
        }

        return $ids;
    }

	public function validateEmail($email)
	{
		return preg_match('/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/', $email);
	}

    public static function mapMerge(array $first, array $second)
    {
        $args = func_get_args();
        unset($args[0]);

        foreach ($args AS $arg)
        {
            if (!is_array($arg) || !$arg)
            {
                continue;
            }
            foreach ($arg AS $key => $value)
            {
                if (array_key_exists($key, $first) && is_array($value) && is_array($first[$key]))
                {
                    $first[$key] = self::mapMerge($first[$key], $value);
                }
                else
                {
                    $first[$key] = $value;
                }
            }
        }

        return $first;
    }

    public function cutText($text, $limit)
    {
        return utf8_substr(strip_tags($text), 0, $limit) . '..';
    }

    public function prepareImage($path, $width, $height)
    {
        // @todo: Check absolute path

        if (!$this->model_tool_image)
        {
            $this->load->model('tool/image');
        }

        $image = '';

        if ($path)
        {
            $image = $this->model_tool_image->resize($path, $width, $height);
        }
        else
        {
            $image = $this->model_tool_image->resize('no_image.jpg', $width, $height);
        }

        return $image;
    }
    /* end HELPER */
}