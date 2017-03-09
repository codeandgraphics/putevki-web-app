<?php
namespace Models\Api;

use Phalcon\Http\Response;

class JSONResponse extends Response
{
	public function __construct($error, $data = [])
	{
		parent::__construct();
		$data['error'] = new Error($error);
		$this->setHeader('Content-Type', 'application/json');
		$this->setJsonContent($data);
	}
}