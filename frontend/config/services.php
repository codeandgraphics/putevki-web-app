<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Dispatcher as Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;

$di = new FactoryDefault();

$di->set('config', $config);

$di->set('url', function () use ($config) {
	$url = new UrlResolver();
	$url->setBaseUri($config->frontend->baseUri);

	return $url;
}, true);

$di->set('router', function() use($config)
{
	$router = new Router();

	foreach($config->frontend->routes as $route => $action)
	{
		$router->add($route, (array) $action);
	}

	$router->notFound(
		array(
			'controller' => 'frontend',
			'action'     => 'error404'
		)
	);

	return $router;
});

$di->setShared('view', function () use ($config) {

	$view = new View();

	$view->setViewsDir($config->frontend->viewsDir);

	$view->registerEngines(array(
		'.volt' => function ($view, $di) use ($config) {

			$volt = new VoltEngine($view, $di);

			$volt->setOptions(array(
				'compiledPath' => $config->cacheDir,
				'compiledSeparator' => '_'
			));

			$volt->getCompiler()->addFilter(
				'size',
				function ($resolvedArgs) {
					return 'count(' . $resolvedArgs . ')';
				}
			);


			return $volt;
		},
		'.phtml' => 'Phalcon\Mvc\View\Engine\Php'
	));

	return $view;
});

$di->set('db', function () use ($config) {
	return new DbAdapter($config->database->toArray());
});


$di->set('flashSession', function () {
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

$di->set('dispatcher', function () {

	$eventsManager = new EventsManager;

	//$eventsManager->attach('dispatch:beforeDispatch', new Backend\Plugins\Security);

	//$eventsManager->attach('dispatch:beforeException', new \Plugins\NotFound);

	$eventsManager->attach(
		'dispatch:beforeException',
		function($event, $dispatcher, $exception)
		{
			switch ($exception->getCode()) {
				case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
				case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
					$dispatcher->forward(
						array(
							'controller' => 'error',
							'action'     => 'error404',
						)
					);
					return false;
			}
		}
	);

	$dispatcher = new Dispatcher;
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

$di->setShared('session', function () {
	$session = new SessionAdapter();
	$session->start();
	return $session;
});

$di->set('simpleView', function() use ($config)
{
	$view = new Phalcon\Mvc\View\Simple();
	$view->setViewsDir($config->backend->viewsDir);
	$view->registerEngines(array(".volt" => 'Phalcon\Mvc\View\Engine\Volt'));
	return $view;
});
