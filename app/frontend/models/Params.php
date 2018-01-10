<?php

namespace Frontend\Models;

use Phalcon\Di;

class Params
{
	const COOKIE_KEY = 'user-params';

	public $city;

	/**
	 * @var SearchParams
	 */
	public $search;

	private $config;

	public function store()
	{
		$encodedObject = json_encode($this);
		$cookieTimeout = $this->config->common->cookieTimeout;
		setcookie(self::COOKIE_KEY, $encodedObject, time() + $cookieTimeout, '/');
	}

	public function fromStored($object)
	{
		$searchParams = new SearchParams();
		if ($object->search) {
			$searchParams->fromStored($object->search);
		}
		$this->city = $object->city ?: (int)$this->config->defaults->city;
		$this->search = $searchParams;
	}


	private function defaultSearchParams()
	{
		return new SearchParams();
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Params* via the `new` operator from outside of this class.
	 */
	protected function __construct()
	{
		$this->config = Di::getDefault()->get('config');

		if (array_key_exists(self::COOKIE_KEY, $_COOKIE)) {
			$storedObject = json_decode($_COOKIE[self::COOKIE_KEY]);
			$this->fromStored($storedObject);
		} else {
			$this->city = (int)$this->config->defaults->city;
			$this->search = $this->defaultSearchParams();
		}
	}

	/**
	 * @var Params The reference to *Params* instance of this class
	 */
	protected static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Params The *Params* instance.
	 */
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Params* instance.
	 *
	 * @return void
	 */
	private function __clone()
	{
	}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup()
	{
	}
}