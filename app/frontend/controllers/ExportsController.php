<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\View;

class ExportsController extends BaseController
{
    public function testAction()
    {
        $css = file_get_contents($this->url->get('exports/css'));
        $scripts = file_get_contents($this->url->get('exports/scripts'));
        $head = file_get_contents($this->url->get('exports/headSearch'));

        echo $css;
        echo $scripts;

        echo $head;

        $this->view->disable();
    }

    public function headAction()
    {
        $this->view->setVar('city', $this->city);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVars([
            'params' => $this->params,
            'page' => 'main'
        ]);
    }

    public function headSearchAction()
    {
        $this->view->setVar('city', $this->city);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVars([
            'params' => $this->params,
            'page' => 'main'
        ]);
    }

    public function footAction()
    {
        $this->view->setVar('city', $this->city);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVars([
            'params' => $this->params,
            'page' => 'main'
        ]);
    }

    public function scriptsAction()
    {
        $this->view->setVar('city', $this->city);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->setVars([
            'params' => $this->params,
            'page' => 'main'
        ]);
    }

    public function cssAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }
}
