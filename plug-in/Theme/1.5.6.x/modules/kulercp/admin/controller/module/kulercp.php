<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

function _t($text, $placeholder = '')
{
    return ControllerModuleKulercp::__($text, $placeholder);
}

/**
 * Class ControllerModuleKulercp
 * @property Language $language
 * @property Response $response
 * @property Document $document
 * @property Session $session
 */
class ControllerModuleKulercp extends Controller {
    const MODE = 'PRODUCTION';

	const TEMPORARY_COLOR_SCHEME_KEY = 'kcp_temporary_color_scheme_css';

	public static $VERSION_TEXT = '1.0.1';

    public static $__ = array();
    public static $lost_texts = array();

    private $options = array(); // Current theme options : color, font...
	private $error = array();
    private $vtab;
    private $htab;

    private $stylePath = '';

    /* @var ModelKulerCp */
    private $model;

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->load->model('kuler/cp');
        $this->model = $this->model_kuler_cp;

        $this->data['token'] = $this->session->data['token'];

        self::$__ = $this->getLanguages();

        $this->update();
    }

    public function index() {
        $this->data['__'] = $this->language->load('module/kulercp');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('design/layout');

        $this->data['stores'] = $this->getStores();
        $this->data['selected_store_id'] = $this->getSelectedStore();
        $this->overrideStoreSetting($this->data['selected_store_id']);

        $this->data['catalog_base'] = $this->getCatalogUrl();
        $this->getTabActive();
        $this->getOptions();

        $this->tabDesign();
        $this->tabCompression();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate()))
        {

            $task = isset($this->request->post['t']) ? $this->request->post['t'] : null;

            // Check for module task aciton
            if ($task && method_exists($this, $task) && $task != 'sitemapGenerate')
            {
                return $this->{$task}();
            }
            else
            {
                if ($task == 'sitemapGenerate')
                {
                    $this->sitemapGenerate();
                }

                $this->saveAction();
            }
        }

        $this->data['block']    = array();
        $this->data['optimal']  = array();
        $this->data['seo']      = array();
        $this->data['font']     = array();

        if (isset($this->request->post['block']))
        {
            $this->data['block'] = $this->request->post['block'];
        }
        else
        {
            if ($this->config->get('blocks'))
            {
                $this->data['block'] = $this->config->get('blocks');

                // Update title for each language if title is string
                $languages = $this->getDataLanguages();
                $this->data['block']['contact']['title'] = $this->translate($this->data['block']['contact']['title']);
                $this->data['block']['twitter']['title'] = $this->translate($this->data['block']['twitter']['title']);
                $this->data['block']['facebook']['title'] = $this->translate($this->data['block']['facebook']['title']);

                // Fill empty field in the information
                $first_info = array();
                foreach ($this->data['block']['info'] as $info_index => $info_value)
                {
                    if ($info_index != 'status')
                    {
                        $first_info = $info_value;
                        break;
                    }
                }

                foreach ($languages as $language)
                {
                    if (!isset($this->data['block']['info'][$language['language_id']]))
                    {
                        $this->data['block']['info'][$language['language_id']] = array();
                    }

                    foreach ($first_info as $key => $value)
                    {
                        if (empty($this->data['block']['info'][$language['language_id']][$key]))
                        {
                            $this->data['block']['info'][$language['language_id']][$key] = $value;
                        }
                    }
                }
            }
            else
            {
                $this->data['block'] = array(
                    'info' => array(
                        'status' => 0,
                    ),
                    'contact' => array(
                        'status' => 0,
                        'title' => '',
                        'skype' => array(
                            'status' => 0,
                            'name1' => '',
                            'name2' => ''
                        ),
                        'email' => array(
                            'status' => 0,
                            'name1' => '',
                            'name2' => ''
                        ),
                        'mobile' => array(
                            'status' => 0,
                            'name1' => '',
                            'name2' => ''
                        ),
                        'phone' => array(
                            'status' => 0,
                            'name1' => '',
                            'name2' => ''
                        ),
                        'fax' => array(
                            'status' => 0,
                            'name1' => '',
                            'name2' => ''
                        )
                    ),
                    'twitter' => array(
                        'status' => 0,
                        'title' => '',
                        'embed_code' => '',
                        'theme' => 'light',
                        'width' => '',
                        'height' => '',
                        'link_color' => '',
                        'background' => 1,
                        'border' => 0,
                        'border_color' => '',
                        'header' => 0,
                        'footer' => 0,
                        'scrollbar' => 1,
                        'number' => 2
                    ),
                    'facebook' => array(
                        'status' => 0,
                        'title' => '',
                        'page' => '',
                        'theme' => 'light',
                        'width' => '',
                        'height' => ''
                    ),
                    'payment' => array(
                        'status' => 0,
                        'items' => array()
                    ),
                    'copyright' => array(
                        'status' => 1,
                        'content' => ''
                    )
                );

                $languages = $this->getDataLanguages();
                foreach ($languages as $language_code => $language)
                {
                    $this->data['block']['info'][$language['language_id']] = array(
                        'title' => '',
                        'description' => ''
                    );
                }

                $payments = $this->getPayments();
                foreach ($payments as $payment)
                {
                    $this->data['block']['payment']['items'][$payment['value']] = array(
                        'status' => 0,
                        'image' => '',
                        'thumb' => ''
                    );
                }
            }
        }

        // Prepare block payment
        if (is_array($this->data['block']) && !empty($this->data['block']['payment']))
        {
            $payments = $this->data['block']['payment'];

            $server = $this->data['catalog_base'];

            foreach ($payments['items'] as &$item)
            {
                if (!empty($item['image']))
                {
                    $item['thumb'] = $server . $item['image'];
                }
                else
                {
                    $item['thumb'] = '';
                }
            }

            $this->data['block']['payment'] = $payments;
        }

        if (isset($this->request->post['optimal'])) {
            $this->data['optimal'] = $this->request->post['optimal'];
        }
        else
        {
            if ($this->config->get('optimal'))
            {
                $this->data['optimal'] = $this->config->get('optimal');
            }
            else
            {
                $this->data['optimal'] = array(
                    'style_theme' => 0,
                    'script_theme' => 0
                );
            }
        }

        if (isset($this->request->post['seo'])) {
            $this->data['seo'] = $this->request->post['seo'];
        }
        else
        {
            if ($this->config->get('seo'))
            {
                $this->data['seo'] = $this->config->get('seo');
            }
            else
            {
                $this->data['seo'] = array(
                    'sitemap_filetype' => 'xml',
                    'sitemap_notify' => 0,
                    'sitemap_robots' => 0,
                    'sitemap_modification' => 0,
                    'sitemap_stylesheet' => 0,
                    'sitemap_limit' => 30,
                    'sitemap_filename' => '',
                    'sitemap_url' => '',

                    // Google Analytics
                    'status' => 0,
                    'position' => 'head'
                );
            }
        }

        if ($this->config->get('color'))
        {
            $this->data['color'] = $this->config->get('color');
        }
        else
        {
            $colors = $this->getColors();
            $this->data['color'] = array(
                'status' => key($colors)
            );
        }

	    // Prepare settings
	    $settings = $this->config->get('kuler_cp_settings');

	    if (!is_array($settings))
	    {
		    $settings = array();
	    }

	    $this->preset($settings);

	    // Custom CSS
		$this->customCss($settings);

	    // Sample Data
	    $this->sampleData($settings);

	    /* REFACTORING */
	    $this->convertThemeOptionsToNew($this->options);

	    $this->load->model('setting/setting');
		$new_settings = $this->model_setting_setting->getSetting('kulercp', $this->data['selected_store_id']);

	    $this->convertOldSettingToNew($new_settings);

	    $this->themeColor($new_settings);
	    $this->colorScheme($new_settings);
	    /* end REFACTORING */

        $this->data['fonts'] = $this->getGoogleFonts();
        $this->data['colors'] = $this->getColors();
        $this->data['payments'] = $this->getPayments();
        $this->data['collections'] = $this->model_kuler_cp->getCollections();

        $this->getFont();
        $this->getErrors();
        $this->getPathways();
        $this->getLanguages();
        $this->getResources();
        $this->getTemplates();
        $this->getSitemaps();

        $this->data['action'] = $this->url->link('module/kulercp', 'token=' . $this->session->data['token']);
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token']);
        $this->data['install_url'] = $this->url->link('module/kulercp/installsampledata', 'token=' . $this->session->data['token']);

        $this->data['token'] = $this->session->data['token'];

        $this->data['languages'] = $this->getDataLanguages();

        $this->data['base'] = $this->getCatalogUrl();
 
        $this->template = 'module/kulercp.phtml';

        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    private function tabDesign()
    {
        $catalog_base = $this->getCatalogUrl();

        if (isset($this->request->post['design']))
        {
            $design = $this->request->post['design'];
        }
        else
        {
            if ($this->config->get('kulercp_design'))
            {
                $design = $this->config->get('kulercp_design');
            }
            else
            {
                $design = array();
            }
        }

        $colors = isset($design['colors']) ? $design['colors'] : array();
        $grouped_design = array();

        $design_options = isset($this->options['design']) ? $this->options['design'] : array();
        foreach ($design_options as $section_label => $section_properties)
        {
            $grouped_design[$section_label] = array(
                'title'=> _t('text_' . $section_label)
            );

            $properties = array();

            foreach ($section_properties as $section_property => $property_type)
            {
                $property = $section_label . '_' . $section_property;

                $properties[$property] = array(
                    'title' => _t('text_' . $section_property),
                    'type' => $property_type,
                    'value' => isset($colors[$property]) ? $colors[$property] : ''
                );

                if ($property_type == 'image')
                {
                    $properties[$property]['thumb'] = $properties[$property]['value'] ? $catalog_base . $properties[$property]['value'] : '';
                }
            }

            $grouped_design[$section_label]['properties'] = $properties;
        }

        $this->data['design'] = $design;
        $this->data['design_sections'] = $grouped_design;
    }

	protected function sampleData(array $settings)
	{
		$this->data['sample_data_downloadable']                 = version_compare(VERSION, '1.5.5.1', '==');

		$this->data['sample_data_download_url']                 = $this->helperLink('module/kulercp/sampleDataDownloadUrl');
		$this->data['sample_data_check_modules_url']            = $this->helperLink('module/kulercp/sampleDataCheckModules');
		$this->data['sample_data_upload_installation_url']      = $this->helperLink('module/kulercp/sampleDataInstall');
	}

	public function sampleDataDownloadUrl()
	{
		$json = array();

		if ($sample_data_base_url = $this->sampleDataGetBaseDataUrl())
		{
			$json['status']         = 1;
			$json['download_url']   = $sample_data_base_url . "index.php?route=module/kuler_sitetools/getsampledataurl&did=" . md5(uniqid());
		}
		else
		{
			$json['status']         = 0;
			$json['message']        = _t('error_invalid_template');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function sampleDataCheckModules()
	{
		$this->language->load('module/kulercp');

		$json = array();
		$demo_url = $this->sampleDataGetBaseDataUrl();

		try
		{
			if (!$demo_url)
			{
				throw new Exception($this->language->get('error_invalid_template'));
			}

			// Get required modules from the demo site
			$module_url = $demo_url . 'index.php?route=module/kuler_sitetools/modules';

			$ch = curl_init($module_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$content = curl_exec($ch);
			curl_close($ch);

			$demo_response = json_decode($content, true);
			$kuler_modules = $demo_response['modules'];

			// Check modules
			$json = array(
				'modules' => array(),
				'installable' => 1,
				'status' => 1
			);

			$path = DIR_APPLICATION . 'controller/module/%s.php';

			foreach ($kuler_modules as $module => $title)
			{
				$json['modules'][$module] = array();

				if (!file_exists(sprintf($path, $module)))
				{
					$json['installable'] = 0;
					$json['modules'][$module]['status'] = 0;
				}
				else
				{
					$json['modules'][$module]['status'] = 1;
				}

				$json['modules'][$module]['title'] = $title;
			}

			if (!$json['installable'])
			{
				$json['status'] = 0;
				$json['message'] = $this->language->get('error_missing_module');
			}
		}
		catch (Exception $e)
		{
			$json = array(
				'status' => 0,
				'message' => $e->getMessage()
			);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function sampleDataInstall()
	{
		try
		{
			$this->language->load('module/kulercp');

			if ($this->request->server['REQUEST_METHOD'] != 'POST' || !$this->user->hasPermission('modify', 'module/kulercp'))
			{
				exit($this->language->get('error_permission'));
			}

			if (!empty($this->request->files['installation']['name']))
			{
				if (strrchr($this->request->files['installation']['name'], '.') != '.zip')
				{
					throw new Exception($this->language->get('error_sample_data'));
				}

				if ($this->request->files['installation']['error'] != UPLOAD_ERR_OK)
				{
					throw new Exception($this->request->files['installation']['error']);
				}
			}
			else
			{
				throw new Exception($this->language->get('error_sample_data'));
			}

			$oc_path = dirname(DIR_APPLICATION) . DIRECTORY_SEPARATOR;
			$upload_file = DIR_DOWNLOAD . 'kcp_tmp' . DIRECTORY_SEPARATOR;

			if (!is_dir($upload_file))
			{
				mkdir($upload_file, 0777, true);
			}

			$upload_file .= 'sample-data.zip';
			move_uploaded_file($this->request->files['installation']['tmp_name'], $upload_file);

			if (file_exists($upload_file))
			{
				// Extract upload zip to root
				$zip = new ZipArchive();

				if (!is_resource(zip_open($upload_file)))
				{
					throw new Exception($this->language->get('error_sample_data_open_upload'));
				}

				$zip->open($upload_file);

				if (!$zip->extractTo($oc_path))
				{
					throw new Exception($this->language->get('error_sample_data'));
				}

				$zip->close();

				// Backup db
				$this->load->model('kuler/cp');
				$model = $this->model_kuler_cp;
				/* @var ModelKulerCp $model */

				$backup_sql = $model->exportBeforeInstallSampleData();

				// Replace with downloaded database
				$this->load->model('tool/backup');
				$sql = file_get_contents(DIR_DOWNLOAD . 'kst_data' . DIRECTORY_SEPARATOR . 'db.sql');

				// Replace the table's prefix
				preg_match('#`(.+?)address`;#', $sql, $matches);
				$sql = str_replace($matches[1], DB_PREFIX, $sql);

				$this->model_tool_backup->restore($sql);

				// Restore db
				$model->restoreAfterInstallSampleData();
				$this->model_tool_backup->restore($backup_sql);

				unlink($upload_file);
				unlink(DIR_DOWNLOAD . 'kst_data' . DIRECTORY_SEPARATOR . 'db.sql');

				$this->session->data['sample_data_success'] = $this->language->get('text_sample_data_installed_successfully');
			}
		}
		catch (Exception $e)
		{
			$this->session->data['sample_data_error'] = $e->getMessage();
		}

		$this->redirect($this->url->link('module/kulercp', 'token=' . $this->session->data['token']));
	}

	protected function convertOldSettingToNew(array &$settings)
	{
		// Theme color
		if (empty($settings['kuler_cp_theme_color']))
		{
			$settings['theme_color'] = isset($settings['color']) ? $settings['color']['status'] : '';
		}

		$new_settings = array();
		foreach ($settings as $setting_key => $setting_value)
		{
			$new_settings['kuler_cp_' . $setting_key] = $setting_value;
		}
	}

	protected function convertThemeOptionsToNew(array &$theme_options)
	{
		// Color
		$colors = array();

		foreach ($theme_options['color'] as $color_value => $color)
		{
			if (!is_array($color))
			{
				$colors[$color_value] = array(
					'name' => $color,
					'scheme' => array()
				);
			}
			else
			{
				$colors[$color_value] = $color;
			}
		}

		$theme_options['color'] = $colors;

		// Design
		if (empty($theme_options['design']))
		{
			$theme_options['design'] = array();
		}
	}

	protected function themeColor(array $settings)
	{
		$theme_options = $this->options;

		// Color Options
		$color_options = array(
			'custom' => _t('tc_text_custom')
		);

		if (!empty($theme_options['color']))
		{
			foreach ($theme_options['color'] as $color_value => $color)
			{
				$color_options[$color_value] = $color['name'];
			}
		}

		// Theme Color
		if (empty($settings['kuler_cp_theme_color']))
		{
			$theme_color = current(array_keys($color_options));
		}
		else
		{
			$theme_color = $settings['kuler_cp_theme_color'];
		}

		$this->data['theme_color'] = $theme_color;
		$this->data['theme_color_options'] = $color_options;
	}

	protected function themeColorPostSave(array &$settings)
	{
		$settings['kuler_cp_theme_color'] = $this->request->post['theme_color'];
	}

	protected function colorScheme(array $settings)
	{
		$theme_options = $this->options;

		// Schemes
		$schemes = array();
		foreach ($theme_options['color'] as $color_value => $color)
		{
			$schemes[$color_value] = $color['scheme'];
		}

		// Custom Scheme
		$custom_scheme = array();
		if (empty($settings['kuler_cp_custom_scheme']))
		{
			foreach ($this->options['design'] as $element_key => $element)
			{
				foreach ($element as $prop_key => $prop)
				{
					$custom_scheme[$element_key . '_' . $prop_key] = '';
				}
			}
		}
		else
		{
			$custom_scheme = $settings['kuler_cp_custom_scheme'];
		}

		$schemes['custom'] = $custom_scheme;

		// Element Definition
		$elements = $this->options['design'];

		$this->data['cs_schemes'] = $schemes;
		$this->data['cs_custom_scheme'] = $custom_scheme;
		$this->data['cs_elements'] = $elements;
	}

	protected function colorSchemeBeforeSave(array &$settings)
	{
		$settings['kuler_cp_custom_scheme'] = isset($this->request->post['color_scheme_custom']) ? $this->request->post['color_scheme_custom'] : array();
	}

	protected function colorSchemePostSave(array $settings)
	{
		$this->compileCustomCss($settings['kuler_cp_custom_scheme'], $this->request->post['store_id']);
	}

	protected function preset(array $settings)
	{
		if (empty($this->options['presets']))
		{
			$presets = array();
		}
		else
		{
			$presets = $this->options['presets'];
		}

		$presets['default'] = 'Default';

		ksort($presets);

		$preset = isset($settings['preset']) ? $settings['preset'] : '';

		foreach ($presets as $preset_file => $preset_name)
		{
			if ($preset_file != 'default' && !$this->model->presetIsFile($preset_file))
			{
				unset($presets[$preset_file]);
			}
		}

		$this->data['preset_presets']   = $presets;
		$this->data['preset_preset']    = $preset;
	}

	protected function presetPostSave(array &$settings)
	{
		$preset = isset($this->request->post['preset']) ? $this->request->post['preset'] : array();

		$settings['preset'] = $preset;
	}

	protected function customCss(array $settings)
	{
		// Default
		if (empty($settings['custom_css']))
		{
			$settings['custom_css'] = array();
		}

		$settings['custom_css'] = array_merge(array(
			'status'        => 0,
			'group'         => 'custom',
			'current_file'  => ''
		), $settings['custom_css']);

		$custom_css = $settings['custom_css'];

		$this->data['custom_css']               = $custom_css;

		// Urls
		$this->data['custom_css_save_url']      = $this->helperLink('module/kulercp/customCssSave');
		$this->data['custom_css_remove_url']    = $this->helperLink('module/kulercp/customCssRemove');
		$this->data['custom_css_load_url']      = $this->helperLink('module/kulercp/customCssLoad');

		$this->data['custom_css_files']         = $this->model->customCssGetFiles();
		$this->data['custom_css_presets']       = $this->model->customCssGetFiles('presets/');
		$this->data['custom_css_customs']       = $this->model->customCssGetFiles('custom/');
		$this->data['custom_css_current_file']  = $custom_css['current_file'];
	}

	protected function customCssPostSave(array &$settings)
	{
		$custom_css = $this->request->post['custom_css'];

		$settings['custom_css'] = $custom_css;
	}

	public function customCssLoad()
	{
		$group = $this->request->get['group'];
		$file = $this->request->request['file'];

		$folder = $this->model->customCssGetCssFolder($group);

		$result = array(
			'status' => 1,
			'css' => @file_get_contents($this->model->customCssSolveFilePath($folder . $file))
		);

		$this->response->setOutput(json_encode($result));
	}

	public function customCssSave()
	{
		$result = array(
			'status' => 0,
			'message' => 'error'
		);

		if ($this->request->server['REQUEST_METHOD'] != 'POST')
		{
			exit(json_encode($result));
		}

		$file = $this->request->post['file'];
		$css = htmlspecialchars_decode($this->request->post['css']);
		$rename = $this->request->post['rename'];

		$folder = $this->model->customCssGetCssFolder($this->request->post['group']);

		if (!$file || !$css)
		{
			exit(json_encode($result));
		}

		if ($rename)
		{
			$this->model->customCssRemoveFile($folder . $rename);
		}

		$folder_path = $this->model->customCssGetCssDirectoryPath() . $folder;

		if (!is_dir($folder_path))
		{
			mkdir($folder_path);
		}

		@file_put_contents($this->model->customCssSolveFilePath($folder . $file), $css);

		$result['status']   = 1;
		$result['message']  = _t('text_custom_css_save_success');

		$this->response->setOutput(json_encode($result));
	}

	public function customCssRemove()
	{
		$result = array(
			'status' => 0,
			'message' => 'error'
		);

		if ($this->request->server['REQUEST_METHOD'] != 'POST')
		{
			exit(json_encode($result));
		}

		$this->model->customCssRemoveFile($this->model->customCssGetCssFolder($this->request->post['group']) . $this->request->post['file']);

		$result['status'] = 1;
		$result['message'] = _t('text_custom_css_remove_success');

		$this->response->setOutput(json_encode($result));
	}

    private function sampleDataGetBaseDataUrl()
    {
        $template = $this->config->get('config_template');

        if (!preg_match('#^(.+)?-pro$#', $template, $matches))
        {
            return false;
        }

        $url = "http://{$matches[1]}.demo.kulerthemes.com/";
//        $url = 'http://dev.dc.localhost/oc1551/'; // TODO: change

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $http_code == 200 ? $url : false;
    }

    private function getSettingByStoreId($key, $store_id)
    {
        if (!$this->model_setting_setting)
        {
            $this->load->model('setting/setting');
        }

        $setting = $this->model_setting_setting->getSetting($key, $this->data['selected_store_id']);

        return is_array($setting) ? $setting : array();
    }

    private function getCatalogUrl()
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
        {
            $base = $this->config->get('config_ssl') ? $this->config->get('config_ssl') : HTTPS_CATALOG;
        }
        else
        {
            $base = $this->config->get('config_url') ? $this->config->get('config_url') : HTTP_CATALOG;
        }

        return $base;
    }

    private function getTabActive() {
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
    
    private function getSitemaps() {
        // Default config
        $seo = isset($this->data['seo']) ? $this->data['seo'] : array();
        $seo+= array(
            'sitemap_detect_filename' => '',
            'sitemap_detect_path' => '',
            'sitemap_detect_url' => '',
        );
        // Detect from Custom location
        $site_root = str_replace('admin/', '', DIR_APPLICATION);
        $sitemap_type = isset($seo['sitemap_filetype']) ? $seo['sitemap_filetype'] : 'xml';
        $sitemap_filename = isset($seo['sitemap_filename']) && $seo['sitemap_filename'] ? $seo['sitemap_filename'] : 'sitemap.' . $sitemap_type;
        $sitemap_found = false;

        // Get the site map path
        if (preg_match('#[/\\\]#', $sitemap_filename) && is_dir(dirname($sitemap_filename))) // absolute sitemap
        {
            $sitemap_path = $sitemap_filename;
        }
        else // relative sitemap
        {
            $sitemap_path = $site_root . $sitemap_filename;
        }

        $list = array(
            $sitemap_path,
            $site_root . 'sitemap.xml',
            $site_root . 'sitemap.gz',
        );

        $server = $this->getCatalogUrl();

        foreach($list as $path) {
            if(file_exists($path)) {
                $info = pathinfo($path);
                $seo['sitemap_detect_filename'] = $info['basename'];
                $seo['sitemap_detect_path'] = $path;

                $converted_site_root = str_replace('\\', '/', $site_root);
                $converted_site_map_path = str_replace('\\', '/', $info['dirname'] . '/');
                $found_relative_path = str_replace($converted_site_root, '', $converted_site_map_path);
                $seo['sitemap_detect_url'] = $server . $found_relative_path . $info['basename'];
                
                $seo['sitemap_filename'] = isset($seo['sitemap_filename']) && $seo['sitemap_filename'] ? $seo['sitemap_filename'] : $info['basename'];
                $seo['sitemap_url'] = isset($seo['sitemap_url']) && $seo['sitemap_url'] ? $seo['sitemap_url'] : $seo['sitemap_detect_url'];
                
                break;
            }
        }

        $this->data['seo'] = $seo;
    }

    private function getTemplates() {
        $this->data['templates'] = array();

        $directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $this->data['templates'][] = basename($directory);
        }
    }
    
    private function getErrors() {
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['sample_data_error']))
        {
            $this->data['sample_data_error'] = $this->session->data['sample_data_error'];
            unset($this->session->data['sample_data_error']);
        }

        if (isset($this->session->data['sample_data_success']))
        {
            $this->data['sample_data_success'] = $this->session->data['sample_data_success'];
            unset($this->session->data['sample_data_success']);
        }
    }
    
    private function getPayments() {
        $this->load->model('setting/extension');
        $extensions = $this->model_setting_extension->getInstalled('payment');
        
        $payments = array();

        foreach($extensions as $extension) {
            $this->language->load('payment/' . $extension);
            $text_link = $this->language->get('text_' . $extension);
            if ($text_link != 'text_' . $extension) {
                $link = $this->language->get('text_' . $extension);
            } else {
                $link = '<em>(no icon)</em>';
            }
            $payments[] = array(
                'name' => $this->language->get('heading_title'),
                'value' => $extension,
                'link' => $link,
            );
        }

        return $payments;
    }
    
    private function fontUpdate() {
        $result = $this->getGoogleFonts(1);
        $count  = count($result);
        if(empty($count)) {
            echo 'Could not update Google Fonts at this time, please try again later, thanks !';
        } else {
            echo 'Update ' . $count . ' Google Fonts successfully !';
        }
        
        die();
    }
    
    private function getFont() {
        // Default config
        $default = array(
            'heading' => array(
                'font-name' => 'Open Sans',
                'font-weight' => '400,600,700',
            ),
            'body' => array(
                'font-name' => 'Open Sans',
                'font-weight' => '400,600,700',
            ),
        );
        
        $font = isset($this->options['font']) ? $this->options['font'] : $default;
        
        foreach ($this->data['fonts'] as $k => $item) {
            if (isset($font['heading']['font-name']) && $item['font-name'] == $font['heading']['font-name']) {
                $font['heading']['font-index'] = $k;
                $font['heading']['font-family'] = $item['font-family'];
                $font['heading']['css-name'] = $item['css-name'];
            }
            if (isset($font['body']['font-name']) && $item['font-name'] == $font['body']['font-name']) {
                $font['body']['font-index'] = $k;
                $font['body']['font-family'] = $item['font-family'];
                $font['body']['css-name'] = $item['css-name'];
            }
        }
        
        if (isset($this->request->post['font'])) {
            $this->data['font'] = $this->request->post['font'];
        }
        else
        {
            if ($this->config->get('font'))
            {
                $this->data['font'] = $this->config->get('font');
            }
            else
            {
                $this->data['font'] = array(
                    'heading' => array(
                        'status' => 0,
                    ),
                    'body' => array(
                        'status' => 0
                    )
                );
            }
        }

        $this->data['font']['heading'] = array_merge($font['heading'], isset($this->data['font']['heading']) ? $this->data['font']['heading'] : array());
        $this->data['font']['body'] = array_merge($font['body'], isset($this->data['font']['body']) ? $this->data['font']['body'] : array());

        return $font;
    }
    
    private function getGoogleFonts($update = 0) {
        $fontArray = array();
        $fontCache = $this->model_kuler_cp->getResourceByType('font', 0);
        if($fontCache && ! $update) {
            $fontArray = unserialize($fontCache['cache']);
        } else {
            error_reporting(0);

            // Get Google Fonts
            $ch = curl_init('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBT19uQZ1dih9wZMi0aQ-b5GWz2onru6so');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $fontContent = curl_exec($ch);
            curl_close($ch);

            $fontJson = @json_decode($fontContent);
            if($fontJson && isset($fontJson->items) && is_array($fontJson->items)) {
                foreach($fontJson->items as $item) {
                    $fontArray[] = array(
                        'font-family' => $item->family,
                        'font-name' => $item->family,
                        'css-name' => str_replace(' ', '+', $item->family),
                    );
                }
                // Save cache to resource table
                $this->model_kuler_cp->insertFields(array(
                    'type' => 'font',
                    'name' => 'google',
                    'router' => '',
                    'version' => 1,
                    'files' => '',
                    'cache' => serialize($fontArray),
                    'status' => 1
                ), 0);
            } else {
                return array();
            }
        }
        return $fontArray;
    }

    private function getResources() {
        $this->document->addStyle('view/kulercore/css/kulercore.css?v=2013.0');
        $this->document->addStyle('view/kulercore/css/kulercp.css?v=2013.0');
        $this->document->addScript('view/kulercore/js/handlebars.js');
        $this->document->addScript('view/javascript/ckeditor/ckeditor.js');
        $this->document->addStyle('view/kulercore/colorpicker/css/colorpicker.css');
        $this->document->addScript('view/kulercore/colorpicker/js/colorpicker.js');
    }
    
    private function getOptions() {
        $options = array();
        $config = DIR_CATALOG . 'view/theme/' . $this->config->get('config_template') . '/includes/options.tpl';
        if (file_exists($config)) {
			$options = include($config);
            if(is_array($options) == false) {
                return array();
            } else {
                $this->options = $options;
            }
        }
        return $options;
    }
    /**
     * Get current theme colors settings
     */
    private function getColors() {
        $colors = array();
        if(isset($this->options['color'])) {
            $colors = $this->options['color'];
        }
        return $colors;
    }
	
    private function getPathways() {
        $this->language->load('module/kulercp');
        
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
            'href' => $this->url->link('module/kulercp', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['moduleLink'] = str_replace('&amp;', '&', $this->url->link('module/kulercp', 'token=' . $this->session->data['token'], 'SSL'));
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

    /**
     * Override default store setting width current store setting
     */
    private function overrideStoreSetting($selected_store_id)
    {
        $this->config->set('config_store_id', intval($selected_store_id));

        $settings = $this->model->getSettingByStoreId($this->config->get('config_store_id'));

        foreach ($settings as $setting_key => $setting_value)
        {
            $this->config->set($setting_key, $setting_value);
        }
    }

    private function getLanguages() {
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['button_install_sample_data'] = $this->language->get('button_install_sample_data');
        $this->data['button_download_sample_data'] = $this->language->get('button_download_sample_data');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_content_top'] = $this->language->get('text_content_top');
        $this->data['text_content_bottom'] = $this->language->get('text_content_bottom');
        $this->data['text_column_left'] = $this->language->get('text_column_left');
        $this->data['text_column_right'] = $this->language->get('text_column_right');

        $this->data['text_notify_google'] = $this->language->get('text_notify_google');
        $this->data['text_notify_bing'] = $this->language->get('text_notify_bing');
        $this->data['text_add_robots'] = $this->language->get('text_add_robots');
        $this->data['text_generate_help'] = $this->language->get('text_generate_help');

        $this->data['text_sample_data_confirm_install'] = $this->language->get('text_sample_data_confirm_install');
        $this->data['text_sample_data_installing'] = $this->language->get('text_sample_data_installing');
        $this->data['text_sample_data_installed'] = $this->language->get('text_sample_data_installed');

        $this->data['text_xml_file'] = $this->language->get('text_xml_file');
        $this->data['text_gzip_file'] = $this->language->get('text_gzip_file');

        $this->data['entry_sample_data'] = $this->language->get('entry_sample_data');
        $this->data['entry_color_scheme'] = $this->language->get('entry_color_scheme');
        $this->data['entry_theme_status'] = $this->language->get('entry_theme_status');
        $this->data['entry_optimal_status'] = $this->language->get('entry_optimal_status');
        $this->data['entry_compress_script'] = $this->language->get('entry_compress_script');
        $this->data['entry_compress_style'] = $this->language->get('entry_compress_style');
        $this->data['entry_ga_status'] = $this->language->get('entry_ga_status');
        $this->data['entry_ga_position'] = $this->language->get('entry_ga_position');
        $this->data['entry_font_google_heading'] = $this->language->get('entry_font_google_heading');
        $this->data['entry_font_google_body'] = $this->language->get('entry_font_google_body');
        $this->data['entry_font_heading'] = $this->language->get('entry_font_heading');
        $this->data['entry_font_body'] = $this->language->get('entry_font_body');
        $this->data['entry_copyright_custom'] = $this->language->get('entry_copyright_custom');
        $this->data['entry_copyright_content'] = $this->language->get('entry_copyright_content');

        $this->data['entry_kuler_module'] = $this->language->get('entry_kuler_module');
        $this->data['entry_module_status'] = $this->language->get('entry_module_status');

        $this->data['text_module_checker'] = $this->language->get('text_module_checker');
        $this->data['text_passed'] = $this->language->get('text_passed');
        $this->data['text_failed'] = $this->language->get('text_failed');
        $this->data['text_install_sample_data_hint'] = $this->language->get('text_install_sample_data_hint');
        $this->data['text_sample_data_installer'] = $this->language->get('text_sample_data_installer');

        $this->data['entry_title'] = $this->language->get('entry_title');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_description'] = $this->language->get('entry_description');

        $this->data['entry_skype'] = $this->language->get('entry_skype');
        $this->data['entry_mail'] = $this->language->get('entry_mail');
        $this->data['entry_mobile'] = $this->language->get('entry_mobile');
        $this->data['entry_phone'] = $this->language->get('entry_phone');
        $this->data['entry_fax'] = $this->language->get('entry_fax');
        $this->data['entry_twitter_embed_code'] = $this->language->get('entry_twitter_embed_code');
        $this->data['entry_twitter_theme'] = $this->language->get('entry_twitter_theme');
        $this->data['entry_twitter_dimension'] = $this->language->get('entry_twitter_dimension');
        $this->data['entry_twitter_link_color'] = $this->language->get('entry_twitter_link_color');
        $this->data['entry_twitter_background'] = $this->language->get('entry_twitter_background');
        $this->data['entry_twitter_border'] = $this->language->get('entry_twitter_border');
        $this->data['entry_twitter_border_color'] = $this->language->get('entry_twitter_border_color');
        $this->data['entry_twitter_header'] = $this->language->get('entry_twitter_header');
        $this->data['entry_twitter_footer'] = $this->language->get('entry_twitter_footer');
        $this->data['entry_twitter_scrollbar'] = $this->language->get('entry_twitter_scrollbar');
        $this->data['entry_tweet_number'] = $this->language->get('entry_tweet_number');
        $this->data['entry_username'] = $this->language->get('entry_username');
        $this->data['entry_facebook'] = $this->language->get('entry_facebook');
		$this->data['entry_facebook_theme'] = $this->language->get('entry_facebook_theme');
        $this->data['entry_width'] = $this->language->get('entry_width');
        $this->data['entry_height'] = $this->language->get('entry_height');

        $this->data['entry_sitemap_detection'] = $this->language->get('entry_sitemap_detection');
        $this->data['entry_sitemap_custom_location'] = $this->language->get('entry_sitemap_custom_location');
        $this->data['entry_sitemap_detect_filename'] = $this->language->get('entry_sitemap_detect_filename');
        $this->data['entry_sitemap_detect_path'] = $this->language->get('entry_sitemap_detect_path');
        $this->data['entry_sitemap_detect_url'] = $this->language->get('entry_sitemap_detect_url');
        $this->data['entry_sitemap_path'] = $this->language->get('entry_sitemap_path');
        $this->data['entry_sitemap_url'] = $this->language->get('entry_sitemap_url');
        $this->data['entry_sitemap_file'] = $this->language->get('entry_sitemap_file');
        $this->data['entry_sitemap_location'] = $this->language->get('entry_sitemap_location');
        $this->data['entry_sitemap_stylesheet'] = $this->language->get('entry_sitemap_stylesheet');
        $this->data['entry_sitemap_modification'] = $this->language->get('entry_sitemap_modification');
        $this->data['entry_sitemap_limit'] = $this->language->get('entry_sitemap_limit');
        $this->data['entry_sitemap_notify_se'] = $this->language->get('entry_sitemap_notify_se');
        $this->data['entry_sitemap_robots'] = $this->language->get('entry_sitemap_robots');        
        $this->data['entry_update_notify'] = $this->language->get('entry_update_notify');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_close'] = $this->language->get('button_close');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_add_module'] = $this->language->get('button_add_module');
        $this->data['button_remove'] = $this->language->get('button_remove');
        $this->data['button_upload'] = $this->language->get('button_upload');

        $__ = $this->language->load('module/kulercp');

        return $__;
    }
    
    public function install() {
		@$this->load->model('setting/setting');
		@$this->load->model('kuler/cp');
		@$this->model_kuler_cp->createTable();
	}
	
	public function uninstall() {
        $this->load->model('setting/setting');

        $stores = $this->getStores();

        foreach ($stores as $store_id => $store_name)
        {
            $this->model_setting_setting->deleteSetting('kulercp', $store_id);
        }

        $this->model->dropTable();
	}
    
    private function sitemapPing($sitemap_url) {
        $curl_req = array();
        $urls = array();
        
        // below are the SEs that we will be pining
        $urls[] = "http://www.google.com/webmasters/tools/ping?sitemap=" . urlencode($sitemap_url);
        $urls[] = "http://www.bing.com/webmaster/ping.aspx?siteMap=" . urlencode($sitemap_url);
        $urls[] = "http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url=" . urlencode($sitemap_url);
        $urls[] = "http://submissions.ask.com/ping?sitemap=" . urlencode($sitemap_url);

        foreach ($urls as $url) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURL_HTTP_VERSION_1_1, 1);
            $curl_req[] = $curl;
        }
        
        //initiating multi handler
        $multiHandle = curl_multi_init();

        // adding all the single handler to a multi handler
        foreach ($curl_req as $key => $curl) {
            curl_multi_add_handle($multiHandle, $curl);
        }
        
        $isactive = null;
        
        do {
            $multi_curl = curl_multi_exec($multiHandle, $isactive);
        } while ($isactive || $multi_curl == CURLM_CALL_MULTI_PERFORM);

        $success = true;
        foreach ($curl_req as $curlO) {
            if (curl_errno($curlO) != CURLE_OK) {
                $success = false;
            }
        }
        curl_multi_close($multiHandle);
        return $success;
    }
    
    private function sitemapRobots($link) {
        // Content write to robots.txt
        $sitemap = "\r\nSITEMAP: {$link}\r\n";
        
        // Check for robots.txt file
        $rootPath = str_replace('admin/', '', DIR_APPLICATION);
        $robotsPath = $rootPath . 'robots.txt';
        
        if(file_exists($robotsPath)) {
            // Check for content 
            $content = file_get_contents($robotsPath);

            // Write content to robots.txt
            if(strpos($content, $sitemap) === false) {
                $fp = fopen($robotsPath, "a+");
                fwrite($fp, $sitemap);
                fclose($fp);
            }
        } else {
            // Write content to robots.txt
            $content = "User-Agent: *\r\n";
            $content.= "Disallow: /admin/\r\n";
            $content.= "Disallow: /install/\r\n";
            $content.= "Disallow: /cache/\r\n";
            $content.= "Disallow: /library/\r\n";
            $content.= "Allow: /\r\n";
            $content.= $sitemap;
            $fp = fopen($robotsPath, "w+");
            fwrite($fp, $content);
            fclose($fp);
        }
    }

    private function sitemapGenerate() {
        $option = array();

        $server = $this->getCatalogUrl();

        // Get and save current config
        if(isset($this->request->post['seo'])) {
            $option = (array) $this->request->post['seo'];
            $this->model_kuler_cp->editSettingValue('kulercp', 'seo', $option, $this->data['selected_store_id']);
        }
        
        // Get seo options
        $filetype = !empty($option['sitemap_filetype']) ? $option['sitemap_filetype'] : 'xml';
        $filename = !empty($option['sitemap_filename']) ? $option['sitemap_filename'] : 'sitemap';
        $filelink = !empty($option['sitemap_url']) ? $option['sitemap_url'] : $server . $filename;
        $stylesheet = isset($option['sitemap_stylesheet']) ? $option['sitemap_stylesheet'] : 0;
        
        // Generate sitemap content
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
        
        if($stylesheet) {
            $sitemap.= "<?xml-stylesheet type=\"text/xsl\" href=\"". $server . "catalog/view/sitemaps/sitemap.xsl\"?>\r\n";
        }
        
        $sitemap.= "<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\r\n";
        $sitemap.= "    <!-- Categories -->\r\n";
        $sitemap.= $this->sitemapCategories(0);
        
        $sitemap.= "    <!-- Lastest products -->\r\n";
        $sitemap.= $this->sitemapProducts();
                
        $sitemap.= "    <!-- Pages -->\r\n";
        $sitemap.= $this->sitemapPages();
        $sitemap.= "</urlset>";

        // Resolve the relative path
        if (!(preg_match('#[/\\\]#', $filename) && is_dir(dirname($filename))))
        {
            $filename = dirname(DIR_APPLICATION) . '/' . $filename;
        }
        
        // Write sitemap content to file
        if($filetype == 'xml') {
            //Generating sitemaps for categories.
            $fp = fopen($filename, "w+");
            fwrite($fp, $sitemap);
            fclose($fp);
        } else {
            //Generating sitemaps for categories.
            $gz = gzopen($filename, "w");
            gzwrite($gz, $sitemap);
            gzclose($gz);
        }
        
        // Notify Google, Bing, Yahoo, Ask about update sitemap
        if(isset($option['sitemap_notify']) && $option['sitemap_notify']) {
            $this->sitemapPing($filelink);
        }
        
        // Add sitemap to ROBOT
        if(isset($option['sitemap_robots']) && $option['sitemap_robots']) {
            $this->sitemapRobots($filelink);
        }
    }
    
    private function sitemapCategories($parent_id, $current_path = '') {
		$output = '';
		
		$results = $this->model_kuler_cp->getSitemapCategories($parent_id);

        $server = $this->getCatalogUrl();
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= $this->sitemapLinkNode($server . 'index.php?route=product/category&path=' . $new_path, 'monthly');
			
        	$output .= $this->sitemapCategories($result['category_id'], $new_path);
		}
		
		return $output;        
    }
    
    private function sitemapProducts() {
		$output = '';
		
        $option = array();
        
        if ($this->config->get('seo')) {
            $option = $this->config->get('seo');
        }
        
        $limit = isset($option['sitemap_limit']) ? $option['sitemap_limit'] : 30;
        
        $data = array(
            'limit' => $limit
        );
        
		$results = $this->model_kuler_cp->getAllProducts($data);

        $server = $this->getCatalogUrl();

        foreach ($results as $result) {
			$output .= $this->sitemapLinkNode($server . 'index.php?route=product/product&product_id=' . $result['product_id'], "weekly", "0.5");
		}
		
		return $output;        
    }
    
    private function sitemapPages() {
		$output = '';
		
		$this->load->model('catalog/information');
		
        $results = $this->model_catalog_information->getInformations();

        $server = $this->getCatalogUrl();
        
		foreach ($results as $result) {
			$output .= $this->sitemapLinkNode($server .  'index.php?route=information/information&information_id=' . $result['information_id']);
		}
        
		$output .= $this->sitemapLinkNode($server . 'index.php?route=information/contact', "weekly", "0.5");
		
		return $output;        
    }
    
    private function sitemapLinkNode($link, $changefreq = 'yearly', $priority = '1.0') {
        
		$link = $this->model->rewrite($link);
		$link = str_replace("&","&amp;", $link);
		
		$output = "";	
		$output .= "    <url>\r\n";
		$output .= "        <loc>" . $link . "</loc>\r\n";
		$output .= "        <lastmod>" . date("Y-m-d\TH:i:sP") . "</lastmod>\r\n";
		$output .= "        <changefreq>" . $changefreq . "</changefreq>\r\n";
		$output .= "        <priority>" . $priority . "</priority>\r\n";
		$output .= "    </url>\r\n";
		
		return $output;        
    }
    
	private function tabCompression() {
        $this->data['resource_url'] = $this->getCatalogUrl() . '?kuler=clean';
        $this->data['config_template'] = $this->config->get('config_template');
	}

    private function optimalAdd() {
        $this->load->model('kuler/cp');

        $root = str_replace('\\', '/', DIR_CATALOG);
        $root = str_replace('catalog/', '', $root);

        $type = $this->request->post['type'];
        $name = $this->request->post['name'];
        $router = $this->request->post['router'];
        $data = $this->request->post['data'];
        $store_id = $this->request->post['store_id'];

        // Valid $name, $router
        $name = $name ? $name : dechex(time());
        $router = $router ? $router : '*';

        $data = json_decode(html_entity_decode($data));
        $data = $data ? (array) $data : array();

        // Type filter
        $list = array();
        foreach ($data as $item) {
            if ($item->type == $type) {
                $list[] = $item;
            }
        }

        $data = $list;

        $respond = array(
            'type' => $type,
            'name' => $name,
            'data' => $data,
        );

        $option = array(
            'encode' => 'Normal',
            'fastDecode' => true,
            'specialChars' => false,
        );

        $content = "/* \r\n";
        $content.= " * Last update: " . date('d/m/Y H:i') . "\r\n";
        $content.= " */\r\n\r\n";

        $server = $this->getCatalogUrl();

        foreach ($data as $k => $item) {
            if (isset($item->value) == false || false == file_exists($root . $item->value)) {
                unset($data[$k]);
                continue;
            }

            $path = $root . $item->value; // Real path to file
            $buffer = file_get_contents($path);
            if ($type == 'style') {
                $this->stylePath = router::linkToAbsolute($server, $item->value);
                $buffer = preg_replace_callback('/url\s*\((?P<url>[^)]+)\)/i', array($this, 'helperGetAbsoluteUrl'), $buffer);

                $content .= $this->compressStyle($buffer);
            }
            if ($type == 'script') {
                $content .= "\n" . $buffer;
            }
        }

        if ($type == 'script')
        {
            $fields = array(
                'js_code' => $content,
                'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
                'output_format' => 'text',
                'output_info' => 'compiled_code'
            );

            $curl = curl_init('http://closure-compiler.appspot.com/compile');

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type' => 'application/x-www-form-urlencoded'));
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

            $content = curl_exec($curl);

            curl_close($curl);

            if (!$content)
            {
                $this->log->write('JS Compression Failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
            }
        }

        if ($type && $name && $content) {
            $fields = array(
                'type' => $type,
                'name' => $name,
                'router' => $router,
                'version' => 1,
                'files' => serialize($data),
                'cache' => $content,
                'status' => 1
            );

            $respond['id'] = $this->model_kuler_cp->insertFields($fields, $store_id);
        }

        echo json_encode($respond);
        die();
    }

    private function validate() {
        $valid = true;
        
        if (!$this->user->hasPermission('modify', 'module/kulercp')) {
            $this->error['warning'] = $this->language->get('error_permission'); $valid = false;
        }
        
        // Validate General block
        if (isset($this->request->post['font'])) {
            $font = $this->request->post['font'];
            if(empty($font['heading']['font-weight'])) {
                $this->data['error_font_heading_weight'] = $this->language->get('error_font_heading_weight');
                $valid = false;
            }
            if(empty($font['body']['font-weight'])) {
                $this->data['error_font_body_weight'] = $this->language->get('error_font_body_weight');
                $valid = false;
            }
            
            if(!$valid) {
                // Active tab::SEO
                return false;
            }
        }
        
        // Validate SEO block only SEO tab active
        if (isset($this->request->post['seo']) && isset($this->request->post['tab']) && ($this->request->post['tab'] == 'seo')) {
            $seo = $this->request->post['seo'];

            if(empty($seo['sitemap_filename']) || !preg_match('#^(\w)+(\.(?i)(xml|gzip)$)#i', $seo['sitemap_filename'])) {
                $this->data['error_sitemap_filename'] = $this->language->get('error_sitemap_filename'); 
                $valid = false;
            }
            
            if(empty($seo['sitemap_url']) || !preg_match("#(http|https)://([a-zA-Z0-9.]|%[0-9A-Za-z]|/|:[0-9]?)*#i", $seo['sitemap_url'])) {
                $this->data['error_sitemap_url'] = $this->language->get('error_sitemap_url'); 
                $valid = false;
            }
            
            if(empty($seo['sitemap_limit']) || !is_numeric($seo['sitemap_limit'])) {
                $this->data['error_sitemap_limit'] = $this->language->get('error_sitemap_limit'); 
                $valid = false;
            }
            
            if(!$valid) {
                $this->data['vtab'] = '#tab-module-seo'; 
                $this->data['htab'] = '#tab-sitemap-generator'; 
                return false;
            }
		}
        
        return $valid;
    }

	private function saveAction() {
        $this->load->model('setting/setting');
        $this->load->model('design/layout');
        $this->load->model('kuler/cp');
        $this->load->model('localisation/language');
        
        $this->request->post['block']['status'] = (
                $this->request->post['block']['info']['status'] || 
                $this->request->post['block']['contact']['status'] || 
                $this->request->post['block']['twitter']['status'] || 
                $this->request->post['block']['facebook']['status'] || 
                $this->request->post['block']['copyright']['status'] || 
                $this->request->post['block']['payment']['status']);

        // Save kuler settings
        $blocks = $this->request->post['block'];
        $optimal = $this->request->post['optimal'];
        $seo = $this->request->post['seo'];
        $font = $this->request->post['font'];

        // Prepare Twitter Embed code
        if (!empty($blocks['twitter']['embed_code']))
        {
            $embedCode = html_entity_decode($blocks['twitter']['embed_code']);

            if (preg_match('#<a(.+?)>#', $embedCode, $matches) && preg_match_all('#([\w-]+)="(.+?)"#', $matches[1], $attrMatches))
            {
                $attributes = array();

                for ($i = 0; $i < count($attrMatches[1]); $i++)
                {
                    $attributes[$attrMatches[1][$i]] = $attrMatches[2][$i];
                }

                $attributes['data-tweet-limit'] = $blocks['twitter']['number'];

                if (empty($blocks['twitter']['theme']))
                {
                    $blocks['twitter']['theme'] = 'light';
                }

                $attributes['data-theme'] = $blocks['twitter']['theme'];

                $dataChrome = array();

                if (!empty($blocks['twitter']['link_color']))
                {
                    $attributes['data-link-color'] = $blocks['twitter']['link_color'];
                }
                else
                {
                    unset($attributes['data-link-color']);
                }

                if (empty($blocks['twitter']['background']))
                {
                    $dataChrome[] = 'transparent';
                }

                if (empty($blocks['twitter']['border']))
                {
                    $dataChrome[] = 'noborders';
                    unset($attributes['data-border-color']);
                }
                else
                {
                    $attributes['data-border-color'] = $blocks['twitter']['border_color'];
                }

                if (empty($blocks['twitter']['header']))
                {
                    $dataChrome[] = 'noheader';
                }

                if (empty($blocks['twitter']['footer']))
                {
                    $dataChrome[] = 'nofooter';
                }

                if (empty($blocks['twitter']['scrollbar']))
                {
                    $dataChrome[] .= 'noscrollbar';
                }

                $attributes['data-chrome'] = implode(' ', $dataChrome);

                if (!empty($blocks['twitter']['width']))
                {
                    $attributes['width'] = $blocks['twitter']['width'];
                }
                else
                {
                    unset($attributes['width']);
                }

                if (!empty($blocks['twitter']['height']))
                {
                    $attributes['height'] = $blocks['twitter']['height'];
                }
                else
                {
                    unset($attributes['height']);
                }

                $attrStr = '';
                foreach ($attributes as $attr => $value)
                {
                    $attrStr .= ' ' . $attr . '="' . $value . '"';
                }

                $blocks['twitter']['embed_code'] = str_replace($matches[1], $attrStr, $embedCode);
            }
        }

		// Set Facebook color scheme
		if (empty($blocks['facebook']['theme']))
		{
			$blocks['facebook']['theme'] = 'dark';
		}

        // Process block payment icons
        if(isset($blocks['payment']['items']) && is_array($blocks['payment']['items'])) {
            $this->load->model('setting/extension');
            foreach ($blocks['payment']['items'] as $extension => & $item) {
                $this->language->load('payment/' . $extension);
                $text_link = $this->language->get('text_' . $extension);
                if ($text_link != 'text_' . $extension) {
                    $link = str_replace('view/image/payment/', HTTP_SERVER . 'view/image/payment/', $this->language->get('text_' . $extension));
                } else {
                    $link = '';
                }
                $item['name'] = $this->language->get('heading_title');
                $item['value'] = $extension;
                $item['link'] = $link;
            }
        }

        if ($this->config->get('optimal')) {
            $optimal = $optimal + $this->config->get('optimal');
        }
        
        if($font) {
            $fonts = $this->getGoogleFonts();
            if(isset($fonts[$font['heading']['font-index']])) {
                $data = $fonts[$font['heading']['font-index']];
                $font['heading']['font-weight'] = preg_replace('#\s+#', '', $font['heading']['font-weight']);
                $font['heading']['font-family'] = str_replace('font-family: ', '', $data['font-family']);
                $font['heading']['font-name'] = $data['font-name'];
                $font['heading']['css-name'] = $data['css-name'];
                $font['heading']['font-selector'] = $this->options['font']['heading']['font-selector'];
            }
            if(isset($fonts[$font['body']['font-index']])) {
                $data = $fonts[$font['body']['font-index']];
                $font['body']['font-weight'] = preg_replace('#\s+#', '', $font['body']['font-weight']);
                $font['body']['font-family'] = str_replace('font-family: ', '', $data['font-family']);
                $font['body']['font-name'] = $data['font-name'];
                $font['body']['css-name'] = $data['css-name'];
                $font['body']['font-selector'] = $this->options['font']['body']['font-selector'];
            }
        }

		$store_id = $this->request->post['store_id'];

		// Save settings
		$settings = array();

		$this->presetPostSave($settings);

		$this->customCssPostSave($settings);

		$new_settings = array();

		$this->themeColorPostSave($new_settings);
		$this->colorSchemeBeforeSave($new_settings);

        $this->model_kuler_cp->editSetting('kulercp', 'kulercp_module', array(), $store_id);
        $this->model_kuler_cp->editSetting('kulercp', 'blocks', $blocks, $store_id);
        $this->model_kuler_cp->editSetting('kulercp', 'optimal', $optimal, $store_id);
        $this->model_kuler_cp->editSetting('kulercp', 'seo', $seo, $store_id);
        $this->model_kuler_cp->editSetting('kulercp', 'font', $font, $store_id);
		$this->model_kuler_cp->editSetting('kulercp', 'kuler_cp_settings', $settings, $store_id);
		$this->model_kuler_cp->editSetting('kulercp', 'color', array(
			'status' => $new_settings['kuler_cp_theme_color']
		), $store_id);

		foreach ($new_settings as $setting_key => $setting_value)
		{
			$this->model->editSetting('kulercp', $setting_key, $setting_value, $store_id);
		}

		// Remove temporary color scheme css
		unset($this->session->data[self::TEMPORARY_COLOR_SCHEME_KEY]);

        $this->language->load('module/kulercp');
        $this->session->data['success'] = $this->language->get('text_success');

        if(isset($this->request->post['op']) && $this->request->post['op'] == 'close') {
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        } else {
            $this->redirect($this->url->link('module/kulercp', 'token=' . $this->session->data['token'] . '&store_id=' . $store_id, 'SSL'));
        }
    }

	private function compressStyle($buffer) {
        // remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

        // remove tabs, new lines, etc.
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);

        // Process space in line
        $buffer = preg_replace_callback('#\{([^\{\}]+)\}#', create_function('$matches', '
	        return "{" . preg_replace_callback(\'#([^{;:]+):([^;}]+)#\', create_function(\'$matches\', \'
	        	$property = trim(preg_replace("#\s+#", "", $matches[1]));
	        	$value = trim(preg_replace("#\s+#", " ", $matches[2]));

	        	return $property . ":" . $value;
	        \'), $matches[1]) . "}";
        '), $buffer);

        $buffer = preg_replace('#\s+#', ' ', $buffer);

        return $buffer;
    }

    private function removeComment($script)
    {
        $script = preg_replace(array('/\\/\\/[^\\n\\r]*[\\n\\r]/', '/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//'), '', $script);

        return $script;
    }

    private function helperGetAbsoluteUrl($matches)
    {
        return sprintf(
            'url(%s)', router::linkToAbsolute($this->stylePath, trim($matches['url'], '\'"'))
        );
    }

    public function getDataLanguages()
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

                    Logger::log($text);
                }
            }

            return $placeholder ? $placeholder : $text;
        }
    }

    public function update()
    {
        $this->model->updateTable();
    }

    private function translate($texts)
    {
        $languages = $this->getDataLanguages();

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

    private function helperLink($route, array $params = array())
    {
        $params['token'] = $this->data['token'];

        return str_replace('&amp;', '&', $this->url->link($route, http_build_query($params), 'SSL'));
    }
}