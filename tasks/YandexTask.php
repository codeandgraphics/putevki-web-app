<?php

use Utils\Yandex;

class YandexTask extends \Phalcon\CLI\Task
{

	public function mainAction()
	{
		$yandex = new Yandex();
		$yandex->getData();
	}
}