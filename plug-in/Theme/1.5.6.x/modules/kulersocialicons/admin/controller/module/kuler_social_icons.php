<?php

class ControllerModuleKulerSocialIcons extends Controller {
    const MODULE_NAME = 'kuler_social_icons';

    private $tab;
	private $error = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->data['languages'] = $this->getLanguageOptions();

        $this->data['config_admin_language_id'] = 1;

        foreach ($this->data['languages'] as $language)
        {
            if ($language['code'] == $this->config->get('config_admin_language'))
            {
                $this->data['config_admin_language_id'] = $language['language_id'];
                break;
            }
        }
    }
    
    public function index() {
        $this->data['__'] = $this->language->load('module/kuler_social_icons');

        $this->beforeBuildingMode();
        $this->getTabActive();
        $this->getLanguages();
        $this->getStores();

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->saveAction();
        }
        
        $this->getBase();
        $this->getErrors();
        $this->getPathways();
        $this->getResources();

        $this->data['token'] = $this->session->data['token'];
        $this->data['action'] = $this->url->link('module/kuler_social_icons', 'token=' . $this->session->data['token']);
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token']);
        
        $this->data['layouts'] = $this->getLayouts();
        $this->data['modules'] = $this->prepareModules($this->getModules());
        $this->data['moduleName'] = self::MODULE_NAME;

        $this->template = 'module/kuler_social_icons.phtml';

        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    private function prepareModules(array $modules)
    {
        foreach ($modules as &$module)
        {
            $module['module_title'] = $this->translate($module['module_title']);
            $module['main_title'] = $module['module_title'][$this->config->get('config_language_id')];
        }

        return $modules;
    }

    protected function beforeBuildingMode()
    {
        // Initialize building mode
        if (isset($this->request->get['ksb_module']))
        {
            $this->session->data['ksb_module'] = $this->request->request['ksb_module'];
            $this->session->data['ksb_building_mode'] = 1;
            $this->session->data['ksb_new'] = $this->request->request['ksb_new'];
            $this->session->data['ksb_token'] = $this->request->request['token'];

            if ($this->request->get['ksb_new'])
            {
                $this->data['ksb_trigger_creation'] = true;
            }
        }

        // Check building mode
        if (isset($this->session->data['ksb_building_mode']) && $this->session->data['ksb_token'] == $this->session->data['token'])
        {
            $this->data['ksb_building_mode'] = 1;

            $this->session->data['kuler_tab'] = '#tab-module-' . $this->session->data['ksb_module'];
        }
        else
        {
            unset(
            $this->session->data['ksb_module'],
            $this->session->data['ksb_building_mode'],
            $this->session->data['ksb_new'],
            $this->session->data['ksb_token']
            );
        }

        // Get the updated module
        if (isset($this->session->data['ksb_updated_module']))
        {
            $this->data['ksb_updated_module'] = $this->session->data['ksb_updated_module'];
            unset($this->session->data['ksb_updated_module']);
        }
    }

    protected function postBuildingMode($modules)
    {
        if (!isset($this->session->data['ksb_building_mode']))
        {
            return;
        }

        $module = array();

        if (isset($this->session->data['ksb_new']) && $this->session->data['ksb_new'])
        {
            $module = end($modules);
            $indexes = array_keys($modules);
            $module['index'] = array_pop($indexes);
            $this->session->data['ksb_module'] = $module['index'];
        }
        else
        {
            $module = $modules[$this->session->data['ksb_module']];
        }

        if (isset($module['module_title']))
        {
            $module['title'] = $module['module_title'];
        }

        $this->session->data['ksb_updated_module'] = json_encode(array(
            'status' => '1',
            'module' => $module
        ));
    }
    
    private function getTabActive() {
        if(isset($this->session->data['kuler_tab'])) {
            $this->data['tab'] = $this->session->data['kuler_tab']; unset($this->session->data['kuler_tab']);
        } else {
            $this->data['tab'] = '';
        }
        if(isset($this->request->post['tab'])) {
            $this->session->data['kuler_tab'] = $this->request->post['tab'];
        }
    }
    
    private function getBase() {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $this->data['base'] = HTTPS_CATALOG;
        } else {
            $this->data['base'] = HTTP_CATALOG;
        }
    }
    
    private function getErrors() {
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
    }
    
    private function getModules() {
		$modules = array();
		if (isset($this->request->post['kuler_social_icons'])) {
			$modules = $this->request->post['kuler_social_icons'];
		} else {
            $this->load->model('setting/setting');

            if ($kuler_social_icons = $this->model_setting_setting->getSetting('kuler_social_icons', $this->data['selected_store_id']))
            {
                if (isset($kuler_social_icons['kuler_social_icons_module']))
                {
                    $modules = $kuler_social_icons['kuler_social_icons_module'];
                }
            }
        }
        return $modules;
	}
    
    private function getLayouts() {
		$this->load->model('design/layout');
		$result = $this->model_design_layout->getLayouts();
		return $result;
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


    private function getResources() {
        $this->document->addStyle('view/kulercore/css/kulercore.css');
        $this->document->addStyle('view/kulercore/colorpicker/css/colorpicker.css');
        $this->document->addScript('view/kulercore/colorpicker/js/colorpicker.js');
    }
	
    private function getPathways() {
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/kuler_social_icons', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
    }

    private function getLanguages() {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_module'] = $this->language->get('text_module');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_showtitle'] = $this->language->get('entry_showtitle');
        $this->data['entry_shortcode'] = $this->language->get('entry_shortcode');

        $this->data['entry_icon_style'] = $this->language->get('entry_icon_style');
        $this->data['entry_icon_size'] = $this->language->get('entry_icon_size');
        $this->data['entry_facebook'] = $this->language->get('entry_facebook');
        $this->data['entry_twitter'] = $this->language->get('entry_twitter');
        $this->data['entry_google'] = $this->language->get('entry_google');
        $this->data['entry_pinterest'] = $this->language->get('entry_pinterest');
        $this->data['entry_rss'] = $this->language->get('entry_rss');
        $this->data['entry_icon_color'] = $this->language->get('entry_icon_color');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_close'] = $this->language->get('button_close');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_module'] = $this->language->get('button_add_module');
        $this->data['button_remove'] = $this->language->get('button_remove');
    }
  
    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/kuler_social_icons')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if($this->error) {
            return false;
        } else {
            return true;
        }
    }

	private function saveAction() {
        $this->load->model('setting/setting');

        $this->request->post['kuler_social_icons_module'] = isset($this->request->post['kuler_social_icons_module']) ? $this->request->post['kuler_social_icons_module'] : array();

        $data = array(
            'kuler_social_icons_module' => $this->request->post['kuler_social_icons_module']
        );

        $this->model_setting_setting->editSetting('kuler_social_icons', $data, $this->request->post['store_id']);
		$this->session->data['success'] = $this->language->get('text_success');

        $this->postBuildingMode($this->request->post['kuler_social_icons_module']);

        if(isset($this->request->post['op']) && $this->request->post['op'] == 'close') {
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->redirect($this->url->link('module/kuler_social_icons', 'token=' . $this->session->data['token']  . '&store_id=' . $this->request->post['store_id'], 'SSL'));
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

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $stores = $this->getStores();

        foreach ($stores as $store_id => $store_name)
        {
            $this->model_setting_setting->deleteSetting('kuler_social_icons', $store_id);
        }
    }
}