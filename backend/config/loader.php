<?php
setlocale(LC_ALL, 'ru_RU.utf8');

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
        $config->backend->controllersDir
    )
);

$namespaces = array_merge((array) $config->namespaces, (array) $config->backend->namespaces);

$loader->registerNamespaces($namespaces);

$loader->register();

