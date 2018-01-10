<?php

namespace Frontend\Controllers;

use Models\Blog\Bloggers;
use Models\Blog\Posts;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorBuilder;

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
				'post.createdBy = author.id',
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

		$morePosts = $this->modelsManager->createBuilder()
			->addFrom(Posts::name())
			->where('created_by = :authorId:', ['authorId' => $post->created_by])
			->andWhere('id <> :postId:', ['postId' => $post->id])
			->orderBy('created DESC')
			->limit(5)
			->getQuery()
			->execute();

		$this->view->setVars([
			'title'         => $post->title . ' на ',
			'post'          => $post,
			'page'          => 'post',
			'morePosts'     => $morePosts,
			'meta'          => $post->getMeta()
		]);
	}

	public function authorAction() {
		$authorUri = $this->dispatcher->getParam('author');

		$author = Bloggers::findFirstByUri($authorUri);

		if(!$author) {
			return $this->response->setStatusCode(404);
		}

		$builder = $this->modelsManager->createBuilder()
			->from(Posts::name())
			->where('created_by = :author:', ['author' => $author->id])
			->orderBy('created DESC');

		$paginator = new PaginatorBuilder(
			array(
				'builder'   => $builder,
				'limit'     => 10,
				'page'      => $this->request->get('page')
			)
		);

		$this->view->setVars([
			'title'         => 'Блог ' . $author->name . ' на ',
			'pagination'    => $paginator->getPaginate(),
			'author'        => $author,
			'page'          => 'post',
			'meta'          => $author->getMeta()
		]);
	}
}
