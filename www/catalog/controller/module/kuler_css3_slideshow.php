<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ControllerModuleKulerCss3Slideshow extends Controller
{
	const MODULE_NAME = 'kuler_css3_slideshow';
	const MODULE_PREFIX = 'kuler_css3_slideshow_';

	protected function index($settings)
	{
		static $module = 0;

		$this->language->load('module/' . self::MODULE_NAME);
		
		$this->document->addStyle('catalog/view/kulercore/css/kuler_css3_slideshow.css');

      	$this->data['heading_title'] = $this->language->get('heading_title');

        $config_language_id = $this->config->get('config_language_id');

        if (isset($settings['data'][$config_language_id]))
        {
            $data = $settings['data'][$config_language_id];
        }
        else
        {
            $current_data = current($settings['data']);

            if (is_array($current_data))
            {
                $data = $current_data;
            }
            else
            {
                $data = $settings['data'];
            }
        }

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')))
		{
		    $base = HTTPS_SERVER;
		}
		else
		{
		    $base = HTTP_SERVER;
		}

        $this->data['moduleTitle'] = $this->translate($settings['title'], $this->config->get('config_language_id'));

        $data['dimension'] = isset($data['dimension']) ? $data['dimension'] : 'fixed';

		// Prepare images
		if ($data['image_source'] == 'images')
		{
			foreach ($data['images'] as $index => $image)
			{
				$imageUrl = $image['image'];

				if (!preg_match('#^http#', $imageUrl))
				{
					$imageUrl = $base . 'image/' . $image['image'];
				}

				$data['images'][$index]['image'] = $imageUrl;
			}
		}
		else if ($data['image_source'] == 'banner')
		{
			$this->load->model('design/banner');

			$images = $this->helperGetBannerImages($data['banner']);

			$data['images'] = array();
			// Convert to absolute link of image
			foreach ($images as $image)
			{
				$data['images'][] = array(
					'title' => $image['title'],
					'image' => $base . 'image/' . $image['image'],
					'link' => $image['link']
				);
			}
		}

		// Slideshow Type
		$data['slideshow_type'] = isset($data['slideshow_type']) ? $data['slideshow_type'] : 'split';

		// Dimension
		$data['width'] = intval($data['width']);
		$data['default_width'] = $data['width'];

		if ($data['width'] % $data['split'] != 0)
		{
			$data['width'] += $data['split'] - ($data['width'] % $data['split']);
		}

        if (empty($data['stretch']))
        {
            $data['stretch'] = 'fill';
        }

        if (empty($data['tablet_height']))
        {
            $data['tablet_height'] = $data['height'];
        }

        if (empty($data['mobile_height']))
        {
            $data['mobile_height'] = $data['height'];
        }

		$data['start_slide'] = 1;

        // Not support CSS3 Animation for IE < 10
        if (preg_match('#MSIE (.+?);#', $this->request->server['HTTP_USER_AGENT'], $matches) && intval($matches[1]) < 10)
        {
            $data['transition'] = 5;
        }

        if ($data['transition'] == 5)
        {
            $this->document->addScript('catalog/view/javascript/jquery/jquery.jcarousel.min.js');
        }

        if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/carousel.css')) {
            $this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/carousel.css');
        } else {
            $this->document->addStyle('catalog/view/theme/default/stylesheet/carousel.css');
        }

        $this->data['data'] = $data;
        $this->data['module'] = $module++;
        $this->data['show_title'] = $settings['show_title'];

		$this->template = $this->getViewFile('template/module/'. self::MODULE_NAME .'.tpl');
		$this->render();
	}

	/**
	 * Overide the method beacause of wrong sort order
	 * @return [array]
	 */
	private function helperGetBannerImages($banner_id)
	{
		$query = $this->db->query("
			SELECT * FROM " . DB_PREFIX . "banner_image bi
			LEFT JOIN " . DB_PREFIX . "banner_image_description bid
				ON (bi.banner_image_id  = bid.banner_image_id)
			WHERE bi.banner_id = " . (int)$banner_id . "
				AND bid.language_id = " . (int)$this->config->get('config_language_id') . "
			ORDER BY bi.banner_image_id ASC
		");
		
		return $query->rows;
	}

	private function getViewFile($path)
	{
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/' . $path))
		{
			$newPath = $this->config->get('config_template') . '/' . $path;
		}
		else
		{
			$newPath = 'default/' . $path;
		}

		return $newPath;
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
}
?>