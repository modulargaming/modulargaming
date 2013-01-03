<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Delete extends View_Base {

	public $title = 'Delete Topic';
	public $category;

	public function post()
	{
		$post = $this->post->as_array();
		return $post;
	}
}
