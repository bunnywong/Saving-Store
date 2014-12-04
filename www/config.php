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

	define('DB_HOSTNAME', 'localhost');
	define('DB_USERNAME', '');	// Edit this
	define('DB_PASSWORD', '');	// Edit this
	define('DB_DATABASE', '');	// Edit this

	define('DB_PREFIX', 'oc_');


?>