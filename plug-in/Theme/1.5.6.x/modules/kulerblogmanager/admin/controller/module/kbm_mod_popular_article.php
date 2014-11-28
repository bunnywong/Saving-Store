<?php
function _t()
{
	return call_user_func_array('ControllerModuleKBMModPopularArticle::__', func_get_args());
}

/**
 * Class ControllerModuleKBMModPopularArticle
 * @property Config $config
 * @property Url $url
 * @property Request $request
 * @property Session $session
 * @property Document $document
 * @property ModelModuleKBMModRecentArticle $model
 * @property ModelModuleKbm $kbm_model
 */
class ControllerModuleKBMModPopularArticle extends Controller
{
	const MODE                  = 'PRODUCTION';

	public static $__           = array();
	public static $lost_texts   = array();

	/* @var  */
	private $model;
	private $kbm_model;
	private $errors = array();

	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->load->model('module/kbm');
		$this->kbm_model = $this->model_module_kbm;

		$this->data['token'] = $this->session->data['token'];
		self::$__ = $this->getLanguages();
		$this->data['__'] = self::$__;
	}

	public function index()
	{
		$this->data['breadcrumbs'] = $this->getPathways();
		$this->data['stores'] = $this->getStores();
		$this->data['selected_store_id'] = $this->getSelectedStore();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
		{
			$this->save();
		}

		$this->prepareAlerts();
		$this->getResources();

		$this->data['action'] = $this->helperLink('module/kbm_mod_popular_article');
		$this->data['cancel'] = $this->helperLink('extension/module');

		$this->data['config_language_id'] = $this->config->get('config_language_id');

		$this->data['languages']                = $this->getLanguageOptions();

		$this->data['layouts'] = $this->getLayoutOptions();
		$this->data['positions'] = $this->getPositionOptions();
		$this->data['default_module'] = $this->getDefaultModule();

		$this->data['category_options'] = $this->kbm_model->getCategoryOptions();

		$modules = array();
		if (isset($this->request->post['modules']))
		{
			$modules = $this->request->post['modules'];
		}
		else {
			$this->load->model('setting/setting');

			if ($kbm_popular_article = $this->model_setting_setting->getSetting('kbm_mod_popular_article', $this->data['selected_store_id']))
			{
				if (isset($kbm_popular_article['kbm_mod_popular_article_module']))
				{
					$modules = $kbm_popular_article['kbm_mod_popular_article_module'];

					foreach ($modules as &$module)
					{
						$module = array_merge($this->data['default_module'], $module);
					}
				}
			}
		}

		$this->data['modules'] = $this->prepareModules($modules);

		$this->document->setTitle(_t('heading_module_title'));

		$this->template = 'module/kbm_mod_popular_article.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function prepareModules($modules)
	{
		if (is_array($modules))
		{
			foreach ($modules as &$module)
			{
				$module['title'] = $this->translate($module['title']);
				$module['main_title'] = $module['title'][$this->config->get('config_language_id')];
			}
		}

		return $modules;
	}

	private function getStores()
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

	/**
	 * Get selected store id from post or get
	 */
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
		$__ = $this->language->load('module/kbm_mod_popular_article');

		return $__;
	}

	private function getPathways() {
		$breadcrumbs = array();

		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$breadcrumbs[] = array(
			'text'      => $this->language->get('heading_module_title'),
			'href'      => $this->url->link('module/kbm_mod_popular_article', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		return $breadcrumbs;
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/kbm_mod_popular_article'))
		{
			$this->errors['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['modules']))
		{
			foreach ($this->request->post['modules'] as $module_index => $module)
			{
				if (empty($module['featured_image_width']) || empty($module['featured_image_height']))
				{
					if (empty($this->errors['featured_image_size']))
					{
						$this->errors['featured_image_size'] = array();
					}

					$this->errors['featured_image_size'][$module_index] = _t('error_featured_image_size');
				}
			}
		}

		return !$this->errors ? true : false;
	}

	private function prepareAlerts()
	{
		$this->data['error_warning'] = '';

		$this->data['error_featured_image_size'] = array();

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

	private function getResources()
	{
		$this->document->addStyle('view/kulercore/css/kulercore.css');
		$this->document->addScript('view/kulercore/js/handlebars.js');
	}

	private function getDefaultModule()
	{
		return array(
			'title'                     => '',
			'show_title'                => 1,
			'layout_id'                 => 1,
			'position'                  => 'content_top',
			'status'                    => 1,
			'sort_order'                => '',

			'product_featured_image'    => 1,
			'product_description'       => 1,
			'specific_categories'       => array(),
			'exclude_categories'        => array(),
			'article_limit'             => 5,
			'description_limit'         => 50,
			'featured_image_width'      => 45,
			'featured_image_height'     => 45
		);
	}

	private function save()
	{
		$this->load->model('setting/setting');

		$this->request->post['modules'] = isset($this->request->post['modules']) ? $this->request->post['modules'] : array();

		$data = array(
			'kbm_mod_popular_article_module' => $this->request->post['modules']
		);

		$this->model_setting_setting->editSetting('kbm_mod_popular_article', $data, $this->request->post['store_id']);

		$this->session->data['success'] = _t('text_success');

		if (isset($this->request->post['op']) && $this->request->post['op'] == 'close')
		{
			$this->redirect($this->helperLink('extension/module'));
		}
		else
		{
			$this->redirect($this->helperLink('module/kbm_mod_popular_article', array('store_id' => $this->request->post['store_id'])));
		}
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
		$results = array_merge($results, $languages);

		return $results;
	}

	private function getLayoutOptions()
	{
		$this->load->model('design/layout');
		$result = $this->model_design_layout->getLayouts();
		return $result;
	}

	private function getPositionOptions()
	{
		return array(
			'content_top'       => _t('text_content_top'),
			'content_bottom'    => _t('text_content_bottom'),
			'column_left'       => _t('text_column_left'),
			'column_right'      => _t('text_column_right')
		);
	}

	public function uninstall()
	{
		$this->load->model('setting/setting');

		$stores = $this->getStores();

		foreach ($stores as $store_id => $store_name)
		{
			$this->model_setting_setting->deleteSetting('kbm_mod_popular_article', $store_id);
		}
	}

	private function helperLink($route, array $params = array())
	{
		$params['token'] = $this->data['token'];

		return $this->url->link($route, http_build_query($params), 'SSL');
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

	private function translate($texts)
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
}