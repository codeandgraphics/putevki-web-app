<?php

namespace Models\Blog;

use Models\BaseModel;

class Bloggers extends BaseModel {
	public $id;
	public $name;
	public $uri;
	public $image;
	public $active;

	public function getSource()
	{
		return 'blog_bloggers';
	}
}