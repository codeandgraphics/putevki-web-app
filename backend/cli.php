<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI,
	Phalcon\Mvc\Model\Transaction\Manager as TransactionManager,
	Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
	Phalcon\CLI\Console as ConsoleApp;
use Phalcon\Config\Adapter\Ini as Config;

define('VERSION', '1.0.0');

$di = new CliDI();

defined('APP_PATH') || define('APP_PATH', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

if (is_readable(APP_PATH . 'config.ini')) {
	$config = new Config(APP_PATH . 'config.ini');
	$di->set('config', $config);
} else {
	die('[ERROR] Cannot load config' . PHP_EOL);
}

$loader = new \Phalcon\Loader();
$loader->registerDirs(
	[
		APP_PATH . 'backend' . DIRECTORY_SEPARATOR . 'tasks'
	]
);
$namespaces = (array) $config->loader->namespaces;

$loader->registerNamespaces(array_map(function($namespace) {
	return APP_PATH . $namespace;
}, $namespaces));

$loader->register();

$di->setShared('transactions', function () {
	return new TransactionManager();
});

$di->set('db', function () use ($config) {
	return new DbAdapter($config->database->toArray());
});

$console = new ConsoleApp();
$console->setDI($di);

$arguments = [];
foreach ($argv as $k => $arg) {
	if ($k === 1) {
		$arguments['task'] = $arg;
	} elseif ($k === 2) {
		$arguments['action'] = $arg;
	} elseif ($k >= 3) {
		$arguments['params'][] = $arg;
	}
}

define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
	$console->handle($arguments);
} catch (\Phalcon\Exception $e) {
	echo $e->getMessage();
	echo PHP_EOL;
	exit(255);
}