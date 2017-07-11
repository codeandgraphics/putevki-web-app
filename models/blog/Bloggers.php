<?php

namespace Models\Blog;

use Models\BaseModel;
use Phalcon\Mvc\Model;

class Bloggers extends BaseModel {
	public $id;
	public $name;
	public $link;
	public $uri;
	public $image;
	public $description;
	public $active;

	public function getSource()
	{
		return 'blog_bloggers';
	}

	/**
	 * @param $uri
	 * @return Bloggers|Model
	 */
	public static function findFirstByUri($uri) {
		return self::findFirst("uri = '$uri'");
	}
}