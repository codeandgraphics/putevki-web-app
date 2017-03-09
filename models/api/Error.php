<?php
namespace Models\Api;

class Error
{
	public $code;
	public $text;

	const NOT_IMPLEMENTED		        = -1;
	const NO_ERROR				        = 0;

	const API_ERROR				        = 1000;
	const API_AUTH_ERROR		        = 1001;
	const API_METHOD_NOT_FOUND	        = 1002;
	const API_PARAMS_MISSED		        = 1003;

	public function __construct($code)
	{
		$errors = [
			self::NOT_IMPLEMENTED               => 'Not implemented',
			self::NO_ERROR                      => 'OK',
			self::API_ERROR				        => 'API Error',
			self::API_AUTH_ERROR		        => 'API Authentication error',
			self::API_METHOD_NOT_FOUND	        => 'Method not found',
			self::API_PARAMS_MISSED		        => 'Some params missed',
		];
		$this->code = $code;
		$this->text = $errors[$code];
	}
}