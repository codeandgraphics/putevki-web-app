<?php

use Phalcon\Config\Adapter\Ini as Config;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\View;

class BackendApplication extends \Phalcon\Mvc\Application
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
			'backendUrl',
			'imagesUrl',
			'managers',
			'session',
			'flashSession',
			'db',
			'view',
			'dispatcher',
		];

		foreach ($services as $service) {
			if (method_exists($this, $service)) {
				$this->$service();
			}
		}
	}

	/** Methods */

	protected function config()
	{
		$config = new Config(APP_PATH . 'config.ini');

		$this->setENV($config->backend->env);
		date_default_timezone_set($config->app->timezone);

		$this->di->set('config', $config);
	}

	protected function managers()
	{
		$eventsManager = new EventsManager();
		$eventsManager->attach('dispatch:beforeDispatch', new Backend\Plugins\Security);

		$this->di->setShared('eventsManager', $eventsManager);
		$this->di->setShared('modelsManager', new ModelsManager());
		$this->di->setShared('transactionManager', new TransactionManager());
	}

	protected function url()
	{
		$this->di->set('url', function () {
			$url = new \Phalcon\Mvc\Url();

			$config = $this->get('config');

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

	protected function backendUrl()
	{
		$this->di->setShared('backendUrl', function () {
			$url = new \Phalcon\Mvc\Url();

			$config = $this->get('config');

			$protocol = $config->app->https ? 'https://' : 'http://';
			$baseUri = $protocol . $config->app->domain . $config->backend->baseUri;

			$url->setBaseUri($baseUri);

			return $url;
		});
	}

	protected function imagesUrl() {
		$this->di->setShared('imagesUrl', function() {
			$url = new \Phalcon\Mvc\Url();

			$config = $this->get('config');

			$protocol = $config->app->https ? 'https://' : 'http://';
			$baseUri = $protocol . $config->images->domain . $config->images->baseUri;

			$url->setBaseUri($baseUri);

			return $url;
		});
	}

	protected function db()
	{
		$this->di->setShared('db', function () {

            $config = $this->get('config');

			$connection = new \Phalcon\Db\Adapter\Pdo\Mysql([
				'host' => $config->database->host,
				'username' => $config->database->username,
				'password' => $config->database->password,
				'dbname' => $config->database->dbname,
				'charset' => $config->database->charset
			]);
			$connection->setEventsManager($this->getShared('eventsManager'));
			return $connection;
		});
	}

	protected function flashSession()
	{
		$this->di->set('flashSession', function () {
			return new FlashSession(
				array(
					'error'   => 'danger',
					'success' => 'success',
					'notice'  => 'info',
					'warning' => 'warning'
				)
			);
		});
	}

	protected function loader()
	{
		$loader = new \Phalcon\Loader();
		$loaderConfig = $this->di->get('config')->loader;

		if (property_exists($loaderConfig, 'namespaces') && count($loaderConfig->namespaces) > 0) {
			if (property_exists($loaderConfig, 'backend') && count($loaderConfig->backend) > 0) {
				$namespaces = array_merge((array)$loaderConfig->namespaces, (array)$loaderConfig->backend);
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
		$this->di->set(
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
		$this->di->set(
			'dispatcher',
			function () {
				$dispatcher = new \Phalcon\Mvc\Dispatcher();
				$dispatcher->setDefaultNamespace('Backend\Controllers');
				$dispatcher->setEventsManager($this->getShared('eventsManager'));
				return $dispatcher;
			}
		);
	}

	protected function view()
	{
		$this->di->set('view', function () {

			$config = $this->get('config');
			$view = new View();
			$view->setViewsDir(APP_PATH . $config->backend->viewsDir);
			$view->registerEngines(array('.volt' => function ($view, $di) use ($config) {
				$volt = new View\Engine\Volt($view, $di);
				$volt->setOptions(array(
					'compiledPath' => APP_PATH . $config->common->cacheDir . 'volt/',
					'compiledSeparator' => '_'
				));
				$compiler = $volt->getCompiler();

				\Utils\Common::addCompilerActions($compiler);

				return $volt;
			}));
			return $view;
		});

		$this->di->set('simpleView', function () {
			$config = $this->get('config');

			$view = new Phalcon\Mvc\View\Simple();
			$view->setViewsDir(APP_PATH . $config->backend->viewsDir);
			$view->registerEngines(array('.volt' => function ($view, $di) use ($config) {
				$volt = new View\Engine\Volt($view, $di);
				$volt->setOptions(array(
					'compiledPath' => APP_PATH . $config->common->cacheDir . 'volt/',
					'compiledSeparator' => '_'
				));
				$compiler = $volt->getCompiler();

				\Utils\Common::addCompilerActions($compiler);

				return $volt;
			}));
			return $view;
		});
	}

	/** Methods */

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
}