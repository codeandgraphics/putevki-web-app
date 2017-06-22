<?php

use Phalcon\Mvc\Router;

$router = new Router();

$router->removeExtraSlashes(true);


$router->add(
    '/',
    [
        'controller' => 'index',
        'action'     => 'index',
    ]
);
$router->add(
	'/{city:[a-z\-]+}',
	[
		'controller' => 'index',
		'action'     => 'city',
	]
);
$router->add(
	'/agreement',
	[
		'controller' => 'index',
		'action'     => 'agreement',
	]
);
$router->add(
	'/app',
	[
		'controller' => 'index',
		'action'     => 'app',
	]
);
$router->add(
	'/uniteller',
	[
		'controller' => 'index',
		'action'     => 'uniteller',
	]
);
$router->add(
	'/tour/{id}',
	[
		'controller' => 'tour',
		'action'     => 'index',
	]
);
$router->add(
	'/pay/{requestId:[0-9]+}',
	[
		'controller' => 'pay',
		'action'     => 'index',
	]
);

/** Search routes */
$router->add(
	'/search/{from}/{where}',
	[
		'controller' => 'search',
		'action'     => 'short',
	]
);
$router->add(
	'/search/{from}/{where}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action'     => 'index',
	]
);

$router->add(
	'/search/{from}/{where}/{hotelName}-{hotelId:[0-9]+}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action'     => 'hotel',
	]
);

/** Hotel routes */
$router->add(
	'/hotel/{name}-{id:[0-9]+}',
	[
		'controller' => 'hotel',
		'action'     => 'index',
	]
);
$router->add(
	'/search/hotel/{from}/{where}/{hotelName}-{hotelId:[0-9]+}',
	[
		'controller' => 'search',
		'action'     => 'hotelShort',
	]
);
$router->add(
	'/search/hotel/{from}/{where}/{hotelName}-{hotelId:[0-9]+}/{date}/{nights}/{adults}/{children}/{stars}/{meal}',
	[
		'controller' => 'search',
		'action'     => 'hotel',
	]
);

/** Other routes */
$router->add(
	'/robots.txt',
	[
		'controller' => 'index',
		'action'     => 'robots',
	]
);

$router->notFound(
	array(
		'controller' => 'error',
		'action'     => 'error404'
	)
);

return $router;
