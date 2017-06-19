<?php

error_reporting(E_ALL);

define('APP_PATH', __DIR__ . '/../../../production-app/' );

if ( !extension_loaded('phalcon') )
{
	echo 'Phalcon extension required.'.PHP_EOL;
	exit;
}
if (version_compare(PHP_VERSION, '5.4.0') < 0) {
	echo 'PHP version must be 5.4+';
	exit;
}
if(!extension_loaded('apc') && !ini_get('apc.enabled'))
{
	echo 'APC required!';
	exit;
}

try
{
	require_once APP_PATH . 'vendor/autoload.php';
	$config = include APP_PATH . 'config/config.php';
	include APP_PATH . 'backend/config/loader.php';
	include APP_PATH . 'backend/config/services.php';

	$application = new \Phalcon\Mvc\Application($di);

	echo $application->handle()->getContent();

}
catch (\Exception $e)
{
	echo $e->getMessage();
}
