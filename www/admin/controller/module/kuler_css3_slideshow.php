<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

// Help load language

function __($text)
{
	// todo: write helper
	return $text;
}

class ControllerModuleKulerCss3Slideshow extends Controller {
	private $error = array();

	const MODULE_PREFIX = 'kuler_css3_slideshow_';
	const MODULE_NAME = 'kuler_css3_slideshow';
	
	public function __construct($registry)
	{
		parent::__construct($registry);

		$this->language->load('module/' . self::MODULE_NAME);

		$this->data['token'] = $this->session->data['token'];

		// fake language
		$this->data['text_module'] = 'Modules';
		$this->data['error_permission'] = '';

		$this->data['breadcrumbs'] = array(
			array(
	       		'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->data['token'], 'SSL'),
	      		'separator' => false
	   		),
   			array(
	       		'text'      => $this->data['text_module'],
				'href'      => $this->url->link('extension/module', 'token=' . $this->data['token'], 'SSL'),
	      		'separator' => '::'
   			),
		);

	}

	public function index()
	{
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->document->setTitle($this->data['heading_title']);
		
		$this->load->model('setting/setting');

        $this->beforeBuildingMode();
        $this->getTabActive();
        $this->getStores();
        
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate())
		{
			$modules = isset($this->request->post['modules'])? $this->request->post['modules'] : array();

            $languages = $this->getLanguages();

			foreach ($modules as &$module)
			{
                foreach ($languages as $language)
                {
                    if (empty($module['data'][$language['language_id']]['image_source']))
                    {
                        $module['data'][$language['language_id']] = current($module['data']);
                    }

                    if ($module['data'][$language['language_id']]['image_source'] == 'images')
                    {
                        if (empty($module['data'][$language['language_id']]['images']))
                        {
                            $module['data'][$language['language_id']]['images'] = array();
                        }
                        else
                        {
                            $module['data'][$language['language_id']]['images'] = $this->helperMultishort($module['data'][$language['language_id']]['images']);
                        }
                    }

                    // Fill empty field
                    $module['data'][$language['language_id']]['split'] = empty($module['data'][$language['language_id']]['split']) ? 10 : $module['data'][$language['language_id']]['split'];
                    $module['data'][$language['language_id']]['duration'] = empty($module['data'][$language['language_id']]['duration'])? 5000 : $module['data'][$language['language_id']]['duration'];
                }
			}

			// Prepare data
			$data = array(
				self::MODULE_PREFIX . 'module' => $modules
			);

			$this->model_setting_setting->editSetting(self::MODULE_NAME, $data, $this->request->post['store_id']);

			// Update banner images
			if (!empty($this->request->post['banner_images']))
			{
				$this->load->model('design/banner');
				
				$bannerImages = $this->request->post['banner_images'];

				foreach ($bannerImages as $moduleIndex => $groupedBannerImages)
				{
                    foreach ($groupedBannerImages as $language_id => $bannerImage)
                    {
                        $bannerImage = $this->helperMultishort($bannerImage);

                        $banner_id = $modules[$moduleIndex]['data'][$language_id]['banner'];
                        $data = array(
                            'banner_image' => $bannerImage
                        );

                        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "'");
                        $this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE banner_id = '" . (int)$banner_id . "'");

                        if (isset($data['banner_image'])) {
                            foreach ($data['banner_image'] as $banner_image) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image SET banner_id = '" . (int)$banner_id . "', link = '" .  $this->db->escape($banner_image['link']) . "', image = '" .  $this->db->escape($banner_image['image']) . "'");
                                $banner_image_id = $this->db->getLastId();

                                foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
                                    $this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image_id . "', language_id = '" . (int)$language_id . "', banner_id = '" . (int)$banner_id . "', title = '" .  $this->db->escape($banner_image_description['title']) . "'");
                                }
                            }
                        }
                    }
				}
			}

            // Kuler Site Builder
            $this->postBuildingMode($modules);

			$this->session->data['success'] = __('Success: You have modified the module Kuler CSS3 Slideshow.');

            if(isset($this->request->post['op']) && $this->request->post['op'] == 'close') {
                $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $this->redirect($this->url->link('module/kuler_css3_slideshow', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->post['store_id'], 'SSL'));
            }
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
		    $this->data['base'] = HTTPS_CATALOG;
		}
		else
		{
		    $this->data['base'] = HTTP_CATALOG;
		}

        $this->data['language_id'] = $this->config->get('config_language_id');
        $this->data['languages'] = $this->getLanguages();

		// Default objects
		$this->data['defaultModule'] = array(
			'title' => '',
			'show_title' => false,
			'layout_id' => '',
			'position' => 'content_top',
			'status' => 1,
			'data' => array(
			)
		);

        foreach ($this->data['languages'] as $language)
        {
            $this->data['defaultModule']['data'][$language['language_id']] = array(
	            'slideshow_type'    => 'split',
                'image_source' => '',
                'banner' => 0,
                'width' => '',
                'height' => '',
                'transition' => 1,
                'split' => 10,
                'autostart' => 1,
                'duration' => 5000,
                'images' => array(),
                'navigation' => true,
                'bullet' => true,
                'width' => 810,
                'height' => 300,
                'screen' => array(1024, 768, 480, 320, '', ''),
                'other_height' => array('', '', '', '', '', '')
            );
        }

		$this->data['defaultImage'] = array(
			'title' => '',
			'link' => '',
			'image' => '',
			'thumb' => '',
			'sort_order' => ''
		);

		// Get modules
        if (isset($this->request->post['modules']))
        {
            $modules = $this->request->post['modules'];
        }
        else
        {
            $modules = array();
            if ($kuler_css3_slideshow = $this->model_setting_setting->getSetting('kuler_css3_slideshow', $this->data['selected_store_id']))
            {
                if (isset($kuler_css3_slideshow['kuler_css3_slideshow_module']))
                {
                    $modules = $kuler_css3_slideshow['kuler_css3_slideshow_module'];
                }
            }
        }

		// Prepare images
		foreach ($modules as $moduleIndex => $module)
		{
            $modules[$moduleIndex]['title'] = $this->translate($modules[$moduleIndex]['title']);

            $modules[$moduleIndex]['data'] = array();

            foreach ($this->data['languages'] as $language)
            {
                if (isset($module['data'][$language['language_id']]))
                {
                    $data = $module['data'][$language['language_id']];
                }
                else
                {
                    $current_data = current($module['data']);

                    if (is_array($current_data))
                    {
                        $data = $current_data;
                    }
                    else
                    {
                        $data = $module['data'];
                    }
                }

                // Check screens
                for ($i = 0; $i < 6; $i++)
                {
                    if (!isset($data['screen']))
                    {
                        $data['screen'][$i] = '';
                        $data['other_height'][$i] = '';
                    }
                }

                if (!empty($data['images']))
                {
                    foreach ($data['images'] as $index => $image)
                    {
                        // Convert to absolute link of the image
                        $data['images'][$index]['thumb'] = $image['image'];

                        if (!preg_match('#^http#', $data['images'][$index]['image']))
                        {
                            $data['images'][$index]['thumb'] = $this->data['base'] . 'image/' . $image['image'];
                        }
                    }
                }

                $modules[$moduleIndex]['data'][$language['language_id']] = $data;
            }
		}

		$this->data['modules'] = $modules;

        // Error & Warning
 		if (isset($this->error['warning']))
 		{
			$this->data['error_warning'] = $this->error['warning'];
		}
		else
		{
			$this->data['error_warning'] = '';
		}

        $this->data['errorImageSource'] = isset($this->error['imageSource'])? $this->error['imageSource'] : array();
        $this->data['error_dimension'] = isset($this->error['dimension'])? $this->error['dimension'] : array();

        if ($this->error)
        {
            $this->data['error_warning'] = $this->language->get('error_warning');
        }

   		$this->addBreadcrumb(array(
   			array(
	       		'text'      => $this->data['heading_title'],
				'href'      => $this->url->link('module/' . self::MODULE_NAME, 'token=' . $this->data['token'], 'SSL'),
	      		'separator' => ' :: '
   			)
   		));
		
		$this->data['action'] = $this->url->link('module/' . self::MODULE_NAME, 'token=' . $this->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->data['token'], 'SSL');
		$this->data['fetchBannerUrl'] = $this->url->link('module/' . self::MODULE_NAME . '/fetchbanner', 'token=' . $this->data['token'], 'SSL');
		$this->data['fetchBannerUrl'] = str_replace('&amp;', '&', $this->data['fetchBannerUrl']);		
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('design/banner');
		$this->data['banners'] = $this->model_design_banner->getBanners();

        $this->data['moduleName'] = self::MODULE_NAME;

		// Kuler assets
		$this->document->addStyle('view/kulercore/css/kulercore.css');

		$this->template = 'module/'. self::MODULE_NAME .'.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	public function fetchBanner()
	{
		$bannerId = $this->request->get['banner_id'];

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		$banner = $this->model_design_banner->getBanner($bannerId);
		$images = $this->getBannerImages($this->request->get['banner_id']);

		// Prepare images
		$result = array(
			'images' => array()
		);

		foreach ($images as $index => $image)
		{
			$result['images'][] = array(
				'titles' => $image['banner_image_description'],
				'image' => $image['image'],
				'thumb' => $this->model_tool_image->resize($image['image'], 100, 100),
				'link' => $image['link'],
				'sort_order' => $index + 1
			);
		}

		echo json_encode($result); exit;
	}

    private function getLanguages()
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

	private function addBreadcrumb(array $breadcrumbs)
	{
		foreach ($breadcrumbs as $breadcrumb)
		{
			$this->data['breadcrumbs'][] = $breadcrumb;			
		}
	}

	/**
	 * Overide the method beacause of wrong sort order
	 * @return [array]
	 */
	private function getBannerImages($banner_id) {
		$banner_image_data = array();

		$banner_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE banner_id = '" . (int)$banner_id . "' ORDER BY banner_image_id ASC");
		
		foreach ($banner_image_query->rows as $banner_image) {
			$banner_image_description_data = array();
			 
			$banner_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "' AND banner_id = '" . (int)$banner_id . "'");
			
			foreach ($banner_image_description_query->rows as $banner_image_description) {			
				$banner_image_description_data[$banner_image_description['language_id']] = array('title' => $banner_image_description['title']);
			}
		
			$banner_image_data[] = array(
				'banner_image_description' => $banner_image_description_data,
				'link'                     => $banner_image['link'],
				'image'                    => $banner_image['image']	
			);
		}
		
		return $banner_image_data;
	}

	/**
	 * Sort the items by sort order
	 * @param  array  $items Format: [key] => array('sort_order' => {number}, ...)
	 * @return array        sorted array
	 */
	private function helperMultishort(array $items)
	{
		$sortOrder = array();
		foreach ($items as $key => $value)
		{
		    $sortOrder[$key] = $value['sort_order'];
		}
		array_multisort($sortOrder, SORT_ASC, $items);

		return $items;
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/'. self::MODULE_NAME))
		{
			$this->error['warning'] = __('Warning: You do not have permission to modify module Kuler CSS3 Slideshow!');
		}

        if (isset($this->request->post['modules']))
        {
            $modules = $this->request->post['modules'];
            $languages = $this->getLanguages();

            foreach ($modules as $index => &$module)
            {
                foreach ($languages as $language)
                {
                    // Dimensions
                    if (!empty($module['data'][$language['language_id']]['image_source']) && empty($module['data'][$language['language_id']]['height']) || ($module['data'][$language['language_id']]['dimension'] == 'fixed' && empty($module['data'][$language['language_id']]['width'])))
                    {
                        $this->error['dimension'][$index][$language['language_id']] = __('Width &amp; Height dimensions required!');
                    }
                }
            }

            $this->request->post['modules'] = $modules;
        }

		if (!$this->error)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
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

            $this->session->data['kuler_tab'] = '#TabModule_' . $this->session->data['ksb_module'];
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
        if(isset($this->session->data['kuler_tab'])) {
            $this->data['tab'] = $this->session->data['kuler_tab']; unset($this->session->data['kuler_tab']);
        } else {
            $this->data['tab'] = '';
        }
      
        // Store current active tab
        if(isset($this->request->post['tab'])) {
            $this->session->data['kuler_tab'] = $this->request->post['tab'];
        }
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

    public function uninstall()
    {
        $this->load->model('setting/setting');

        $stores = $this->getStores();

        foreach ($stores as $store_id => $store_name)
        {
            $this->model_setting_setting->deleteSetting('kuler_css3_slideshow', $store_id);
        }
    }

    private function translate($texts)
    {
        $languages = $this->getLanguages();

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
?>