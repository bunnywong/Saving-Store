<?php
	function base_url2(){
		return 'http://savingstore.com.hk/';
	}// !base_url()
	// --------------------------------------------------

	// HTTP
	define('HTTP_SERVER', base_url2().'admin/');
	define('HTTP_CATALOG', base_url2());

	// HTTPS
	define('HTTPS_SERVER', base_url2().'admin/');
	define('HTTPS_CATALOG', base_url2());

	// DIR
	define('DIR_APPLICATION', '../admin/');
	define('DIR_SYSTEM', '../system/');
	define('DIR_DATABASE', '../system/database/');
	define('DIR_LANGUAGE', '../admin/language/');
	define('DIR_TEMPLATE', '../admin/view/template/');
	define('DIR_CONFIG', '../system/config/');
	define('DIR_IMAGE', '../image/');
	define('DIR_CACHE', '../system/cache/');
	define('DIR_DOWNLOAD', '../download/');
	define('DIR_LOGS', '../system/logs/');
	define('DIR_CATALOG', '../catalog/');

	// DB
	define('DB_DRIVER', 'mysqli');

	define('DB_HOSTNAME', 'localhost');
	define('DB_USERNAME', '');	// Edit this
	define('DB_PASSWORD', '');	// Edit this
	define('DB_DATABASE', '');	// Edit this

	define('DB_PREFIX', 'oc_');
?>