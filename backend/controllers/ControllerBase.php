<?php

namespace Backend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Backend\Models\Users;

class ControllerBase extends Controller
{
	public $user;

	public function initialize()
	{
		$user = $this->session->get('auth');

		$this->user = Users::findFirst($user['id']);

		$this->view->user = $this->user;
	}

	public function error404Action()
	{
	}

	public function error404()
	{
		$this->view->disable();
		$response = new Response();
		$response->setStatusCode(404, 'Not Found');
		$response->setContent('Page not found');
		$response->send();
	}
}
