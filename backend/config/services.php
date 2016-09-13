<?php

use \Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url 				as UrlResolver,
	Phalcon\Db\Adapter\Pdo\Mysql	as DbAdapter,
	Phalcon\Mvc\View\Engine\Volt	as VoltEngine,
	Phalcon\Mvc\Dispatcher			as Dispatcher,
	Phalcon\Events\Manager			as EventsManager,
	Phalcon\Session\Adapter\Files	as SessionAdapter,
	Phalcon\Flash\Session			as FlashSession;

$di = new FactoryDefault();

$di->set('config', $config);

$di->set('url', function () use ($config)
{
	$url = new UrlResolver();
	$url->setBaseUri($config->backend->baseUri);
	return $url;
}, true);

$di->setShared('view', function () use ($config)
{
	$view = new \Phalcon\Mvc\View();

	$view->setViewsDir($config->backend->viewsDir);

	$view->registerEngines(array(
		'.volt' => function ($view, $di) use ($config)
		{
			$volt = new VoltEngine($view, $di);

			$volt->setOptions(array(
				'compiledPath' => $config->cacheDir,
				'compiledSeparator' => '_'
			));

			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));

	return $view;
});

$di->set('db', function () use ($config)
{
	return new DbAdapter($config->database->toArray());
});

$di->set('flashSession', function ()
{
	$flash = new FlashSession(
		array(
			'error'   => 'danger',
			'success' => 'success',
			'notice'  => 'info',
			'warning' => 'warning'
		)
	);

	return $flash;
});

$di->set('dispatcher', function ()
{
	$eventsManager = new EventsManager;

	$eventsManager->attach('dispatch:beforeDispatch', new Backend\Plugins\Security);

	//$eventsManager->attach('dispatch:beforeException', new \Plugins\NotFound);

	$dispatcher = new Dispatcher;
	$dispatcher->setEventsManager($eventsManager);
	$dispatcher->setDefaultNamespace("Backend\\Controllers");

	return $dispatcher;
});

$di->setShared('session', function ()
{
	$session = new SessionAdapter();
	$session->start();
	return $session;
});

$di->set('simpleView', function() use ($config)
{
	$view = new \Phalcon\Mvc\View\Simple();
	$view->setViewsDir($config->backend->viewsDir);
	$view->registerEngines(array(".volt" => 'Phalcon\Mvc\View\Engine\Volt'));
	return $view;
});


$di->set('frontendConfig', $config->frontend);
