<?php

defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config(array(

	'database' => array(
		'adapter'		=> 'Mysql',
		'host'			=> 'localhost',
		'username'		=> 'putevki_xml',
		'password'		=> '7Z6t1L3t',
		'dbname'		=> 'putevki_xml',
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
		'env'					=> 'development',// production|development
		'baseUri'				=> '/admin/',
		'controllersDir'		=> APP_PATH . 'backend/controllers/',
		'viewsDir'				=> APP_PATH . 'backend/views/',
		'requestEmail'			=> 'online@putevki.ru',
		'publicURL'				=> 'https://online.putevki.ru/admin/',

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
			'shopId'		=> '00002051', //Идентификатор точки продаж
			'lifeTime'		=> 3600, //Время жизни формы оплаты
			'meanType'		=> '', //Тип платежной системы
			'moneyType'		=> '', //Тип электронной валюты
			'urlOk'			=> 'pay/success/',
			'urlNo'			=> 'pay/fail/',
			'login'			=> '996',
			'password'		=> 'oMGKUvVs771GFvW9yr9qjvNPMw5rwUdsxJZ0qiwt5tEHDIjGwUvMY01CJmHsQ8Kr2jhDDdNvK6OcQUXT',
		],

		'publicURL' => 'https://online.putevki.ru/',
		'phone'		=> '+7 (495) 789 10 39',
		'defaultCity' => 1,
		'defaultFlightCity' => 1,
		'cryptKey'	=> '>d#p>aDW[2mQQX 3{',
		'cookie_remember_timeout'	=> 60*60*24*30,
		'smtp'	=> array(
			'login'		=> 'dubna105@mail.ru',
			'password'	=> 'T52a59W88',
			'host'		=> 'ssl://smtp.mail.ru',
			'name'		=> 'Putevki.ru',
			'to'		=> 'online@putevki.ru'
		)
	]
));
