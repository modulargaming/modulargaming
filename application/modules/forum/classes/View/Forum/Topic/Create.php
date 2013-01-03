<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Create extends View_Base {

	public $title = 'Create Topic';
	public $category;

	public function post()
	{
		$post = $this->post->as_array();
		$post['content'] = Security::xss_clean($post['content']);
		return $post;
	}
}
