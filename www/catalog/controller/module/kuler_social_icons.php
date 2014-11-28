<?php

/**
 * Kuler Control Panel Config
 * 
 * + Use for header.tpl
 *  kuler_theme_color
 *  kuler_analytics_code
 *  kuler_analytics_position
 *  kuler_compress_scripts
 *  kuler_compress_styles
 */
class ControllerModuleKulerSocialIcons extends Controller {

	protected function index($setting) {
        $setting['module_title'] = $this->translate($setting['module_title'], $this->config->get('config_language_id'));

        $this->data['module_title'] = isset($setting['module_title']) ? $setting['module_title'] : 'Social';
    	$this->data['show_title'] = isset($setting['show_title']) ? $setting['show_title'] : 0;

    	$this->data['setting'] = $setting;

		$this->document->addStyle('catalog/view/kulercore/css/kuler_social_icons.css');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/kuler_social_icons.phtml')) {
			$this->template = $this->config->get('config_template') . '/template/module/kuler_social_icons.phtml';
		} else {
			$this->template = 'default/template/module/kuler_social_icons.phtml';
		}
		
		$this->render();
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