<?php

namespace Models\Blog;

use Models\BaseModel;
use Models\Meta;
use Phalcon\Mvc\Model;

class Posts extends BaseModel {
	public $id;
	public $created_by;
	public $title;
	public $uri;
	public $excerpt;
	public $content;
	public $preview;
	public $metaKeywords;
	public $metaDescription;
	public $created;
	public $active;

	public function getSource()
	{
		return 'blog_posts';
	}

	/**
	 * @return Meta
	 */
	public function getMeta() : Meta {
		return new Meta($this->metaKeywords, $this->metaDescription);
	}

	public function fromJoomla($data) {

		$this->id = (int) $data['id'];
		$this->created_by = (int) $data['created_by'];
		$this->created = $data['created'];
		$this->title = $data['title'];
		$this->uri = $data['permalink'];
		$this->excerpt = $data['intro'];
		$this->content = $data['content'];
		$this->active = 1;

		$preview = json_decode($data['image']);
		if($preview->mime === 'image/jpeg') {
			$previewData = file_get_contents($preview->url);
			if($previewData) {
				$path = $this->getDI()->get('config')->images->path . 'blog/' . $this->id . '.jpg';
				file_put_contents($path, $previewData);
				$this->preview = $this->id . '.jpg';
			}
		}
	}

	/**
	 * @param $uri
	 * @return Posts|Model
	 */
	public static function findFirstByUri($uri) {
		return self::findFirst("uri = '$uri'");
	}
}