<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Post_Index extends View_Base {

	public $post;

	public function title()
	{
		return 'Forum - ' . $this->post['title'];
	}

	public function post()
	{
		$post = $this->post;
		$post['created'] = Date::format($post['created']);
		$post['user_id'] = $post['user_id'];
		return $post;
	}


}
