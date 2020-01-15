<?php

namespace Backend\Controllers;

use Models\Blog\Bloggers;
use Models\Blog\Posts;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Form;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class BlogController extends ControllerBase
{
    public function indexAction()
    {
        $builder = $this->modelsManager
            ->createBuilder()
            ->columns(['post.*', 'author.*'])
            ->addFrom(Posts::name(), 'post')
            ->leftJoin(Bloggers::name(), 'post.createdBy = author.id', 'author')
            ->orderBy('post.created DESC');

        $posts = $builder->getQuery()->execute();

        $paginator = new PaginatorModel(array(
            'data' => $posts,
            'limit' => 20,
            'page' => $this->request->get('page')
        ));

        $this->view->setVar('pagination', $paginator->getPaginate());

        $this->view->setVar('posts', $posts);
    }

    public function postAction()
    {
        $id = $this->dispatcher->getParam(0, 'int');

        if ($id) {
            $post = Posts::findFirstById($id);
            $form = new Form($post);
        } else {
            $form = new Form();
        }

        $form->add(new Text('uri'));
        $form->add(new Text('title'));
        $form->add(new File('preview'));
        $form->add(new TextArea('excerpt'));
        $form->add(new TextArea('content'));
        $form->add(new Text('metaKeywords'));
        $form->add(new TextArea('metaDescription'));
        $form->add(new Select('active', [0 => 'Нет', 1 => 'Да']));
        $form->add(
            new Select('createdBy', Bloggers::find(), [
                'using' => ['id', 'name']
            ])
        );

        if ($this->request->isPost()) {
            if (!$id) {
                $post = new Posts();
            }
            $form->bind($_POST, $post);

            if ($form->isValid()) {
                if ($this->request->hasFiles()) {
                    $file = $this->request->getUploadedFiles()[0];

                    if ($file->getSize() > 0) {
                        $fileName = $post->id . '.' . $file->getExtension();
                        $path =
                            $this->config->images->path . 'blog/' . $fileName;
                        $file->moveTo($path);
                        $post->preview = $fileName;
                    }
                }
                if (!$id) {
                    $post->created = new \DateTime();
                    $post->create();
                    $this->response->redirect('admin/blog/post/' . $post->id);
                } else {
                    $post->save();
                    $this->flashSession->success('Пост успешно сохранен');
                }
            }
        }

        $this->view->setVar('post', $post);
        $this->view->setVar('form', $form);
    }

    public function bloggersAction()
    {
        $bloggers = Bloggers::find();

        $paginator = new PaginatorModel(array(
            'data' => $bloggers,
            'limit' => 10,
            'page' => $this->request->get('page')
        ));

        $this->view->setVar('pagination', $paginator->getPaginate());

        $this->view->setVar('bloggers', $bloggers);
    }

    public function bloggerAction()
    {
        $id = $this->dispatcher->getParam(0, 'int');
        $blogger = Bloggers::findFirstById($id);

        $form = new Form($blogger);

        $form->add(new Text('name'));
        $form->add(new Text('link'));
        $form->add(new Text('uri'));
        $form->add(new File('image'));
        $form->add(new TextArea('description'));
        $form->add(new Text('metaKeywords'));
        $form->add(new TextArea('metaDescription'));
        $form->add(new Select('active', [0 => 'Нет', 1 => 'Да']));

        if ($this->request->isPost()) {
            $form->bind($_POST, $blogger);

            if ($form->isValid()) {
                if ($this->request->hasFiles()) {
                    $file = $this->request->getUploadedFiles()[0];

                    if ($file->getSize() > 0) {
                        $fileName = $blogger->id . '.' . $file->getExtension();
                        $path =
                            $this->config->images->path .
                            'blog/bloggers/' .
                            $fileName;
                        $file->moveTo($path);
                        $blogger->image = $fileName;
                    }
                }

                $blogger->save();
                $this->flashSession->success('Блоггер успешно сохранен');
            }
        }

        $this->view->setVar('blogger', $blogger);
        $this->view->setVar('form', $form);
    }
}
