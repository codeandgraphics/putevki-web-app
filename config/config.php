<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config(array(

	'database' => array(
		'adapter'		=> 'Mysql',
		'host'			=> 'localhost',
		'username'		=> 'putevki_spb',
		'password'		=> '1R6r0V3z',
		'dbname'		=> 'putevki_spb',
		'charset'		=> 'utf8'
	),
	'cacheDir'				=> APP_PATH . 'cache/',

	'namespaces'			=> [
		'Interfaces'				=> APP_PATH . 'interfaces/',
		'Models\Tourvisor'			=> APP_PATH . 'models/tourvisor/',
		'Models\References'			=> APP_PATH . 'models/references/',
		'Models\Yandex'				=> APP_PATH . 'models/yandex/',
		'Models'					=> APP_PATH . 'models/',
		'Utils'						=> APP_PATH . 'utils/',
		'Utils\Email'				=> APP_PATH . 'utils/email/',
	],

	'backend'   => [
		'env'					=> 'production',// production|development
		'baseUri'				=> '/admin/',
		'controllersDir'		=> APP_PATH . 'backend/controllers/',
		'viewsDir'				=> APP_PATH . 'backend/views/',
		'requestEmail'			=> 'info@toursfera.com',
		'publicURL'				=> 'https://spb.putevki.ru/admin/',

		'namespaces'    => [
			'Backend\Controllers'		=> APP_PATH . 'backend/controllers/',
			'Backend\Models'			=> APP_PATH . 'backend/models/',
			'Backend\Plugins'			=> APP_PATH . 'backend/plugins/',
			'Backend\Utils'				=> APP_PATH . 'backend/utils/'
		],
	],

	'frontend'   => [
		'env'					=> 'production',// production|development
		'baseUri'				=> '/',
		'controllersDir'		=> APP_PATH . 'frontend/controllers/',
		'viewsDir'				=> APP_PATH . 'frontend/views/',

		'namespaces'    => [
			'Frontend\Models'			=> APP_PATH . 'frontend/models/',
			'Backend\Controllers'		=> APP_PATH . 'backend/controllers/',
			'Backend\Models'			=> APP_PATH . 'backend/models/',
			'Backend\Plugins'			=> APP_PATH . 'backend/plugins/'
		],

		'routes'		=> [

			'/{city}' => [
				'controller'	=> 'index',
				'action'		=> 'index'
			],

			'/agreement' => [
				'controller'	=> 'index',
				'action'		=> 'agreement'
			],

			'/uniteller' => [
				'controller'	=> 'index',
				'action'		=> 'uniteller'
			],

			'/tour/{id}' => [
				'controller'	=> 'tour',
				'action'		=> 'index'
			],

			'/yandex' => [
				'controller'	=> 'yandex',
				'action'		=> 'index'
			],

			'/pay/{requestId}' => [
				'controller'	=> 'pay',
				'action'		=> 'index'
			],

			'/search/{from}/{where}/' => [
				'controller'	=> 'search',
				'action'		=> 'short'
			],

			'/search/{from}/{where}' => [
				'controller'	=> 'search',
				'action'		=> 'short'
			],

			'/search/hotel/{from}/{where}/{hotelName}-{id:[0-9]+}/' => [
				'controller'	=> 'search',
				'action'		=> 'hotelShort'
			],

			'/search/hotel/{from}/{where}/{hotelName}-{id:[0-9]+}' => [
				'controller'	=> 'search',
				'action'		=> 'hotelShort'
			],

			'/hotel/{name}-{id:[0-9]+}' => [
				'controller'	=> 'hotel',
				'action'		=> 'index'
			],

			'/search/{from}/{where}/{date}/{nights}/{adults}/{kids}/{starsbetter}/{mealbetter}' => [
				'controller'	=> 'search',
				'action'		=> 'index'
			],

			'/search/{from}/{where}/{date}/{nights}/{adults}/{kids}/{starsbetter}/{mealbetter}/' => [
				'controller'	=> 'search',
				'action'		=> 'index'
			],

			'/search/hotel/{from}/{where}/{hotelName}-{id:[0-9]+}/{date}/{nights}/{adults}/{kids}/{starsbetter}/{mealbetter}' => [
				'controller'	=> 'search',
				'action'		=> 'hotel'
			],

			'/search/{from}/{where}/{hotelName}-{id:[0-9]+}/{date}/{nights}/{adults}/{kids}/{starsbetter}/{mealbetter}/' => [
				'controller'	=> 'search',
				'action'		=> 'hotel'
			],

			'/robots.txt'	=> [
				'controller'	=> 'index',
				'action'		=> 'robots'
			],

		],

		'uniteller'		=> [
			'orderPrefix'	=> 'PTVK',
			'shopId'		=> '00009500',
			'lifeTime'		=> 3600,
			'meanType'		=> '',
			'moneyType'		=> '',
			'urlOk'			=> 'pay/success/',
			'urlNo'			=> 'pay/fail/',
			'login'			=> '1830',
			'password'		=> 'obHl9HviE4XW5O53hIV1RQJWmT6HRgMvXh79Ml4eelAYVgDpgvNVQSHn9LNiGg1Hnya8kffaNIEZdSbM',
		],

		'publicURL' => 'https://spb.putevki.ru/',
		'defaultCity' => 1,
		'defaultFlightCity' => 5,
		'phone'		=> '+7 (812) 643-34-09',
		'cryptKey'	=> '>d#p>aDW[2mQQX 3{',
		'cookie_remember_timeout'	=> 60*60*24*30,
		'smtp'	=> array(
			'login'		=> 'dubna105@mail.ru',
			'password'	=> 'T52a59W88',
			'host'		=> 'ssl://smtp.mail.ru',
			'name'		=> 'Putevki.ru',
			'to'		=> 'info@toursfera.ru'
		)
	]
));
