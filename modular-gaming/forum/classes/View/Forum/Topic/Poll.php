<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Poll extends Abstract_View {

	public $title = 'Poll';

	public function post()
	{
		$post = $this->post->as_array();
		return $post;
	}
}
