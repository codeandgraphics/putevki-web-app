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
        'excluded' => true
	]
);
$router->add(
    '/sitemap.xml',
    [
        'controller' => 'sitemap',
        'action' => 'index',
        'excluded' => true
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
    '/goryashchie-tury',
    [
        'controller' => 'index',
        'action' => 'hot',
    ]
);
$router->add(
    '/tury-bez-pereleta',
    [
        'controller' => 'index',
        'action' => 'withoutFlight',
    ]
);
$router->add(
    '/kontakty',
    [
        'controller' => 'index',
        'action' => 'kontakty',
        'excluded' => true
    ]
);
$router->add(
    '/mobile.html',
    [
        'controller' => 'index',
        'action' => 'mobileHtml',
        'excluded' => true
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
        'excluded' => true
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
        'excluded' => true
	]
);
$router->add(
	'/pay/{paymentId:[0-9]+}',
	[
		'controller' => 'pay',
		'action' => 'index',
        'excluded' => true
	]
);

/** Search routes */
$router->add(
	'/search/{from}/{where}',
	[
		'controller' => 'search',
		'action' => 'short',
        'excluded' => true
	]
);
$router->add(
	'/search/{from}/{where}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action' => 'index',
        'excluded' => true
	]
);

$router->add(
	'/search/{from}/{where}/{hotelName}-{hotelId:[0-9]+}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action' => 'hotel',
        'excluded' => true
	]
);

/** Hotel routes */
$router->add(
	'/hotel/{name}-{id:[0-9]+}',
	[
		'controller' => 'hotel',
		'action' => 'index',
        'excluded' => true
	]
);
$router->add(
	'/search/hotel/{from}/{where}/{hotelName}-{hotelId:[0-9]+}',
	[
		'controller' => 'search',
		'action' => 'hotelShort',
        'excluded' => true
	]
);
$router->add(
	'/search/hotel/{from}/{where}/{hotelName}-{hotelId:[0-9]+}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action' => 'hotel',
        'excluded' => true
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
        'excluded' => true
	]
);
$router->add(
	'/tury/{region:[a-z\-]+}',
	[
		'controller' => 'countries',
		'action' => 'tury',
        'excluded' => true
	]
);
$router->add(
	'/countries/{country:[a-z\-]+}/{region:[a-z\-]+}',
	[
		'controller' => 'countries',
		'action' => 'region',
        'excluded' => true
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
        'excluded' => true
	]
);
$router->add(
	'/blog/author/{author:[a-z0-9\-]+}',
	[
		'controller' => 'blog',
		'action' => 'author',
        'excluded' => true
	]
);
$router->add(
	'/blog/entry/{post:[a-z0-9\-]+}',
	[
		'controller' => 'blog',
		'action' => 'entry',
        'excluded' => true
	]
);

/** Other routes */
$router->add(
	'/robots.txt',
	[
		'controller' => 'index',
		'action' => 'robots',
        'excluded' => true
	]
);

$router->notFound(
	array(
		'controller' => 'error',
		'action' => 'error404'
	)
);

return $router;