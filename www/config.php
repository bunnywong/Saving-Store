<?php
	function base_url(){
		return 'http://savingstore.com.hk/';
	}// !base_url()
	// --------------------------------------------------

	// HTTP
	define('HTTP_SERVER', base_url());

	// HTTPS
	define('HTTPS_SERVER', base_url());

	// DIR
	define('DIR_APPLICATION', 'catalog/');
	define('DIR_SYSTEM', 'system/');
	define('DIR_DATABASE', 'system/database/');
	define('DIR_LANGUAGE', 'catalog/language/');
	define('DIR_TEMPLATE', 'catalog/view/theme/');
	define('DIR_CONFIG', 'system/config/');
	define('DIR_IMAGE', 'image/');
	define('DIR_CACHE', 'system/cache/');
	define('DIR_DOWNLOAD', 'download/');
	define('DIR_LOGS', 'system/logs/');

	// DB
	define('DB_DRIVER', 'mysqli');

	// Fill in the blank
	define('DB_HOSTNAME', '');
	define('DB_USERNAME', '');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', '');

	define('DB_PREFIX', 'oc_');
	define('ORDER_PREFIX', 'DS');
	define('ORDER_DIGI', 4);

	function year_perfix($date){
		return substr($date,2,2).substr($date,5,2);
	}

?>