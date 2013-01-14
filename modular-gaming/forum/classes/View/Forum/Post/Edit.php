<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Post_Edit extends Abstract_View {

	public $post;

	public function title()
	{
		return 'Editing post #'.$this->post->id;
	}

	public function post()
	{
		$post = $this->post->as_array();
		return $post;
	}


}

