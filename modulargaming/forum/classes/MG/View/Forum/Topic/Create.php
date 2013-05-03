<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Forum_Topic_Create extends Abstract_View_Forum_Category {

	public $title = 'Create Topic';

	public function post()
	{
		$post = $this->post->as_array();
		return $post;
	}

	public function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Create',
				'href'  => Route::url('forum.category', array(
					'action' => 'create',
					'id'     => $this->category->id
				))
			)
		));
	}
}
