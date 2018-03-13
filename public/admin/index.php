<?php

error_reporting(E_ALL);

define('PHALCON_MIN_VERSION', 30040);

define('APP_START', microtime(true));
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . '../dev-app/' . DIRECTORY_SEPARATOR );

if( !is_dir(APP_PATH . '/vendor')) {
	echo 'Composer not initialized. ' . PHP_EOL;
	echo 'Run `composer install`' . PHP_EOL;
	exit;
}
if (version_compare(PHP_VERSION, '7.0.0') < 0) {
	echo 'PHP version must be 7.0+';
	exit;
}
if ( !extension_loaded('phalcon') ) {
	echo 'Phalcon extension required.'.PHP_EOL;
	exit;
}
if (Phalcon\Version::getId() < PHALCON_MIN_VERSION) {
	echo 'Phalcon must be version 3.0.0 or greater, server version: ' . \Phalcon\Version::get() . PHP_EOL;
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