<?php

namespace Frontend\Controllers;

use Models\Blog\Bloggers;
use Models\Blog\Posts;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class BlogController extends BaseController
{
	public function indexAction()
	{
		$builder = $this->modelsManager->createBuilder()
			->columns([
				'post.*',
				'author.*'
			])
			->addFrom(Posts::name(), 'post')
			->leftJoin(
				Bloggers::name(),
				'post.created_by = author.id',
				'author')
			->where('post.active = 1')
			->orderBy('post.created DESC');

		$posts = $builder->getQuery()->execute();

		$paginator = new PaginatorModel(
			array(
				'data' => $posts,
				'limit' => 20,
				'page' => $this->request->get('page')
			)
		);

		$this->view->setVar('pagination', $paginator->getPaginate());

		$this->view->setVar('posts', $posts);

		$this->view->setVars([
			'title'     => 'Блог о путешествиях на ',
		]);
	}

	public function postAction()
	{
		$postUri = $this->dispatcher->getParam('post');

		$post = Posts::findFirstByUri($postUri);

		if(!$post) {
			return $this->response->setStatusCode(404);
		}



		$this->view->setVars([
			'title'         => $post->title . ' на ',
			'post'          => $post,
			'page'          => 'post',
			'meta'          => $post->getMeta()
		]);
	}
}
