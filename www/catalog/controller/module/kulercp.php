<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ControllerModuleKulercp extends Controller {
	const TEMPORARY_COLOR_SCHEME_CSS_KEY = 'kcp_temporary_color_scheme_css';

    private $options;
	public function index() {
		header('Access-Control-Allow-Origin: *');

		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('kulercp', $this->config->get('config_store_id'));
		$this->convertOldSettingsToNew($settings);

		$this->load->model('kuler/cp');
        
		$lang       = (int)$this->config->get('config_language_id');
        $seo        = $this->config->get('seo');
        $font       = $this->config->get('font');
        $block      = $this->config->get('blocks');
        $optimal    = $this->config->get('optimal');

        $options    = $this->getOptions();

        // Clear all style / script when disable mode for backend list theme style / script
        if(isset($this->request->get['kuler']) && $this->request->get['kuler'] == 'clean') {
            return;
        }

        // Get theme color style
        $this->config->set('kuler_theme_color', $settings['theme_color']);

        // Get Kuler Google Analysic
        if($seo && isset($seo['status']) && $seo['status'] && $this->config->get('config_google_analytics')) {
            $this->config->set('kuler_analytics_code', html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8'));
            $this->config->set('kuler_analytics_position', $seo['position']);
        }

        // Get current scripts
        if($optimal && isset($optimal['script_theme']) && $optimal['script_theme']) {
            $this->config->set('kuler_compress_scripts', $this->model_kuler_cp->getCompressScripts());
            $this->config->set('kuler_compress_script_type', 'theme');
        }

        // Get current styles
        if($optimal && isset($optimal['style_theme']) && $optimal['style_theme']) {
            $this->config->set('kuler_compress_styles', $this->model_kuler_cp->getCompressStyles());
            $this->config->set('kuler_compress_style_type', 'theme');
        }

        // Get font config
        if($font && isset($font['heading']['status']) && $font['heading']['status'] == 1) {
            $font['heading'] = $font['heading'] + (isset($this->options['font']['heading']) ? $this->options['font']['heading'] : array());
            $this->config->set('kuler_heading_font', $font['heading']);
        } else {
            $this->config->set('kuler_heading_font', isset($this->options['font']['heading']) ? $this->options['font']['heading'] : array());
        }

        if($font && isset($font['body']['status']) && $font['body']['status'] == 1) {
            $font['body'] = $font['body'] + (isset($this->options['font']['body']) ? $this->options['font']['body'] : array());
            $this->config->set('kuler_body_font', $font['body']);
        } else {
            $this->config->set('kuler_body_font', isset($this->options['font']['body']) ? $this->options['font']['body'] : array());
        }

        // Block payment icons
        if(isset($block['payment']['status']) && $block['payment']['status'] && $block['payment']['items']) {
            $this->config->set('kuler_payment_status', 1);

            $payment_items = $block['payment']['items'];

            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
            {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }

            foreach ($payment_items as &$payment_item)
            {
                if (!empty($payment_item['image']))
                {
                    $payment_item['link'] = '<a><img src="'. $server . $payment_item['image'] .'" alt="'. $payment_item['name'] .'" /></a>';
                }
            }

            $this->config->set('kuler_payment_items', $payment_items);
        }

        // Block count
		
		$count = 0;
		
		if($block['info']['status'] == 1) {
			$count++;
		}
		if($block['contact']['status'] == 1) {
			$count++;
		}
		if($block['twitter']['status'] == 1) {
			$count++;
		}
		if($block['facebook']['status'] == 1) {
			$count++;
		}

		// Process language

		$info = isset($block['info']) ? $block['info'] : null;
		
		if($info) {
            if (!isset($info[$lang]))
            {
                foreach ($info as $lang_info)
                {
                    if (is_array($lang_info))
                    {
                        $default = $lang_info;
                        break;
                    }
                }

                $current = $default;
            }
            else
            {
                $current = $info[$lang];
            }

            $first_info = array();
            foreach ($info as $info_index => $info_value)
            {
                if ($info_index != 'status')
                {
                    $first_info = $info_value;
                }
            }

            if (!isset($info[$lang]))
            {
                $info[$lang] = array();
            }

            foreach ($first_info as $key => $value)
            {
                if (empty($info[$lang][$key]))
                {
                    $info[$lang][$key] = $value;
                }
            }

            $info[$lang]['status'] = $info['status'];
            $info[$lang]['description'] = html_entity_decode($info[$lang]['description'], ENT_QUOTES, 'UTF-8');
            $block['info'] = $info[$lang];
		}

        // Prepare block
        $block['contact']['title'] = $this->translate($block['contact']['title'], $lang);
        $block['twitter']['title'] = $this->translate($block['twitter']['title'], $lang);
        $block['facebook']['title'] = $this->translate($block['facebook']['title'], $lang);

        $this->language->load('module/kulercp');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');

		$this->data['block'] = $block;
		$this->data['count'] = $count;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kulercp.phtml')) {
			$this->template = $this->config->get('config_template') . '/template/module/kulercp.phtml';
		} else {
			$this->template = 'default/template/module/kulercp.phtml';
		}

		$this->render();

        if (!$block['info']['status'] && !$block['contact']['status'] && !$block['twitter']['status'] && !$block['facebook']['status'])
        {
            $this->output = false;
        }
	}

    public function colorScheme()
    {
	    $this->load->model('setting/setting');
	    $settings = $this->model_setting_setting->getSetting('kulercp', $this->config->get('config_store_id'));
	    $this->convertOldSettingsToNew($settings);

	    $this->output = false;
	    if (($settings['preset'] == 'default' && $settings['custom_scheme']) || isset($this->session->data[self::TEMPORARY_COLOR_SCHEME_CSS_KEY]))
	    {
		    $this->load->model('kuler/cp');

		    $css = isset($this->session->data[self::TEMPORARY_COLOR_SCHEME_CSS_KEY]) ? $this->session->data[self::TEMPORARY_COLOR_SCHEME_CSS_KEY] : $this->model_kuler_cp->colorSchemeCompile($settings['custom_scheme']);

		    $this->output = '<style id="stylesheet-color">' . $css . '</style>';
		    return true;
	    }
    }

	public function saveTemporaryColorScheme()
	{
		// Get color scheme
		$scheme = array();

		if (isset($this->request->get['color']))
		{
			// Get color scheme by color
			$color = $this->request->get['color'];

			$theme_options = $this->getOptions();

			if (isset($theme_options['color']) && isset($theme_options['color'][$color]) && is_array($theme_options['color'][$color]))
			{
				$scheme = $theme_options['color'][$color]['scheme'];
			}
		}
		else if ($this->request->get['scheme'] && is_array($this->request->get['scheme']))
		{
			// Get color scheme by input
			$scheme = $this->request->get['scheme'];
		}


		$output = '';

		if ($scheme)
		{
			// Generate CSS for color scheme
			$this->load->model('kuler/cp');

			$output = $this->model_kuler_cp->colorSchemeCompile($scheme);

			// Save output in session
			$this->session->data[self::TEMPORARY_COLOR_SCHEME_CSS_KEY] = $output;

			if (isset($this->request->get['scheme']))
			{
				$this->session->data['kcp_color_scheme'] = $this->request->get['scheme'];
				setcookie('kst_theme_color', 'custom');
			}

			if (isset($this->request->get['color']))
			{
				$this->session->data['kcp_color_scheme'] = array();
				setcookie('kst_theme_color', $this->request->get['color']);
			}
		}

		echo $output;
	}

	public function usePreset()
	{
		$settings = $this->config->get('kuler_cp_settings');

		if ($settings && isset($settings['preset']))
		{
			$preset_file = DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/presets/' . $settings['preset'] . '.css';

			if (file_exists($preset_file))
			{
				$this->output = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/presets/' . $settings['preset'] . '.css';
				return true;
			}
		}

		return false;
	}

	public function useCustomCSS()
	{
		$settings = $this->config->get('kuler_cp_settings');
		$custom_css_mask = DIR_TEMPLATE . '%s/stylesheet/%s';

		if ($settings && isset($settings['custom_css']) && $settings['custom_css']['status'] && isset($settings['custom_css']['current_file']))
		{
			$folder = '';

			if (!empty($settings['custom_css']['group']))
			{
				switch ($settings['custom_css']['group'])
				{
					case 'default':
						$folder = '';
						break;
					case 'preset':
						$folder = 'presets/';
						break;
					case 'custom':
					default:
						$folder = 'custom/';
				}
			}

			$file = $folder . $settings['custom_css']['current_file'];

			if (file_exists(sprintf($custom_css_mask, $this->config->get('config_template'), $file)))
			{
				$this->output = sprintf('catalog/view/theme/%s/stylesheet/%s', $this->config->get('config_template'), $file);

				return true;
			}
		}

		$this->output = false;
	}

    public function getCustomCopyright()
    {
        $blocks = $this->config->get('blocks');

        if (!is_array($blocks) || !isset($blocks['copyright']) || !$blocks['copyright']['status'])
        {
            return false;
        }

        $this->output = html_entity_decode($blocks['copyright']['content'], ENT_QUOTES, 'UTF-8');

        return true;
    }

	public function getProductImage($product_id)
	{
		$product = $this->model_catalog_product->getProduct($product_id);

		$this->output = $this->prepareImage($product['image']);

		return true;
	}

	public function getChildProductImages($product_id)
	{
		$results = $this->model_catalog_product->getProductImages($product_id);

		$images = array();
		foreach ($results as $result)
		{
			$images[] = array(
				'original'  => $this->prepareImage($result['image']),
				'popup'     => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
				'thumb'     => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_additional_width'), $this->config->get('config_image_additional_height'))
			);
		}

		$this->output = $images;

		return true;
	}

	protected function convertOldSettingsToNew(array &$settings)
	{
		// Theme Color
		if (empty($settings['kuler_cp_theme_color']))
		{
			$settings['theme_color'] = $settings['color']['status'];
		}
		else
		{
			$settings['theme_color'] = $settings['kuler_cp_theme_color'];
		}

		// Color Scheme
		if (empty($settings['kuler_cp_custom_scheme']))
		{
			// TODO: Default value
			$settings['custom_scheme'] = array();
		}
		else
		{
			$settings['custom_scheme'] = $settings['kuler_cp_custom_scheme'];
		}

		// Preset
		if (isset($settings['kuler_cp_settings']) && isset($settings['kuler_cp_settings']['preset']))
		{
			$settings['preset'] = $settings['kuler_cp_settings']['preset'];
		}
		else
		{
			$settings['preset'] = 'default';
		}
	}

    private function getOptions() {
        $options = array();
        $config = DIR_TEMPLATE . $this->config->get('config_template') . '/includes/options.tpl';
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

    private function translate($texts, $language_id)
    {
        if (is_array($texts))
        {
            $first = current($texts);

            if (is_string($first))
            {
                $texts = empty($texts[$language_id]) ? $first : $texts[$language_id];
            }
            else if (is_array($texts))
            {
                if (!isset($texts[$language_id]))
                {
                    $texts[$language_id] = array();
                }

                foreach ($first as $key => $value)
                {
                    if (empty($texts[$language_id][$key]))
                    {
                        $texts[$language_id][$key] = $value;
                    }
                }
            }
        }

        return $texts;
    }

	private function prepareImage($image)
	{
		$front_store_url = $this->getFrontStoreUrl();

		if (!empty($image))
		{
			return $front_store_url . 'image/' . $image;
		}
		else
		{
			return $this->model_tool_image->resize('no_image.jpg', $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
		}
	}

	private function getFrontStoreUrl()
	{
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
			$server = $this->config->get('config_ssl');
		}
		else
		{
			$server = $this->config->get('config_url');
		}

		return $server;
	}
}

?>