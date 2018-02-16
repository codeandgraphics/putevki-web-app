<?php

use Phalcon\Mvc\Router;

$router = new Router();

$router->removeExtraSlashes(true);

$router->add(
	'/',
	[
		'controller' => 'index',
		'action' => 'index',
	]
);
$router->add(
	'/{city:[a-z\-]+}',
	[
		'controller' => 'index',
		'action' => 'city',
	]
);
$router->add(
    '/search-full',
    [
        'controller' => 'index',
        'action' => 'searchFull',
    ]
);
$router->add(
	'/agreement',
	[
		'controller' => 'index',
		'action' => 'agreement',
	]
);
$router->add(
	'/about',
	[
		'controller' => 'index',
		'action' => 'about',
	]
);
$router->add(
	'/contacts',
	[
		'controller' => 'index',
		'action' => 'contacts',
	]
);
$router->add(
	'/app',
	[
		'controller' => 'index',
		'action' => 'app',
	]
);
$router->add(
	'/migrate',
	[
		'controller' => 'index',
		'action' => 'migrate',
	]
);
$router->add(
	'/uniteller',
	[
		'controller' => 'index',
		'action' => 'uniteller',
	]
);
$router->add(
    '/best-tours',
    [
        'controller' => 'index',
        'action' => 'bestTours',
    ]
);
$router->add(
    '/personal',
    [
        'controller' => 'index',
        'action' => 'personal',
    ]
);
$router->add(
	'/tour/{id}',
	[
		'controller' => 'tour',
		'action' => 'index',
	]
);
$router->add(
	'/pay/{requestId:[0-9]+}',
	[
		'controller' => 'pay',
		'action' => 'index',
	]
);

/** Search routes */
$router->add(
	'/search/{from}/{where}',
	[
		'controller' => 'search',
		'action' => 'short',
	]
);
$router->add(
	'/search/{from}/{where}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action' => 'index',
	]
);

$router->add(
	'/search/{from}/{where}/{hotelName}-{hotelId:[0-9]+}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action' => 'hotel',
	]
);

/** Hotel routes */
$router->add(
	'/hotel/{name}-{id:[0-9]+}',
	[
		'controller' => 'hotel',
		'action' => 'index',
	]
);
$router->add(
	'/search/hotel/{from}/{where}/{hotelName}-{hotelId:[0-9]+}',
	[
		'controller' => 'search',
		'action' => 'hotelShort',
	]
);
$router->add(
	'/search/hotel/{from}/{where}/{hotelName}-{hotelId:[0-9]+}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action' => 'hotel',
	]
);

/** Countries routes */
$router->add(
	'/countries',
	[
		'controller'    => 'countries',
		'action'        => 'index',
	]
);
$router->add(
	'/countries/{country:[a-z\-]+}',
	[
		'controller' => 'countries',
		'action' => 'country',
	]
);
$router->add(
	'/tury/{region:[a-z\-]+}',
	[
		'controller' => 'countries',
		'action' => 'region',
	]
);
$router->add(
	'/countries/{country:[a-z\-]+}/{region:[a-z\-]+}',
	[
		'controller' => 'countries',
		'action' => 'region',
	]
);

/** Blog routes */
$router->add(
	'/blog',
	[
		'controller'    => 'blog',
		'action'        => 'index',
	]
);
$router->add(
	'/blog/{post:[a-z0-9\-]+}',
	[
		'controller' => 'blog',
		'action' => 'post',
	]
);
$router->add(
	'/blog/author/{author:[a-z0-9\-]+}',
	[
		'controller' => 'blog',
		'action' => 'author',
	]
);
$router->add(
	'/blog/entry/{post:[a-z0-9\-]+}',
	[
		'controller' => 'blog',
		'action' => 'post',
	]
);

/** Other routes */
$router->add(
	'/robots.txt',
	[
		'controller' => 'index',
		'action' => 'robots',
	]
);

$router->notFound(
	array(
		'controller' => 'error',
		'action' => 'error404'
	)
);

return $router;
