<?php

error_reporting(E_ALL);

define('APP_START', microtime(true));
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR );

if( !is_dir(APP_PATH . '/vendor')) {
	echo 'Composer not initialized. ' . PHP_EOL;
	echo 'Run `composer install`' . PHP_EOL;
	exit;
}
if ( !extension_loaded('phalcon') ) {
	echo 'Phalcon extension required.'.PHP_EOL;
	exit;
}
if (version_compare(PHP_VERSION, '5.4.0') < 0) {
	echo 'PHP version must be 5.4+';
	exit;
}
if(!extension_loaded('apc') && !ini_get('apc.enabled')) {
	echo 'APC required!';
	exit;
}

try {
	require_once APP_PATH . 'backend/BackendApplication.php';

	$app = new BackendApplication();
	$response = $app->handle();
	$response->send();

} catch (\Exception $e) {
	echo $e->getMessage();
}