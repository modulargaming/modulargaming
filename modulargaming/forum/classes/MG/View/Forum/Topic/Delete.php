<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Forum_Topic_Delete extends Abstract_View_Forum_Topic {

	public $title = 'Delete Topic';

	public function post()
	{
		$post = $this->post->as_array();
		return $post;
	}

	public function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Delete',
				'href'  => Route::url('forum.topic', array(
					'action' => 'delete',
					'id'     => $this->topic->id
				))
			)
		));
	}
}
