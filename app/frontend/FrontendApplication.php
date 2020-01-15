<?php

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as Config;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;

class FrontendApplication extends Application
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
            'router',
            'session',
            'db',
            'view',
            'dispatcher'
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

        $this->di->set('config', $config);
    }

    protected function managers()
    {
        $this->di->setShared('eventsManager', new EventsManager());
        $this->di->setShared('modelsManager', new ModelsManager());
        $this->di->setShared('transactionManager', new TransactionManager());
    }

    protected function url()
    {
        $this->di->set('url', function () {
            $url = new \Phalcon\Mvc\Url();

            $config = $this->get('config');

            $protocol = $config->app->https ? 'https://' : 'http://';
            $baseUri =
                $protocol . $config->app->domain . $config->frontend->baseUri;

            $staticUri =
                $protocol .
                $config->app->staticDomain .
                str_replace(
                    '%version%',
                    $config->frontend->version,
                    $config->frontend->staticUri
                );

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
            $baseUri =
                $protocol . $config->app->domain . $config->backend->baseUri;

            $url->setBaseUri($baseUri);

            return $url;
        });
    }

    protected function imagesUrl()
    {
        $this->di->setShared('imagesUrl', function () {
            $url = new \Phalcon\Mvc\Url();

            $config = $this->get('config');

            $protocol = $config->app->https ? 'https://' : 'http://';
            $baseUri =
                $protocol . $config->images->domain . $config->images->baseUri;

            $url->setBaseUri($baseUri);

            return $url;
        });
    }

    protected function db()
    {
        $this->di->setShared('db', function () {
            $config = $this->get('config');

            $connection = new Mysql([
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

    protected function loader()
    {
        $loader = new \Phalcon\Loader();
        $loaderConfig = $this->di->get('config')->loader;

        if (
            property_exists($loaderConfig, 'namespaces') &&
            count($loaderConfig->namespaces) > 0
        ) {
            if (
                property_exists($loaderConfig, 'frontend') &&
                count($loaderConfig->frontend) > 0
            ) {
                $namespaces = array_merge(
                    (array) $loaderConfig->namespaces,
                    (array) $loaderConfig->frontend
                );
            } else {
                $namespaces = (array) $loaderConfig->namespaces;
            }
            $loader->registerNamespaces(
                array_map(function ($item) {
                    return APP_PATH . $item;
                }, (array) $namespaces)
            );
        }

        if (property_exists($loaderConfig, 'composer')) {
            require APP_PATH . $loaderConfig->composer;
        }

        $loader->register();
    }

    protected function session()
    {
        $this->di->set('session', function () {
            $session = new SessionAdapter();
            $session->start();
            return $session;
        });
    }

    protected function dispatcher()
    {
        $this->di->set('dispatcher', function () {
            $eventsManager = $this->getShared('eventsManager');
            $eventsManager->attach('dispatch:beforeException', function (
                $event,
                $dispatcher,
                $exception
            ) {
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(array(
                            'controller' => 'error',
                            'action' => 'error404'
                        ));
                        return false;
                }
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Frontend\Controllers');
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });
    }

    protected function router()
    {
        $this->di->setShared('router', function () {
            return require APP_PATH . 'frontend/Routes.php';
        });
    }

    protected function view()
    {
        $this->di->set('view', function () {
            $config = $this->get('config');

            $view = new View();
            $view->setViewsDir(APP_PATH . $config->frontend->viewsDir);
            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) {
                    $volt = new View\Engine\Volt($view, $di);
                    $volt->setOptions(array(
                        'compiledPath' =>
                            APP_PATH . $config->common->cacheDir . 'volt/',
                        'compiledSeparator' => '_'
                    ));
                    $compiler = $volt->getCompiler();

                    \Utils\Common::addCompilerActions($compiler);

                    return $volt;
                }
            ));
            return $view;
        });

        $this->di->set('simpleView', function () {
            $config = $this->get('config');

            $view = new Phalcon\Mvc\View\Simple();
            $view->setViewsDir(APP_PATH . $config->backend->viewsDir);
            $view->registerEngines(array(
                '.volt' => function ($view, $di) use ($config) {
                    $volt = new View\Engine\Volt($view, $di);
                    $volt->setOptions(array(
                        'compiledPath' =>
                            APP_PATH . $config->common->cacheDir . 'volt/',
                        'compiledSeparator' => '_'
                    ));
                    $compiler = $volt->getCompiler();

                    \Utils\Common::addCompilerActions($compiler);

                    return $volt;
                }
            ));
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
