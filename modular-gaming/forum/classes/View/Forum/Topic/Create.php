<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Create extends Abstract_View {

	public $title = 'Create Topic';
	public $category;

	public function post()
	{
		$post = $this->post->as_array();
		return $post;
	}
}
