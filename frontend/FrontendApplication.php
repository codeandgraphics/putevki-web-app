<?php

use Phalcon\Config\Adapter\Ini as Config;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;

class FrontendApplication extends \Phalcon\Mvc\Application implements \Phalcon\Di\InjectionAwareInterface
{

	const ENV_DEVELOPMENT = 'development';
	const ENV_PRODUCTION = 'production';

	protected $_ENV;
	protected $_log;
	protected $_config;

	public function __construct()
	{
		parent::__construct();

		$this->initDI();

		setlocale(LC_TIME, 'ru_RU');

	}

	protected function initDI()
	{
		$this->setDI(new \Phalcon\Di\FactoryDefault());

		$services = [
			'config',
			'loader',
			'url',
			'managers',
			'router',
			'session',
			'db',
			'view',
			'dispatcher',
		];

		foreach ($services as $service) {
			if (method_exists($this, $service)) {
				$this->$service();
			}
		}

		//die();
	}

	/** Methods */

	protected function config()
	{
		$config = new Config(APP_PATH . 'config.ini');

		$this->setENV($config->frontend->env);
		date_default_timezone_set($config->app->timezone);

		$this->getDI()->set('config', $config);

		$this->setConfig($config);
	}

	protected function managers()
	{
		$di = $this->getDI();
		$di->setShared('eventsManager', new EventsManager());
		$di->setShared('modelsManager', new ModelsManager());
		$di->setShared('transactionManager', new TransactionManager());
	}

	protected function url()
	{
		$this->getDI()->set('url', function () {
			$url = new \Phalcon\Mvc\Url();

			$config = $this->getConfig();

			$protocol = $config->app->https ? 'https://' : 'http://';
			$baseUri = $protocol . $config->app->domain . $config->frontend->baseUri;

			$staticUri = $protocol .
				$config->app->staticDomain .
				str_replace('%version%', $config->frontend->version, $config->frontend->staticUri);

			$url->setBaseUri($baseUri);
			$url->setStaticBaseUri($staticUri);

			return $url;
		});
	}

	protected function db()
	{
		$this->getDI()->setShared('db', function () {
			$config = $this->getConfig();

			$connection = new \Phalcon\Db\Adapter\Pdo\Mysql([
				'host' => $config->database->host,
				'username' => $config->database->username,
				'password' => $config->database->password,
				'dbname' => $config->database->dbname,
				'charset' => $config->database->charset
			]);
			$connection->setEventsManager($this->getDI()->getShared('eventsManager'));
			return $connection;
		});
	}

	protected function loader()
	{
		$loader = new \Phalcon\Loader();
		$loaderConfig = $this->getDI()->get('config')->loader;

		if (property_exists($loaderConfig, 'namespaces') && count($loaderConfig->namespaces) > 0) {
			if (property_exists($loaderConfig, 'frontend') && count($loaderConfig->frontend) > 0) {
				$namespaces = array_merge((array)$loaderConfig->namespaces, (array)$loaderConfig->frontend);
			} else {
				$namespaces = (array)$loaderConfig->namespaces;
			}
			$loader->registerNamespaces(array_map(function ($item) {
				return APP_PATH . $item;
			}, (array)$namespaces));
		}

		if (property_exists($loaderConfig, 'composer')) {
			require APP_PATH . $loaderConfig->composer;
		}

		$loader->register();
	}

	protected function session()
	{
		$this->getDI()->set(
			'session',
			function () {
				$session = new SessionAdapter();
				$session->start();
				return $session;
			}
		);
	}

	protected function dispatcher()
	{
		$this->getDI()->set(
			'dispatcher',
			function () {
				$eventsManager = $this->getDI()->getShared('eventsManager');
				$eventsManager->attach(
					'dispatch:beforeException',
					function ($event, $dispatcher, $exception) {
						switch ($exception->getCode()) {
							case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
							case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
								$dispatcher->forward(
									array(
										'controller' => 'error',
										'action' => 'error404',
									)
								);
								return false;
						}
					}
				);

				$dispatcher = new Dispatcher();
				$dispatcher->setDefaultNamespace('Frontend\Controllers');
				$dispatcher->setEventsManager($eventsManager);
				return $dispatcher;
			}
		);
	}

	protected function router()
	{
		$this->getDI()->setShared('router', function () {
			return require APP_PATH . 'frontend/Routes.php';
		});
	}

	protected function view()
	{
		$this->getDI()->set('view', function () {
			$view = new View();
			$view->setViewsDir(APP_PATH . $this->getConfig()->frontend->viewsDir);
			$view->registerEngines(array('.volt' => function ($view, $di) {
				$volt = new View\Engine\Volt($view, $di);
				$volt->setOptions(array(
					'compiledPath' => APP_PATH . $this->getConfig()->common->cacheDir . 'volt/',
					'compiledSeparator' => '_'
				));
				return $volt;
			}));
			return $view;
		});



		$this->getDI()->set('simpleView', function () {
			$view = new Phalcon\Mvc\View\Simple();
			$view->setViewsDir(APP_PATH . $this->getConfig()->backend->viewsDir);
			$view->registerEngines(array('.volt' => 'Phalcon\Mvc\View\Engine\Volt'));
			return $view;
		});
	}

	/** Setters/Getters */

	/**
	 * @return mixed
	 */
	public function getENV()
	{
		return $this->_ENV;
	}

	/**
	 * @param mixed $ENV
	 */
	public function setENV($ENV)
	{
		$this->_ENV = $ENV;
	}

	/**
	 * Get the dependency injector
	 *
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->_config;
	}

	/**
	 * Sets the dependency injector
	 *
	 * @param mixed $config
	 */
	public function setConfig(Config $config)
	{
		$this->_config = $config;
	}


}