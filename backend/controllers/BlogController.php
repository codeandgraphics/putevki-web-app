<?php

namespace Backend\Controllers;

use Models\Blog\Bloggers;
use Models\Blog\Posts;
use Models\Countries;
use Models\Regions;
use Models\Tourvisor;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;

class BlogController extends ControllerBase
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
			->orderBy('post.created DESC');

		$posts = $builder->getQuery()->execute();

		$this->view->setVar('posts', $posts);

	}

	public function postAction()
	{
		$id = $this->dispatcher->getParam(0, 'int');
		$post = Posts::findFirstById($id);

		$form = new Form($post);

		$form->add(new Text('uri'));
		$form->add(new Text('title'));
		$form->add(new File('preview'));
		$form->add(new TextArea('excerpt'));
		$form->add(new TextArea('content'));
		$form->add(new Text('metaKeywords'));
		$form->add(new TextArea('metaDescription'));
		$form->add(new Select('active', [0 => 'Нет', 1 => 'Да']));

		if ($this->request->isPost()) {
			$form->bind($_POST, $post);

			if ($form->isValid()) {

				if($this->request->hasFiles()) {
					$file = $this->request->getUploadedFiles()[0];

					if($file->getSize() > 0) {
						$fileName = $post->id . '.' . $file->getExtension();
						$path = $this->config->images->path . 'blog/' . $fileName;
						$file->moveTo($path);
						$post->preview = $fileName;
					}
				}

				$post->save();
				$this->flashSession->success('Пост успешно сохранен');
			}
		}

		$this->view->setVar('post', $post);
		$this->view->setVar('form', $form);
	}
}