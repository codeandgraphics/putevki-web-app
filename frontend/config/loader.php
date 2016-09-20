<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
	array(
		$config->frontend->controllersDir
	)
);

$namespaces = array_merge((array) $config->namespaces, (array) $config->frontend->namespaces);

$loader->registerNamespaces($namespaces);

$loader->register();
