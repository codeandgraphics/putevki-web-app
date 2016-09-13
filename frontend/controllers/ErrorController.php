<?php

class ErrorController extends ControllerFrontend
{
	public function error404Action()
	{
		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
		$this->response->setStatusCode(404, 'Not Found');
		$this->view->pick('error/404');
	}

	public function error204Action()
	{
		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
		$this->response->setStatusCode(204, 'No content');
		$this->view->pick('error/204');
	}
}