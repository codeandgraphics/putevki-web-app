<?php

error_reporting(E_ALL);

define('APP_START', microtime(true));
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR );

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
	require_once APP_PATH . 'frontend/Application.php';

	$app = new Application();
	$response = $app->handle();
	$response->send();

	/*require_once APP_PATH . 'vendor/autoload.php';
	$config = include APP_PATH . 'config/config.php';
	include APP_PATH . 'frontend/config/loader.php';
	include APP_PATH . 'frontend/config/services.php';

	$application = new \Phalcon\Mvc\Application($di);

	echo $application->handle()->getContent();*/

} catch (\Exception $e) {
	echo $e->getMessage();
}