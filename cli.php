<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI,
	Phalcon\Mvc\Model\Transaction\Manager as TransactionManager,
	Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
	Phalcon\CLI\Console as ConsoleApp;

define('VERSION', '1.0.0');

$di = new CliDI();

defined('APP_PATH') || define('APP_PATH', realpath('.') . DIRECTORY_SEPARATOR);

if (is_readable(APP_PATH . 'config/config.php'))
{
	$config = include APP_PATH . 'config/config.php';
	$di->set('config', $config);
}

$loader = new \Phalcon\Loader();
$loader->registerDirs(
	array(
		APP_PATH . 'tasks'
	)
);
$loader->registerNamespaces((array) $config->namespaces);
$loader->register();

$di->setShared('transactions', function(){
	return new TransactionManager();
});

$di->set('db', function () use ($config) {
	return new DbAdapter($config->database->toArray());
});

$console = new ConsoleApp();
$console->setDI($di);

$arguments = array();
foreach ($argv as $k => $arg)
{
	if ($k == 1)
	{
		$arguments['task'] = $arg;
	}
	elseif ($k == 2)
	{
		$arguments['action'] = $arg;
	}
	elseif ($k >= 3)
	{
		$arguments['params'][] = $arg;
	}
}

define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try
{
	$console->handle($arguments);
}
catch (\Phalcon\Exception $e)
{
	echo $e->getMessage();
	exit(255);
}