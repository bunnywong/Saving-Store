<?php
/**
 * Class ModelModuleKbm
 * @property Db $db
 * @property Language $language
 * @property User $user
 */
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

    private $author         = array();
    private $role           = '-';
    private $permissions    = array();

    private $settings       = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        // Set current time
        self::$time = time();

        // Setting
        $config_settings = $this->config->get('kbm_settings');

	    // Does not initialize if the module not installed
	    if (!$config_settings)
	    {
		    return ;
	    }

        $this->settings = self::mapMerge($this->getDefaultSettings(), $config_settings ? $config_settings : array());
        $this->settings = $this->prepareSettings($this->settings);

        // Cache permissions
        $user_id = intval($this->user->getId());

        $query = $this->db->query("SELECT * FROM `". BLOG_TABLE_PREFIX ."author` WHERE user_id = $user_id LIMIT 0,1");

        if ($query->row)
        {
            $this->author = $query->row;

            $role_id = $query->row['role_id'];

            $permissions = $this->getPermissionsByRole($role_id);

            if ($permissions)
            {
                $this->permissions = $permissions;
            }
        }
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

    public function edit($table, array $data, $where)
    {
        $fields = array();

        foreach ($data as $column_key => $column_value)
        {
            $fields[] = "`$column_key` = $column_value";
        }

        if (!$fields)
        {
            throw new Exception('Kuler Blog Manager: No data to update');
        }

        $table = BLOG_TABLE_PREFIX . $table;
        $field_clause = implode(', ', $fields);

        $this->db->query("
                UPDATE `$table`
                SET $field_clause
                WHERE $where
            ");

        return true;
    }

    public function delete($table, $where)
    {
        $table = BLOG_TABLE_PREFIX . $table;

        $this->db->query("DELETE FROM `$table` WHERE $where");
    }
    /* end CRUD */

	/* Install & Uninstall */
	public function createTables()
	{
		$db = $this->db;

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."article` (
			  `article_id` int(11) NOT NULL AUTO_INCREMENT,
			  `author_id` int(11) NOT NULL,
			  `viewed` int(5) NOT NULL DEFAULT '0',
			  `featured_image` varchar(255) DEFAULT NULL,
			  `date_added` int(10) unsigned NOT NULL,
			  `date_modified` int(10) unsigned NOT NULL,
			  `date_published` int(10) unsigned NOT NULL,
			  `sort_order` int(11) NOT NULL DEFAULT '0',
			  `status` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`article_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."article_description` (
			  `article_id` int(11) NOT NULL,
			  `language_id` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `meta_keyword` varchar(255) NOT NULL,
			  `meta_description` varchar(255) NOT NULL,
			  `tags` varchar(255) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."article_related` (
			  `article_id` int(11) NOT NULL,
			  `related_id` int(11) NOT NULL,
			  PRIMARY KEY (`article_id`,`related_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."article_to_category` (
			  `article_id` int(11) NOT NULL,
			  `category_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."article_to_layout` (
			  `article_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL,
			  `layout_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."article_to_store` (
			  `article_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."author` (
			  `author_id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `role_id` tinyint(3) NOT NULL,
			  PRIMARY KEY (`author_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."category` (
			  `category_id` int(11) NOT NULL AUTO_INCREMENT,
			  `parent_id` int(11) NOT NULL DEFAULT '0',
			  `image` varchar(255) NOT NULL,
			  `column` int(3) NOT NULL,
			  `article_order` int(3) NOT NULL DEFAULT '0',
			  `article_image_width` int(3) DEFAULT NULL,
			  `article_image_height` int(3) DEFAULT NULL,
			  `character_limit` int(5) DEFAULT NULL,
			  `date_added` int(10) unsigned NOT NULL,
			  `date_modified` int(10) unsigned NOT NULL,
			  `sort_order` int(3) DEFAULT NULL,
			  `status` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`category_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."category_description` (
			  `category_id` int(11) NOT NULL,
			  `language_id` int(11) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `meta_keyword` varchar(255) NOT NULL,
			  `meta_description` varchar(255) NOT NULL,
			  `description` text NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."category_path` (
			  `category_id` int(11) NOT NULL,
			  `path_id` int(11) NOT NULL,
			  `level` int(11) NOT NULL,
			  PRIMARY KEY (`category_id`,`path_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."category_to_layout` (
			  `category_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL,
			  `layout_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."category_to_store` (
			  `category_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."comment` (
			  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
			  `article_id` int(11) NOT NULL,
			  `parent_comment_id` int(11) NOT NULL DEFAULT '0',
			  `author_id` int(11) NOT NULL,
			  `author_type` enum('admin','customer','guest') NOT NULL,
			  `content` text NOT NULL,
			  `data` text,
			  `date_added` int(10) unsigned NOT NULL,
			  `date_modified` int(10) unsigned NOT NULL,
			  `status` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`comment_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
		");

		$db->query("
			CREATE TABLE IF NOT EXISTS `". BLOG_TABLE_PREFIX ."permission` (
			  `role_id` int(3) NOT NULL,
			  `permissions` text NOT NULL,
			  PRIMARY KEY (`role_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");
	}

	public function dropTables()
	{
		$db = $this->db;

		$db->query("
		DROP TABLE
			`". BLOG_TABLE_PREFIX ."article`,
			`". BLOG_TABLE_PREFIX ."article_description`,
			`". BLOG_TABLE_PREFIX ."article_related`,
			`". BLOG_TABLE_PREFIX ."article_to_category`,
			`". BLOG_TABLE_PREFIX ."article_to_layout`,
			`". BLOG_TABLE_PREFIX ."article_to_store`,
			`". BLOG_TABLE_PREFIX ."author`,
			`". BLOG_TABLE_PREFIX ."category`,
			`". BLOG_TABLE_PREFIX ."category_description`,
			`". BLOG_TABLE_PREFIX ."category_path`,
			`". BLOG_TABLE_PREFIX ."category_to_layout`,
			`". BLOG_TABLE_PREFIX ."category_to_store`,
			`". BLOG_TABLE_PREFIX ."comment`,
			`". BLOG_TABLE_PREFIX ."permission`;");
	}

	public function addBlogLayout()
	{
		$this->load->model('design/layout');

		$layout = array(
			'name'          => 'Kuler Blog Manager',
			'layout_route'  => array()
		);

		foreach ($this->getStoreOptions() as $store_id => $store_name)
		{
			$layout['layout_route'][] = array(
				'store_id'  => $store_id,
				'route'     => 'module/kbm'
			);
		}

		$this->model_design_layout->addLayout($layout);
	}

	public function deleteBlogLayout()
	{
		$this->load->model('design/layout');

		$layouts = $this->model_design_layout->getLayouts();

		foreach ($layouts as $layout)
		{
			if ($layout['name'] == 'Kuler Blog Manager')
			{
				$this->model_design_layout->deleteLayout($layout['layout_id']);
			}
		}
	}

	public function uninstallRelatedModules()
	{
		$this->load->model('setting/extension');

		$installed_modules = $this->model_setting_extension->getInstalled('module');

		foreach ($installed_modules as $code)
		{
			if (strstr($code, 'kbm_'))
			{
				$this->model_setting_extension->uninstall('module', $code);
			}
		}
	}
	/* end Install & Uninstall */

    /* SETTING */
    public function getSetting($group, $key)
    {
        return isset($this->settings[$group]) && isset($this->settings[$group][$key]) ? $this->settings[$group][$key] : null;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getDefaultSettings()
    {
	    $home_descriptions = array();

	    foreach ($this->getLanguages() as $language)
	    {
		    $home_descriptions[$language['language_id']] = array(
			    'blog_name'                 => 'Kuler Blog Manager',
			    'blog_keyword'              => '',
			    'blog_meta_description'     => '',
			    'blog_home_description'     => ''
		    );
	    }

        return array(
            // Home
            'home' => array(
                'description'               => $home_descriptions,
	            'show_title'                => 1,
                'article_order'             => self::OPTION_ARTICLE_ORDER_DATE_CREATED_DESC,
                'exclude_category'          => array(),
                'column'                    => 1
            ),
            // Category
            'category' => array(
                'articles_per_page'         => 8,
                'article_characters'        => 600,
                'featured_image_width'      => 150,
                'featured_image_height'     => 150,

                'virtual_directory'         => 0,
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

    public function editSetting(array $settings)
    {
        $this->load->model('setting/setting');

        $this->model_setting_setting->editSetting('kbm', array('kbm_settings' => $settings));
    }

    public function prepareSettings(array $settings)
    {
        // Home
        // Home - Description
        $languages = $this->getLanguages();

        $home_descriptions = $settings['home']['description'];
        $first_home_description = current($home_descriptions);

        if (!$first_home_description)
        {
            $first_home_description = array(
                'blog_name'                     => '',
                'blog_keyword'                  => '',
                'blog_meta_description'         => '',
                'blog_home_description'         => ''
            );
        }

        foreach ($languages as $language)
        {
            if (!isset($home_descriptions[$language['language_id']]))
            {
                $home_descriptions[$language['language_id']] = array();
            }

            foreach ($first_home_description as $key => $value)
            {
                if (empty($home_descriptions[$language['language_id']][$key]))
                {
                    $home_descriptions[$language['language_id']][$key] = $value;
                }
            }
        }

        $settings['home']['description'] = $home_descriptions;

        // Comment
        // Comment - Admin Badge Group
        foreach ($settings['comment']['admin_badge_group'] as $group_id)
        {
            if (!isset($settings['comment']['admin_badge_color'][$group_id]))
            {
                $settings['comment']['admin_badge_color'][$group_id] = '';
            }
        }

        // Comment - Customer Badge Group
        foreach ($settings['comment']['customer_badge_group'] as $group_id)
        {
            if (!isset($settings['comment']['customer_badge_color'][$group_id]))
            {
                $settings['comment']['customer_badge_color'][$group_id] = '';
            }
        }

        return $settings;
    }

    public function getSearchDisplayOptions()
    {
        return array(
            self::OPTION_SEARCH_DISPLAY_PLAIN_LIST      => _t('text_plain_list'),
            self::OPTION_SEARCH_DISPLAY_COMPACT_LIST    =>_t('text_compact_list'),
            self::OPTION_SEARCH_DISPLAY_GRID            => _t('text_grid')
        );
    }

    public function getCommentOrderOptions()
    {
        return array(
            self::OPTION_COMMENT_ORDER_DATE_CREATED_ASC     => _t('text_date_created_asc'),
            self::OPTION_COMMENT_ORDER_DATE_CREATED_DESC    => _t('text_date_created_desc')
        );
    }

    public function getUserOptions(array $exclude_user = array())
    {
        $this->load->model('user/user');

        $users = $this->model_user_user->getUsers();

        $results = array();
        foreach ($users as $user)
        {
            if (!in_array($user['user_id'], $exclude_user))
            {
                $results[$user['user_id']] = $user['username'];
            }
        }

        return $results;
    }

	public function hackEnableSeoInIndexFile($hack = true)
	{
		$index_path = DIR_APPLICATION . '/../index.php';

		if (file_exists($index_path))
		{
			$content = file_get_contents($index_path);

			// TODO: Replace better
			if ($hack)
			{
				$content = str_replace('$controller->addPreAction(new Action(\'common/seo_url\'));', '$controller->addPreAction(new Action(\'common/kuler_seo_url\'));', $content);
			}
			else
			{
				$content = str_replace('$controller->addPreAction(new Action(\'common/kuler_seo_url\'));', '$controller->addPreAction(new Action(\'common/seo_url\'));', $content);
			}

			@file_put_contents($index_path, $content);
		}
	}
    /* end SETTING */

    /* ROLE */
    public function getRoleByUserId($user_id)
    {
        $user_id = intval($user_id);

        $query = $this->db->query("
            SELECT * FROM `". BLOG_TABLE_PREFIX ."author` WHERE `user_id` = $user_id
        ");

        return $query && isset($query['role_id']) ? $query['role_id'] : null;
    }
    /* end ROLE */

    /* PERMISSION */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions) ? true : false;
    }

    public function getPermissionsByRole($role)
    {
        $role = intval($role);

        $query = $this->db->query("SELECT * FROM `". BLOG_TABLE_PREFIX ."permission` WHERE role_id = $role LIMIT 0,1");

        if ($query->row)
        {
            return unserialize($query->row['permissions']);
        }

        return array();
    }

    public function setupAdmin()
    {
        $username = $this->db->escape($this->user->getUserName());
        $user_id = intval($this->user->getId());
        $role_admin = self::ROLE_ADMIN;

        $this->db->query("
            INSERT INTO `". BLOG_TABLE_PREFIX ."author`
            SET name = '$username',
            user_id = $user_id,
            role_id = $role_admin
        ");
    }

    public function setupDefaultPermission()
    {
        $role_permissions = array();
        $roles = array(self::ROLE_AUTHOR, self::ROLE_EDITOR, self::ROLE_ADMIN);

        foreach ($roles as $role)
        {
            $role_permissions[$role] = $this->getDefaultPermission($role);
        }

        $this->setPermission($role_permissions);
    }

    public function setPermission(array $role_permissions)
    {
        foreach ($role_permissions as $role => $permissions)
        {
            $this->db->query("
                INSERT INTO `". BLOG_TABLE_PREFIX ."permission`
                SET `role_id` = ". intval($role) .",
                `permissions` = '". serialize($permissions) ."'
                ON DUPLICATE KEY UPDATE `permissions` = '". serialize($permissions) ."'
            ");
        }
    }

    public function getDefaultPermission($role)
    {
        $permissions = array();

        if ($role == self::ROLE_AUTHOR)
        {
            $permissions = array('create_article', 'edit_own_article');
        }
        else if ($role > self::ROLE_AUTHOR)
        {
            $permission_options = $this->getPermissionOptionsByRole($role);

            // Set permission in group article, category, comment for editor, admin
            foreach ($permission_options as $permission_group_name => $permission_group)
            {
                if ($permission_group_name == 'article' || $permission_group_name == 'category' || $permission_group_name == 'comment')
                {
                    foreach ($permission_group as $permission => $permission_name)
                    {
                        $permissions[] = $permission;
                    }
                }
            }

            if ($role == self::ROLE_EDITOR)
            {
                $permissions[] = 'add_author';
            }
            else if ($role == self::ROLE_ADMIN)
            {
                $permissions[] = 'add_author';
                $permissions[] = 'edit_author';
                $permissions[] = 'remove_author';
                $permissions[] = 'edit_group_permission';
                $permissions[] = 'edit_setting';

            }
        }

        return $permissions;
    }

    public function getPermissionOptionsByRole($role)
    {
        $permissions = array(
            'article' => array(
                'create_article'        => _t('permission_create_article'),
                'copy_article'          => _t('permission_copy_article'),
                'edit_own_article'      => _t('permission_edit_own_article'),
                'edit_other_article'    => _t('permission_edit_other_article'),
                'change_author'         => _t('permission_change_author'),
                'remove_article'        => _t('permission_remove_article')
            ),
            'category' => array(
                'create_category'       => _t('permission_create_category'),
                'copy_category'         => _t('permission_copy_category'),
                'edit_category'         => _t('permission_edit_category'),
                'remove_category'       => _t('permission_remove_category')
            ),
            'comment' => array(
                'edit_comment'          => _t('permission_edit_comment'),
                'reply_comment'         => _t('permission_reply_comment'),
                'remove_comment'        => _t('permission_remove_comment')
            )
        );

        if ($role > self::ROLE_AUTHOR)
        {
            $permissions['author'] = array(
                'add_author'            => _t('permission_add_author'),
                'edit_author'           => _t('permission_edit_author', 'Edit Author'),
                'remove_author'         => _t('permission_remove_author', 'Remove Author'),
                'edit_group_permission' => _t('permission_edit_group_permission')
            );
        }

        if ($role > self::ROLE_EDITOR)
        {
            $permissions['setting'] = array(
                'edit_setting'          => _t('permission_edit_blog_setting', 'Edit Blog Setting')
            );
        }

        return $permissions;
    }
    /* end PERMISSION */

    /* CATEGORY */
    public function getCategories(array $conditions = array())
    {
        $config_language_id = (int)$this->config->get('config_language_id');

        $where_clause = '';

        if (!empty($conditions['name']))
        {
            $where_clause .= " AND cd2.name LIKE '" . $this->db->escape($conditions['name']) . "%'";
        }

        $query = $this->db->query("
            SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id,
                (
                    SELECT sub_c.sort_order
                    FROM `". BLOG_TABLE_PREFIX ."category_path` sub_cp
                    INNER JOIN `". BLOG_TABLE_PREFIX ."category` sub_c
                        ON  sub_cp.category_id = sub_c.category_id
                    WHERE sub_cp.category_id = cp.category_id
                    ORDER BY level DESC
                    LIMIT 1
                ) sort_order,
                (
                    SELECT sub_c.status
                    FROM `". BLOG_TABLE_PREFIX ."category_path` sub_cp
                    INNER JOIN `". BLOG_TABLE_PREFIX ."category` sub_c
                        ON  sub_cp.category_id = sub_c.category_id
                    WHERE sub_cp.category_id = cp.category_id
                    ORDER BY level DESC
                    LIMIT 1
                ) status
            FROM ". BLOG_TABLE_PREFIX ."category_path cp
            LEFT JOIN " . BLOG_TABLE_PREFIX . "category c
                ON (cp.path_id = c.category_id)
            LEFT JOIN " . BLOG_TABLE_PREFIX . "category_description cd1
                ON (c.category_id = cd1.category_id AND cd1.language_id = $config_language_id)
            LEFT JOIN " . BLOG_TABLE_PREFIX . "category_description cd2
                ON (cp.category_id = cd2.category_id AND cd2.language_id = $config_language_id)
            WHERE 1 = 1
                $where_clause
            GROUP BY cp.category_id
            ORDER BY name
        ");

        return $query->rows;
    }

    public function getCategoryById($category_id)
    {
        $category_id = intval($category_id);

        $query = $this->db->query("
            SELECT *
            FROM `". BLOG_TABLE_PREFIX ."category`
            WHERE category_id = $category_id
            LIMIT 0, 1
        ");

        return $query->row;
    }

    public function getCategoryDescriptionsByCategoryId($category_id)
    {
        $category_id = intval($category_id);

        $query = $this->db->query("
            SELECT *
            FROM `". BLOG_TABLE_PREFIX ."category_description`
            WHERE category_id = $category_id
        ");

        $results = array();

        foreach ($query->rows as $description)
        {
            $results[$description['language_id']] = $description;
        };

        return $results;
    }

    public function getCategoryStoresByCategoryId($category_id)
    {
        $category_id = intval($category_id);

        $query = $this->db->query("
            SELECT *
            FROM `". BLOG_TABLE_PREFIX ."category_to_store`
            WHERE category_id = $category_id
        ");

        $results = array();

        foreach ($query->rows as $category_store)
        {
            $results[] = $category_store['store_id'];
        }

        return $results;
    }

    public function getCategoryLayoutsByCategoryId($category_id)
    {
        $category_id = intval($category_id);

        $query = $this->db->query("
            SELECT *
            FROM `". BLOG_TABLE_PREFIX ."category_to_layout`
            WHERE category_id = $category_id
        ");

        $results = array();

        foreach ($query->rows as $category_layout)
        {
            $results[$category_layout['store_id']] = $category_layout['layout_id'];
        }

        return $results;
    }

    public function countCategories()
    {
        $query = $this->db->query("
            SELECT COUNT(*) AS category_total
            FROM ". BLOG_TABLE_PREFIX ."category
        ");

        return isset($query->row['category_total']) ? $query->row['category_total'] : 0;
    }

    public function insertCategory(array $category)
    {
        // Category
        $data = array(
            'parent_id'             => intval($category['category']['parent_id']),
            'image'                 => $this->escapeString($category['category']['image']),
            'column'                => intval($category['category']['column']),
            'article_order'         => intval($category['category']['article_order']),
            'article_image_width'   => intval($category['category']['article_image_width']),
            'article_image_height'  => intval($category['category']['article_image_height']),
            'character_limit'       => intval($category['category']['character_limit']),
            'date_added'            => self::$time,
            'date_modified'         => self::$time,
            'sort_order'            => intval($category['category']['sort_order']),
            'status'                => intval($category['category']['status']),
        );

        $category_id = $this->insert('category', $data);

        // Category Description
        foreach ($category['description'] as $language_id => $category_description)
        {
            $description_data = array(
                'category_id'       => $category_id,
                'language_id'       => intval($language_id),
                'name'              => $this->escapeString($category_description['name']),
                'meta_keyword'      => $this->escapeString($category_description['meta_keyword']),
                'meta_description'  => $this->escapeString($category_description['meta_description']),
                'description'       => $this->escapeString($category_description['description']),
            );

            $this->insert('category_description', $description_data);
        }

        // Category to Path
        $level = 0;

        $parent_id = intval($category['category']['parent_id']);

        $query = $this->db->query("
            SELECT *
            FROM `". BLOG_TABLE_PREFIX ."category_path`
            WHERE category_id = $parent_id
            ORDER BY level ASC
        ");

        foreach ($query->rows as $path)
        {
            $this->insert('category_path', array(
                'category_id' => $category_id,
                'path_id' => $path['path_id'],
                'level' => $level
            ));

            $level++;
        }

        $this->insert('category_path', array(
            'category_id' => $category_id,
            'path_id' => $category_id,
            'level' => $level
        ));

        // Category to Store
        if (isset($category['store']))
        {
            foreach ($category['store'] as $category_store)
            {
                $this->insert('category_to_store', array(
                    'category_id' => $category_id,
                    'store_id' => intval($category_store)
                ));
            }
        }

        // Category to Layout
        if (isset($category['layout']))
        {
            foreach ($category['layout'] as $store_id => $layout_id)
            {
                $this->insert('category_to_layout', array(
                    'category_id' => $category_id,
                    'store_id' => intval($store_id),
                    'layout_id' => intval($layout_id)
                ));
            }
        }

        // Keyword
        if ($category['keyword'])
        {
            $keyword = $this->escapeString($category['keyword']);

            $this->db->query("
                INSERT INTO " . DB_PREFIX . "url_alias
                SET query = 'kbm_category_id=$category_id',
                    keyword = $keyword
            ");
        }

        return $category_id;
    }

    public function editCategory(array $category)
    {
        $category_id = $category['category']['category_id'];
        $category_where = "category_id = $category_id";

        $parent_id = intval($category['category']['parent_id']);

        // Category
        $data = array(
            'parent_id'             => $parent_id,
            'image'                 => $this->escapeString($category['category']['image']),
            'column'                => intval($category['category']['column']),
            'article_order'         => intval($category['category']['article_order']),
            'article_image_width'   => intval($category['category']['article_image_width']),
            'article_image_height'  => intval($category['category']['article_image_height']),
            'character_limit'       => intval($category['category']['character_limit']),
            'date_modified'         => self::$time,
            'sort_order'            => intval($category['category']['sort_order']),
            'status'                => intval($category['category']['status']),
        );

        $this->edit('category', $data, $category_where);

        // Description
        $this->delete('category_description', $category_where);

        foreach ($category['description'] as $language_id => $category_description)
        {
            $description_data = array(
                'category_id'       => $category_id,
                'language_id'       => intval($language_id),
                'name'              => $this->escapeString($category_description['name']),
                'meta_keyword'      => $this->escapeString($category_description['meta_keyword']),
                'meta_description'  => $this->escapeString($category_description['meta_description']),
                'description'       => $this->escapeString($category_description['description']),
            );

            $this->insert('category_description', $description_data);
        }

        // Path

        // Get parent path
        $query = $this->db->query("SELECT * FROM `". BLOG_TABLE_PREFIX ."category_path` WHERE category_id = $parent_id ORDER BY `level` ASC");

        $parent_path_ids = array();
        foreach ($query->rows as $parent_path)
        {
            $parent_path_ids[] = $parent_path['path_id'];
        }

        $path_query = $this->db->query("SELECT * FROM `" . BLOG_TABLE_PREFIX . "category_path` WHERE path_id = $category_id ORDER BY `level` ASC");

        if ($path_query->rows)
        {
            foreach ($path_query->rows as $category_path)
            {
                $this->db->query("DELETE FROM `" . BLOG_TABLE_PREFIX . "category_path` WHERE category_id = {$category_path['category_id']} AND level < {$category_path['level']}");

                // Get rest of path
                $path_ids = $parent_path_ids;

                $query = $this->db->query("SELECT * FROM `". BLOG_TABLE_PREFIX ."category_path` WHERE category_id = {$category_path['category_id']} ORDER BY level ASC");

                foreach ($query->rows as $path)
                {
                    $path_ids[] = $path['path_id'];
                }

                // Re-path
                $level = 0;
                foreach ($path_ids as $path_id)
                {
                    $path_id = intval($path_id);

                    $this->db->query("REPLACE INTO `". BLOG_TABLE_PREFIX ."category_path` SET category_id = {$category_path['category_id']}, path_id = $path_id, level = $level");

                    $level++;
                }
            }
        }

        // Store
        $this->delete('category_to_store', $category_where);

        if ($category['store'])
        {
            foreach ($category['store'] as $store_id)
            {
                $this->insert('category_to_store', array(
                    'category_id' => $category_id,
                    'store_id' => intval($store_id)
                ));
            }
        }

        // Layout
        $this->delete('category_to_layout', $category_where);

        if ($category['layout'])
        {
            foreach ($category['layout'] as $store_id => $layout_id)
            {
                $this->insert('category_to_layout', array(
                    'category_id' => $category_id,
                    'store_id' => intval($store_id),
                    'layout_id' => intval($layout_id)
                ));
            }
        }

        // Keyword
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'kbm_category_id=$category_id'");

        if ($category['keyword'])
        {
            $keyword = $this->escapeString($category['keyword']);

            $this->db->query("
                INSERT INTO " . DB_PREFIX . "url_alias
                SET query = 'kbm_category_id=$category_id',
                    keyword = $keyword
            ");
        }

        return true;
    }

    public function editCategoryStatusOrder($category_id, array $category)
    {
        $categoryId = intval($category_id);

        $data = array();

        if (isset($category['status']))
        {
            $data['status'] = $category['status'];
        }

        if (isset($category['sort_order']))
        {
            $data['sort_order'] = $category['sort_order'];
        }

        $this->edit('category', $data, "category_id=$category_id");
    }

    public function deleteCategory($category_id)
    {
        $category_id = intval($category_id);
        $category_where = "category_id=$category_id";

        $this->delete('category_path', $category_where);

        $query = $this->db->query("
            SELECT * FROM `". BLOG_TABLE_PREFIX ."category_path` WHERE path_id = $category_id
        ");

        foreach ($query->rows as $sub_path)
        {
            $this->deleteCategory($sub_path['category_id']);
        }

        // Remove related data
        $this->delete('category', $category_where);
        $this->delete('category_description', $category_where);
        $this->delete('category_to_store', $category_where);
        $this->delete('category_to_layout', $category_where);
        $this->delete('article_to_category', $category_where);

        $category_query = "'kbm_category_id=$category_id'";
        $this->db->query("DELETE FROM `". DB_PREFIX ."url_alias` WHERE query = $category_query");

        return true;
    }

    public function copyCategory($category_id, $parent_id = null)
    {
        $category = array(
            'category'      => $this->getCategoryById($category_id),
            'description'   => $this->getCategoryDescriptionsByCategoryId($category_id),
            'store'         => $this->getCategoryStoresByCategoryId($category_id),
            'layout'        => $this->getCategoryLayoutsByCategoryId($category_id),
            'keyword'       => $this->getUrlAlias('category', $category_id)
        );

        if ($parent_id !== null)
        {
            $category['category']['parent_id'] = $parent_id;
        }

        $new_category_id = intval($this->insertCategory($category));

        // Copy sub category
        $query = $this->db->query("
            SELECT * FROM `". BLOG_TABLE_PREFIX ."category_path` WHERE path_id = $category_id
        ");

        foreach ($query->rows as $sub_path)
        {
            if ($sub_path['category_id'] != $category_id)
            {
                $this->copyCategory($sub_path['category_id'], $new_category_id);
            }
        }

        return $new_category_id;
    }

    public function getCategoryPaths($category_ids)
    {
        if (!is_array($category_ids))
        {
            $category_ids = array($category_ids);
        }

        foreach ($category_ids as &$category_id)
        {
            $category_id = intval($category_id);
        }

        $category_ids_str = implode(', ', $category_ids);
        $config_language_id = intval($this->config->get('config_language_id'));

        $query = $this->db->query("
            SELECT cp.category_id, GROUP_CONCAT( cd.name ORDER BY cp.level SEPARATOR  ' > ' ) AS name
            FROM ". BLOG_TABLE_PREFIX ."category_path cp
            LEFT JOIN ". BLOG_TABLE_PREFIX ."category_description cd
                ON (cp.path_id = cd.category_id AND cd.language_id = $config_language_id)
            WHERE cp.category_id IN ($category_ids_str)
            GROUP BY cp.category_id
            ORDER BY name
        ");

        $results = array();
        foreach ($query->rows as $category_path)
        {
            $results[$category_path['category_id']] = $category_path['name'];
        }

        return $results;
    }

    public function getCategoryOptions($category_id = 0)
    {
        $categories = $this->getCategories();

        $results = array();
        foreach ($categories as $category)
        {
            $results[$category['category_id']] = $category['name'];
        }

        // Remove current category and its children
        if ($category_id)
        {
            $category_id = intval($category_id);

            $query = $this->db->query("SELECT * FROM `". BLOG_TABLE_PREFIX ."category_path` WHERE path_id = $category_id");

            foreach ($query->rows as $path)
            {
                foreach($results as $option_category_id => $option_category_name)
                {
                    if ($option_category_id == $path['category_id'])
                    {
                        unset($results[$option_category_id]);
                    }
                }
            }
        }

        return $results;
    }
    /* end CATEGORY */

    /* AUTHOR */
    public function getAuthors()
    {
        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."author a
            INNER JOIN ". DB_PREFIX ."user u
                ON a.user_id = u.user_id
        ");

        return $query->rows;
    }

    public function getCurrentAuthor()
    {
        return $this->author;
    }

    public function countAuthors()
    {
        $query = $this->db->query("
            SELECT COUNT(*) AS author_total
            FROM ". BLOG_TABLE_PREFIX ."author
        ");

        return isset($query->row['author_total']) ? $query->row['author_total'] : 0;
    }

    public function insertAuthor(array $author)
    {
        $author = array(
            'user_id'   => intval($author['user_id']),
            'name'      => $this->escapeString($author['name']),
            'role_id'   => intval($author['role_id'])
        );

        return $this->insert('author', $author);
    }

    public function deleteAuthor($author_id)
    {
        $author_id = intval($author_id);

        $this->delete('author', "author_id=$author_id");

        // todo: remove author-related data, comment, article

        return true;
    }

    public function renameAuthor($author_id, $new_name)
    {
        $author = array(
            'name' => $this->escapeString($new_name)
        );

        $author_id = intval($author_id);

        $this->edit('author', $author, "author_id=$author_id");
    }

    public function prepareAuthors(array $authors)
    {
        return array_map(array($this, 'prepareAuthor'), $authors);
    }

    public function prepareAuthor(array $author)
    {
        $author_options = $this->getAuthorGroupOptions();

        $author['group_name'] = $author_options[$author['role_id']];

        return $author;
    }

    public function getAuthorOptions()
    {
        $authors = $this->getAuthors();

        $results = array();
        foreach ($authors as $author)
        {
            $results[$author['author_id']] = $author['name'];
        }

        return $results;
    }

    public function getAuthorGroupOptions()
    {
        return array(
            self::OPTION_AUTHOR_GROUP_AUTHOR    => _t('text_author'),
            self::OPTION_AUTHOR_GROUP_EDITOR    => _t('text_editor'),
            self::OPTION_AUTHOR_GROUP_ADMIN     => _t('text_admin')
        );
    }

    public function getUserIdsAsAuthor()
    {
        $query = $this->db->query("SELECT * FROM ". BLOG_TABLE_PREFIX ."author");

        $results = array();
        foreach ($query->rows as $author)
        {
            $results[] = $author['user_id'];
        }

        return $results;
    }
    /* end AUTHOR */

    /* ARTICLE */
    public function getArticles(array $conditions = array(), array $fetch_options = array())
    {
        $config_language_id = intval($this->config->get('config_language_id'));

        $where          = $this->prepareArticleConditions($conditions);
        $join_options   = $this->prepareArticleJoinOptions($conditions);
        $limit_clause   = $this->prepareLimitClause($fetch_options);
        $order_clause   = $this->prepareArticleOrderByClause($fetch_options);

        $query = $this->db->query("
            SELECT a.article_id, a.date_added, a.status, a.sort_order, ad.name, ad.description, au.name AS author_name
                ". $join_options['selected_fields'] ."
            FROM ". BLOG_TABLE_PREFIX ."article a
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (a.article_id = ad.article_id AND ad.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."author au
                ON (a.author_id = au.author_id)
            ". $join_options['join_tables'] ."
            WHERE $where
            $order_clause
            $limit_clause
        ");

        return $query->rows;
    }

    public function getArticleByArticleId($article_id)
    {
        $article_id = intval($article_id);

        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."article
            WHERE article_id = $article_id
            LIMIT 0, 1
        ");

        return $query->row;
    }

    public function getArticleDescriptionsByArticleId($article_id)
    {
        $article_id = intval($article_id);

        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."article_description
            WHERE article_id = $article_id
        ");

        $results = array();
        foreach ($query->rows as $article_description)
        {
            $results[$article_description['language_id']] = $article_description;
        }

        return $results;
    }

    public function getArticleCategoryIdsByArticleId($article_id)
    {
        $article_id = intval($article_id);

        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."article_to_category
            WHERE article_id = $article_id
        ");

        $results = array();
        foreach ($query->rows as $category)
        {
            $results[] = $category['category_id'];
        }

        return $results;
    }

    public function getArticleStoresByArticleId($article_id)
    {
        $article_id = intval($article_id);

        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."article_to_store
            WHERE article_id = $article_id
        ");

        $results = array();
        foreach ($query->rows as $store)
        {
            $results[] = $store['store_id'];
        }

        return $results;
    }

    public function getArticleLayoutsByArticleId($article_id)
    {
        $article_id = intval($article_id);

        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."article_to_layout
            WHERE article_id = $article_id
        ");

        $results = array();
        foreach ($query->rows as $layout)
        {
            $results[$layout['store_id']] = $layout['layout_id'];
        }

        return $results;
    }

    public function countArticles(array $conditions = array())
    {
        $config_language_id = intval($this->config->get('config_language_id'));

        $where          = $this->prepareArticleConditions($conditions);
        $join_options   = $this->prepareArticleJoinOptions($conditions);

        $query = $this->db->query("
            SELECT COUNT(*) AS article_total
                ". $join_options['selected_fields'] ."
            FROM ". BLOG_TABLE_PREFIX ."article a
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (a.article_id = ad.article_id AND ad.language_id = $config_language_id)
            INNER JOIN ". BLOG_TABLE_PREFIX ."author au
                ON (a.author_id = au.author_id)
            ". $join_options['join_tables'] ."
            WHERE $where
        ");

        return isset($query->row['article_total']) ? $query->row['article_total'] : 0;
    }

    public function insertArticle(array $article)
    {
        // Article
        $article_data = $article['article'];

        $article_data['author_id']          = intval($article_data['author_id']);
        $article_data['featured_image']     = $this->escapeString($article_data['featured_image']);
        $article_data['date_added']         = $this->convertDateTimeToTimestamp($article_data['date_added']);
        $article_data['date_modified']      = $this->convertDateTimeToTimestamp($article_data['date_modified']);
        $article_data['date_published']     = $this->convertDateToTimestamp($article_data['date_published']);
        $article_data['sort_order']         = $this->escapeString($article_data['sort_order']);
        $article_data['status']             = intval($article_data['status']);

        $article_id = intval($this->insert('article', $article_data));

        // Description
        $descriptions = $article['description'];

        foreach ($descriptions as $language_id => $description)
        {
            $description['article_id']          = intval($article_id);
            $description['language_id']         = intval($language_id);
            $description['name']                = $this->escapeString($description['name']);
            $description['description']         = $this->escapeString($description['description']);
            $description['meta_keyword']        = $this->escapeString($description['meta_keyword']);
            $description['meta_description']    = $this->escapeString($description['meta_description']);
            $description['tags']                = $this->escapeString($this->standardizeProductTags($description['tags']));

            $this->insert('article_description', $description);
        }

        // Category
        if (isset($article['category']))
        {
            $categories = $article['category'];

            foreach ($categories as $category_id)
            {
                $this->insert('article_to_category', array(
                    'article_id'    => intval($article_id),
                    'category_id'   => intval($category_id)
                ));
            }
        }

        // Store
        if (isset($article['store']))
        {
            $stores = $article['store'];

            foreach ($stores as $store_id)
            {
                $this->insert('article_to_store', array(
                    'article_id'    => $article_id,
                    'store_id'      => intval($store_id)
                ));
            }
        }

        // Related Articles
        if (isset($article['related_articles']))
        {
            $related_article_ids = $article['related_articles'];

            foreach ($related_article_ids as $related_article_id)
            {
                $this->insert('article_related', array(
                    'article_id'    => $article_id,
                    'related_id'    => intval($related_article_id)
                ));
            }
        }

        // Layout
        if (isset($article['layout']))
        {
            $store_layouts = $article['layout'];

            foreach ($store_layouts as $store_id => $layout_id)
            {
                $this->insert('article_to_layout', array(
                    'article_id'    => $article_id,
                    'store_id'      => intval($store_id),
                    'layout_id'     => intval($layout_id)
                ));
            }
        }

        // Keyword
        if ($article['keyword'])
        {
            $article['keyword'] = $this->escapeString($article['keyword']);
            $url_query          = $this->escapeString("kbm_article_id=$article_id");

            $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias SET query = $url_query, keyword = {$article['keyword']}");
        }

	    $this->deleteArticleTagsCache();

        return $article_id;
    }

    public function editArticle(array $article)
    {
        $article_id = intval($article['article']['article_id']);
        $article_where  = "article_id=$article_id";

        // Article Data
        $data = $article['article'];
        $data['author_id']          = intval($data['author_id']);
        $data['featured_image']     = $this->escapeString($data['featured_image']);
        $data['date_added']         = $this->convertDateTimeToTimestamp($data['date_added']);
        $data['date_modified']      = $this->convertDateTimeToTimestamp($data['date_modified']);
        $data['date_published']     = $this->convertDateToTimestamp($data['date_published']);
        $data['sort_order']         = $this->escapeString($data['sort_order']);
        $data['status']             = intval($data['status']);

        $this->edit('article', $data, $article_where);

        // Description
        $this->delete('article_description', $article_where);

        $descriptions = $article['description'];
        foreach ($descriptions as $language_id => $description)
        {
            $description['article_id']          = intval($article_id);
            $description['language_id']         = intval($language_id);
            $description['name']                = $this->escapeString($description['name']);
            $description['description']         = $this->escapeString($description['description']);
            $description['meta_keyword']        = $this->escapeString($description['meta_keyword']);
            $description['meta_description']    = $this->escapeString($description['meta_description']);
            $description['tags']                = $this->escapeString($this->standardizeProductTags($description['tags']));

            $this->insert('article_description', $description);
        }

        // Category
        $this->delete('article_to_category', $article_where);

        if (isset($article['category']))
        {
            $categories = $article['category'];

            foreach ($categories as $category_id)
            {
                $this->insert('article_to_category', array(
                    'article_id'    => intval($article_id),
                    'category_id'   => intval($category_id)
                ));
            }
        }

        // Related Articles
        $this->delete('article_related', $article_where);

        if (isset($article['related_articles']))
        {
            $related_article_ids = $article['related_articles'];

            foreach ($related_article_ids as $related_article_id)
            {
                $this->insert('article_related', array(
                    'article_id'    => $article_id,
                    'related_id'    => intval($related_article_id)
                ));
            }
        }

        // Store
        $this->delete('article_to_store', $article_where);

        if (isset($article['store']))
        {
            $stores = $article['store'];

            foreach ($stores as $store_id)
            {
                $this->insert('article_to_store', array(
                    'article_id'    => $article_id,
                    'store_id'      => intval($store_id)
                ));
            }
        }

        // Layout
        $this->delete('article_to_layout', $article_where);

        if (isset($article['layout']))
        {
            $store_layouts = $article['layout'];

            foreach ($store_layouts as $store_id => $layout_id)
            {
                $this->insert('article_to_layout', array(
                    'article_id'    => $article_id,
                    'store_id'      => intval($store_id),
                    'layout_id'     => intval($layout_id)
                ));
            }
        }

        // Keyword
        $url_query = $this->escapeString("kbm_article_id=$article_id");
        $this->db->query("DELETE FROM ". DB_PREFIX ."url_alias WHERE query = $url_query");

        if ($article['keyword'])
        {
            $article['keyword'] = $this->escapeString($article['keyword']);
            $url_query          = $this->escapeString("kbm_article_id=$article_id");

            $this->db->query("INSERT INTO ". DB_PREFIX ."url_alias SET query = $url_query, keyword = {$article['keyword']}");
        }

	    $this->deleteArticleTagsCache();

	    return true;
    }

    public function deleteArticle($article_id)
    {
        $article_id = intval($article_id);
        $article_where = "article_id=$article_id";

        // Category, Description, Store, Layout, Keyword
        $this->delete('article', $article_where);
        $this->delete('article_description', $article_where);
        $this->delete('article_to_category', $article_where);
        $this->delete('article_to_store', $article_where);
        $this->delete('article_to_layout', $article_where);

        $url_query = $this->escapeString("kbm_article_id=$article_id");
        $this->db->query("DELETE FROM ". DB_PREFIX ."url_alias WHERE query = $url_query");

	    // Delete related comments
	    $comments = $this->getComments(array('article_id' => $article_id));
	    foreach ($comments as $comment)
	    {
		    $this->deleteComment($comment['comment_id']);
	    }

	    $this->deleteArticleTagsCache();

	    return true;
    }

    public function copyArticle($article_id)
    {
        $article = array(
            'article'       => $this->getArticleByArticleId($article_id),
            'description'   => $this->getArticleDescriptionsByArticleId($article_id),
            'category'      => $this->getArticleCategoryIdsByArticleId($article_id),
            'store'         => $this->getArticleStoresByArticleId($article_id),
            'layout'        => $this->getArticleLayoutsByArticleId($article_id),
            'keyword'       => $this->getUrlAlias('article', $article_id),
        );

        unset($article['article']['article_id']);

        $article['article']['date_added']       = $this->formatDateTime(self::$time);
        $article['article']['date_modified']    = $this->formatDateTime(self::$time);
        $article['article']['date_published']   = $this->formatDate($article['article']['date_published']);

        return $this->insertArticle($article);
    }

    public function editArticleStatusOrder($article_id, array $article)
    {
        $article_id = intval($article_id);

        $data = array();

        if (isset($article['status']))
        {
            $data['status'] = $article['status'];
        }

        if (isset($article['sort_order']))
        {
            $data['sort_order'] = $article['sort_order'];
        }

	    $this->cache->delete(self::CACHE_PRODUCT_TAGS);

	    $this->edit('article', $data, "article_id=$article_id");
    }

    public function prepareArticles(array $articles)
    {
        return array_map(array($this, 'prepareArticle'), $articles);
    }

    public function prepareArticle(array $article)
    {
        $article_id = intval($article['article_id']);

        // Date
        $article['date_added_formatted'] = $this->formatDate($article['date_added']);

        // Categories
        $query = $this->db->query("
            SELECT *
            FROM ". BLOG_TABLE_PREFIX ."article_to_category a2c
            WHERE article_id = $article_id
        ");

        $category_ids = array();
        foreach ($query->rows as $category)
        {
            $category_ids[] = $category['category_id'];
        }

        $article['categories'] = $category_ids ? $this->getCategoryPaths($category_ids) : array();

        // Stores
        $query = $this->db->query("
            SELECT a2s.store_id, s.name
            FROM ". BLOG_TABLE_PREFIX ."article_to_store a2s
            LEFT JOIN ". DB_PREFIX ."store s
                ON (a2s.store_id = s.store_id)
            WHERE article_id = $article_id
        ");

        $article['stores'] = array();
        foreach ($query->rows as $store)
        {
            if ($store['store_id'] == 0)
            {
                $article['stores'][$store['store_id']] = $this->config->get('config_name') . $this->language->get('text_default');
            }
            else
            {
                $article['stores'][$store['store_id']] = $store['name'];
            }
        }

        // Keyword
        $article['keyword'] = $this->getUrlAlias('article', $article_id);

        return $article;
    }

    public function prepareArticleJoinOptions(array $conditions)
    {
        $join_tables        = '';
        $selected_fields   = '';

        if (!empty($conditions['store_id']))
        {
            $join_tables = '
                INNER JOIN '. BLOG_TABLE_PREFIX .'article_to_store a2s
                    ON (a.article_id = a2s.article_id)';
        }

        if (!empty($conditions['category_id']))
        {
            $join_tables = '
                INNER JOIN '. BLOG_TABLE_PREFIX .'article_to_category a2c
                    ON (a.article_id = a2c.article_id)
            ';
        }

        return array(
            'join_tables'       => $join_tables,
            'selected_fields'  => $selected_fields
        );
    }

    public function prepareArticleConditions(array $conditions)
    {
        $sql_conditions = array();

        if (!empty($conditions['date_added_start']))
        {
            $sql_conditions[] = 'a.date_added >= ' . $this->convertDateToTimestamp($conditions['date_added_start']);
        }

        if (!empty($conditions['date_added_end']))
        {
            $sql_conditions[] = 'a.date_added <= ' . $this->convertDateToTimestamp($conditions['date_added_end']);
        }

        if (!empty($conditions['category_id']))
        {
            $sql_conditions[] = 'a2c.category_id = ' . intval($conditions['category_id']);
        }

        if (!empty($conditions['author_id']))
        {
            $sql_conditions[] = 'a.author_id = ' . intval($conditions['author_id']);
        }

        if (isset($conditions['status']))
        {
            $sql_conditions[] = 'a.status = ' . intval($conditions['status']);
        }

        if (!empty($conditions['store_id']))
        {
            $sql_conditions[] = 'a2c.store_id = ' . intval($conditions['store_id']);
        }

        return $sql_conditions ? '(' . implode(') AND (', $sql_conditions) . ')' : '1 = 1';
    }

    public function prepareArticleOrderByClause(array $fetch_options)
    {
        $order_by = '';

        if (!empty($fetch_options['order']))
        {
            switch ($fetch_options['order'])
            {
                case 'date_added':
                    $order_by .= 'a.date_added';
                    break;
                case 'author_name':
                    $order_by .= 'au.name';
                    break;
                case 'sort_order':
                    $order_by .= 'a.sort_order';
                    break;
                default:
                    $order_by .= 'ad.name';
                    break;
            }

            if (!empty($fetch_options['order_direction']) && $fetch_options['order_direction'] == 'desc')
            {
                $order_by .= ' DESC';
            }
            else
            {
                $order_by .= ' ASC';
            }
        }

        return $order_by ? 'ORDER BY ' . $order_by : '';
    }

    public function getRelatedArticleIds($article_id)
    {
        // Get related product ids
        $article_id = intval($article_id);

        $query = $this->db->query("
            SELECT * FROM ". BLOG_TABLE_PREFIX ."article_related WHERE article_id = $article_id
        ");

        $results = array();
        foreach ($query->rows as $related)
        {
            $results[] = $related['related_id'];
        }

        return $results;
    }

    public function prepareRelatedArticles(array $related_ids)
    {
        $config_language_id = $this->config->get('config_language_id');

        $results = array();
        foreach ($related_ids as $related_id)
        {
            $related_id = intval($related_id);

            $article_query = $this->db->query("
                SELECT a.article_id, ad.name
                FROM ". BLOG_TABLE_PREFIX ."article a
                LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                    ON (a.article_id = ad.article_id AND ad.language_id = $config_language_id)
                WHERE a.article_id = $related_id
            ");

            if ($article_query->row)
            {
                $results[$related_id] = $article_query->row['name'];
            }
        }

        return $results;
    }

    public function standardizeProductTags($tags)
    {
        $tags = preg_replace('/\s+,\s+/', '', $tags);
	    $tags = trim($tags, ',');

        return $tags;
    }

    public function getArticleOrderOptions()
    {
        return array(
            self::OPTION_ARTICLE_ORDER_SORT_ORDER_ASC       => _t('text_sort_order_asc'),
            self::OPTION_ARTICLE_ORDER_SORT_ORDER_DESC      => _t('text_sort_order_desc'),
            self::OPTION_ARTICLE_ORDER_DATE_CREATED_ASC     => _t('text_date_created_asc'),
            self::OPTION_ARTICLE_ORDER_DATE_CREATED_DESC    => _t('text_date_created_desc')
        );
    }

	public function deleteArticleTagsCache()
	{
		foreach ($this->getLanguages() as $language)
		{
			$this->cache->delete(self::CACHE_PRODUCT_TAGS . ".{$language['language_id']}");
		}
	}
    /* end ARTICLE */

    /* Comment */
    public function getComments(array $conditions, array $fetch_options = array())
    {
        $where_conditions   = $this->prepareCommentConditions($conditions);
        $limit_clause       = $this->prepareLimitClause($fetch_options);

        $config_language_id = intval($this->config->get('config_language_id'));

        $query = $this->db->query("
            SELECT c.comment_id, c.content, c.date_added, c.author_id, c.author_type, c.data, c.parent_comment_id, c.status, c.article_id, ad.name AS article_name
            FROM ". BLOG_TABLE_PREFIX ."comment c
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (c.article_id = ad.article_id AND ad.language_id = $config_language_id)
            WHERE $where_conditions
            $limit_clause
        ");

        return $query->rows;
    }

    public function editComment($comment)
    {
        $comment_id = intval($comment['comment_id']);
        $comment_where = "comment_id=$comment_id";

        // @todo: Check whether edit author or not

        $data = array(
            'data'      => $this->escapeString(serialize($comment['author'])),
            'content'   => $this->escapeString($comment['content']),
            'status'    => intval($comment['status'])
        );

        $this->edit('comment', $data, $comment_where);
    }

    public function editCommentStatus($comment_id, $data)
    {
        $comment_id = intval($comment_id);

        $this->edit('comment', array(
            'status' => intval($data['status'])
        ), "comment_id = $comment_id");

        return true;
    }

    public function replyComment($comment_id, array $reply)
    {
        $comment_id = intval($comment_id);

        $data = array(
            'parent_comment_id'     => $comment_id,
            'article_id'            => intval($reply['article_id']),
            'author_id'             => intval($reply['author_id']),
            'author_type'           => $this->escapeString($reply['author_type']),
            'content'               => $this->escapeString($reply['content']),
            'data'                  => $this->escapeString(serialize($reply['author'])),
            'date_added'            => self::$time,
            'date_modified'         => self::$time,
            'status'                => 1
        );

        return $this->insert('comment', $data);
    }

    public function deleteComment($comment_id)
    {
        $comment_id = intval($comment_id);

        $this->delete('comment', "comment_id = $comment_id");

        return true;
    }

    public function countComments(array $conditions = array())
    {
        $where_conditions   = $this->prepareCommentConditions($conditions);
        $config_language_id = intval($this->config->get('config_language_id'));

        $query = $this->db->query("
            SELECT COUNT(c.comment_id) AS comment_total
            FROM ". BLOG_TABLE_PREFIX ."comment c
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (c.article_id = ad.article_id AND ad.language_id = $config_language_id)
            WHERE $where_conditions
        ");

        return isset($query->row['comment_total']) ? $query->row['comment_total'] : 0;
    }

    public function getCommentByCommentId($comment_id)
    {
        $comment_id             = intval($comment_id);
        $config_language_id     = intval($this->config->get('config_language_id'));

        $query = $this->db->query("
            SELECT c.comment_id, c.content, c.date_added, c.author_id, c.author_type, c.data, c.parent_comment_id, c.status, c.article_id, ad.name AS article_name
            FROM ". BLOG_TABLE_PREFIX ."comment c
            LEFT JOIN ". BLOG_TABLE_PREFIX ."article_description ad
                ON (c.article_id = ad.article_id AND ad.language_id = $config_language_id)
            WHERE c.comment_id = $comment_id
        ");

        return $query->row;
    }

    public function prepareCommentConditions(array $conditions)
    {
        $sql_conditions = array();

        if (isset($conditions['status']))
        {
            $sql_conditions[] = 'c.status = ' . intval($conditions['status']);
        }

	    if (!empty($conditions['article_id']))
	    {
		    $sql_conditions[] = 'c.article_id = ' . intval($conditions['article_id']);
	    }

        return $sql_conditions ? implode(') AND (', $sql_conditions) : '1=1';
    }

    public function prepareComments(array $comments)
    {
        return array_map(array($this, 'prepareComment'), $comments);
    }

    public function prepareComment(array $comment)
    {
        // Content
        $comment['content'] = htmlentities($comment['content'], ENT_QUOTES, 'UTF-8');

        // Author
        $comment['author'] = $this->getCommentAuthor($comment);

        // Parent Comment Author
        if ($comment['parent_comment_id'])
        {
            $parent_comment = $this->getCommentByCommentId($comment['parent_comment_id']);
            $parent_comment_author = $this->getCommentAuthor($parent_comment);

            $comment['parent_comment'] = array(
                'author' => $parent_comment_author
            );
        }

        // Date
        $comment['date_added_formatted'] = $this->formatDateTime($comment['date_added']);

        return $comment;
    }

    public function getCommentAuthor(array $comment)
    {
        $author_id = intval($comment['author_id']);

        $author = array(
            'name'      => '',
            'email'     => '',
            'website'   => ''
        );

        if ($comment['author_type'] == self::AUTHOR_TYPE_ADMIN)
        {
            $query = $this->db->query("
                SELECT a.author_id, a.name, u.email
                FROM ". BLOG_TABLE_PREFIX ."author a
                LEFT JOIN ". DB_PREFIX ."user u
                    ON (a.user_id = u.user_id)
                WHERE a.author_id = $author_id
                LIMIT 0, 1
            ");

            if ($query->row)
            {
                $author = array_merge($author, $query->row);
            }
        }
        else if ($comment['author_type'] == self::AUTHOR_TYPE_CUSTOMER)
        {
            $query = $this->db->query("
                SELECT c.customer_id, c.firstname, c.lastname, c.email
                FROM ". DB_PREFIX ."customer c
                WHERE c.customer_id = $author_id
            ");

            if ($query->row)
            {
                $author = array_merge($author, array(
                    'customer_id'   => $query->row['customer_id'],
                    'name'          => $query->row['firstname'] . ' ' . $query->row['lastname'],
                    'email'         => $query->row['email'],
                ));
            }
        }

        if ($comment['data'])
        {
            $author = array_merge($author, unserialize($comment['data']));
        }

        return $author;
    }
    /* end Comment */

    public function getCategoryColumnOptions()
    {
        return array(
            1 => sprintf(_t('text_x_column'), 1),
            2 => sprintf(_t('text_x_column'), 2),
            3 => sprintf(_t('text_x_column'), 3),
            4 => sprintf(_t('text_x_column'), 4),
        );
    }

    /* OTHER */
    public function prepareLimitClause(array $fetch_options)
    {
        $limit_clause = '';

        if (!empty($fetch_options['page']))
        {
            $page = $fetch_options['page'];
            $per_page = $fetch_options['per_page'];

            $limit_clause = (($page - 1) * $per_page) . ', ' . $per_page;
        }

        return $limit_clause ? 'LIMIT ' . $limit_clause : '';
    }

    public function getCustomerGroupOptions()
    {
        $this->load->model('sale/customer_group');

        $groups = $this->model_sale_customer_group->getCustomerGroups();

        $results = array();
        foreach ($groups as $group)
        {
            $results[$group['customer_group_id']] = $group['name'];
        }

        return $results;
    }

    public function getAdminGroupOptions()
    {
        $this->load->model('user/user_group');

        $groups = $this->model_user_user_group->getUserGroups();

        $results = array();
        foreach ($groups as $group)
        {
            $results[$group['user_group_id']] = $group['name'];
        }

        return $results;
    }

    public function getCapchaOptions()
    {
        return array(
            self::OPTION_CAPCHA_DISABLED                => _t('text_disabled'),
            self::OPTION_CAPCHA_GUEST_ONLY              => _t('text_guest_only'),
            self::OPTION_CAPCHA_ALL_VISITOR_AND_CLIENT  => _t('text_all_visitor_and_client'),
            self::OPTION_CAPCHA_ALL_EXCEPT_NON_DEFAULT  => _t('text_all_except_non_default')
        );
    }

    public function getStoreOptions()
    {
        $this->load->model('setting/store');

        // Get stores
        $rows = $this->model_setting_store->getStores();

        $stores = array(
            0 => $this->config->get('config_name') . $this->language->get('text_default')
        );

        foreach ($rows as $row)
        {
            $stores[$row['store_id']] = $row['name'];
        }

        return $stores;
    }

    public function getLayoutOptions($include_empty = true)
    {
        $this->load->model('design/layout');
        $layouts = $this->model_design_layout->getLayouts();

        $results = $include_empty ? array(0 => '') : array();
        foreach ($layouts as $layout)
        {
            $results[$layout['layout_id']] = $layout['name'];
        }

        return $results;
    }

    public function getCatalogBase()
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
        {
            $base = $this->config->get('config_ssl') ? $this->config->get('config_ssl') : HTTPS_CATALOG;
        }
        else
        {
            $base = $this->config->get('config_url') ? $this->config->get('config_url') : HTTP_CATALOG;
        }

        return str_replace('&amp;', '&', $base);
    }

    public function getLanguages()
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

	public function getLanguageOptions()
	{
		$this->load->model('localisation/language');
		$languages = $this->model_localisation_language->getLanguages();
		$config_language = $this->config->get('config_language');

		// Fix error in OpenCart 1.5.4.x
		if (!isset($languages[$config_language]))
		{
			$config_language = $this->config->get('config_language_id');
		}

		$results = array();
		$default_language = $languages[$config_language];
		unset($languages[$config_language]);

		$results[$config_language] = $default_language;
		$results = array_merge($results, $languages);

		return $results;
	}

	public function translate($texts)
	{
		$languages = $this->getLanguageOptions();

		if (is_string($texts))
		{
			$text = $texts;
			$texts = array();

			foreach ($languages as $language)
			{
				$texts[$language['language_id']] = $text;
			}
		}
		else if (is_array($texts))
		{
			$first = current($texts);

			foreach ($languages as $language)
			{
				if (is_string($first))
				{
					if (empty($texts[$language['language_id']]))
					{
						$texts[$language['language_id']] = $first;
					}
				}
				else if (is_array($first))
				{
					if (!isset($texts[$language['language_id']]))
					{
						$texts[$language['language_id']] = array();
					}

					foreach ($first as $key => $val)
					{
						if (empty($texts[$language['language_id']][$key]))
						{
							$texts[$language['language_id']][$key] = $val;
						}
					}
				}
			}
		}

		return $texts;
	}
    /* end OTHER */

    /* HELPER */
    public function formatDateTime($time)
    {
        return date('Y-m-d H:i', $time);
    }

    public function formatDate($time)
    {
        return date('Y-m-d', $time);
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

    public function getUrlAlias($type, $id)
    {
        $url_query = '';
        $id = intval($id);

        if ($type == 'category')
        {
            $url_query = 'kbm_category_id';
        }
        else if ($type == 'article')
        {
            $url_query = 'kbm_article_id';
        }

        $url_query .= '=' . $id;
        $url_query = $this->escapeString($url_query);

        $query = $this->db->query("
            SELECT *
            FROM `". DB_PREFIX ."url_alias`
            WHERE query = $url_query
        ");

        return isset($query->row['keyword']) ? $query->row['keyword'] : '';
    }

    public function escapeString($str)
    {
        return "'" . $this->db->escape($str) . "'";
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
    /* end HELPER */
}