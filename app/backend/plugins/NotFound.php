<?php

namespace Backend\Plugins;

use Phalcon\Mvc\User\Plugin;

class NotFound extends Plugin
{
    public function beforeException($event, $dispatcher, $exception)
    {
        $dispatcher->forward(array(
            'controller' => 'index',
            'action' => 'error404'
        ));

        return false;
    }
}
