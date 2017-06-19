<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\View;
use Models\Cities;

class ExportsController extends BaseController
{
	public function testAction() {
		$css = file_get_contents('https://online.putevki.ru/exports/css');
		$scripts = file_get_contents('https://online.putevki.ru/exports/scripts');
		$head = file_get_contents('https://online.putevki.ru/exports/headSearch');

		echo $css;
		echo $scripts;

		echo $head;

		$this->view->disable();
	}

	public function headAction()
	{
		$this->currentCity = Cities::checkCity();
		$this->view->setVar('currentCity', $this->currentCity);

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

		$this->view->setVars([
			'params'			=> $this->params,
			'page'				=> 'main'
		]);
	}

	public function headSearchAction()
	{
		$this->currentCity = Cities::checkCity();
		$this->view->setVar('currentCity', $this->currentCity);

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

		$this->view->setVars([
			'params'			=> $this->params,
			'page'				=> 'main'
		]);
	}
	
	public function footAction()
	{
		$this->currentCity = Cities::checkCity();
		$this->view->setVar('currentCity', $this->currentCity);

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		
		$this->view->setVars([
			'params'			=> $this->params,
			'page'				=> 'main'
		]);
	}
	
	public function scriptsAction()
	{
		$this->currentCity = Cities::checkCity();
		$this->view->setVar('currentCity', $this->currentCity);

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		
		$this->view->setVars([
			'params'			=> $this->params,
			'page'				=> 'main'
		]);
	}
	
	public function cssAction()
	{
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	}
		
}
