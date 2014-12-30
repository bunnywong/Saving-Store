<?php

	function base_url2(){
		return 'http://savingstore.com.hk/';
	}// !base_url()

	function year_perfix($date){
		return substr($date,2,2).substr($date,5,2);
	}// !year_perfix

	function zh_order_status($status){
		$status = strtolower($status);

		if( $status == 'pending' ){
			echo '處理中';
		}elseif($status == 'complete' ){
			echo '已完成';
		}elseif($status == 'canceled' ){
			echo '已取消';
		}else{
			echo '請聯絡我們';
		}
	}// !zh_order_status

	// --------------------------------------------------

	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

	// HTTP
	define('HTTP_SERVER', base_url2().'admin/');
	define('HTTP_CATALOG', base_url2());

	// HTTPS
	define('HTTPS_SERVER', base_url2().'admin/');
	define('HTTPS_CATALOG', base_url2());

	// DIR
	define('DIR_APPLICATION', 'your_path_to'.DS.'admin'.DS);
	define('DIR_SYSTEM'		, 'your_path_to'.DS.'system'.DS);
	define('DIR_DATABASE'	, 'your_path_to'.DS.'system'.DS.'database'.DS);
	define('DIR_LANGUAGE'	, 'your_path_to'.DS.'admin'.DS.'language'.DS);
	define('DIR_TEMPLATE'	, 'your_path_to'.DS.'admin'.DS.'view'.DS.'template'.DS);
	define('DIR_CONFIG'		, 'your_path_to'.DS.'system'.DS.'config'.DS);
	define('DIR_IMAGE'		, 'your_path_to'.DS.'image'.DS);
	define('DIR_CACHE'		, 'your_path_to'.DS.'system'.DS.'cache'.DS);
	define('DIR_DOWNLOAD'	, 'your_path_to'.DS.'download'.DS);
	define('DIR_LOGS'		, 'your_path_to'.DS.'system'.DS.'logs'.DS);
	define('DIR_CATALOG'	, 'your_path_to'.DS.'catalog'.DS);

	// DB
	define('DB_DRIVER', 'mysqli');

	define('DB_HOSTNAME', '');
	define('DB_USERNAME', '');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', '');

	define('DB_PREFIX', 'oc_');
	define('ORDER_PREFIX', 'DS');
	define('ORDER_DIGI', 4);

?>