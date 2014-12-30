<?php

	function base_url(){
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
	define('HTTP_SERVER', base_url());

	// HTTPS
	define('HTTPS_SERVER', base_url());

	// DIR
	define('DIR_APPLICATION', 'your_path_to'.DS.'catalog'.DS);
	define('DIR_SYSTEM'		, 'your_path_to'.DS.'system'.DS);
	define('DIR_DATABASE'	, 'your_path_to'.DS.'system/database'.DS);
	define('DIR_LANGUAGE'	, 'your_path_to'.DS.'catalog/language'.DS);
	define('DIR_TEMPLATE'	, 'your_path_to'.DS.'catalog/view/theme'.DS);
	define('DIR_CONFIG'		, 'your_path_to'.DS.'system/config'.DS);
	define('DIR_IMAGE'		, 'your_path_to'.DS.'image'.DS);
	define('DIR_CACHE'		, 'your_path_to'.DS.'system/cache'.DS);
	define('DIR_DOWNLOAD'	, 'your_path_to'.DS.'download'.DS);
	define('DIR_LOGS'		, 'your_path_to'.DS.'system/logs'.DS);

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

?>