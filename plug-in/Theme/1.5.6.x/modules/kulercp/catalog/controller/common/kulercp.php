<?php

/*--------------------------------------------------------------------------/
* @Author		KulerThemes.com http://www.kulerthemes.com
* @Copyright	Copyright (C) 2012 - 2013 KulerThemes.com. All rights reserved.
* @License		KulerThemes.com Proprietary License
/---------------------------------------------------------------------------*/

class ControllerCommonKulercp extends Controller {
	public function index() {
		$this->load->model('kuler/cp');

		$cache = '';
		
		$file = isset($_GET['cache']) ? $_GET['cache'] : '';

		if ($file != 'theme' && false == preg_match('/^(.*)\.(js|css)$/', $file, $matches)) {
			die();
		}

		if($file == 'theme') {
			$name = 'default';
			$type = 'theme';
		} else {
			$name = $matches[1];
			$type = $matches[2];
		}

		// Setup content header
		if ($type == 'js') {
			header('Content-type: text/javascript');
		}
		
		if ($type == 'css' || $type == 'theme') {
			header('Content-type: text/css');
		}
		
		if($type == 'js')  $type = 'script';
		if($type == 'css') $type = 'style';

		// Set client cache
		$seconds = 2160000;
		$time = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";

		// Set header cache limit 1 month
		header("Expires: $time");
		header("Pragma: cache");
		header("Cache-Control: max-age=$seconds");

		// Respond content
		$result = $this->model_kuler_cp->getCache($type, $name, $this->config->get('config_store_id'));

		echo $result; die();
	}
}
?>