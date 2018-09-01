<?php

namespace Frontend\Controllers;

use Models\Blog\Bloggers;
use Models\Tourvisor\Operators;
use Models\Blog\Posts;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorBuilder;

class OperatorsController extends BaseController
{
	public function operatorAction()
	{
		$operatorSlug = $this->dispatcher->getParam('slug');

    $operator = Operators::findFirstBySlug($operatorSlug);
    
		if(!$operator) {
			return $this->response->setStatusCode(404);
		}

		$this->view->setVars([
			'title'         => $operator->fullName . ' на ',
			'operator'      => $operator,
			'page'          => 'operator'
		]);
	}
}
