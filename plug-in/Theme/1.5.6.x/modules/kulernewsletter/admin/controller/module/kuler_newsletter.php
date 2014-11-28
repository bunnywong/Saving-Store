<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ControllerModuleKulerNewsletter extends Controller {
	const MODULE_NAME = 'kuler_newsletter';
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
        $this->load->model('module/kuler_newsletter');
		$this->load->model('localisation/language');
        $this->load->model('setting/setting');

        $this->beforeBuildingMode();
        $this->getTabActive();

		$this->getLanguages();
		$this->getPathways();
        $this->getStores();

        $this->getResources();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$task = isset($this->request->post['task']) ? $this->request->post['task'] : null;
            if ($task && method_exists($this, $task)) {
                return $this->{$task}();
            } else {
                $this->saveAction();
            }
		}

		$this->getErrors();
		
		$this->data['action'] = $this->url->link('module/kuler_newsletter', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

        $this->data['default_module'] = $this->getDefaultModule();

		$this->data['modules'] = array();
		
		if (isset($this->request->post['kuler_newsletter_module'])) {
			$this->data['modules'] = $this->request->post['kuler_newsletter_module'];
		} else {
            $this->load->model('setting/setting');

            if ($kuler_newsletter = $this->model_setting_setting->getSetting('kuler_newsletter', $this->data['selected_store_id']))
            {
                if (isset($kuler_newsletter['kuler_newsletter_module']))
                {
                    $this->data['modules'] = $kuler_newsletter['kuler_newsletter_module'];
                }
            }
        }

        $this->data['modules'] = $this->prepareModules($this->data['modules']);

        // Get mailchimp list foreach module
        if($this->data['modules'] && is_array($this->data['modules'])) {
            $cache = $this->config->get('kuler_newsletter_cache');
            $cache = is_array($cache) ? $cache : array();
            foreach($this->data['modules'] as $row =>& $module) {
                if(isset($module['icontact']['key']) && $module['icontact']['key'] && isset($cache[$module['icontact']['key']])) {
                    $module['icontact']['lists'] = $cache[$module['icontact']['key']];
                } else {
                    $module['icontact']['lists'] = array();
                }
                
                if(isset($module['mailchimp']['key']) && $module['mailchimp']['key'] && isset($cache[$module['mailchimp']['key']])) {
                    $module['mailchimp']['lists'] = $cache[$module['mailchimp']['key']];
                } else {
                    $module['mailchimp']['lists'] = array();
                }

                if(isset($module['aweber']['key']) && $module['aweber']['key'] && isset($cache[$module['aweber']['key']])) {
                    $module['aweber']['lists'] = $cache[$module['aweber']['key']];
                } else {
                    $module['aweber']['lists'] = array();
                }
            }
        }
       
		$this->data['layouts'] = $this->getLayouts();
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		$this->data['moduleName'] = self::MODULE_NAME;

		$this->template = 'module/kuler_newsletter.phtml';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

    private function prepareModules(array $modules)
    {
        $default_module = $this->getDefaultModule();

        foreach ($modules as &$module)
        {
            $module['module_title'] = $this->translate($module['module_title']);
            $module['pre_text'] = $this->translate($module['pre_text']);
            $module['email_text'] = $this->translate($module['email_text']);

            if (!isset($module['subscription_success_message']))
            {
                $module['subscription_success_message'] = $default_module['subscription_success_message'];
            }
            else
            {
                $module['subscription_success_message'] = $this->translate($module['subscription_success_message']);
            }

            $module['main_title'] = $module['module_title'][$this->config->get('config_language_id')];
        }

        return $modules;
    }

    private function getDefaultModule()
    {
        $subscription_success_message = array();

        $languages = $this->getLanguageOptions();

        foreach ($languages as $language)
        {
            $subscription_success_message[$language['language_id']] = $this->language->get('text_subscription_success_message');
        }

        return array(
            'subscription_success_message' => $subscription_success_message
        );
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/kuler_newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->post['kuler_newsletter_module'])) {
			foreach ($this->request->post['kuler_newsletter_module'] as $key => $value) {
                $valid = true;
                $type  = $value['type'];
				if (empty($value[$type]['key']) || strlen($value[$type]['key']) < 5) {
					$this->error['key'][$type][$key] = $this->language->get('error_key');
                    $this->data['vtab'] = '#tab-module-' . $key;
                    $this->data['htab'] = '#service-' . $type . '-' . $key;
                    $valid = false;
				}
                if (empty($value['button_text']) || strlen($value['button_text']) < 2) {
					$this->error['button_text'][$key] = $this->language->get('error_button_text');
                    $this->data['vtab'] = '#tab-module-' . $key;
                    $valid = false;
				}
                if($valid == false) {
                    return false;
                }
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
		
	protected function saveAction() {
        $this->request->post['kuler_newsletter_module'] = isset($this->request->post['kuler_newsletter_module']) ? $this->request->post['kuler_newsletter_module'] : array();

        $data = array(
            'kuler_newsletter_module' => $this->request->post['kuler_newsletter_module']
        );

		$this->model_setting_setting->editSetting('kuler_newsletter', $data, $this->request->post['store_id']);
		$this->session->data['success'] = $this->language->get('text_success');

        $this->postBuildingMode($this->request->post['kuler_newsletter_module']);

        if(isset($this->request->post['op']) && $this->request->post['op'] == 'close') {
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->redirect($this->url->link('module/kuler_newsletter', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->post['store_id'], 'SSL'));
        }
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

            $this->session->data['kuler_vtab'] = '#tab-module-' . $this->session->data['ksb_module'];
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
	
    protected function getTabActive() {
        // Remove last active tab
        if(isset($this->session->data['kuler_vtab'])) {
            $this->data['vtab'] = $this->session->data['kuler_vtab']; unset($this->session->data['kuler_vtab']);
        } else {
            $this->data['vtab'] = '';
        }
        if(isset($this->session->data['kuler_htab'])) {
            $this->data['htab'] = $this->session->data['kuler_htab']; unset($this->session->data['kuler_htab']);
        } else {
            $this->data['htab'] = '';
        }
        // Store current active tab
        if(isset($this->request->post['vtab'])) {
            $this->session->data['kuler_vtab'] = $this->request->post['vtab'];
        }
        if(isset($this->request->post['htab'])) {
            $this->session->data['kuler_htab'] = $this->request->post['htab'];
        }
    }
    
	protected function getErrors() {
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = array();
		}
        
        if (isset($this->error['button_text'])) {
			$this->data['error_button_text'] = $this->error['button_text'];
		} else {
			$this->data['error_button_text'] = array();
		}
	}
	
    protected function getListAjax() {
        $list = array();
        
        $key = $this->request->post['key'];
        $type = $this->request->post['type'];
        
        if($type == 'mailchimp') {
            $list = $this->getMailchimpList($key);
        } else if($type == 'icontact') {
            $user = $this->request->post['username'];
            $pass = $this->request->post['password'];
            $list = $this->getiContactList($key, $user, $pass); 
        } else if($type == 'aweber') {
            $secret = $this->request->post['secret'];
            $list = $this->getAweberList($key, $secret);
        }

        $result = array(
            'status' => 0,
            'cotnent' => '',
        );
        
        if($list) {
            $content = '';
            foreach($list as $item) {
                $content .= '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
            }
            $result['status'] = 1;
            $result['content'] = $content;
        }
        
        header('Content-type: application/json');
        echo json_encode($result);
        die();
    }
    
    protected function getListCache() {
        $key = $this->request->post['key'];
        $cache = $this->config->get('kuler_newsletter_cache');
        $cache = is_array($cache) ? $cache : array();
        $result = array(
            'status' => 0,
            'cotnent' => '',
        );
        if(isset($cache[$key])) {
            $list = $cache[$key];
            $content = '';
            foreach($list as $item) {
                $content .= '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
            }
            $result['status'] = 1;
            $result['content'] = $content;
        }
        header('Content-type: application/json');
        echo json_encode($result);
        die();
    }
    
    protected function getAweberList($key, $secret) {
        
    }
    
    protected function getMailchimpList($key) {
        $api = new MCAPI($key);
        if($api) {
            $result = $api->lists();
            if(isset($result['data'])) {
                // Store to cache
                $cache = $this->config->get('kuler_newsletter_cache');
                $cache = is_array($cache) ? $cache : array();
                
                $list = array();
                
                foreach($result['data'] as $item) {
                    $list[] = array(
                        'id' => $item['id'],
                        'name' => $item['name']
                    );
                }

                $cache[$key] = $list;
                
                $this->model_setting_setting->editSetting('kuler_newsletter_cache', array('kuler_newsletter_cache' => $cache));

                return $list;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    protected function getiContactList($key, $user, $pass) {
        // Give the API your information
        iContactApi::getInstance()->setConfig(array(
            'appId' => $key,
            'apiUsername' => $user,
            'apiPassword' => $pass,
        ));

        // Store the singleton
        $oiContact = iContactApi::getInstance();
        
        $result = $oiContact->getLists();
        
        if($result) {
            // Store to cache
            $cache = $this->config->get('kuler_newsletter_cache');
            $cache = is_array($cache) ? $cache : array();

            $list = array();

            foreach($result as $item) {
                $list[] = array(
                    'id' => $item->listId,
                    'name' => $item->name
                );
            }

            $cache[$key] = $list;

            $this->model_setting_setting->editSetting('kuler_newsletter_cache', array('kuler_newsletter_cache' => $cache));

            return $list;
        } else {
            return array();
        }
    }
    
	protected function getResources() {
		$this->document->addStyle('view/kulercore/css/kulercore.css');
	}
	
	protected function getLayouts() {
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


    protected function getLanguages() {
		$this->data['__'] = $this->language->load('module/kuler_newsletter');
		$this->document->setTitle($this->language->get('heading_title'));

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
        $this->data['entry_pretext'] = $this->language->get('entry_pretext');
		$this->data['entry_email_text'] = $this->language->get('entry_email_text');
        $this->data['entry_subscription_success_message'] = $this->language->get('entry_subscription_success_message');
		$this->data['entry_email_width'] = $this->language->get('entry_email_width');
		$this->data['entry_button_text'] = $this->language->get('entry_button_text');
		$this->data['entry_shortcode'] = $this->language->get('entry_shortcode');
        
        $this->data['entry_service'] = $this->language->get('entry_service');
        $this->data['entry_api'] = $this->language->get('entry_api');
        $this->data['entry_list_id'] = $this->language->get('entry_list_id');
        
        $this->data['entry_username'] = $this->language->get('entry_username');
        $this->data['entry_password'] = $this->language->get('entry_password');
		
		$this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_close'] = $this->language->get('button_close');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
	}
	
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
			'href'      => $this->url->link('module/kuler_newsletter', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
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
            $this->model_setting_setting->deleteSetting('kuler_newsletter', $store_id);
        }
    }
}
?>