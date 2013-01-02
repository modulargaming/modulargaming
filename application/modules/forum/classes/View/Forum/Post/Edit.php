<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Post_Edit extends View_Base {

	public $post;

	public function title()
	{
		return 'Editing post #'.$this->post->id;
	}

	public function post()
	{
		return $this->post->as_array();
	}


}

