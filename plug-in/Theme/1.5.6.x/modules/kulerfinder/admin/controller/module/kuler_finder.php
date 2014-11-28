<?php

class ControllerModuleKulerFinder extends Controller {
	private $error = array(); 
	
	public function index() {   

		$this->getLanguages();
		$this->getPathways();
        $this->getStores();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
        {
			$this->saveAction();
		}
		
		$this->getError();
		
		$this->data['action'] = $this->url->link('module/kuler_finder', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        $defaults = array(
            'status'                        => 0,
            'search_input_width'            => '',
            'search_field_text'             => 'Search',
            'search_result_limit'           => 3,
            'category'                      => 1,
	        'manufacturer_filter'           => 0,
	        'product_description_filter'    => 0,
            'image_width'                   => '80',
            'image_height'                  => '80',
            'name'                          => 1,
            'price'                         => 1,
            'rating'                        => 1,
            'description'                   => 1,
            'add'                           => 1,
            'wishlist'                      => 1,
            'compare'                       => 1
        );

        $this->data['module'] = $defaults;

		if (isset($this->request->post['kuler_finder']))
        {
			$this->data['module'] = $this->request->post['kuler_finder'];
		} else {
            $this->load->model('setting/setting');

            if ($kuler_finder = $this->model_setting_setting->getSetting('kuler_finder', $this->data['selected_store_id']))
            {
                if (isset($kuler_finder['kuler_finder']))
                {
                    $this->data['module'] = array_merge($defaults, $kuler_finder['kuler_finder']);
                }
            }
        }

        $this->data['module'] = $this->prepareModules($this->data['module']);
        $this->data['defaults'] = $defaults;
        $this->data['languages'] = $this->getLanguageOptions();
		$this->data['token'] = $this->session->data['token'];

        $this->document->addStyle('view/kulercore/css/kulercore.css');

		$this->template = 'module/kuler_finder.phtml';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

    private function prepareModules($module)
    {
        if (is_array($module))
        {
            $module['search_field_text'] = $this->translate($module['search_field_text']);
        }

        return $module;
    }

	protected function saveAction() {
		$this->load->model('setting/setting');

        $data = array(
            'kuler_finder' => $this->request->post['kuler_finder']
        );

		$this->model_setting_setting->editSetting('kuler_finder', $data, $this->request->post['store_id']);

		$this->session->data['success'] = $this->language->get('text_success');
        if(isset($this->request->post['op']) && $this->request->post['op'] == 'close') {
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
    		$this->redirect($this->url->link('module/kuler_finder', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->post['store_id'], 'SSL'));
        }
	}
	
	protected function getLanguages() {
		$this->data['__'] = $this->language->load('module/kuler_finder');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_success'] = $this->language->get('text_success');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['text_default_search'] = $this->language->get('text_default_search');

        $this->data['entry_module'] = $this->language->get('entry_module');
		$this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_input_width'] = $this->language->get('entry_input_width');
        $this->data['entry_search_field_text'] = $this->language->get('entry_search_field_text');
        $this->data['entry_search_result_limit'] = $this->language->get('entry_search_result_limit');

		$this->data['entry_manufacturer_filter'] = $this->language->get('entry_manufacturer_filter');
		$this->data['entry_product_description_filter'] = $this->language->get('entry_product_description_filter');

		$this->data['entry_product_dimension'] = $this->language->get('entry_product_dimension');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_thumb'] = $this->language->get('entry_thumb');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_teaser'] = $this->language->get('entry_teaser');
		$this->data['entry_rating'] = $this->language->get('entry_rating');
		$this->data['entry_compare'] = $this->language->get('entry_compare');
		$this->data['entry_wishlist'] = $this->language->get('entry_wishlist');
		$this->data['entry_cart'] = $this->language->get('entry_cart');
		
		$this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_close'] = $this->language->get('button_close');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');		
	}
	
	/**
	 * @todo : Set pathway
	 */
	protected function getPathways() {
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/kuler_finder', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
	}

    private function getStores()
    {
        $this->load->model('setting/store');

        $rows = $this->model_setting_store->getStores();

        $stores = array(
            0 => $this->config->get('config_name') . $this->language->get('text_default')
        );

        foreach ($rows as $row)
        {
            $stores[$row['store_id']] = $row['name'];
        }

        $this->data['selected_store_id'] = 0;
        if (isset($this->request->post['store_id']))
        {
            $this->data['selected_store_id'] = $this->request->post['store_id'];
        }
        else if (isset($this->request->get['store_id']))
        {
            $this->data['selected_store_id'] = $this->request->get['store_id'];
        }

        $this->data['stores'] = $stores;

        return $stores;
    }

    /**
	 * @todo : Set error message form
	 */
	protected function getError() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        if (isset($this->error['dimension'])) {
            $this->data['error_dimension'] = $this->error['dimension'];
        } else {
            $this->data['error_dimension'] = array();
        }
	}
	
	/**
	 * @todo : Validate form beforeSave
	 */
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/kuler_finder'))
        {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        $setting = $this->request->post['kuler_finder'];

        if (empty($setting['image_width']) || empty($setting['image_height']))
        {
            $this->error['dimension'] = $this->language->get('error_dimension');
            return false;
        }


        if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $stores = $this->getStores();

        foreach ($stores as $store_id => $store_name)
        {
            $this->model_setting_setting->deleteSetting('kuler_finder', $store_id);
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
}
?>