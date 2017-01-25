<?php

use Phalcon\Http\Response			as Response,
	Phalcon\Mvc\View				as View,
	Models\Tourvisor				as Tourvisor,
	Models\Cities					as Cities,
	Frontend\Models\SearchQueries	as SearchQueries,
	Frontend\Models\Populars		as Populars;

class ExportsController extends ControllerFrontend
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
